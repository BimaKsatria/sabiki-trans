<?php

namespace App\Services;

use Midtrans\Config;
use Midtrans\CoreApi;
use Illuminate\Http\Request;

class MidtransService
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    public function createQrisPayment(array $params)
    {
        $response = CoreApi::charge($params);
        
        if (!isset($response->actions)) {
            throw new \Exception('Invalid QRIS response');
        }

        return (object) [
            'transaction_id' => $response->transaction_id,
            'qr_url' => collect($response->actions)->firstWhere('name', 'generate-qr-code')->url,
            'expiry_time' => $response->expiry_time ?? null
        ];
    }

    public function verifyNotification(Request $request)
    {
        $serverKey = config('midtrans.server_key');
        $hashed = hash('sha512', 
            $request->order_id . 
            $request->status_code . 
            $request->gross_amount . 
            $serverKey
        );

        return $hashed === $request->signature_key;
    }
}