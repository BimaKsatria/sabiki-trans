<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PaymentStatusUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $paymentId;
    public $status;

    public function __construct($paymentId, $status)
    {
        $this->paymentId = $paymentId;
        $this->status = $status;
    }

    public function broadcastOn()
    {
        return new Channel('payment.' . $this->paymentId);
    }

    public function broadcastWith()
    {
        return [
            'payment_id' => $this->paymentId,
            'status' => $this->status,
        ];
    }

    public function broadcastAs()
    {
        return 'payment.updated';
    }
}
