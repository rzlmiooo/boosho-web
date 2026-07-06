<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Belanja - BooSho</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f8fafc; }
    </style>
</head>
<body class="text-gray-800">

    <!-- Navigasi -->
    <nav class="bg-white shadow-sm px-6 py-4 flex justify-between items-center border-b border-gray-100 sticky top-0 z-10">
        <div class="flex items-center gap-6">
            <h1 class="text-2xl font-bold text-blue-600">BooSho.</h1>
            <div class="hidden md:flex gap-1">
                <a href="{{ route('dashboard') }}"
                   class="px-4 py-2 rounded-lg text-sm font-semibold text-gray-500 hover:text-blue-600 hover:bg-gray-50 transition">
                    Dashboard
                </a>
                @if(!Auth::user()->isAdmin())
                    <a href="{{ route('keranjang') }}"
                       class="px-4 py-2 rounded-lg text-sm font-semibold bg-blue-50 text-blue-600 border border-blue-100">
                        🛒 Keranjang
                    </a>
                @endif
            </div>
        </div>
        <div class="flex items-center gap-3">
            <span class="font-semibold text-gray-600 text-sm">
                Halo, {{ Auth::user()->name }}
                <span class="ml-1 text-xs font-bold bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full">{{ Auth::user()->role }}</span>
            </span>
            <form action="{{ route('logout') }}" method="POST" class="inline" id="logout-form">
                @csrf
                <button type="button" onclick="konfirmasiLogout()"
                    class="text-sm text-red-500 font-semibold hover:text-red-700 hover:bg-red-50 px-3 py-1.5 rounded-lg transition">
                    Logout
                </button>
            </form>
        </div>
    </nav>

    <!-- Konten -->
    <div class="max-w-screen-lg mx-auto px-4 sm:px-6 py-8">

        <!-- Header -->
        <div class="flex items-center gap-4 mb-6">
            <a href="{{ route('dashboard') }}"
                class="text-sm text-gray-500 hover:text-blue-600 font-semibold flex items-center gap-1.5 hover:bg-gray-100 px-3 py-2 rounded-lg transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali Belanja
            </a>
            <div class="h-5 w-px bg-gray-200"></div>
            <div>
                <h1 class="text-xl font-bold text-gray-800">🛒 Keranjang Belanja</h1>
            </div>
        </div>

        @if($carts->isEmpty())
            <!-- Empty State -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-16 text-center">
                <div class="text-5xl mb-4">🛒</div>
                <h3 class="text-lg font-bold text-gray-700 mb-2">Keranjang Masih Kosong</h3>
                <p class="text-sm text-gray-400 mb-6">Tambahkan buku dari katalog untuk memulai belanja.</p>
                <a href="{{ route('dashboard') }}"
                    class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-bold px-6 py-3 rounded-xl transition shadow-sm">
                    📖 Jelajahi Katalog Buku
                </a>
            </div>

        @else
            @php $totalHarga = 0; @endphp

            <div class="flex flex-col lg:flex-row gap-6">

                <!-- Daftar Item -->
                <div class="flex-1">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                            <h2 class="font-bold text-gray-800">Item Pesanan</h2>
                            <span class="text-xs text-gray-400 bg-gray-50 border border-gray-200 px-2.5 py-1 rounded-full font-semibold">{{ $carts->count() }} item</span>
                        </div>

                        <div class="p-4 flex flex-col gap-3">
                            @foreach($carts as $cart)
                            @php $subtotal = $cart->book->price * $cart->quantity; $totalHarga += $subtotal; @endphp
                            <div class="flex flex-col sm:flex-row sm:items-center gap-4 border border-gray-100 rounded-xl p-4 hover:border-blue-100 hover:bg-blue-50/20 transition">

                                <!-- Book Info -->
                                <div class="flex items-center gap-3 flex-1 min-w-0">
                                    <div class="w-11 h-11 rounded-xl bg-blue-50 border border-blue-100 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                        </svg>
                                    </div>
                                    <div class="min-w-0">
                                        <h3 class="font-bold text-blue-600 text-sm truncate">{{ $cart->book->title }}</h3>
                                        <p class="text-xs text-gray-400">{{ $cart->book->author }}</p>
                                        <p class="text-xs text-gray-500 font-semibold mt-0.5">Rp {{ number_format($cart->book->price, 0, ',', '.') }} / buku</p>
                                    </div>
                                </div>

                                <!-- Controls -->
                                <div class="flex items-center gap-4 sm:flex-shrink-0">
                                    <!-- Quantity Control -->
                                    <div class="flex items-center gap-1 bg-gray-50 border border-gray-200 rounded-xl p-1">
                                        <form action="/cart/{{ $cart->id }}/update" method="POST" class="inline">
                                            @csrf
                                            <input type="hidden" name="action" value="decrease">
                                            <button type="submit"
                                                class="w-8 h-8 rounded-lg bg-white border border-gray-200 text-gray-600 hover:bg-gray-100 active:scale-90 transition font-bold flex items-center justify-center shadow-sm text-base">
                                                −
                                            </button>
                                        </form>
                                        <span class="w-9 text-center font-bold text-gray-800 text-sm select-none">{{ $cart->quantity }}</span>
                                        <form action="/cart/{{ $cart->id }}/update" method="POST" class="inline">
                                            @csrf
                                            <input type="hidden" name="action" value="increase">
                                            <button type="submit"
                                                class="w-8 h-8 rounded-lg bg-white border border-gray-200 text-gray-600 hover:bg-gray-100 active:scale-90 transition font-bold flex items-center justify-center shadow-sm text-base">
                                                +
                                            </button>
                                        </form>
                                    </div>

                                    <!-- Subtotal -->
                                    <div class="text-right w-28 flex-shrink-0">
                                        <p class="text-[10px] text-gray-400 uppercase tracking-wider">Subtotal</p>
                                        <p class="font-bold text-gray-800 text-sm">Rp {{ number_format($subtotal, 0, ',', '.') }}</p>
                                    </div>

                                    <!-- Delete -->
                                    <form action="/cart/{{ $cart->id }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                            class="p-2 text-red-400 hover:text-red-600 bg-red-50 hover:bg-red-100 border border-red-100 rounded-xl transition"
                                            title="Hapus dari keranjang">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Ringkasan Pesanan -->
                <div class="lg:w-72 flex-shrink-0">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 sticky top-24">
                        <h3 class="font-bold text-gray-800 mb-4 pb-4 border-b border-gray-100">Ringkasan Pesanan</h3>

                        <div class="space-y-2 mb-4">
                            @foreach($carts as $cart)
                            @php $s = $cart->book->price * $cart->quantity; @endphp
                            <div class="flex justify-between items-center text-xs">
                                <span class="text-gray-500 truncate max-w-[140px]">{{ $cart->book->title }} (×{{ $cart->quantity }})</span>
                                <span class="text-gray-700 font-semibold flex-shrink-0 ml-2">Rp {{ number_format($s, 0, ',', '.') }}</span>
                            </div>
                            @endforeach
                        </div>

                        <div class="border-t border-gray-100 pt-4 mb-5">
                            <div class="flex justify-between items-center">
                                <span class="font-bold text-gray-700">Total</span>
                                <span class="font-bold text-xl text-blue-600">Rp {{ number_format($totalHarga, 0, ',', '.') }}</span>
                            </div>
                            <p class="text-[10px] text-gray-400 mt-1">Kode pembayaran dikirim admin setelah checkout</p>
                        </div>

                        <form action="/checkout" method="POST" id="checkout-form">
                            @csrf
                            <button type="button" onclick="konfirmasiCheckout()"
                                class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3.5 rounded-xl text-sm transition shadow-sm active:scale-95 flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Checkout Sekarang
                            </button>
                        </form>

                        <div class="mt-3 flex items-start gap-2 bg-blue-50 border border-blue-100 rounded-xl p-3">
                            <svg class="w-4 h-4 text-blue-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="text-[10px] text-blue-600 leading-relaxed">Setelah checkout, admin akan memberikan kode Virtual Account untuk menyelesaikan pembayaran.</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <script>
        @if(session('success')) Swal.fire({ icon: 'success', title: 'Berhasil!', text: '{{ session("success") }}', showConfirmButton: false, timer: 1800 }); @endif
        @if(session('error')) Swal.fire({ icon: 'error', title: 'Oops!', text: '{{ session("error") }}', confirmButtonColor: '#2563eb' }); @endif

        function konfirmasiCheckout() {
            Swal.fire({
                title: '🛒 Konfirmasi Checkout',
                text: "Setelah checkout, admin akan mengirimkan kode pembayaran Virtual Account kepada Anda.",
                icon: 'info', showCancelButton: true,
                confirmButtonColor: '#16a34a', cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Checkout!', cancelButtonText: 'Batal'
            }).then((r) => { if (r.isConfirmed) document.getElementById('checkout-form').submit(); })
        }

        function konfirmasiLogout() {
            Swal.fire({
                title: 'Logout?', icon: 'warning', showCancelButton: true,
                confirmButtonColor: '#d33', cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Keluar!', cancelButtonText: 'Batal'
            }).then((r) => { if (r.isConfirmed) document.getElementById('logout-form').submit(); })
        }
    </script>
</body>
</html>