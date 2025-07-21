<?php

namespace App\Http\Controllers\Api;

use Midtrans\Config;
use Midtrans\CoreApi;
use App\Models\rentals;
use App\Models\bookings;
use App\Models\payments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PaymentsController extends Controller
{
    public function index(Request $request)
    {
        $userId = $request->query('user_id');

        if ($userId) {
            return payments::whereHas('rental', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
                ->with(['rental.car'])
                ->get();
        }

        return payments::with(['rental.car'])->get();
    }

    public function show($id)
    {
        return payments::with('rental')->findOrFail($id);
    }

    public function store(Request $request)
    {
        ini_set('max_execution_time', 120);
        Log::info('Payment store initiated', $request->all());

        $user = Auth::user();
        if (!$user) {
            Log::warning('Unauthenticated payment attempt');
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $validated = $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'amount' => 'required|numeric',
            'payment_method' => 'required|string|in:qris,cash',
        ]);

        $booking = bookings::findOrFail($validated['booking_id']);

        $rental = rentals::firstOrCreate(
            ['booking_id' => $validated['booking_id']],
            [
                'user_id' => $user->id,
                'status' => 'pending',
                'car_id' => $booking->car_id,
                'pickup_date' => $booking->pickup_date,
                'return_date' => $booking->return_date,
                'with_driver' => $booking->with_driver,
            ]
        );

        if ($validated['payment_method'] === 'cash') {
            return $this->handleCashPayment($validated, $user, $rental->id, $booking->id);
        }

        $this->initMidtransConfig();
        $orderId = 'ORDER-' . $user->id . '-' . time() . '-' . uniqid();

        try {
            if ($validated['payment_method'] === 'qris') {
                $params = [
                    'payment_type' => 'qris',
                    'transaction_details' => [
                        'order_id' => $orderId,
                        'gross_amount' => (int) round($validated['amount']),
                    ],
                    'customer_details' => [
                        'first_name' => $user->name ?? 'Customer',
                        'email' => $user->email ?? 'customer@example.com',
                    ],
                    'qris' => [
                        'acquirer' => 'gopay',
                    ]
                ];

                Log::info('Midtrans CoreAPI Request:', $params);

                $qrisResponse = CoreApi::charge($params);
                Log::info('Midtrans CoreAPI Response:', (array) $qrisResponse);

                if (!isset($qrisResponse->actions)) {
                    throw new \Exception('QRIS response invalid: missing actions');
                }

                $qrUrl = null;
                foreach ($qrisResponse->actions as $action) {
                    if ($action->name === 'generate-qr-code') {
                        $qrUrl = $action->url;
                        break;
                    }
                }

                if (!$qrUrl) {
                    throw new \Exception('QR code URL not found in response');
                }

                $paymentData = [
                    'rental_id' => $rental->id,
                    'booking_id' => $booking->id,
                    'amount' => $validated['amount'],
                    'payment_date' => now(),
                    'payment_method' => $validated['payment_method'],
                    'status' => 'unpaid',
                    'order_id' => $orderId,
                    'midway_transaction_id' => $qrisResponse->transaction_id,
                    'snap_token' => null,
                    'redirect_url' => $qrUrl,
                ];

                $payment = payments::create($paymentData);
                Log::info('Payment created', $payment->toArray());

                return response()->json([
                    'qr_url' => $qrUrl,
                    'payment' => $payment,
                ], 201);
            } else {
                return response()->json([
                    'message' => 'Payment method not implemented yet'
                ], 501);
            }
        } catch (\Exception $e) {
            Log::error('Midtrans Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'params' => $params ?? null
            ]);

            return response()->json([
                'message' => 'Payment processing failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $payment = payments::findOrFail($id);
            $payment->update($request->all());
            return response()->json($payment);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update payment',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $payment = payments::findOrFail($id);
            $payment->delete();

            return response()->noContent();
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete payment',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function handleNotification(Request $request)
    {
        $corsHeaders = [
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Methods' => 'POST, OPTIONS',
            'Access-Control-Allow-Headers' => 'Content-Type',
        ];

        if ($request->isMethod('OPTIONS')) {
            return response()->json()->withHeaders($corsHeaders);
        }

        $rawBody = $request->getContent();
        Log::info('Raw Midtrans Notification:', ['raw' => $rawBody]);

        try {
            $notificationData = json_decode($rawBody, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception('Invalid JSON: ' . json_last_error_msg());
            }

            // Log data notifikasi yang diterima
            Log::info('Midtrans Notification Data:', $notificationData);

            // Verifikasi field wajib
            $requiredFields = ['order_id', 'transaction_status', 'gross_amount', 'signature_key'];
            foreach ($requiredFields as $field) {
                if (!isset($notificationData[$field])) {
                    throw new \Exception("Missing required field: $field");
                }
            }

            $serverKey = config('midtrans.server_key');
            if (!$serverKey) {
                throw new \Exception('Midtrans server key is not configured');
            }

            // Hitung signature
            $signature = hash(
                'sha512',
                $notificationData['order_id'] .
                    $notificationData['status_code'] .
                    $notificationData['gross_amount'] .
                    $serverKey
            );

            // Log perhitungan signature untuk debugging
            Log::debug('Signature Calculation', [
                'components' => [
                    'order_id' => $notificationData['order_id'],
                    'status_code' => $notificationData['status_code'],
                    'gross_amount' => $notificationData['gross_amount'],
                    'server_key' => substr($serverKey, 0, 6) . '...'
                ],
                'calculated_signature' => $signature,
                'received_signature' => $notificationData['signature_key']
            ]);

            // Bypass signature verification in non-production environments
            $isProduction = config('midtrans.is_production', false);
            if ($isProduction && $signature !== $notificationData['signature_key']) {
                Log::error('Invalid signature', [
                    'expected' => $signature,
                    'received' => $notificationData['signature_key']
                ]);
                return response()->json(['status' => 'error', 'message' => 'Invalid signature'], 400)
                    ->withHeaders($corsHeaders);
            }

            // Cari payment berdasarkan order_id
            $payment = payments::where('order_id', $notificationData['order_id'])->first();
            if (!$payment) {
                Log::error('Payment not found', [
                    'order_id' => $notificationData['order_id'],
                    'available_payments' => payments::pluck('order_id')->toArray()
                ]);
                return response()->json(['status' => 'error', 'message' => 'Payment not found'], 404)
                    ->withHeaders($corsHeaders);
            }

            // Mapping status
            $statusMap = [
                'capture'    => 'paid',
                'settlement' => 'paid',
                'pending'    => 'unpaid',
                'deny'       => 'failed',
                'expire'     => 'failed',
                'cancel'     => 'failed',
                'refund'     => 'refunded',
                'partial_refund' => 'partially_refunded'
            ];

            $transactionStatus = $notificationData['transaction_status'];
            if (!isset($statusMap[$transactionStatus])) {
                Log::warning('Unhandled transaction status', ['status' => $transactionStatus]);
                return response()->json([
                    'status' => 'error',
                    'message' => "Unhandled transaction status: $transactionStatus"
                ], 400)->withHeaders($corsHeaders);
            }

            $newStatus = $statusMap[$transactionStatus];
            if ($payment->status !== $newStatus) {
                $payment->status = $newStatus;
                $payment->save();

                // Jika pembayaran berhasil, update status terkait
                if ($newStatus === 'paid') {
                    $rental = $payment->rental;
                    if ($rental) {
                        $booking = $rental->booking;
                        if ($booking) {
                            $booking->status = 'approved';
                            $booking->save();

                            $rental->status = 'ongoing';
                            $rental->save();

                            // $car = $rental->car;
                            // if ($car) {
                            //     $car->status = 'rented';
                            
                            //     $car->save();
                            //     Log::info('Car status updated to rented', [
                            //         'car_id' => $car->id,
                            //         'car_status' => $car->status,
                            //     ]);
                            // } else {
                            //     Log::warning('Car not found via booking for rental', [
                            //         'rental_id' => $rental->id,
                            //         'booking_id' => $rental->booking_id
                            //     ]);
                            // }

                            Log::info('Booking approved and rental started (ongoing)', [
                                'booking_id' => $booking->id,
                                'rental_id' => $rental->id
                            ]);
                        } else {
                            Log::error('Booking not found for rental', [
                                'rental_id' => $rental->id,
                                'payment_id' => $payment->id
                            ]);
                        }
                    } else {
                        Log::error('Rental not found for payment', [
                            'payment_id' => $payment->id
                        ]);
                    }
                }
            }

            return response()->json(['status' => 'success'])->withHeaders($corsHeaders);
        } catch (\Exception $e) {
            Log::error('Notification Processing Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'raw_body' => $rawBody
            ]);
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500)->withHeaders($corsHeaders);
        }
    }

    private function handleCashPayment($validated, $user, $rentalId, $bookingId)
    {
        try {
            $orderId = 'CASH-' . time() . '-' . uniqid();

            $paymentData = [
                'rental_id' => $rentalId,
                'booking_id' => $bookingId,
                'amount' => $validated['amount'],
                'payment_date' => now(),
                'payment_method' => 'cash',
                'status' => 'paid',
                'order_id' => $orderId,
            ];

            $payment = payments::create($paymentData);
            Log::info('Cash payment processed', $payment->toArray());

            $rental = rentals::find($rentalId);
            if ($rental) {
                $booking = $rental->booking;

                if ($booking) {
                    $booking->status = 'confirmed';
                    $booking->save();

                    $rental->status = 'confirmed';
                    $rental->save();

                    Log::info('Booking and rental confirmed for cash payment', [
                        'booking_id' => $booking->id,
                        'rental_id' => $rental->id
                    ]);
                } else {
                    Log::error('Booking not found for rental in cash payment', [
                        'rental_id' => $rentalId
                    ]);
                }
            } else {
                Log::error('Rental not found in cash payment', [
                    'rental_id' => $rentalId
                ]);
            }

            return response()->json([
                'message' => 'Cash payment processed',
                'payment' => $payment
            ], 201);
        } catch (\Exception $e) {
            Log::error('Cash Payment Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'message' => 'Cash payment failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    private function initMidtransConfig()
    {
        $serverKey = config('midtrans.server_key');
        $isProduction = config('midtrans.is_production', false);

        if (empty($serverKey)) {
            throw new \Exception('Midtrans server key is not configured');
        }

        Config::$serverKey = $serverKey;
        Config::$isProduction = $isProduction;
        Config::$isSanitized = true;
        Config::$is3ds = true;

        Log::info('Midtrans config initialized', [
            'server_key' => substr($serverKey, 0, 6) . '...',
            'is_production' => $isProduction
        ]);
    }

    public function paymentsByUserViaBooking($user_id)
    {
        $payments = DB::table('payments')
            ->join('rentals', 'rentals.id', '=', 'payments.rental_id')
            ->join('bookings', 'bookings.id', '=', 'rentals.booking_id')
            ->join('customers', 'customers.id', '=', 'bookings.customer_id')
            ->where('customers.user_id', $user_id)
            ->select('payments.*', 'bookings.id as booking_id', 'customers.user_id')
            ->get();

        return response()->json($payments);
    }

    public function checkPaymentStatus($orderId)
    {
        try {
            $payment = payments::where('order_id', $orderId)->first();

            if (!$payment) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Payment not found'
                ], 404);
            }

            // Jika sudah paid, ubah status terkait
            if ($payment->status === 'paid') {
                $rental = $payment->rental;
                if ($rental) {
                    $booking = $rental->booking;
                    if ($booking) {
                        $booking->status = 'approved';
                        $booking->save();
                    }

                    $rental->status = 'ongoing';
                    $rental->save();
                }
            }

            return response()->json([
                'status' => 'success',
                'payment_status' => $payment->status
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
