<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class payments extends Model
{
    protected $guarded = [];
    protected $table = 'payments';

    protected $casts = [
        'payment_date' => 'datetime',
    ];


    protected $fillable = [
        'booking_id',
        'rental_id',
        'amount',
        'payment_date',
        'payment_method',
        'status',
        'order_id',
        'transaction_id',
        'snap_token',
    ];

    /**
     * Relasi ke rental
     */
    public function rental()
    {
        return $this->belongsTo(rentals::class);
    }

    public function booking()
    {
        return $this->belongsTo(bookings::class);
    }
}
