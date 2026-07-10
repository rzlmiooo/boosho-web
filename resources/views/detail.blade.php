<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $book->title }} - Detail Buku</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f8fafc; }
        /* Menghilangkan panah spinner pada input number */
        input[type="number"]::-webkit-inner-spin-button,
        input[type="number"]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
    </style>
</head>
<body class="text-gray-800">

    <!-- Navigasi -->
    <nav class="bg-white/90 backdrop-blur shadow-sm px-6 py-3 flex justify-between items-center border-b border-indigo-100 sticky top-0 z-50">
        <div class="flex items-center gap-8">
            <h1 class="text-2xl font-bold text-indigo-600 tracking-tight">BooSho<span class="text-indigo-400">.</span></h1>
            <div class="hidden md:flex gap-5">
                <a href="{{ route('dashboard') }}" class="text-sm font-medium text-gray-500 hover:text-indigo-600 transition">Dashboard</a>
                <a href="{{ route('katalog') }}" class="text-sm font-medium text-gray-500 hover:text-indigo-600 transition">Katalog Buku</a>

                @if(Auth::check() && Auth::user()->isAdmin())
                    <a href="{{ route('admin.orders') }}" class="text-sm font-medium text-gray-500 hover:text-indigo-600 transition">📋 Daftar Pesanan</a>
                @elseif(Auth::check())
                    <a href="{{ route('keranjang') }}" class="text-sm font-medium text-gray-500 hover:text-indigo-600 transition">🛒 Keranjang</a>
                @endif
            </div>
        </div>
        <div class="flex items-center gap-4">
        <!-- Profile Dropdown -->
        <div class="relative" x-data="{ open: false }">
            @if(Auth::check())
                <button @click="open = !open" @click.outside="open = false" class="flex items-center gap-2 focus:outline-none bg-indigo-50 hover:bg-indigo-100 border border-indigo-100 rounded-full pl-3 pr-1 py-1 transition group">
                    <span class="text-sm font-semibold text-indigo-700">{{ explode(' ', Auth::user()->name)[0] }}</span>
                    @if(Auth::user()->isAdmin())
                        <span class="text-[10px] bg-indigo-200 text-indigo-800 px-1.5 py-0.5 rounded-full font-bold uppercase tracking-wider">Admin</span>
                    @endif
                    <div class="w-8 h-8 rounded-full bg-indigo-200 flex items-center justify-center text-indigo-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </div>
                </button>

                <!-- Dropdown Menu -->
                <div x-show="open" 
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="transform opacity-0 scale-95"
                     x-transition:enter-end="transform opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-75"
                     x-transition:leave-start="transform opacity-100 scale-100"
                     x-transition:leave-end="transform opacity-0 scale-95"
                     class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-gray-100 py-1 z-50"
                     style="display: none;">
                    
                    <a href="{{ route('account') }}" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 hover:text-indigo-600 transition flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        My Account
                    </a>
                    
                    <hr class="border-gray-100 my-1">
                    
                    <form action="/logout" method="POST" class="w-full m-0" id="logout-form">
                        @csrf
                        <button type="button" onclick="konfirmasiLogout()" class="w-full text-left px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                            Logout
                        </button>
                    </form>
                </div>
            @else
                <a href="/login" class="text-sm text-indigo-600 font-semibold hover:text-indigo-800 transition">Login</a>
            @endif
        </div>
    </div>
</nav>

    <!-- Konten Utama -->
    <div class="max-w-screen-xl mx-auto px-4 sm:px-6 py-10">

        <!-- Tombol Kembali -->
        <a href="javascript:history.back()" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-indigo-600 transition mb-6 font-medium bg-white px-4 py-2 rounded-xl shadow-sm border border-gray-100 w-fit">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali
        </a>

        @if(session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-sm font-medium shadow-sm">✅ {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm font-medium shadow-sm">⚠️ {{ session('error') }}</div>
        @endif

        <!-- Card Container Utama -->
        <div class="bg-white rounded-[2rem] shadow-sm hover:shadow-md transition duration-300 border border-gray-100 overflow-hidden">
            <div class="flex flex-col md:flex-row">

                <!-- Sisi Kiri: Cover Buku -->
                <div class="w-full md:w-[40%] lg:w-[35%] bg-gray-50/50 p-8 flex items-start justify-center border-b md:border-b-0 md:border-r border-gray-100">
                    <div class="w-full max-w-[280px] aspect-[3/4] bg-white rounded-2xl shadow-lg overflow-hidden flex items-center justify-center relative border border-gray-100">
                        @if($book->cover)
                            <img src="{{ asset('storage/' . $book->cover) }}" alt="Cover {{ $book->title }}" class="w-full h-full object-cover">
                        @else
                            <div class="flex flex-col items-center justify-center text-gray-300">
                                <svg class="w-20 h-20 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                </svg>
                                <span class="text-sm font-medium">Tanpa Cover</span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Sisi Kanan: Detail & Aksi -->
                <div class="w-full md:w-[60%] lg:w-[65%] p-8 lg:p-10 flex flex-col">
                    <div class="flex-grow">

                        <!-- Judul dan Penulis -->
                        <div class="mb-8">
                            <h1 class="text-2xl md:text-4xl font-bold text-gray-900 leading-tight mb-2">{{ $book->title }}</h1>
                            <p class="text-lg md:text-xl text-gray-500 font-medium">Oleh <span class="text-gray-700">{{ $book->author }}</span></p>
                        </div>

                        <!-- Info Grid (Kategori, Harga, Stok) -->
                        <div class="grid grid-cols-2 lg:grid-cols-3 gap-y-8 gap-x-6 mb-8 p-6 bg-gray-50/50 rounded-2xl border border-gray-100">

                            <div class="flex flex-col">
                                <span class="text-xs text-gray-400 font-bold mb-1.5 uppercase tracking-wider">Kategori</span>
                                <span class="text-sm font-semibold text-gray-800">Umum / Fiksi</span>
                            </div>

                            <div class="flex flex-col">
                                <span class="text-xs text-gray-400 font-bold mb-1.5 uppercase tracking-wider">Harga</span>
                                <span class="text-xl font-bold text-blue-600">Rp {{ number_format($book->price, 0, ',', '.') }}</span>
                            </div>

                            <div class="flex flex-col">
                                <span class="text-xs text-gray-400 font-bold mb-1.5 uppercase tracking-wider">Status Stok</span>
                                <div>
                                    @if($book->stock <= 0)
                                        <span class="inline-block text-[11px] font-bold bg-red-50 text-red-500 border border-red-100 px-3 py-1 rounded-full">Habis</span>
                                    @else
                                        <span class="inline-block text-[11px] font-bold bg-green-50 text-green-600 border border-green-100 px-3 py-1 rounded-full">Tersedia ({{ $book->stock }})</span>
                                    @endif
                                </div>
                            </div>

                        </div>

                        <!-- Deskripsi -->
                        <div class="mb-8">
                            <h3 class="text-sm font-bold text-gray-800 mb-3 uppercase tracking-wider flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/></svg>
                                Deskripsi Buku
                            </h3>
                            <div class="text-sm text-gray-600 leading-relaxed text-justify">
                                @if($book->description)
                                    <p>{{ $book->description }}</p>
                                @else
                                    <p class="italic text-gray-400 bg-gray-50 p-4 rounded-xl border border-dashed border-gray-200">Belum ada sinopsis atau deskripsi untuk buku ini.</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Footer Action (Form Add to Cart) -->
                    <div class="pt-8 border-t border-gray-100">
                        @if(Auth::check() && !Auth::user()->isAdmin())
                            @if($book->stock > 0)
                                <form action="/cart/{{ $book->id }}" method="POST">
                                    @csrf
                                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-6">

                                        <!-- Input Qty -->
                                        <div class="w-full sm:w-auto">
                                            <label class="block text-xs text-gray-500 font-bold mb-2 uppercase tracking-wider">Jumlah</label>
                                            <div class="flex items-center bg-white border border-gray-200 rounded-xl px-2 py-1.5 shadow-sm w-fit">
                                                <button type="button" onclick="adjustQty(-1)"
                                                    class="w-10 h-10 rounded-lg bg-gray-50 hover:bg-blue-50 text-gray-600 hover:text-blue-600 active:scale-95 transition font-bold text-xl flex items-center justify-center">−</button>

                                                <input type="number" id="qty-input" name="quantity" value="1" min="1" max="{{ $book->stock }}"
                                                    class="w-16 text-center bg-transparent border-0 font-bold text-gray-800 text-lg focus:outline-none focus:ring-0">

                                                <button type="button" onclick="adjustQty(1)"
                                                    class="w-10 h-10 rounded-lg bg-gray-50 hover:bg-blue-50 text-gray-600 hover:text-blue-600 active:scale-95 transition font-bold text-xl flex items-center justify-center">+</button>
                                            </div>
                                        </div>

                                        <!-- Tombol Tambah Keranjang -->
                                        <div class="w-full sm:w-auto sm:flex-1 sm:mt-6">
                                            <button type="submit"
                                                class="w-full px-8 py-4 bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm rounded-xl transition shadow-md hover:shadow-lg hover:-translate-y-1 active:translate-y-0 flex items-center justify-center gap-2">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                                Tambah ke Keranjang
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            @else
                                <div class="px-6 py-4 bg-gray-100 text-gray-500 font-bold text-sm rounded-xl text-center border border-gray-200">
                                    Maaf, Stok Buku Habis
                                </div>
                            @endif
                        @elseif(Auth::check() && Auth::user()->isAdmin())
                            <div class="px-6 py-4 bg-yellow-50 text-yellow-700 font-bold text-sm rounded-xl border border-yellow-200">
                                Fitur keranjang hanya tersedia untuk pembeli (User).
                            </div>
                        @else
                            <a href="{{ route('login') }}" class="block w-full px-6 py-4 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-sm rounded-xl transition shadow-md hover:shadow-lg text-center">
                                Login untuk Menambahkan ke Keranjang
                            </a>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Script Logika Qty & Konfirmasi -->
    <script>
        @if(session('success'))
            Swal.fire({ icon: 'success', title: 'Berhasil!', text: '{{ session("success") }}', showConfirmButton: false, timer: 1800 });
        @endif
        @if(session('error'))
            Swal.fire({ icon: 'error', title: 'Oops!', text: '{{ session("error") }}', confirmButtonColor: '#2563eb' });
        @endif

        function adjustQty(delta) {
            const input = document.getElementById('qty-input');
            const maxStock = parseInt(input.getAttribute('max')) || 9999;
            let val = parseInt(input.value) + delta;

            if (val < 1) val = 1;
            if (val > maxStock) {
                val = maxStock;
                Swal.fire({
                    icon: 'warning',
                    title: 'Batas Stok',
                    text: `Maksimal pembelian untuk buku ini adalah ${maxStock} buah.`,
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000
                });
            }

            input.value = val;
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