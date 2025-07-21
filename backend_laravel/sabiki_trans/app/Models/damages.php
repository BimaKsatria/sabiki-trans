<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class damages extends Model
{
    protected $guarded = [];
    protected $table = 'damages';
    public $timestamps = false;

    protected $fillable = [
        'car_id',
        'rental_id',
        'description',
    ];

    public function rental()
    {
        return $this->belongsTo(rentals::class, 'rental_id');
    }

    public function car()
    {
        return $this->belongsTo(cars::class, 'car_id');
    }
}
