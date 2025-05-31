<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\Motor;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Rental extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'motor_id',
        'start_date',
        'end_date',
        'total_price',
        'status',
    ];

    protected $attributes = [
        'status' => 'pending',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function motor(): BelongsTo
    {
        return $this->belongsTo(Motor::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    protected static function booted()
    {
        static::creating(function ($rental) {
            $start = Carbon::parse($rental->start_date);
            $end = Carbon::parse($rental->end_date);
            $lamaHari = $start->diffInDays($end) + 1;

            $motor = Motor::find($rental->motor_id);
            $hargaPerHari = $motor ? $motor->rental_price_per_day : 0;

            $rental->total_price = $hargaPerHari * $lamaHari;
        });
    }
}
