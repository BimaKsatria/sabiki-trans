<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class discount_usages extends Model
{
    protected $fillable = ['discount_id', 'user_id', 'used_at'];

    public function discount()
    {
        return $this->belongsTo(discount::class);
    }

    public function booking()
    {
        return $this->belongsTo(bookings::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
