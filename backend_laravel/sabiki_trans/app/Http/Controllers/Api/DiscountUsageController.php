<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\discount;
use App\Models\discount_usages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DiscountUsageController extends Controller
{
    public function index()
    {
        return discount_usages::with(['discount', 'user'])->get();
    }

    public function show($id)
    {
        return discount_usages::with(['discount', 'user'])->findOrFail($id);
    }

    /**
     * Mencatat penggunaan diskon dan update counter
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'discount_id' => 'required|exists:discount,id',
            'user_id' => 'required|exists:users,id',
            'booking_id' => 'required|exists:bookings,id',
            'subtotal' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()
            ], 400);
        }

        try {
            DB::beginTransaction();

            // Dapatkan data diskon
            $discount = discount::findOrFail($request->discount_id);

            // Hitung nilai diskon
            $discountAmount = $this->calculateDiscount(
                $discount->type,
                $discount->value,
                $request->subtotal,
                $discount->max_discount
            );

            // Catat penggunaan diskon
            $discountUsage = discount_usages::create([
                'discount_id' => $request->discount_id,
                'user_id' => $request->user_id,
                'booking_id' => $request->booking_id,
                'used_at' => now(),
                'discount_amount' => $discountAmount
            ]);

            // Update counter penggunaan di tabel discount
            $discount->increment('used_count');

            DB::commit();

            return response()->json([
                'message' => 'Discount usage recorded successfully',
                'discount_usage' => $discountUsage,
                'discount_amount' => $discountAmount
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'Failed to record discount usage: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Menghitung nilai diskon
     */
    private function calculateDiscount($type, $value, $subtotal, $maxDiscount)
    {
        if ($type === 'percent') {
            $calculated = ($subtotal * $value) / 100;
            return $maxDiscount && $calculated > $maxDiscount 
                ? $maxDiscount 
                : $calculated;
        }
        
        // Fixed amount
        return $maxDiscount && $value > $maxDiscount 
            ? $maxDiscount 
            : $value;
    }

    public function update(Request $request, $id)
    {
        $usage = discount_usages::findOrFail($id);
        $usage->update($request->all());

        return $usage;
    }

    public function destroy($id)
    {
        $usage = discount_usages::findOrFail($id);
        $usage->delete();

        return response()->json(['message' => 'Discount usage deleted successfully']);
    }
}