@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Beri Ulasan untuk Penyewaan Motor: {{ $rental->motor->nama_motor }}</h2>

        <form action="{{ route('reviews.store', $rental->id) }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="rating">Rating (1-5)</label>
                <input type="number" name="rating" id="rating" min="1" max="5" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="comment">Komentar (opsional)</label>
                <textarea name="comment" id="comment" class="form-control" rows="4"></textarea>
            </div>

            <button type="submit" class="btn btn-success">Kirim Review</button>
        </form>
    </div>
@endsection
