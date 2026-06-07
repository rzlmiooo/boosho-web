<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Anda - BooSho</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style> body { font-family: 'Poppins', sans-serif; background-color: #f8fafc; } </style>
</head>
<body class="text-gray-800">

    <nav class="bg-white shadow-sm p-4 flex justify-between items-center border-b-2 border-blue-100">
        <div class="flex items-center gap-6">
            <h1 class="text-2xl font-bold text-blue-600 ml-4">BooSho.</h1>
            <div class="hidden md:flex gap-4">
                <a href="{{ route('dashboard') }}" class="font-semibold text-gray-500 hover:text-blue-600 transition">Dashboard</a>
                <a href="{{ route('katalog') }}" class="font-semibold text-gray-500 hover:text-blue-600 transition">Katalog Buku</a>
                <a href="{{ route('keranjang') }}" class="font-semibold text-blue-600 border-b-2 border-blue-600 pb-1">🛒 Keranjang</a>
            </div>
        </div>
    </nav>

    <div class="container mx-auto mt-8 p-4 max-w-4xl">
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <h2 class="text-xl font-bold text-gray-700 border-b pb-4 mb-6">Isi Keranjang Anda</h2>

            @if($carts->isEmpty())
                <div class="text-center py-10">
                    <p class="text-gray-500 mb-4">Keranjang Anda masih kosong.</p>
                    <a href="{{ route('katalog') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Mulai Belanja</a>
                </div>
            @else
                @php $totalHarga = 0; @endphp
                <div class="flex flex-col gap-4 mb-6">
                    @foreach($carts as $cart)
                    @php $subtotal = $cart->book->price * $cart->quantity; $totalHarga += $subtotal; @endphp
                    <div class="flex justify-between items-center border p-4 rounded bg-gray-50">
                        <div>
                            <h3 class="font-bold text-lg text-blue-600">{{ $cart->book->title }}</h3>
                            <p class="text-sm text-gray-500">Harga: Rp {{ number_format($cart->book->price, 0, ',', '.') }} x {{ $cart->quantity }}</p>
                        </div>
                        <div class="flex items-center gap-4">
                            <span class="font-bold text-gray-800">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                            <form action="/cart/{{ $cart->id }}" method="POST">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-500 font-bold hover:underline">Hapus</button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="border-t pt-4 flex justify-between items-center">
                    <span class="text-xl font-bold text-gray-700">Total Harga: <span class="text-blue-600">Rp {{ number_format($totalHarga, 0, ',', '.') }}</span></span>
                    
                    <form action="/checkout" method="POST" id="checkout-form">
                        @csrf
                        <button type="button" onclick="konfirmasiCheckout()" class="bg-green-600 text-white font-bold px-6 py-3 rounded-lg hover:bg-green-700 shadow-md transition">
                            Checkout Sekarang
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>

    <script>
        @if(session('success')) Swal.fire({ icon: 'success', title: 'Berhasil!', text: '{{ session("success") }}', showConfirmButton: false, timer: 1500 }); @endif
        @if(session('error')) Swal.fire({ icon: 'error', title: 'Oops...', text: '{{ session("error") }}', confirmButtonColor: '#2563eb' }); @endif

        function konfirmasiCheckout() {
            Swal.fire({
                title: 'Konfirmasi Pembelian', text: "Yakin ingin menyelesaikan pesanan ini?", icon: 'info',
                showCancelButton: true, confirmButtonColor: '#16a34a', cancelButtonColor: '#d33', confirmButtonText: 'Ya, Bayar!'
            }).then((result) => {
                if (result.isConfirmed) document.getElementById('checkout-form').submit();
            })
        }
    </script>
</body>
</html>