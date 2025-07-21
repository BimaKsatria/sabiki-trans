<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\rating;
use App\Models\cars;

class RatingController extends Controller
{
    // Ambil semua rating
    public function index()
    {
        return response()->json(rating::with(['users', 'car'])->get());
    }

    // Simpan rating baru
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'cars_id' => 'required|exists:cars,id',
            'score'   => 'required|numeric|min:0|max:5',
        ]);

        $rating = rating::create($request->only('user_id', 'cars_id', 'score'));

        return response()->json([
            'message' => 'Rating berhasil ditambahkan',
            'data' => $rating
        ], 201);
    }

    // Ambil rating berdasarkan ID mobil
    public function getByCar($cars_id)
    {
        $ratings = rating::where('cars_id', $cars_id)->with('users')->get();

        return response()->json($ratings);
    }

    // Update rating (opsional)
    public function update(Request $request, $id)
    {
        $rating = rating::findOrFail($id);

        $request->validate([
            'score' => 'required|numeric|min:0|max:5',
        ]);

        $rating->update(['score' => $request->score]);

        return response()->json([
            'message' => 'Rating berhasil diperbarui',
            'data' => $rating
        ]);
    }

    // Hapus rating (opsional)
    public function destroy($id)
    {
        $rating = rating::findOrFail($id);
        $rating->delete();

        return response()->json(['message' => 'Rating berhasil dihapus']);
    }

    public function averageRating($cars_id)
    {
        $average = rating::where('cars_id', $cars_id)->avg('score');

        return response()->json(['cars_id' => $cars_id, 'average_rating' => round($average, 1)]);
    }
}
