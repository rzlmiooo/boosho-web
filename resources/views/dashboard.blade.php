<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - BooSho</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f8fafc; }
    </style>
</head>
<body class="text-gray-800" x-data>

    <nav class="bg-white/90 backdrop-blur shadow-sm px-6 py-3 flex justify-between items-center border-b border-indigo-100 sticky top-0 z-50">
        <div class="flex items-center gap-8">
            <h1 class="text-2xl font-bold text-indigo-600 tracking-tight">BooSho<span class="text-indigo-400">.</span></h1>
            <div class="hidden md:flex gap-5">
                <a href="{{ route('dashboard') }}" class="text-sm font-semibold text-indigo-600 border-b-2 border-indigo-500 pb-0.5">Home</a>
                <a href="{{ route('katalog') }}" class="text-sm font-medium text-gray-500 hover:text-indigo-600 transition">Katalog Buku</a>

                @if(!Auth::user()->isAdmin())
                    <a href="{{ route('keranjang') }}" class="text-sm font-medium text-gray-500 hover:text-indigo-600 transition">🛒 Keranjang</a>
                @else
                    <a href="{{ route('admin.orders') }}" class="text-sm font-medium text-gray-500 hover:text-indigo-600 transition">📋 Daftar Pesanan</a>
                @endif
            </div>
        </div>
        
        <div class="flex items-center gap-2">
            @if(Auth::check())
                @include('partials.notification-bell')
            @endif
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
                        
                        <form action="{{ route('logout') }}" method="POST" class="w-full m-0" id="logout-form">
                            @csrf
                            <button type="button" onclick="konfirmasiLogout()" class="w-full text-left px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                Logout
                            </button>
                        </form>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="text-sm text-indigo-600 font-semibold hover:text-indigo-800 transition">Login</a>
                @endif
            </div>
        </div>
    </nav>

    <!-- Hero / Jumbotron Section -->
    <div class="bg-gradient-to-r from-indigo-600 via-blue-600 to-indigo-700 text-white py-12 px-6 sm:px-12">
        <div class="max-w-screen-xl mx-auto flex flex-col md:flex-row items-center justify-between gap-8">
            <div class="max-w-2xl">
                <span class="bg-white/20 text-white text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider">🌟 Selamat Datang di BooSho</span>
                <h2 class="text-3xl sm:text-4xl font-extrabold mt-3 leading-tight">Jelajahi Dunia Pengetahuan & Imajinasi Tanpa Batas</h2>
                <p class="mt-4 text-indigo-100 text-sm sm:text-base leading-relaxed">
                    BooSho menyediakan koleksi buku digital terlengkap mulai dari teknologi, sains, sejarah, fiksi, hingga novel romantis. Mulailah perjalanan membaca Anda hari ini dengan kemudahan pembayaran digital.
                </p>
                <div class="mt-6 flex flex-wrap gap-4">
                    <a href="{{ route('katalog') }}" class="bg-white text-indigo-700 hover:bg-indigo-50 font-bold px-6 py-3 rounded-xl transition shadow-sm">
                        Mulai Cari Buku
                    </a>
                </div>
            </div>
            <div class="hidden md:block w-72 h-44 bg-white/10 backdrop-blur border border-white/20 rounded-2xl p-6 flex flex-col justify-between">
                <span class="text-4xl">📖</span>
                <div>
                    <h4 class="font-bold text-sm">Promo Hari Ini</h4>
                    <p class="text-xs text-indigo-200 mt-1">Diskon hingga 50% untuk kategori terpilih.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-screen-xl mx-auto px-4 sm:px-6 py-12">

        <!-- 1. SECTION REKOMENDASI BUKU -->
        <section class="mb-12">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                        <span>✨</span> Rekomendasi Untuk Anda
                    </h2>
                    <p class="text-xs text-gray-400 mt-1">Buku-buku pilihan cerdas berdasarkan riwayat kunjungan Anda</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($recommendedBooks as $book)
                    <div onclick="window.location='/books/{{ $book->id }}'" class="cursor-pointer border border-gray-200/80 rounded-2xl hover:shadow-lg hover:-translate-y-1 transition duration-300 bg-white flex flex-col group overflow-hidden">
                        <div class="w-full aspect-[3/4] bg-gray-100 relative overflow-hidden flex items-center justify-center">
                            @if($book->cover)
                                <img src="{{ asset('storage/' . $book->cover) }}" alt="Cover {{ $book->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                            @else
                                <div class="w-full h-full flex flex-col items-center justify-center bg-gray-50 text-gray-300">
                                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                </div>
                            @endif

                            @if($book->category)
                                <span class="absolute top-3 left-3 bg-indigo-600 text-white text-[10px] font-bold px-2.5 py-0.5 rounded-full shadow-sm">
                                    {{ $book->category }}
                                </span>
                            @endif
                        </div>

                        <div class="p-5 flex flex-col flex-grow justify-between">
                            <div>
                                <h3 class="font-bold text-gray-800 text-sm leading-snug mb-1 line-clamp-2">{{ $book->title }}</h3>
                                <p class="text-xs text-gray-400 mb-3">{{ $book->author }}</p>
                                
                                @if($book->genres)
                                    <div class="flex flex-wrap gap-1 mb-4">
                                        @foreach(array_slice($book->genres, 0, 2) as $genre)
                                            <span class="text-[9px] bg-slate-100 text-slate-600 font-semibold px-2 py-0.5 rounded-full border border-slate-200">
                                                {{ $genre }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif
                            </div>

                            <div>
                                <div class="flex justify-between items-center mb-4">
                                    <div>
                                        @if($book->discounted_price < $book->price)
                                            <span class="text-xs text-gray-400 line-through">Rp {{ number_format($book->price, 0, ',', '.') }}</span>
                                            <p class="font-bold text-indigo-600 text-sm">Rp {{ number_format($book->discounted_price, 0, ',', '.') }}</p>
                                        @else
                                            <p class="font-bold text-indigo-600 text-sm">Rp {{ number_format($book->price, 0, ',', '.') }}</p>
                                        @endif
                                    </div>
                                    <button type="button" @click.stop="$dispatch('open-review', { id: {{ $book->id }} })" class="flex items-center gap-1.5 bg-yellow-50 hover:bg-yellow-100 border border-yellow-200 px-2.5 py-1 rounded-full transition group shadow-sm">
                                        <span class="text-yellow-500 group-hover:scale-110 transition-transform">⭐</span>
                                        <span class="text-xs font-bold text-yellow-700">{{ $book->average_rating > 0 ? $book->average_rating : 'Baru' }}</span>
                                    </button>
                                </div>

                                <div class="pt-3 border-t border-gray-100 relative">
                                    <a href="/books/{{ $book->id }}" onclick="event.stopPropagation()" class="block w-full text-center py-2 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-bold text-xs rounded-xl transition">
                                        Lihat Detail
                                    </a>
                                    @if($book->stock > 0)
                                        <div class="absolute -top-2 -right-1 bg-green-100 border border-green-200 text-green-700 text-[9px] font-extrabold px-1.5 py-0.5 rounded-full shadow-sm">
                                            Stok: {{ $book->stock }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        <!-- 2. SECTION RILISAN TERBARU -->
        <section class="mb-12">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                        <span>🚀</span> Rilisan Buku Terbaru
                    </h2>
                    <p class="text-xs text-gray-400 mt-1">Koleksi buku segar yang baru saja mendarat di BooSho</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($latestBooks as $book)
                    <div onclick="window.location='/books/{{ $book->id }}'" class="cursor-pointer border border-gray-200/80 rounded-2xl hover:shadow-lg hover:-translate-y-1 transition duration-300 bg-white flex flex-col group overflow-hidden relative">
                        <!-- 'New' Badge -->
                        <span class="absolute top-3 right-3 bg-green-500 text-white text-[9px] font-bold px-2 py-0.5 rounded-full z-10 shadow-sm uppercase tracking-wide">
                            Baru
                        </span>

                        <div class="w-full aspect-[3/4] bg-gray-100 relative overflow-hidden flex items-center justify-center">
                            @if($book->cover)
                                <img src="{{ asset('storage/' . $book->cover) }}" alt="Cover {{ $book->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                            @else
                                <div class="w-full h-full flex flex-col items-center justify-center bg-gray-50 text-gray-300">
                                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                </div>
                            @endif

                            @if($book->category)
                                <span class="absolute top-3 left-3 bg-blue-600 text-white text-[10px] font-bold px-2.5 py-0.5 rounded-full shadow-sm">
                                    {{ $book->category }}
                                </span>
                            @endif
                        </div>

                        <div class="p-5 flex flex-col flex-grow justify-between">
                            <div>
                                <h3 class="font-bold text-gray-800 text-sm leading-snug mb-1 line-clamp-2">{{ $book->title }}</h3>
                                <p class="text-xs text-gray-400 mb-3">{{ $book->author }}</p>
                                
                                @if($book->genres)
                                    <div class="flex flex-wrap gap-1 mb-4">
                                        @foreach(array_slice($book->genres, 0, 2) as $genre)
                                            <span class="text-[9px] bg-slate-100 text-slate-600 font-semibold px-2 py-0.5 rounded-full border border-slate-200">
                                                {{ $genre }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif
                            </div>

                            <div>
                                <div class="flex justify-between items-center mb-4">
                                    <div>
                                        @if($book->discounted_price < $book->price)
                                            <span class="text-xs text-gray-400 line-through">Rp {{ number_format($book->price, 0, ',', '.') }}</span>
                                            <p class="font-bold text-indigo-600 text-sm">Rp {{ number_format($book->discounted_price, 0, ',', '.') }}</p>
                                        @else
                                            <p class="font-bold text-indigo-600 text-sm">Rp {{ number_format($book->price, 0, ',', '.') }}</p>
                                        @endif
                                    </div>
                                    <button type="button" @click.stop="$dispatch('open-review', { id: {{ $book->id }} })" class="flex items-center gap-1.5 bg-yellow-50 hover:bg-yellow-100 border border-yellow-200 px-2.5 py-1 rounded-full transition group shadow-sm">
                                        <span class="text-yellow-500 group-hover:scale-110 transition-transform">⭐</span>
                                        <span class="text-xs font-bold text-yellow-700">{{ $book->average_rating > 0 ? $book->average_rating : 'Baru' }}</span>
                                    </button>
                                </div>

                                <div class="pt-3 border-t border-gray-100 relative">
                                    <a href="/books/{{ $book->id }}" onclick="event.stopPropagation()" class="block w-full text-center py-2 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-bold text-xs rounded-xl transition">
                                        Lihat Detail
                                    </a>
                                    @if($book->stock > 0)
                                        <div class="absolute -top-2 -right-1 bg-green-100 border border-green-200 text-green-700 text-[9px] font-extrabold px-1.5 py-0.5 rounded-full shadow-sm">
                                            Stok: {{ $book->stock }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        <!-- 3. SECTION PROMO DISKON SPESIAL -->
        <section class="mb-12">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                        <span>🔥</span> Spesial Promo Diskon
                    </h2>
                    <p class="text-xs text-gray-400 mt-1">Dapatkan penawaran harga terbaik terbatas waktu hari ini!</p>
                </div>
            </div>

            @if($discountedBooks->isEmpty())
                <div class="text-center py-12 bg-white rounded-2xl border border-gray-100 text-gray-400">
                    <p class="font-medium text-sm">Sedang tidak ada buku berdiskon aktif saat ini.</p>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    @foreach($discountedBooks as $book)
                        <div onclick="window.location='/books/{{ $book->id }}'" class="cursor-pointer border border-gray-200/80 rounded-2xl hover:shadow-lg hover:-translate-y-1 transition duration-300 bg-white flex flex-col group overflow-hidden relative">
                            <!-- Discount Badge -->
                            <span class="absolute top-3 right-3 bg-red-500 text-white text-[10px] font-extrabold px-2.5 py-1 rounded-full z-10 shadow-sm uppercase tracking-wide animate-pulse">
                                {{ $book->discount_percent }}% OFF
                            </span>

                            <div class="w-full aspect-[3/4] bg-gray-100 relative overflow-hidden flex items-center justify-center">
                                @if($book->cover)
                                    <img src="{{ asset('storage/' . $book->cover) }}" alt="Cover {{ $book->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                                @else
                                    <div class="w-full h-full flex flex-col items-center justify-center bg-gray-50 text-gray-300">
                                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                    </div>
                                @endif

                                @if($book->category)
                                    <span class="absolute top-3 left-3 bg-blue-600 text-white text-[10px] font-bold px-2.5 py-0.5 rounded-full shadow-sm">
                                        {{ $book->category }}
                                    </span>
                                @endif
                            </div>

                            <div class="p-5 flex flex-col flex-grow justify-between">
                                <div>
                                    <h3 class="font-bold text-gray-800 text-sm leading-snug mb-1 line-clamp-2">{{ $book->title }}</h3>
                                    <p class="text-xs text-gray-400 mb-3">{{ $book->author }}</p>
                                    
                                    @if($book->genres)
                                        <div class="flex flex-wrap gap-1 mb-4">
                                            @foreach(array_slice($book->genres, 0, 2) as $genre)
                                                <span class="text-[9px] bg-slate-100 text-slate-600 font-semibold px-2 py-0.5 rounded-full border border-slate-200">
                                                    {{ $genre }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>

                                <div>
                                    <div class="flex justify-between items-center mb-4">
                                        <div>
                                            <span class="text-xs text-gray-400 line-through">Rp {{ number_format($book->price, 0, ',', '.') }}</span>
                                            <p class="font-bold text-red-500 text-sm">Rp {{ number_format($book->discounted_price, 0, ',', '.') }}</p>
                                        </div>
                                        <button type="button" @click.stop="$dispatch('open-review', { id: {{ $book->id }} })" class="flex items-center gap-1.5 bg-yellow-50 hover:bg-yellow-100 border border-yellow-200 px-2.5 py-1 rounded-full transition group shadow-sm">
                                            <span class="text-yellow-500 group-hover:scale-110 transition-transform">⭐</span>
                                            <span class="text-xs font-bold text-yellow-700">{{ $book->average_rating > 0 ? $book->average_rating : 'Baru' }}</span>
                                        </button>
                                    </div>

                                    <div class="pt-3 border-t border-gray-100 relative">
                                        <a href="/books/{{ $book->id }}" onclick="event.stopPropagation()" class="block w-full text-center py-2 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-bold text-xs rounded-xl transition">
                                            Lihat Detail
                                        </a>
                                        @if($book->stock > 0)
                                            <div class="absolute -top-2 -right-1 bg-green-100 border border-green-200 text-green-700 text-[9px] font-extrabold px-1.5 py-0.5 rounded-full shadow-sm">
                                                Stok: {{ $book->stock }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </section>

    </div>

    <script>
        @if(session('success')) Swal.fire({ icon: 'success', title: 'Berhasil!', text: '{{ session("success") }}', showConfirmButton: false, timer: 1800 }); @endif
        @if(session('error')) Swal.fire({ icon: 'error', title: 'Oops!', text: '{{ session("error") }}', confirmButtonColor: '#2563eb' }); @endif

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
                <div class="flex items-center gap-3">
                    <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Kategori:</span>
                    <div class="flex items-center gap-2">
                        <button type="button" @click="showPositif = !showPositif" :class="showPositif ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-500'" class="px-4 py-1.5 rounded-xl text-xs font-semibold transition shadow-sm">
                            Positif
                        </button>
                        <button type="button" @click="showKritis = !showKritis" :class="showKritis ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-500'" class="px-4 py-1.5 rounded-xl text-xs font-semibold transition shadow-sm">
                            Negatif
                        </button>
                    </div>
                </div>
            </div>

            <!-- Content Body (Scrollable) -->
            <div class="flex-1 overflow-y-auto p-6 bg-gray-50/50">
                @if(Auth::check() && Auth::user()->role === 'user')
                <!-- Tulis Ulasan Form -->
                <div class="mb-5">
                    <button @click="showWriteForm = !showWriteForm" class="w-full flex items-center justify-center gap-2 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-semibold py-2.5 px-4 rounded-xl border border-indigo-100 transition text-sm">
                        <span x-text="showWriteForm ? '✕ Batal Menulis Ulasan' : '✍️ Tulis Ulasan Baru'"></span>
                    </button>
                    
                    <div x-show="showWriteForm" x-transition class="mt-3 bg-white p-5 rounded-2xl border border-indigo-100 shadow-sm">
                        <form :action="'/books/' + bookId + '/reviews'" method="POST" class="flex flex-col gap-4">
                            @csrf
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1.5">Rating Bintang</label>
                                <select name="rating" required class="w-full text-sm bg-gray-50 border border-gray-200 rounded-xl px-3 py-2.5 font-medium text-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                    <option value="5">⭐⭐⭐⭐⭐ (5) Sangat Bagus</option>
                                    <option value="4">⭐⭐⭐⭐ (4) Bagus</option>
                                    <option value="3">⭐⭐⭐ (3) Lumayan</option>
                                    <option value="2">⭐⭐ (2) Buruk</option>
                                    <option value="1">⭐ (1) Sangat Buruk</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1.5">Komentar</label>
                                <textarea name="comment" rows="3" required placeholder="Bagaimana menurutmu tentang buku ini?" class="w-full text-sm bg-gray-50 border border-gray-200 rounded-xl px-3 py-2.5 font-medium text-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 resize-none"></textarea>
                            </div>
                            <div class="flex justify-end">
                                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs px-5 py-2.5 rounded-xl transition shadow-sm">
                                    Kirim Ulasan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                @endif

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
                bookId: null,
                showWriteForm: false,
                bookTitle: '',
                averageRating: 0,
                totalReviews: 0,
                reviews: [],
                filterRating: 'all',
                showPositif: true,
                showKritis: true,
                
                open(id) {
                    this.isOpen = true;
                    this.isLoading = true;
                    this.bookId = id;
                    this.showWriteForm = false;
                    this.reviews = [];
                    // Reset filters when opening new book
                    this.filterRating = 'all';
                    this.showPositif = true;
                    this.showKritis = true;
                    
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
                        const showAllSentiment = !this.showPositif && !this.showKritis;
                        let matchSentiment = false;
                        if (showAllSentiment) {
                            matchSentiment = true;
                        } else {
                            if (this.showPositif && review.sentiment === 'positif') matchSentiment = true;
                            if (this.showKritis && review.sentiment === 'kritis') matchSentiment = true;
                        }
                        return matchRating && matchSentiment;
                    });
                }
            }));
        });
    </script>
</body>
</html>