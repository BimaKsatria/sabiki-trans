<?php

namespace App\Events;

use App\Models\Payment;
use App\Models\payments;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PaymentCompleted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $payment;

    public function __construct(payments $payment)
    {
        $this->payment = $payment;
    }
}