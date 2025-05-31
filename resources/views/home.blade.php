<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rental Motor</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <nav class="bg-gray-800 text-white p-4">
        <div class="container mx-auto flex justify-between">
            <a href="{{ route('home') }}" class="font-bold">Rental Motor</a>
            <div>
                @guest
                    <a href="{{ route('login') }}" class="mr-4">Login</a>
                    <a href="{{ route('register') }}">Register</a>
                @endguest

                @auth
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="ml-4">Logout</button>
                    </form>
                @endauth
            </div>


        </div>
    </nav>
    <main class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-6 text-center">Daftar Motor Tersedia</h1>
        @if (session('error'))
            <div class="bg-red-100 text-red-700 p-4 mb-4 rounded">{{ session('error') }}</div>
        @endif
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @forelse ($motors as $motor)
                <div class="border p-6 rounded-lg shadow-lg">
                    @if ($motor->image)
                        <img src="{{ asset('storage/' . $motor->image) }}" alt="{{ $motor->brand }} {{ $motor->model }}"
                            class="w-full h-48 object-cover rounded mb-4">
                    @else
                        <div class="w-full h-48 bg-gray-200 flex items-center justify-center rounded mb-4">
                            <span class="text-gray-500">Tidak ada gambar</span>
                        </div>
                    @endif
                    <h2 class="text-xl font-semibold mb-2">{{ $motor->brand }} {{ $motor->model }}</h2>
                    <p class="text-sm text-gray-500 mb-2">Nomor Plat: {{ $motor->plate_number }}</p>
                    <p class="text-green-600 text-sm mb-2">Dapat jas hujan</p>
                    <p class="font-bold text-blue-600">Harga: Rp
                        {{ number_format($motor->rental_price_per_day, 0, ',', '.') }}/hari</p>
                    <a href="https://wa.me/6281337063361?text=Saya%20ingin%20menyewa%20motor%20{{ urlencode($motor->brand . ' ' . $motor->model) }}%20(Nomor%20Plat:%20{{ urlencode($motor->plate_number) }})%20dengan%20harga%20Rp%20{{ number_format($motor->rental_price_per_day, 0, ',', '.') }}%20per%20hari.%20Mohon%20info%20ketersediaan!"
                        class="bg-blue-500 text-white px-4 py-2 rounded mt-4 inline-block" target="_blank">Sewa
                        Sekarang</a>
                </div>
            @empty
                <p class="text-center text-gray-500">Tidak ada motor tersedia saat ini.</p>
            @endforelse
        </div>
        {{ $motors->links() }}
    </main>
</body>

</html>
