<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StatusSewa extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_status', // contoh: 'Menunggu', 'Aktif', 'Selesai', 'Dibatalkan'
    ];

    // Relasi ke Rental (jika satu status bisa punya banyak rental)
    public function rentals()
    {
        return $this->hasMany(Rental::class, 'status_sewa_id');
    }
}
