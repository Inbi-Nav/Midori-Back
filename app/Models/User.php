<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use App\Models\Order;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'provider_request'
    ];

    protected function casts(): array {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

       public function orders() {
        return $this->hasMany(Order::class);
    }

    public function isAdmin(): bool {
    return $this->role === 'admin';
    }

    public function isClient(): bool {
        return $this->role === 'client';
    }

   public function isProvider(): bool {
        return $this->role === 'provider';
    } 
}
