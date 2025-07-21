<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class car_categories extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table = 'car_categories';
    protected $fillable = ['name', 'description', 'icon'];

    public function cars()
    {
        return $this->belongsToMany(cars::class, 'car_category_car', 'car_category_id', 'car_id');
    }
}
