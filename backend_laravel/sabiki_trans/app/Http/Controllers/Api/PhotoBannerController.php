<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\photo_banner;

class PhotoBannerController extends Controller
{
    public function index()
    {
        $banners = photo_banner::where('is_active', 1)
            ->orderBy('order')
            ->get(['id', 'name', 'file_path']);

        return response()->json([
            'success' => true,
            'data' => $banners,
        ]);
    }
}
