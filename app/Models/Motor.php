<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Motor extends Model
{
    use HasFactory;
    protected $fillable = [
        'brand',
        'model',
        'plate_number',
        'description',
        'rental_price_per_day',
        'status',
        'image',
    ];
    public function rentals()
    {
        return $this->hasMany(Rental::class);
    }
}
