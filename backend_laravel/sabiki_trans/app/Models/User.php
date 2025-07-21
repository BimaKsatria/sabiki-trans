<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Filament\Panel;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'provider',       // Google, Facebook, dll.
        'provider_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    function canAccesPanel(Panel $panel)
    {
        if ($panel->getId() == 'admin') {
            if ($this->is_admin) {
                return true;
            }

            return false;
        }


        if ($panel->getId() == 'user') {
            if ($this->is_admin) {
                return false;
            }

            return true;
        }
    }

    public function customer()
    {
        return $this->hasMany(customers::class, 'user_id');
    }

    public function ratings()
    {
        return $this->hasMany(rating::class, 'user_id');
    }

    public function discounts()
    {
        return $this->hasMany(discount::class, 'discount_id');
    }

    public function discount_usage()
    {
        return $this->hasMany(discount_usages::class, 'discount_id');
    }
}
