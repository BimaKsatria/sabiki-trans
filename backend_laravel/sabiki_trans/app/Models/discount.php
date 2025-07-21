<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class discount extends Model
{
    protected $guarded = [];
    protected $table = 'discount';
    protected $fillable = ['code', 'user_id', 'type', 'value', 'max_discount', 'start_date', 'end_date', 'usage_limit', 'used_count', 'active'];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'min_purchase' => 'float',
        'max_discount' => 'float',
        'value' => 'float',
    ];

    public function booking()
    {
        return $this->hasMany(bookings::class, 'discount_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function discountUsages()
    {
        return $this->hasMany(Discount_Usages::class, 'discount_id');
    }
}
