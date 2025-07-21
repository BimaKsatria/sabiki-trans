<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\cars;
use Illuminate\Http\Request;

class CarController extends Controller
{
    public function index()
    {
        $cars = cars::with('car_photos')->get()->map(function ($car) {
            return [
                'id' => $car->id,
                'brand' => $car->brand,
                'model' => $car->model,
                'price_per_day' => $car->price_per_day,
                'image_url' => $car->photos->first()?->file_path
                    ? asset('storage/' . $car->photos->first()->file_path)
                    : null,

                'rating' => round($car->ratings()->avg('rating') ?? 0, 1),
            ];
        });

        return response()->json($cars);
    }

    public function show($id)
    {
        // Ambil mobil dengan relasi categories dan photos
        $car = cars::with(['categories', 'photos', 'ratings'])->find($id);

        // Jika mobil tidak ditemukan, kirim response error
        if (!$car) {
            return response()->json([
                'success' => false,
                'message' => 'Car not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $car->id,
                'brand' => $car->brand,
                'model' => $car->model,
                'year' => $car->year,
                'price_per_day' => $car->price_per_day,
                'description' => $car->description,
                'status' => $car->status,

                // ambil semua kategori yang terkait
                'categories' => $car->categories->map(function ($category) {
                    return [
                        'id' => $category->id,
                        'name' => $category->name,
                        'description' => $category->description,
                        'icon_url' => $category->icon ? asset('storage/' . $category->icon) : null,
                    ];
                }),

                // ambil thumbnail pertama dari relasi photos
                'thumbnail' => $car->thumbnail
                    ? asset('storage/' . $car->thumbnail)
                    : null,

                // ambil semua foto terkait
                'photos' => $car->photos->map(function ($photo) {
                    return asset('storage/' . $photo->file_path);
                }),

                // optional: average rating
                'average_rating' => round($car->ratings->avg('rating') ?? 5.0, 1),

                // dummy data sementara
                //'features' => ['Bluetooth', 'Aux', 'Auto'],
                'owner_name' => 'Sabiki Trans',
                'join_date' => 'Sep 2020',
                'owner_location' => 'Pasuruan',
            ],
        ]);
    }


    public function availableCars(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Cari mobil yang tidak sedang disewa pada rentang tanggal tersebut
        $availableCars = cars::whereDoesntHave('rentals', function ($query) use ($startDate, $endDate) {
            $query->where(function ($q) use ($startDate, $endDate) {
                $q->where('pickup_date', '<=', $endDate)
                    ->where('return_date', '>=', $startDate);
            });
        })->get();

        return response()->json($availableCars);
    }
}
