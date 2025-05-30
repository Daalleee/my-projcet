<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // 🔒 Batasi akses panel berdasarkan role
    public function canAccessPanel(Panel $panel): bool
    {
        return match ($panel->getId()) {
            'admin' => $this->role === 'admin',
            'kasir' => $this->role === 'kasir',
            default => false,
        };
    }
    // Relasi misalnya dengan Rental dan Review (jika ada)
    public function rentals()
    {
        return $this->hasMany(Rental::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
