<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Motor;
use App\Models\Rental;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RentalController extends Controller
{
    public function indexMotors()
    {
        $motors = Motor::where('status', 'available')->get();
        return view('user.motors.index', compact('motors'));
    }

    public function rentForm($motorId)
    {
        $motor = Motor::findOrFail($motorId);
        return view('user.rent.form', compact('motor'));
    }

    public function storeRental(Request $request, $motorId)
    {
        $request->validate([
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
        ]);

        Rental::create([
            'user_id' => Auth::id(),
            'motor_id' => $motorId,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => 'pending',
        ]);

        return redirect()->route('user.rentals')->with('success', 'Pemesanan berhasil diajukan.');
    }

    public function myRentals()
    {
        $rentals = Rental::where('user_id', Auth::id())->with('motor')->get();
        return view('user.rentals.index', compact('rentals'));
    }

    public function reviewForm($rentalId)
    {
        $rental = Rental::with('motor')->findOrFail($rentalId);
        return view('user.review.form', compact('rental'));
    }

    public function storeReview(Request $request, $rentalId)
    {
        $request->validate(['content' => 'required|string|max:500']);

        Review::create([
            'user_id' => Auth::id(),
            'rental_id' => $rentalId,
            'content' => $request->content,
        ]);

        return redirect()->route('user.rentals')->with('success', 'Ulasan berhasil dikirim.');
    }
}
