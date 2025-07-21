<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class customers extends Model
{
    protected $guarded = [];
    protected $table = 'customers';
    protected $fillable = ['user_id', 'phone', 'address'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function booking()
    {
        return $this->hasMany(bookings::class, 'customer_id');
    }
}
