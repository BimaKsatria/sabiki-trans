<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\rentals;
use App\Models\payments;
use Illuminate\Support\Facades\Log;

class RentalDetailsController extends Controller
{
    public function index()
    {
        try {
            // // Ambil semua rental dengan relasi yang diperlukan
            // $rentals = rentals::with([
            //     'booking.customer.user',
            //     'booking.cars',
            // ])->get();

            $userId = request()->query('user_id');

            $query = rentals::with([
                'booking.customer.user',
                'booking.cars',
                'booking.payment'
            ]);

            if ($userId) {
                $query->whereHas('booking.customer.user', function ($q) use ($userId) {
                    $q->where('id', $userId);
                });
            }

            $rentals = $query->get();

            // Debug: Tampilkan jumlah rental yang ditemukan
            Log::info('Total rentals found: ' . $rentals->count());

            // Ambil semua payment terkait sekaligus
            $rentalIds = $rentals->pluck('id')->toArray();
            $payments = payments::whereIn('rental_id', $rentalIds)
                ->orderBy('created_at', 'desc')
                ->get()
                ->groupBy('rental_id');

            // Debug: Tampilkan payment yang ditemukan
            Log::info('Payments grouped by rental_id: ' . json_encode($payments->keys()->toArray()));

            $formattedData = $rentals->map(function ($rental) use ($payments) {
                // Debug untuk setiap rental
                Log::debug("Processing rental ID: {$rental->id}");

                if (!$rental->booking) {
                    Log::warning("Rental {$rental->id} has no associated booking");
                    return null;
                }

                // Handle mobil
                $carModel = 'Mobil tidak tersedia';
                if ($rental->booking->cars) {
                    $carModel = $rental->booking->cars->brand . ' ' .
                        $rental->booking->cars->model . ' ' .
                        $rental->booking->cars->year;
                    $carId = $rental->booking->cars->id;
                } else {
                    $carModel = 'Mobil tidak tersedia';
                    $carId = null;
                    Log::warning("Rental {$rental->id} has no car associated with booking");
                }

                // Ambil payment untuk rental ini
                $rentalPayments = $payments->get($rental->id, collect());
                $payment = $rentalPayments->first();

                // Jika tidak ada payment, coba cari dengan booking_id
                if (!$payment && $rental->booking_id) {
                    Log::debug("No payment found for rental {$rental->id}, trying with booking_id: {$rental->booking_id}");
                    $payment = payments::where('booking_id', $rental->booking_id)
                        ->orderBy('created_at', 'desc')
                        ->first();
                }

                // Format data payment
                $paymentData = null;
                if ($payment) {
                    $paymentData = [
                        'order_id' => $payment->order_id ?? 'N/A',
                        'payment_method' => $payment->payment_method ?? 'unknown',
                        'status' => $payment->status ?? 'pending',
                        'amount' => $payment->amount,
                        'payment_date' => $payment->payment_date,
                    ];
                    Log::debug("Payment found for rental {$rental->id}: " . $payment->order_id);
                } else {
                    Log::warning("No payment found for rental {$rental->id}");
                }

                return [
                    'id' => $rental->id,
                    'pickup_date' => $rental->pickup_date,
                    'return_date' => $rental->return_date,
                    'status' => $rental->status,
                    'booking' => [
                        'id' => $rental->booking->id,
                        'customer' => [
                            'name' => $rental->booking->customer->user->name ?? 'Nama tidak tersedia',
                        ],
                        'car' => [
                            'id' => $carId,
                            'model' => $carModel,
                        ],
                    ],
                    'payment' => $paymentData,
                ];
            })->filter()->values();

            return response()->json($formattedData);
        } catch (\Exception $e) {
            Log::error('Error in RentalDetailsController: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
            return response()->json([
                'error' => 'Failed to fetch rental details',
                'message' => $e->getMessage(),
                'trace' => env('APP_DEBUG') ? $e->getTraceAsString() : []
            ], 500);
        }
    }
}
