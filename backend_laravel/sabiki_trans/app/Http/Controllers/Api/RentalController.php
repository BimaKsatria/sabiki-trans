<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\cars;
use App\Models\rentals;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class RentalController extends Controller
{

    public function index(Request $request)
    {
        $customerId = $request->query('customer_id');

        $rentals = rentals::whereIn('booking_id', function ($query) use ($customerId) {
            $query->select('id')
                ->from('bookings')
                ->where('customer_id', $customerId);
        })
            ->with(['booking.user', 'booking.car', 'booking.payment'])
            ->get()
            ->map(function ($rental) {
                // Tambahkan flag untuk menandai apakah sudah bisa dirating
                $rental->can_rate = $rental->status === 'completed' &&
                    !$rental->booking->ratings()->exists();
                return $rental;
            });

        return response()->json($rentals);
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'booking_id' => 'required|exists:bookings,id',
                'pickup_date' => 'required|date',
                'return_date' => 'required|date|after_or_equal:pickup_date',
                'status' => 'required|string',
                'driver_id' => 'nullable|exists:drivers,id',
                'pickup_location' => 'nullable|string',
                'return_location' => 'nullable|string',
            ]);

            $rental = Rentals::create($validated);
            return response()->json($rental, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $rental = rentals::with(['booking', 'driver', 'damage'])->find($id);
        if (!$rental) {
            return response()->json(['message' => 'Rental not found'], 404);
        }

        return response()->json($rental);
    }

    public function update(Request $request, $id)
    {
        $rental = rentals::find($id);
        if (!$rental) {
            return response()->json(['message' => 'Rental not found'], 404);
        }

        $validated = $request->validate([
            'booking_id' => 'sometimes|exists:bookings,id',
            'pickup_date' => 'sometimes|date',
            'return_date' => 'sometimes|date|after_or_equal:pickup_date',
            'status' => 'sometimes|in:pending,ongoing,completed,overdue',
            'driver_id' => 'nullable|exists:driver,id',
        ]);

        $rental->update($validated);
        return response()->json($rental);
    }

    public function destroy($id)
    {
        $rental = rentals::find($id);
        if (!$rental) {
            return response()->json(['message' => 'Rental not found'], 404);
        }

        $rental->delete();
        return response()->json(['message' => 'Rental deleted']);
    }

    public function storeRating(Request $request, $id)
    {
        $request->validate([
            'score' => 'required|integer|min:1|max:5',
        ]);

        $rental = rentals::findOrFail($id);

        if ($rental->status !== 'completed') {
            return response()->json(['message' => 'Rental belum selesai'], 400);
        }

        $booking = $rental->booking;
        $carId = $booking->car_id;

        // Cek apakah sudah ada rating
        if ($booking->ratings()->exists()) {
            return response()->json(['message' => 'Anda sudah memberikan rating'], 400);
        }

        // Buat rating baru
        $rating = $booking->ratings()->create([
            'user_id' => $booking->customer->user_id,
            'cars_id' => $carId,
            'score' => $request->score,
        ]);

        // Update rating mobil
        $car = cars::find($carId);
        $car->rating = $car->ratings()->avg('score');
        $car->save();

        return response()->json([
            'message' => 'Rating berhasil disimpan',
            'rating' => $rating
        ], 201);
    }



    // public function getAvailableCars(Request $request)
    // {
    //     $request->validate([
    //         'start_date' => 'required|date',
    //         'end_date' => 'required|date|after:start_date',
    //     ]);

    //     $startDate = $request->start_date;
    //     $endDate = $request->end_date;

    //     // Dapatkan mobil yang sedang disewa pada rentang tanggal tersebut
    //     $unavailableCarIds = DB::table('rentals')
    //         ->join('bookings', 'rentals.booking_id', '=', 'bookings.id')
    //         ->where(function ($query) use ($startDate, $endDate) {
    //             $query->where('rentals.pickup_date', '<=', $endDate)
    //                 ->where('rentals.return_date', '>=', $startDate);
    //         })
    //         ->where('rentals.status', 'ongoing')
    //         ->pluck('bookings.car_id')
    //         ->toArray();

    //     // Ambil semua mobil yang TIDAK termasuk dalam unavailableCarIds DAN statusnya 'available'
    //     $availableCars = cars::where('status', 'available')
    //         ->whereNotIn('id', $unavailableCarIds)
    //         ->with('ratings') // Eager load ratings
    //         ->get()
    //         ->map(function ($car) {
    //             // Hitung rating rata-rata
    //             $car->rating = $car->ratings->avg('score') ?? 0.0;
    //             return $car;
    //         });

    //     return response()->json([
    //         'success' => true,
    //         'available_cars' => $availableCars,
    //         'message' => count($availableCars) ? 'Available cars found' : 'No cars available',
    //         'count' => count($availableCars)
    //     ]);
    // }

    public function getAvailableCars(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        $startDate = $request->start_date;
        $endDate = $request->end_date;

        // Perbaikan logika pengecekan tanggal
        $unavailableCarIds = DB::table('rentals')
            ->join('bookings', 'rentals.booking_id', '=', 'bookings.id')
            ->where(function ($query) use ($startDate, $endDate) {
                $query->where(function ($q) use ($startDate, $endDate) {
                    // Mobil tidak tersedia jika ada jadwal yang bertabrakan
                    $q->where('rentals.pickup_date', '<=', $endDate)
                        ->where('rentals.return_date', '>=', $startDate);
                })->orWhere(function ($q) use ($startDate, $endDate) {
                    // Tanggal mulai berada dalam rentang sewa
                    $q->where('rentals.pickup_date', '>=', $startDate)
                        ->where('rentals.pickup_date', '<=', $endDate);
                })->orWhere(function ($q) use ($startDate, $endDate) {
                    // Tanggal selesai berada dalam rentang sewa
                    $q->where('rentals.return_date', '>=', $startDate)
                        ->where('rentals.return_date', '<=', $endDate);
                });
            })
            ->where('rentals.status', 'ongoing')
            ->pluck('bookings.car_id')
            ->toArray();

        // Debug: Log mobil yang tidak tersedia
        Log::info('Unavailable car IDs', ['ids' => $unavailableCarIds]);

        $availableCars = cars::where('status', 'available')
            ->whereNotIn('id', $unavailableCarIds)
            ->with('ratings')
            ->get()
            ->map(function ($car) {
                return [
                    'id' => $car->id,
                    'brand' => $car->brand,
                    'model' => $car->model,
                    'status' => $car->status,
                    'price_per_day' => $car->price_per_day,
                    'image_url' => $car->thumbnail ?? null,
                    'rating' => $car->ratings->avg('score') ?? 0.0,
                ];
            });

        // Debug: Log mobil yang tersedia
        Log::info('Available cars', ['count' => count($availableCars)]);

        return response()->json([
            'success' => true,
            'available_cars' => $availableCars,
            'message' => count($availableCars) ? 'Available cars found' : 'No cars available',
            'count' => count($availableCars)
        ]);
    }



    public function checkAvailability(Request $request)
    {
        $request->validate([
            'car_id' => 'required|exists:cars,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        $carId = $request->car_id;
        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);

        // Cek apakah ada rental yang overlapping dengan status ongoing
        $existingRental = DB::table('rentals')
            ->join('bookings', 'rentals.booking_id', '=', 'bookings.id')
            ->where('bookings.car_id', $request->car_id)
            ->where(function ($query) use ($request) {
                $query->where('rentals.pickup_date', '<=', $request->end_date)
                    ->where('rentals.return_date', '>=', $request->start_date);
            })
            ->where('rentals.status', 'ongoing')
            ->exists();


        if ($existingRental) {
            return response()->json([
                'available' => false,
                'message' => 'Mobil tidak tersedia pada tanggal yang dipilih karena sedang disewa'
            ]);
        }

        return response()->json([
            'available' => true,
            'message' => 'Mobil tersedia'
        ]);
    }
}
