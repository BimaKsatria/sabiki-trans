<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class car_photos extends Model
{
    protected $guarded = [];
    protected $table = 'car_photos';
    protected $fillable = ['car_id', 'file_path'];

    public function car()
    {
        return $this->belongsTo(cars::class);
    }
}
