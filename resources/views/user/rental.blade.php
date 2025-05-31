@foreach ($rentals as $rental)
    <div class="p-4 border rounded mb-4">
        <p>Motor: {{ $rental->motor->nama_motor }}</p>
        <p>Status: {{ $rental->status }}</p>

        @if ($rental->status === 'selesai' && !$rental->review)
            <a href="{{ route('reviews.create', $rental->id) }}" class="btn btn-primary">
                Beri Review
            </a>
        @elseif ($rental->review)
            <p>✅ Sudah direview</p>
        @endif
    </div>
@endforeach
