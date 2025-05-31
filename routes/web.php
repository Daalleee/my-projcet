<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RentalController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ProfileController;


Route::get('/', [HomeController::class, 'index'])->name('home');


Route::post('/rent/{motorId}', [RentalController::class, 'store'])->name('rent');


Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


require __DIR__ . '/auth.php';

Route::middleware(['auth'])->group(function () {
    Route::get('/rental/{rental}/review', [ReviewController::class, 'create'])->name('reviews.create');
    Route::post('/rental/{rental}/review', [ReviewController::class, 'store'])->name('reviews.store');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/reviews/{rental}/create', [ReviewController::class, 'create'])->name('reviews.create');
    Route::post('/reviews/{rental}', [ReviewController::class, 'store'])->name('reviews.store');
});

Route::get('/dashboard/reviews', [ReviewController::class, 'index'])
    ->middleware('auth')
    ->name('reviews.index');
