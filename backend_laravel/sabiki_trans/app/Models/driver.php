<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class driver extends Model
{
    protected $guarded = [];
    protected $table = 'driver';
    protected $fillable = [
        'service',
        'driver_fee',
    ];

    public function rental()
    {
        return $this->hasMany(rentals::class, 'driver_id');
    }
}
