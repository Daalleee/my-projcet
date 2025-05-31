<?php

namespace App\Http\Controllers;

use App\Models\Motor;
use App\Models\Rental;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class RentalController extends Controller
{
    public function store(Request $request, $motorId)
    {
        // Validasi input dari form
        $request->validate([
            'penyewa_name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
        ]);

        // Cek apakah motor ada dan tersedia
        $motor = Motor::findOrFail($motorId);
        if ($motor->status !== 'available') {
            return redirect()->route('home')->with('error', 'Motor tidak tersedia untuk disewa.');
        }

        // Buat entri sewa
        $startDate = Carbon::today();
        $endDate = Carbon::today()->addDay(); // Default 1 hari
        $days = $startDate->diffInDays($endDate) + 1;
        $totalPrice = $days * $motor->rental_price_per_day;

        $rental = Rental::create([
            'user_id' => Auth::check() ? Auth::id() : null,
            'motor_id' => $motor->id,
            'phone_number' => $request->phone_number,
            'penyewa_name' => $request->penyewa_name,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'total_price' => $totalPrice,
            'status' => 'pending',
        ]);

        // Debugging: Log jika entri gagal
        if (!$rental) {
            Log::error('Gagal membuat entri sewa untuk motor ID: ' . $motorId . ', Data: ' . json_encode($request->all()));
            return redirect()->route('home')->with('error', 'Gagal membuat entri sewa. Silakan coba lagi.');
        }

        // Redirect ke WhatsApp dengan informasi penyewa
        $whatsappMessage = urlencode("Saya, {$request->penyewa_name} (Telp: {$request->phone_number}), ingin menyewa motor {$motor->brand} {$motor->model} (Nomor Plat: {$motor->plate_number}) untuk {$days} hari dengan harga Rp " . number_format($totalPrice, 0, ',', '.') . ". Mohon info pembayaran!");
        $whatsappUrl = "https://wa.me/6281337063361?text={$whatsappMessage}";

        return redirect()->to($whatsappUrl);
    }
}
