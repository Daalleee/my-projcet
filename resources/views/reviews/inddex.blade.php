@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="text-2xl font-bold mb-4">Daftar Ulasan</h1>

        @forelse ($reviews as $review)
            <div class="border p-4 rounded mb-3">
                <strong>{{ $review->user->name }}</strong> menyewa
                <em>{{ $review->rental->motor->nama_motor ?? 'Motor tidak ditemukan' }}</em><br>
                Rating: ⭐ {{ $review->rating }}<br>
                Komentar: {{ $review->comment }}
            </div>
        @empty
            <p>Belum ada ulasan.</p>
        @endforelse
    </div>
@endsection
@foreach ($rentals as $rental)
    <div class="border p-3 mb-3">
        <p>Motor: {{ $rental->motor->nama_motor }}</p>
        <p>Status: {{ $rental->status }}</p>

        @if ($rental->status === 'selesai' && !$rental->review)
            <a href="{{ route('reviews.create', $rental->id) }}" class="bg-blue-500 text-white px-3 py-1 rounded">
                Beri Review
            </a>
        @elseif ($rental->review)
            <p class="text-green-600">✅ Sudah direview</p>
        @endif
    </div>
@endforeach
