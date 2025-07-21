<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class photo_banner extends Model
{
    protected $table = 'photo_banner';

    protected $fillable = [
        'name',
        'file_path',
        'is_active',
        'order',
    ];
}
