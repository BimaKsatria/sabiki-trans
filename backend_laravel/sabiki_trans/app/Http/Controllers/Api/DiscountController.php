<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\discount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class DiscountController extends Controller
{
    public function index()
    {
        return discount::with(['user', 'booking', 'discountUsages'])->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|unique:discount,code',
            'user_id' => 'nullable|exists:users,id',
            'type' => 'required|in:percent,fixed',
            'value' => 'required|numeric',
            'max_discount' => 'nullable|numeric',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'usage_limit' => 'nullable|integer',
            'used_count' => 'nullable|integer',
            'active' => 'required|boolean',
        ]);

        return discount::create($validated);
    }

    public function check(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'code' => 'required|string',
            'subtotal' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'valid' => false,
                'error' => 'Invalid input data'
            ], 400);
        }

        $code = $request->input('code');
        $subtotal = $request->input('subtotal');

        // Cari diskon berdasarkan kode
        $discount = Discount::where('code', $code)->first();

        // Jika diskon tidak ditemukan
        if (!$discount) {
            return response()->json([
                'valid' => false,
                'error' => 'Kode diskon tidak valid'
            ]);
        }

        $now = Carbon::now();

        // Cek masa berlaku diskon
        if ($now->lt($discount->start_date) || $now->gt($discount->end_date)) {
            return response()->json([
                'valid' => false,
                'error' => 'Diskon sudah kadaluarsa'
            ]);
        }

        // Cek minimum pembelian
        if ($discount->min_purchase && $subtotal < $discount->min_purchase) {
            return response()->json([
                'valid' => false,
                'error' => 'Minimal pembelian Rp ' . number_format($discount->min_purchase, 0, ',', '.')
            ]);
        }

        // Hitung jumlah diskon
        $discountAmount = 0;

        if ($discount->type === 'percent') {
            // Diskon persentase
            $discountAmount = $subtotal * ($discount->value / 100);
            
            // Jika ada batas maksimal diskon
            if ($discount->max_discount && $discountAmount > $discount->max_discount) {
                $discountAmount = $discount->max_discount;
            }
        } else {
            // Diskon nominal tetap
            $discountAmount = $discount->value;
        }

        // Pastikan diskon tidak melebihi subtotal
        if ($discountAmount > $subtotal) {
            $discountAmount = $subtotal;
        }

        return response()->json([
            'valid' => true,
            'discount_amount' => $discountAmount,
            'discount' => [
                'id' => $discount->id,
                'code' => $discount->code,
                'name' => $discount->name,
                'type' => $discount->type,
                'value' => $discount->value,
                'min_purchase' => $discount->min_purchase,
                'max_discount' => $discount->max_discount,
                'start_date' => $discount->start_date,
                'end_date' => $discount->end_date,
            ]
        ]);
    }
}
