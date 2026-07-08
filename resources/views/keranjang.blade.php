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
                    @php 
                        $subtotal = $cart->book->price * $cart->quantity; 
                        $totalHarga += $subtotal; 
                    @endphp
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center border p-4 rounded bg-gray-50 gap-4">
                        <div class="flex-1">
                            <a href="/books/{{ $cart->book->id }}" class="font-bold text-lg text-blue-600 hover:underline hover:text-blue-800 transition">
                                {{ $cart->book->title }}
                            </a>
                            <p class="text-sm text-gray-500 mt-1">Harga: Rp {{ number_format($cart->book->price, 0, ',', '.') }}</p>
                            
                            <div class="flex items-center mt-3">
                                <button type="button" onclick="updateQty({{ $cart->id }}, -1, {{ $cart->book->stock }})" class="px-3 py-1 bg-gray-200 text-gray-700 rounded-l-lg hover:bg-gray-300 font-bold transition">
                                    -
                                </button>
                                
                                <input type="number" id="qty-{{ $cart->id }}" value="{{ $cart->quantity }}" min="1" max="{{ $cart->book->stock }}" class="w-14 text-center border-y border-gray-200 py-1 focus:outline-none text-gray-800 font-semibold pointer-events-none" readonly>
                                
                                <button type="button" onclick="updateQty({{ $cart->id }}, 1, {{ $cart->book->stock }})" class="px-3 py-1 bg-gray-200 text-gray-700 rounded-r-lg hover:bg-gray-300 font-bold transition">
                                    +
                                </button>
                                
                                <span class="text-xs text-green-600 font-semibold ml-3">(Sisa Stok: {{ $cart->book->stock }})</span>
                            </div>
                        </div>

                        <div class="flex items-center gap-6">
                            <form action="/cart/{{ $cart->id }}" method="POST">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-500 font-bold hover:underline text-sm">Hapus</button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="border-t pt-4 flex flex-col sm:flex-row justify-between items-center gap-4">
                    <span class="text-lg font-bold text-gray-700">Estimasi Tagihan: <br class="hidden sm:block"> 
                        <span class="text-blue-600 text-xl" id="grand-total-text">Rp {{ number_format($totalHarga, 0, ',', '.') }}</span>
                    </span>
                    
                    <form action="/checkout" method="POST" id="checkout-form">
                        @csrf
                        @foreach($carts as $cart)
                            <input type="hidden" name="quantities[{{ $cart->id }}]" id="hidden-qty-{{ $cart->id }}" value="{{ $cart->quantity }}">
                            <input type="hidden" id="price-{{ $cart->id }}" value="{{ $cart->book->price }}">
                        @endforeach

                        <button type="button" onclick="konfirmasiCheckout()" class="bg-green-600 text-white font-bold px-6 py-3 rounded-lg hover:bg-green-700 shadow-md transition w-full sm:w-auto">
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

        // Fungsi baru untuk sinkronisasi jumlah + dan -
        // Fungsi untuk sinkronisasi jumlah + dan -
        function updateQty(itemId, perubahan, batasStok) {
            let inputQty = document.getElementById('qty-' + itemId); 
            let hiddenQty = document.getElementById('hidden-qty-' + itemId); 
            
            let nilaiSekarang = parseInt(inputQty.value);
            let nilaiBaru = nilaiSekarang + perubahan;

            if (nilaiBaru >= 1 && nilaiBaru <= batasStok) {
                inputQty.value = nilaiBaru; 
                hiddenQty.value = nilaiBaru; 
                updateTotalTagihan(); // Panggil fungsi hitung harga secara Live
            } else if (nilaiBaru > batasStok) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Stok Terbatas',
                    text: 'Maaf, Anda tidak bisa membeli melebihi sisa stok yang ada (' + batasStok + ' buku).',
                    confirmButtonColor: '#2563eb'
                });
            }
        }

        // KODE BARU: Fungsi untuk menghitung ulang semua harga
        function updateTotalTagihan() {
            let total = 0;
            
            // Loop semua input tersembunyi yang menyimpan jumlah buku
            document.querySelectorAll('input[id^="hidden-qty-"]').forEach(function(input) {
                let itemId = input.id.replace('hidden-qty-', ''); // Ambil ID item
                let qty = parseInt(input.value); // Ambil jumlah barang saat ini
                let price = parseInt(document.getElementById('price-' + itemId).value); // Ambil harga satuan
                
                total += (qty * price); // Jumlahkan
            });

            // Format gaya Rupiah (titik per ribuan)
            let formatRupiah = new Intl.NumberFormat('id-ID').format(total);
            
            // Tembakkan hasilnya ke layar
            document.getElementById('grand-total-text').innerHTML = 'Rp ' + formatRupiah;
        }
    </script>
</body>
</html>