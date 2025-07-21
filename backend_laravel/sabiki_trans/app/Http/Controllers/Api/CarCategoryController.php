<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\car_categories;
use Illuminate\Http\JsonResponse;

class CarCategoryController extends Controller
{
    /**
     * Display a listing of car categories.
     */
    public function index(): JsonResponse
    {
        $categories = car_categories::select('id', 'name', 'description as features')
            ->get();

        return response()->json($categories);
    }
}
