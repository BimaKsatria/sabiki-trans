<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class rating extends Model
{
    protected $guarded = [];
    protected $table = 'rating';
    protected $fillable = ['user_id', 'cars_id', 'score'];

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function car()
    {
        return $this->belongsTo(cars::class, 'cars_id');
    }

    
}
