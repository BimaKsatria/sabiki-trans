<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class bookings extends Model
{
    protected $guarded = [];
    protected $table = 'bookings';
    protected $fillable = ['customer_id', 'car_id', 'start_date', 'end_date', 'status', 'pickup_location', 'return_location'];

    public function customer()
    {
        return $this->belongsTo(customers::class, 'customer_id');
    }

    public function cars()
    {
        return $this->belongsTo(cars::class, 'car_id');
    }

    public function discount()
    {
        return $this->belongsTo(discount::class, 'discount_id');
    }

    public function rental()
    {
        return $this->hasMany(rentals::class, 'booking_id');
    }

    public function rating()
    {
        return $this->hasOne(Rating::class, 'booking_id');
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class, 'booking_id');
    }


    public function payment()
    {
        return $this->hasOneThrough(
            payments::class,    // Model target
            rentals::class,      // Model perantara
            'booking_id',        // FK di rentals (menghubungkan ke bookings)
            'rental_id',         // FK di payments (menghubungkan ke rentals)
            'id',                // PK di bookings
            'id'                 // PK di rentals
        );
    }
}
