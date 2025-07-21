<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class rentals extends Model
{
    protected $guarded = [];
    protected $table = 'rentals';
    protected $fillable = [
        'booking_id',
        'pickup_date',
        'return_date',
        'status',
        'driver_id',
        'pickup_location',
        'return_location'
    ];

    public function booking()
    {
        return $this->belongsTo(bookings::class, 'booking_id');
    }

    public function driver()
    {
        return $this->belongsTo(driver::class, 'driver_id');
    }

    public function damage()
    {
        return $this->hasMany(damages::class, 'rental_id');
    }

    public function car()
    {
        return $this->hasOneThrough(
            cars::class,     // Target: tabel `cars`
            bookings::class, // Perantara: tabel `bookings`
            'id',            // bookings.id (foreign key di bookings)
            'id',            // cars.id (primary key)
            'booking_id',    // rentals.booking_id
            'car_id'         // bookings.car_id
        );
    }
}
