<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - BooSho</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style> body { font-family: 'Poppins', sans-serif; background-color: #f8fafc; } </style>
</head>
<body class="text-gray-800">

    <nav class="bg-white shadow-sm p-4 flex justify-between items-center border-b-2 border-blue-100 sticky top-0 z-10">
        <div class="flex items-center gap-6">
            <h1 class="text-2xl font-bold text-blue-600 ml-4">BooSho.</h1>
            <div class="hidden md:flex gap-4">
                <a href="{{ route('dashboard') }}" class="font-semibold text-blue-600 border-b-2 border-blue-600 pb-1">Dashboard</a>
                <a href="{{ route('katalog') }}" class="font-semibold text-gray-500 hover:text-blue-600 transition">Katalog Buku</a>
            </div>
        </div>
        <div class="mr-4 flex items-center gap-4">
            <span class="font-semibold text-gray-600">Halo, {{ Auth::user()->name }} <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded-full ml-1">{{ Auth::user()->role }}</span></span>
            <form action="{{ route('logout') }}" method="POST" class="inline" id="logout-form">
                @csrf
                <button type="button" onclick="konfirmasiLogout()" class="text-red-500 font-semibold hover:text-red-700 transition">Logout</button>
            </form>
        </div>
    </nav>

    <div class="container mx-auto mt-8 p-4">
        <div class="bg-gradient-to-r from-blue-600 to-blue-400 p-8 rounded-xl shadow-md text-white mb-8">
            <h2 class="text-3xl font-bold mb-2">Sistem Administrasi Digital BooSho</h2>
            <p class="text-blue-50 max-w-2xl text-lg leading-relaxed">
                Platform berbasis web yang dirancang khusus untuk meningkatkan efisiensi manajemen data toko buku. Sistem ini mempermudah pencatatan stok, pengelolaan harga, dan memberikan pengalaman antarmuka yang bersih serta terstruktur.
            </p>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <div class="flex justify-between items-center mb-6 border-b pb-4">
                <h2 class="text-xl font-bold text-gray-700">Preview Buku Terbaru</h2>
                <a href="{{ route('katalog') }}" class="text-sm font-semibold text-blue-600 hover:underline">Lihat Seluruh Katalog &rarr;</a>
            </div>

            @if($previewBooks->isEmpty())
                <div class="text-center py-8 text-gray-500">Belum ada data buku.</div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    @foreach($previewBooks as $book)
                    <div class="border border-gray-100 rounded-lg p-5 bg-gray-50 hover:bg-white hover:shadow-md transition">
                        <h3 class="font-bold text-lg text-gray-800">{{ $book->title }}</h3>
                        <p class="text-sm text-gray-500 mt-1">{{ $book->author }}</p>
                        <p class="mt-3 font-bold text-blue-600">Rp {{ number_format($book->price, 0, ',', '.') }}</p>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <script>
        function konfirmasiLogout() {
            Swal.fire({
                title: 'Yakin ingin keluar?', icon: 'warning', showCancelButton: true,
                confirmButtonColor: '#d33', cancelButtonColor: '#3085d6', confirmButtonText: 'Ya, Logout!'
            }).then((result) => {
                if (result.isConfirmed) document.getElementById('logout-form').submit();
            })
        }
    </script>
</body>
</html>