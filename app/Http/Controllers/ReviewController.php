<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Rental;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
  /**
   * Tampilkan form ulasan untuk rental tertentu.
   */
  public function create($rentalId)
  {
    $rental = Rental::findOrFail($rentalId);

    // Pastikan hanya user yang menyewa yang bisa memberi ulasan
    if ($rental->user_id !== Auth::id()) {
      abort(403, 'Tidak diizinkan mengakses ulasan ini.');
    }

    return view('reviews.create', compact('rental'));
  }

  /**
   * Simpan ulasan ke database.
   */
  public function store(Request $request, $rentalId)
  {
    $rental = Rental::findOrFail($rentalId);

    if ($rental->user_id !== Auth::id()) {
      abort(403, 'Tidak diizinkan.');
    }

    $validated = $request->validate([
      'rating' => 'required|integer|min:1|max:5',
      'comment' => 'nullable|string|max:1000',
    ]);

    // Cek apakah sudah pernah review
    if ($rental->review) {
      return redirect()->back()->with('error', 'Anda sudah memberikan ulasan untuk penyewaan ini.');
    }

    Review::create([
      'rental_id' => $rental->id,
      'user_id' => Auth::id(),
      'rating' => $validated['rating'],
      'comment' => $validated['comment'] ?? '',
    ]);

    return redirect()->route('dashboard')->with('success', 'Ulasan berhasil dikirim.');
  }
  public function index()
  {
    $reviews = Review::with(['user', 'rental.motor'])->latest()->get();

    return view('reviews.index', compact('reviews'));
  }
}
