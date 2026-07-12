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
                <a href="{{ route('dashboard') }}" class="text-sm font-medium text-gray-500 hover:text-indigo-600 transition">Home</a>
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
                                @if($book->discounted_price < $book->price)
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="text-xs text-gray-400 line-through">Rp {{ number_format($book->price, 0, ',', '.') }}</span>
                                        <span class="text-[10px] font-bold bg-red-100 text-red-600 px-1.5 py-0.5 rounded">{{ $book->discount_percent }}% OFF</span>
                                    </div>
                                    <span class="text-xl font-bold text-red-600">Rp {{ number_format($book->discounted_price, 0, ',', '.') }}</span>
                                @else
                                    <span class="text-xl font-bold text-blue-600">Rp {{ number_format($book->price, 0, ',', '.') }}</span>
                                @endif
                            </div>

                            <div class="flex flex-col">
                                <span class="text-xs text-gray-400 font-bold mb-1.5 uppercase tracking-wider">Ulasan</span>
                                <div>
                                    <button type="button" @click="$dispatch('open-review', { id: {{ $book->id }} })" class="flex items-center gap-2 bg-yellow-50 hover:bg-yellow-100 border border-yellow-200 px-3 py-1.5 rounded-full transition group shadow-sm">
                                        <span class="text-yellow-500 group-hover:scale-110 transition-transform">⭐</span>
                                        <span class="text-sm font-bold text-yellow-700">{{ $book->average_rating > 0 ? $book->average_rating : 'Belum direview' }}</span>
                                        @if($book->reviews->count() > 0)
                                            <span class="text-[10px] text-yellow-600 font-medium ml-1">({{ $book->reviews->count() }})</span>
                                        @endif
                                    </button>
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
                                        <div class="w-full sm:w-auto sm:flex-1 sm:mt-6 relative">
                                            <button type="submit"
                                                class="w-full px-8 py-4 bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm rounded-xl transition shadow-md hover:shadow-lg hover:-translate-y-1 active:translate-y-0 flex items-center justify-center gap-2">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                                Tambah ke Keranjang
                                            </button>
                                            <div class="absolute -top-3 -right-2 bg-green-100 border border-green-200 text-green-700 text-[10px] font-extrabold px-2 py-1 rounded-full shadow-sm">
                                                Stok: {{ $book->stock }}
                                            </div>
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
            
            <!-- SECTION TULIS ULASAN (DI BAWAH DETAIL) -->
            @if(Auth::check() && Auth::user()->role === 'user')
            <div class="bg-white p-8 rounded-[32px] shadow-sm border border-gray-100 mb-12">
                <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                    <span>✍️</span> Tulis Ulasan Anda
                </h3>
                <form action="/books/{{ $book->id }}/reviews" method="POST" class="flex flex-col gap-5">
                    @csrf
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Rating Bintang</label>
                        <select name="rating" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none transition">
                            <option value="5">⭐⭐⭐⭐⭐ (5) Sangat Bagus</option>
                            <option value="4">⭐⭐⭐⭐ (4) Bagus</option>
                            <option value="3">⭐⭐⭐ (3) Lumayan</option>
                            <option value="2">⭐⭐ (2) Buruk</option>
                            <option value="1">⭐ (1) Sangat Buruk</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Komentar</label>
                        <textarea name="comment" rows="3" required placeholder="Bagaimana menurutmu tentang buku ini?" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none transition resize-none"></textarea>
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold px-6 py-2.5 rounded-xl transition shadow-sm">Kirim Ulasan</button>
                    </div>
                </form>
            </div>
            @endif
            
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

    {{-- ===== MODAL REVIEW BUKU (Global Alpine.js Component) ===== --}}
    <div x-data="reviewModal()" @open-review.window="open($event.detail.id)" x-show="isOpen" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center">
        <!-- Backdrop -->
        <div x-show="isOpen" x-transition.opacity class="fixed inset-0 bg-black/60 backdrop-blur-sm" @click="close()"></div>
        
        <!-- Modal Content -->
        <div x-show="isOpen" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-8 scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 scale-100"
             x-transition:leave-end="opacity-0 translate-y-8 scale-95"
             class="relative bg-white w-full max-w-2xl mx-4 rounded-3xl shadow-2xl overflow-hidden flex flex-col max-h-[85vh]">
            
            <!-- Header -->
            <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between bg-white z-10">
                <div>
                    <h2 class="text-xl font-bold text-gray-900 line-clamp-1" x-text="'Ulasan: ' + bookTitle">Ulasan Buku</h2>
                    <div class="flex items-center gap-2 mt-1">
                        <span class="text-yellow-400 text-sm">⭐</span>
                        <span class="text-sm font-bold text-gray-700" x-text="averageRating + ' rata-rata'"></span>
                        <span class="text-sm text-gray-400" x-text="'(' + totalReviews + ' ulasan)'"></span>
                    </div>
                </div>
                <button @click="close()" class="p-2 bg-gray-50 hover:bg-red-50 text-gray-400 hover:text-red-500 rounded-full transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <!-- Filters -->
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-100 flex flex-wrap gap-4 z-10">
                <!-- Rating Filter -->
                <div class="flex items-center gap-2">
                    <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Bintang:</span>
                    <select x-model="filterRating" class="text-sm bg-white border border-gray-200 rounded-lg px-3 py-1.5 font-medium text-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="all">Semua</option>
                        <option value="5">5 Bintang</option>
                        <option value="4">4 Bintang</option>
                        <option value="3">3 Bintang</option>
                        <option value="2">2 Bintang</option>
                        <option value="1">1 Bintang</option>
                    </select>
                </div>
                
                <!-- Sentiment Filter -->
                <div class="flex items-center gap-2">
                    <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Kategori NLP:</span>
                    <select x-model="filterSentiment" class="text-sm bg-white border border-gray-200 rounded-lg px-3 py-1.5 font-medium text-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="all">Semua Sentimen</option>
                        <option value="positif">Positif</option>
                        <option value="kritis">Kritis</option>
                    </select>
                </div>
            </div>

            <!-- Content Body (Scrollable) -->
            <div class="flex-1 overflow-y-auto p-6 bg-gray-50/50">
                <!-- Loading State -->
                <div x-show="isLoading" class="flex flex-col items-center justify-center py-12">
                    <svg class="w-10 h-10 text-indigo-500 animate-spin mb-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    <span class="text-sm font-medium text-gray-500">Memuat ulasan NLP...</span>
                </div>

                <!-- Empty State -->
                <div x-show="!isLoading && filteredReviews.length === 0" class="flex flex-col items-center justify-center py-12 text-center" style="display: none;">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center text-3xl mb-4">😶</div>
                    <h3 class="text-gray-900 font-bold mb-1">Tidak ada ulasan ditemukan</h3>
                    <p class="text-sm text-gray-500">Cobalah mengubah filter pencarian Anda.</p>
                </div>

                <!-- Reviews List -->
                <div x-show="!isLoading && filteredReviews.length > 0" class="space-y-4" style="display: none;">
                    <template x-for="review in filteredReviews" :key="review.id">
                        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm transition hover:shadow-md">
                            <div class="flex justify-between items-start mb-3">
                                <div>
                                    <h4 class="font-bold text-gray-900 text-sm" x-text="review.user_name"></h4>
                                    <span class="text-xs text-gray-400" x-text="review.created_at_human"></span>
                                </div>
                                <div class="flex flex-col items-end gap-1.5">
                                    <span class="inline-flex items-center gap-1 bg-yellow-50 text-yellow-700 text-xs font-bold px-2 py-0.5 rounded-full border border-yellow-200">
                                        ⭐ <span x-text="review.rating + '/5'"></span>
                                    </span>
                                    <span :class="review.sentiment === 'positif' ? 'bg-green-50 text-green-700 border-green-200' : 'bg-red-50 text-red-700 border-red-200'" class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-extrabold uppercase tracking-wide border">
                                        NLP: <span x-text="review.sentiment"></span>
                                    </span>
                                </div>
                            </div>
                            <p class="text-gray-700 text-sm leading-relaxed" x-text="review.comment"></p>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('reviewModal', () => ({
                isOpen: false,
                isLoading: false,
                bookTitle: '',
                averageRating: 0,
                totalReviews: 0,
                reviews: [],
                filterRating: 'all',
                filterSentiment: 'all',
                
                open(id) {
                    this.isOpen = true;
                    this.isLoading = true;
                    this.reviews = [];
                    // Reset filters when opening new book
                    this.filterRating = 'all';
                    this.filterSentiment = 'all';
                    
                    fetch('/api/books/' + id + '/reviews')
                        .then(res => res.json())
                        .then(data => {
                            this.bookTitle = data.book_title;
                            this.averageRating = data.average_rating;
                            this.totalReviews = data.total_reviews;
                            this.reviews = data.reviews;
                            this.isLoading = false;
                        })
                        .catch(err => {
                            console.error(err);
                            this.isLoading = false;
                        });
                },
                close() {
                    this.isOpen = false;
                },
                get filteredReviews() {
                    return this.reviews.filter(review => {
                        const matchRating = this.filterRating === 'all' || review.rating.toString() === this.filterRating;
                        const matchSentiment = this.filterSentiment === 'all' || review.sentiment === this.filterSentiment;
                        return matchRating && matchSentiment;
                    });
                }
            }));
        });
    </script>
</body>
</html>