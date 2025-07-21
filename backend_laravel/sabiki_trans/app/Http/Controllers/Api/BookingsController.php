<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Log;
use App\Models\bookings;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\discount;

class BookingsController extends Controller
{
    public function index(Request $request)
    {
        // Tambahkan log untuk debugging
        Log::info('Bookings API Request:', [
            'status' => $request->input('status'),
            'user_id' => $request->input('user_id')
        ]);

        $query = bookings::query()->with(['customer.user', 'cars', 'rental']);

        // Filter status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Filter user_id (perbaikan relasi)
        if ($request->has('user_id')) {
            $query->whereHas('customer', function ($q) use ($request) {
                $q->where('user_id', $request->user_id);

                // Tambahkan log relasi
                Log::info('Filtering by user_id:', ['user_id' => $request->user_id]);
            });
        }

        // Tambahkan log query SQL
        Log::info('Bookings Query:', ['sql' => $query->toSql(), 'bindings' => $query->getBindings()]);

        $bookings = $query->get();

        // Log jumlah booking yang ditemukan
        Log::info('Bookings Found:', ['count' => $bookings->count()]);

        // Mapping data
        $mappedBookings = $bookings->map(function ($booking) {
            return [
                'id' => $booking->id,
                'status' => $booking->status,
                'start_date' => $booking->start_date,
                'end_date' => $booking->end_date,
                'customer' => [
                    'user' => [
                        'name' => $booking->customer->user->name ?? 'N/A',
                    ]
                ],
                'cars' => [
                    'id' => $booking->cars->id,
                    'brand' => $booking->cars->brand,
                    'model' => $booking->cars->model,
                ],
                'car_id' => $booking->car_id,
                'is_rated' => $booking->rating()->exists(),
            ];
        });



        return response()->json($mappedBookings);
    }

    public function show($id)
    {
        $booking = bookings::with(['customer.user', 'cars', 'rental'])->findOrFail($id);

        // Tambahkan field is_rated
        $booking->is_rated = $booking->rating()->exists();

        return response()->json($booking);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'car_id' => 'required|exists:cars,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|string',
            'pickup_location' => 'nullable|string',
            'return_location' => 'nullable|string',
        ]);

        //$booking = bookings::create($validated);

        $total = $this->calculateTotalWithDiscount(
            $request->subtotal,
            $request->discount_id
        );

        $bookingData = array_merge($validated, ['total' => $total]);
        $booking = bookings::create($bookingData);

        // Catat penggunaan diskon jika ada
        if ($request->discount_id) {
            $this->recordDiscountUsage(
                $request->discount_id,
                $request->customer_id,
                $booking->id,
                $request->subtotal
            );
        }


        return response()->json($booking, 201);
    }

    public function update(Request $request, $id)
    {
        $booking = bookings::findOrFail($id);
        $booking->update($request->only('status'));
        return response()->json($booking);
    }

    public function destroy($id)
    {
        $booking = bookings::findOrFail($id);
        $booking->delete();

        return response()->json(['message' => 'Deleted successfully']);
    }

    private function calculateTotalWithDiscount($subtotal, $discountId)
    {
        if (!$discountId) return $subtotal;

        $discount = discount::find($discountId);
        if (!$discount) return $subtotal;

        if ($discount->type === 'percent') {
            $discountAmount = ($subtotal * $discount->value) / 100;
            if ($discount->max_discount && $discountAmount > $discount->max_discount) {
                $discountAmount = $discount->max_discount;
            }
            return $subtotal - $discountAmount;
        }

        // Fixed discount
        $discountAmount = $discount->value;
        if ($discount->max_discount && $discountAmount > $discount->max_discount) {
            $discountAmount = $discount->max_discount;
        }
        return $subtotal - $discountAmount;
    }

    /**
     * Catat penggunaan diskon
     */
    private function recordDiscountUsage($discountId, $userId, $bookingId, $subtotal)
    {
        try {
            app(DiscountUsageController::class)->store(new Request([
                'discount_id' => $discountId,
                'user_id' => $userId,
                'booking_id' => $bookingId,
                'subtotal' => $subtotal
            ]));
        } catch (\Exception $e) {
            Log::error('Failed to record discount usage: ' . $e->getMessage());
        }
    }
}
