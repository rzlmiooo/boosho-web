<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog Lengkap - BooSho</title>
    <meta name="description" content="Temukan koleksi buku terlengkap di BooSho. Cari, filter, dan temukan buku favorit Anda dengan mudah.">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f0f4ff; }
        #search-input:focus { box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.25); }
        .filter-card { background: white; border-radius: 16px; border: 1px solid #e0e7ff; box-shadow: 0 2px 12px rgba(99, 102, 241, 0.07); }
        .book-card { transition: transform 0.2s ease, box-shadow 0.2s ease; }
        .book-card:hover { transform: translateY(-4px); box-shadow: 0 12px 30px rgba(99, 102, 241, 0.15); }
        input[type="range"] { accent-color: #6366f1; }
        .filter-badge { animation: fadeIn 0.2s ease; }
        @keyframes fadeIn { from { opacity: 0; transform: scale(0.9); } to { opacity: 1; transform: scale(1); } }
        @keyframes shimmer { 0% { background-position: -400px 0; } 100% { background-position: 400px 0; } }
        .skeleton { background: linear-gradient(90deg, #e8eaf6 25%, #c5cae9 50%, #e8eaf6 75%); background-size: 800px 100%; animation: shimmer 1.4s infinite; border-radius: 8px; }
        .filter-scroll::-webkit-scrollbar { width: 4px; }
        .filter-scroll::-webkit-scrollbar-track { background: #f0f4ff; }
        .filter-scroll::-webkit-scrollbar-thumb { background: #c7d2fe; border-radius: 4px; }
    </style>
</head>
<body class="text-gray-800">

    <nav class="bg-white/90 backdrop-blur shadow-sm px-6 py-3 flex justify-between items-center border-b border-indigo-100 sticky top-0 z-50">
        <div class="flex items-center gap-8">
            <h1 class="text-2xl font-bold text-indigo-600 tracking-tight">BooSho<span class="text-indigo-400">.</span></h1>
            <div class="hidden md:flex gap-5">
                <a href="{{ route('dashboard') }}" class="text-sm font-medium text-gray-500 hover:text-indigo-600 transition">Home</a>
                
                <a href="{{ route('katalog') }}" class="text-sm font-semibold text-indigo-600 border-b-2 border-indigo-500 pb-0.5">Katalog Buku</a>

                @if(!Auth::check() || !Auth::user()->isAdmin())
                    <a href="{{ route('keranjang') }}" class="text-sm font-medium text-gray-500 hover:text-indigo-600 transition">🛒 Keranjang</a>
                @else
                    <a href="{{ route('admin.orders') }}" class="text-sm font-medium text-gray-500 hover:text-indigo-600 transition">📋 Daftar Pesanan</a>
                @endif
            </div>
        </div>
        
        <div class="flex items-center gap-4">
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

    <div class="max-w-screen-xl mx-auto px-4 py-8">

        {{-- ===== HEADER + SEARCH BAR UTAMA ===== --}}
        <div class="mb-8">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-5">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">Katalog Buku</h2>
                    <p class="text-sm text-gray-500 mt-0.5">
                        Menampilkan <span class="font-semibold text-indigo-600">{{ $books->count() }}</span>
                        dari <span class="font-semibold">{{ $totalBooks }}</span> total buku
                        @if($activeFilter)
                            <span class="ml-2 text-xs bg-indigo-100 text-indigo-700 px-2 py-0.5 rounded-full font-semibold">Filter Aktif</span>
                        @endif
                    </p>
                </div>
                @if(Auth::check() && Auth::user()->isAdmin())
                    <button onclick="bukaModal()"
                        class="bg-indigo-600 text-white px-5 py-2.5 rounded-xl hover:bg-indigo-700 transition shadow-sm font-semibold text-sm flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Tambah Buku
                    </button>
                @endif
            </div>

            {{-- Search Bar Utama --}}
            <form id="filter-form" method="GET" action="{{ route('katalog') }}">
                <div class="relative mb-4">
                    <div class="absolute inset-y-0 left-4 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input
                        type="text"
                        id="search-input"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Cari judul buku atau nama penulis..."
                        autocomplete="off"
                        class="w-full pl-12 pr-4 py-3.5 rounded-2xl border border-indigo-200 bg-white text-gray-800 placeholder-gray-400 focus:outline-none focus:border-indigo-500 transition text-sm shadow-sm"
                    >
                    @if(request('search'))
                        <button type="button" onclick="clearSearch()"
                            class="absolute inset-y-0 right-4 flex items-center text-gray-400 hover:text-gray-600 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    @endif
                </div>

                {{-- ===== LAYOUT: SIDEBAR FILTER + GRID BUKU ===== --}}
                <div class="flex gap-6 items-start">

                    {{-- SIDEBAR FILTER --}}
                    <aside class="hidden lg:block w-64 shrink-0">
                        <div class="filter-card p-5 sticky top-20">
                            <div class="flex items-center justify-between mb-5">
                                <h3 class="font-bold text-gray-700 text-sm flex items-center gap-2">
                                    <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/>
                                    </svg>
                                    Filter & Urutan
                                </h3>
                                @if($activeFilter)
                                    <a href="{{ route('katalog') }}"
                                        class="text-xs text-red-500 hover:text-red-700 font-semibold transition hover:underline">
                                        Reset
                                    </a>
                                @endif
                            </div>

                            {{-- SORT --}}
                            <div class="mb-5">
                                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Urutkan</label>
                                <select name="sort" onchange="submitFilter()"
                                    class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm text-gray-700 bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-indigo-400 transition cursor-pointer">
                                    <option value="latest"     {{ request('sort', 'latest') === 'latest'     ? 'selected' : '' }}>🕐 Terbaru</option>
                                    <option value="price_asc"  {{ request('sort') === 'price_asc'            ? 'selected' : '' }}>💰 Harga Terendah</option>
                                    <option value="price_desc" {{ request('sort') === 'price_desc'           ? 'selected' : '' }}>💎 Harga Tertinggi</option>
                                    <option value="title_asc"  {{ request('sort') === 'title_asc'            ? 'selected' : '' }}>🔤 Judul A–Z</option>
                                </select>
                            </div>

                            <hr class="border-indigo-100 mb-5">

                            {{-- HARGA --}}
                            <div class="mb-5">
                                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-3">Rentang Harga</label>
                                <div class="flex gap-2 mb-2">
                                    <div class="w-1/2">
                                        <label class="text-xs text-gray-400 mb-1 block">Minimum</label>
                                        <input type="number" name="min_price" id="min_price"
                                            value="{{ request('min_price') }}"
                                            placeholder="0"
                                            min="0"
                                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 transition bg-gray-50"
                                            onchange="submitFilter()">
                                    </div>
                                    <div class="w-1/2">
                                        <label class="text-xs text-gray-400 mb-1 block">Maksimum</label>
                                        <input type="number" name="max_price" id="max_price"
                                            value="{{ request('max_price') }}"
                                            placeholder="∞"
                                            min="0"
                                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 transition bg-gray-50"
                                            onchange="submitFilter()">
                                    </div>
                                </div>
                                {{-- Quick price buttons --}}
                                <div class="flex flex-wrap gap-1.5 mt-2">
                                    <button type="button" onclick="setHarga(0, 50000)"
                                        class="text-xs px-2 py-1 rounded-lg border border-indigo-200 text-indigo-600 hover:bg-indigo-50 transition {{ (request('min_price') == 0 && request('max_price') == 50000) ? 'bg-indigo-100 font-semibold' : 'bg-white' }}">
                                        &lt; 50rb
                                    </button>
                                    <button type="button" onclick="setHarga(50000, 150000)"
                                        class="text-xs px-2 py-1 rounded-lg border border-indigo-200 text-indigo-600 hover:bg-indigo-50 transition {{ (request('min_price') == 50000 && request('max_price') == 150000) ? 'bg-indigo-100 font-semibold' : 'bg-white' }}">
                                        50–150rb
                                    </button>
                                    <button type="button" onclick="setHarga(150000, '')"
                                        class="text-xs px-2 py-1 rounded-lg border border-indigo-200 text-indigo-600 hover:bg-indigo-50 transition {{ (request('min_price') == 150000 && !request('max_price')) ? 'bg-indigo-100 font-semibold' : 'bg-white' }}">
                                        &gt; 150rb
                                    </button>
                                </div>
                            </div>

                            <hr class="border-indigo-100 mb-5">

                            {{-- KETERSEDIAAN STOK --}}
                            <div class="mb-5">
                                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-3">Ketersediaan</label>
                                <label class="flex items-center gap-3 cursor-pointer group">
                                    <div class="relative">
                                        <input type="checkbox" name="in_stock" value="1" id="in_stock"
                                            {{ request()->boolean('in_stock') ? 'checked' : '' }}
                                            onchange="submitFilter()"
                                            class="sr-only">
                                        <div id="toggle-track"
                                            class="w-11 h-6 rounded-full transition-colors duration-200 {{ request()->boolean('in_stock') ? 'bg-indigo-500' : 'bg-gray-300' }}">
                                        </div>
                                        <div id="toggle-thumb"
                                            class="absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full shadow transition-transform duration-200 {{ request()->boolean('in_stock') ? 'translate-x-5' : 'translate-x-0' }}">
                                        </div>
                                    </div>
                                    <span class="text-sm text-gray-700 font-medium group-hover:text-indigo-600 transition">Hanya stok tersedia</span>
                                </label>
                            </div>

                            {{-- TOMBOL APPLY --}}
                            <button type="submit"
                                class="w-full bg-indigo-600 text-white py-2.5 rounded-xl font-semibold text-sm hover:bg-indigo-700 transition shadow-sm mt-1">
                                Terapkan Filter
                            </button>
                        </div>
                    </aside>

                    {{-- KONTEN KANAN: BADGE FILTER AKTIF + GRID BUKU --}}
                    <div class="flex-1 min-w-0">

                        {{-- BADGE FILTER AKTIF --}}
                        @php
                            $activeBadges = [];
                            if(request('search'))     $activeBadges[] = ['label' => 'Keyword: "'.request('search').'"', 'clear_param' => 'search'];
                            if(request('min_price'))  $activeBadges[] = ['label' => 'Min: Rp '.number_format(request('min_price'),0,',','.'), 'clear_param' => 'min_price'];
                            if(request('max_price'))  $activeBadges[] = ['label' => 'Max: Rp '.number_format(request('max_price'),0,',','.'), 'clear_param' => 'max_price'];
                            if(request()->boolean('in_stock')) $activeBadges[] = ['label' => 'Stok Tersedia', 'clear_param' => 'in_stock'];
                            if(request('sort') && request('sort') !== 'latest') $activeBadges[] = ['label' => 'Urut: '.request('sort'), 'clear_param' => 'sort'];
                        @endphp

                        @if(count($activeBadges) > 0)
                            <div class="flex flex-wrap gap-2 mb-4">
                                @foreach($activeBadges as $badge)
                                    <span class="filter-badge inline-flex items-center gap-1.5 bg-indigo-50 border border-indigo-200 text-indigo-700 text-xs font-semibold px-3 py-1.5 rounded-full">
                                        {{ $badge['label'] }}
                                        <a href="{{ request()->fullUrlWithQuery([$badge['clear_param'] => null]) }}"
                                            class="text-indigo-400 hover:text-indigo-700 transition">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        </a>
                                    </span>
                                @endforeach
                                <a href="{{ route('katalog') }}"
                                     class="filter-badge inline-flex items-center gap-1 text-xs font-semibold text-red-500 hover:text-red-700 px-3 py-1.5 rounded-full border border-red-200 bg-red-50 hover:bg-red-100 transition">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4l16 16M4 20L20 4"/>
                                    </svg>
                                    Reset Semua
                                </a>
                            </div>
                        @endif

                        {{-- ===== GRID BUKU ===== --}}
                        @if($books->isEmpty())
                            <div class="flex flex-col items-center justify-center py-20 text-center">
                                <div class="w-24 h-24 bg-indigo-50 rounded-full flex items-center justify-center mb-5">
                                    <svg class="w-12 h-12 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <h3 class="text-xl font-bold text-gray-700 mb-2">Buku Tidak Ditemukan</h3>
                                <p class="text-gray-500 text-sm mb-5 max-w-xs">
                                    @if(request('search'))
                                        Tidak ada buku yang cocok dengan "<strong>{{ request('search') }}</strong>". Coba kata kunci lain.
                                    @else
                                        Tidak ada buku yang sesuai dengan filter yang kamu pilih.
                                    @endif
                                </p>
                                <a href="{{ route('katalog') }}"
                                    class="px-5 py-2.5 bg-indigo-600 text-white font-semibold text-sm rounded-xl hover:bg-indigo-700 transition shadow-sm">
                                    Lihat Semua Buku
                                </a>
                            </div>
                        @else
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6" id="book-grid">
                            @foreach($books as $book)
                            <div class="border border-gray-200/80 rounded-2xl hover:shadow-lg hover:-translate-y-1 transition duration-300 bg-white flex flex-col group overflow-hidden">
                                
                                {{-- Cover Image Container --}}
                                <div class="w-full aspect-[3/4] bg-gray-100 relative overflow-hidden flex items-center justify-center">
                                    @if(isset($book->cover) && $book->cover)
                                        <img src="{{ asset('storage/' . $book->cover) }}" alt="Cover {{ $book->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                                    @else
                                        <div class="w-full h-full flex flex-col items-center justify-center bg-gray-50 text-gray-300">
                                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                                            </svg>
                                        </div>
                                    @endif

                                    @if($book->category)
                                        <span class="absolute top-3 left-3 bg-indigo-600 text-white text-[10px] font-bold px-2.5 py-0.5 rounded-full shadow-sm">
                                            {{ $book->category }}
                                        </span>
                                    @endif
                                </div>

                                {{-- Card Content --}}
                                <div class="p-5 flex flex-col flex-grow justify-between">
                                    <div>
                                        <h3 class="font-bold text-gray-800 text-sm leading-snug mb-1 line-clamp-2">
                                            @if(request('search'))
                                                {!! preg_replace('/(' . preg_quote(request('search'), '/') . ')/i', '<mark class="bg-yellow-100 text-yellow-800 rounded px-0.5">$1</mark>', e($book->title)) !!}
                                            @else
                                                {{ $book->title }}
                                            @endif
                                        </h3>
                                        
                                        <p class="text-xs text-gray-400 mb-3">
                                            @if(request('search'))
                                                {!! preg_replace('/(' . preg_quote(request('search'), '/') . ')/i', '<mark class="bg-yellow-100 text-yellow-800 rounded px-0.5">$1</mark>', e($book->author)) !!}
                                            @else
                                                {{ $book->author }}
                                            @endif
                                        </p>

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
                                        {{-- Price & Rating --}}
                                        <div class="flex justify-between items-center mb-4">
                                            <div>
                                                @if($book->discounted_price < $book->price)
                                                    <span class="text-xs text-gray-400 line-through">Rp {{ number_format($book->price, 0, ',', '.') }}</span>
                                                    <p class="font-bold text-indigo-600 text-sm">Rp {{ number_format($book->discounted_price, 0, ',', '.') }}</p>
                                                @else
                                                    <p class="font-bold text-indigo-600 text-sm">Rp {{ number_format($book->price, 0, ',', '.') }}</p>
                                                @endif
                                            </div>
                                            
                                            <button type="button" @click="$dispatch('open-review', { id: {{ $book->id }} })" class="flex items-center gap-1.5 bg-yellow-50 hover:bg-yellow-100 border border-yellow-200 px-2.5 py-1 rounded-full transition group shadow-sm">
                                                <span class="text-yellow-500 group-hover:scale-110 transition-transform">⭐</span>
                                                <span class="text-xs font-bold text-yellow-700">{{ $book->average_rating > 0 ? $book->average_rating : 'Baru' }}</span>
                                            </button>
                                        </div>

                                        {{-- Action Buttons --}}
                                        <div class="pt-3 border-t border-gray-100 relative">
                                            @if(Auth::check() && Auth::user()->isAdmin())
                                                <div class="flex gap-2">
                                                    <a href="/books/{{ $book->id }}/edit" class="w-1/2 text-center bg-yellow-400 hover:bg-yellow-500 text-yellow-900 font-bold text-xs py-2 rounded-xl transition shadow-sm">Edit</a>
                                                    <button type="button" onclick="konfirmasiHapus({{ $book->id }})" class="w-1/2 text-xs text-red-600 bg-red-50 font-bold hover:bg-red-500 hover:text-white border border-red-200 py-2 rounded-xl transition shadow-sm">Hapus</button>
                                                </div>
                                            @else
                                                <div class="flex flex-col gap-2">
                                                    <a href="/books/{{ $book->id }}" class="block w-full text-center py-2 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-bold text-xs rounded-xl transition">
                                                        Detail Buku
                                                    </a>
                                                    @if($book->stock > 0)
                                                        <button type="button" onclick="tambahKeKeranjang({{ $book->id }})" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs py-2 rounded-xl transition flex items-center justify-center gap-1.5 shadow-sm">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                                            + Keranjang
                                                        </button>
                                                        <div class="absolute -top-2 -right-1 bg-green-100 border border-green-200 text-green-700 text-[9px] font-extrabold px-1.5 py-0.5 rounded-full shadow-sm">
                                                            Stok: {{ $book->stock }}
                                                        </div>
                                                    @else
                                                        <button type="button" disabled class="w-full bg-gray-100 text-gray-400 font-bold text-xs py-2 rounded-xl cursor-not-allowed border border-gray-200 flex items-center justify-center gap-1.5">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                                            Stok Habis
                                                        </button>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @endif

                    </div>{{-- end flex-1 --}}
                </div>{{-- end flex gap-6 --}}
            </form>
        </div>

    </div>{{-- end max-w container --}}

    {{-- ===== MODAL TAMBAH BUKU (Admin) ===== --}}
    @if(Auth::check() && Auth::user()->isAdmin())
    <div id="modalTambah" class="fixed inset-0 bg-black/60 backdrop-blur-sm hidden items-center justify-center z-50 p-4">
        <div class="bg-white p-7 rounded-2xl shadow-2xl w-full max-w-lg border-t-4 border-indigo-500 max-h-[90vh] overflow-y-auto animate-in">
            <div class="flex items-center justify-between mb-5">
                <h3 class="text-xl font-bold text-gray-800">Tambah Buku Baru</h3>
                <button onclick="tutupModal()" class="text-gray-400 hover:text-gray-600 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <form action="/books" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                
                <div>
                    <label class="block text-sm font-semibold mb-1 text-gray-700">Cover Gambar (Opsional)</label>
                    <input type="file" name="cover" accept="image/jpeg,image/png,image/jpg"
                        class="w-full border border-gray-200 p-2.5 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 transition bg-gray-50">
                </div>

                <div>
                    <label class="block text-sm font-semibold mb-1 text-gray-700">Judul Buku <span class="text-red-500">*</span></label>
                    <input type="text" name="title" required placeholder="Masukkan judul buku..."
                        class="w-full border border-gray-200 p-3 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 transition bg-gray-50">
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Deskripsi / Sinopsis <span class="text-red-500">*</span></label>
                    <textarea name="description" rows="3" required placeholder="Masukkan sinopsis atau deskripsi lengkap buku..."
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-300 focus:outline-none transition bg-gray-50 text-gray-800"></textarea>
                </div>
                
                <div>
                    <label class="block text-sm font-semibold mb-1 text-gray-700">Nama Penulis <span class="text-red-500">*</span></label>
                    <input type="text" name="author" required placeholder="Masukkan nama penulis..."
                         class="w-full border border-gray-200 p-3 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 transition bg-gray-50">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold mb-1 text-gray-700">Kategori</label>
                        <input type="text" name="category" placeholder="Fiksi, Sains, Agama..."
                             class="w-full border border-gray-200 p-3 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 transition bg-gray-50">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold mb-1 text-gray-700">Stok <span class="text-red-500">*</span></label>
                        <input type="number" name="stock" required placeholder="Contoh: 20"
                             class="w-full border border-gray-200 p-3 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 transition bg-gray-50">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold mb-1 text-gray-700">Harga (Rp) <span class="text-red-500">*</span></label>
                    <input type="number" name="price" required placeholder="Contoh: 75000"
                        class="w-full border border-gray-200 p-3 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 transition bg-gray-50">
                </div>

                <div>
                    <label class="block text-sm font-semibold mb-2 text-gray-700 font-bold">Genre Buku</label>
                    <div class="grid grid-cols-3 gap-2 bg-gray-50 p-3 rounded-xl border border-gray-200 max-h-32 overflow-y-auto">
                        @php 
                            $daftarGenre = ['Fiksi', 'Non-Fiksi', 'Edukasi', 'Novel', 'Sejarah', 'Fantasi', 'Misteri', 'Biografi', 'Romantis', 'Teknologi', 'Sains', 'Agama'];
                        @endphp
                        @foreach($daftarGenre as $genre)
                        <label class="flex items-center space-x-1.5 cursor-pointer">
                            <input type="checkbox" name="genres[]" value="{{ $genre }}" 
                                class="w-3.5 h-3.5 text-indigo-600 rounded border-gray-300 focus:ring-indigo-500">
                            <span class="text-xs text-gray-700 font-medium">{{ $genre }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <div class="flex justify-end gap-2 pt-2 border-t border-gray-100">
                    <button type="button" onclick="tutupModal()"
                        class="px-4 py-2.5 bg-gray-100 text-gray-700 font-semibold text-sm rounded-xl hover:bg-gray-200 transition">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-5 py-2.5 bg-indigo-600 text-white font-semibold text-sm rounded-xl hover:bg-indigo-700 transition shadow-sm">
                        Simpan Buku
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

    <form id="global-cart-form" method="POST" class="hidden">@csrf</form>
    <form id="global-delete-form" method="POST" class="hidden">@csrf @method('DELETE')</form>

    {{-- ===== JAVASCRIPT ===== --}}
    <script>
        // ---- SweetAlert notifications ----
        @if(session('success'))
            Swal.fire({ icon: 'success', title: 'Berhasil!', text: '{{ session("success") }}', showConfirmButton: false, timer: 1800, timerProgressBar: true });
        @endif
        @if(session('error'))
            Swal.fire({ icon: 'error', title: 'Oops...', text: '{{ session("error") }}', confirmButtonColor: '#6366f1' });
        @endif

        // ---- Modal Tambah Buku ----
        function bukaModal() { document.getElementById('modalTambah').classList.remove('hidden'); document.getElementById('modalTambah').classList.add('flex'); }
        function tutupModal() { document.getElementById('modalTambah').classList.add('hidden'); document.getElementById('modalTambah').classList.remove('flex'); }
        document.getElementById('modalTambah')?.addEventListener('click', function(e) { if (e.target === this) tutupModal(); });

        // ---- Form Global Handlers ----
        function tambahKeKeranjang(id) {
            const form = document.getElementById('global-cart-form');
            form.action = '/cart/' + id;
            form.submit();
        }

        function konfirmasiHapus(id) {
            Swal.fire({
                title: 'Hapus Buku?', text: 'Data tidak bisa dikembalikan!',
                icon: 'warning', showCancelButton: true,
                confirmButtonColor: '#ef4444', cancelButtonColor: '#6366f1',
                confirmButtonText: 'Ya, Hapus!', cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById('global-delete-form');
                    form.action = '/books/' + id;
                    form.submit();
                }
            });
        }

        // ---- Konfirmasi logout ----
        function konfirmasiLogout() {
            Swal.fire({
                title: 'Yakin ingin keluar?', icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#ef4444', cancelButtonColor: '#6366f1',
                confirmButtonText: 'Ya, Logout!', cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) document.getElementById('logout-form').submit();
            });
        }

        // ---- Live search dengan debounce ----
        let searchTimer = null;
        document.getElementById('search-input').addEventListener('input', function() {
            clearTimeout(searchTimer);
            searchTimer = setTimeout(() => {
                document.getElementById('filter-form').submit();
            }, 500); // 500ms debounce
        });

        // ---- Submit filter otomatis ----
        function submitFilter() { document.getElementById('filter-form').submit(); }

        // ---- Clear search ----
        function clearSearch() { document.getElementById('search-input').value = ''; document.getElementById('filter-form').submit(); }

        // ---- Quick price range buttons ----
        function setHarga(min, max) {
            document.getElementById('min_price').value = min;
            document.getElementById('max_price').value = max;
            submitFilter();
        }

        // ---- Toggle switch visual sync ----
        const toggleCheckbox = document.getElementById('in_stock');
        const toggleTrack    = document.getElementById('toggle-track');
        const toggleThumb    = document.getElementById('toggle-thumb');
        if (toggleCheckbox) {
            toggleCheckbox.addEventListener('change', function() {
                if (this.checked) {
                    toggleTrack.classList.replace('bg-gray-300', 'bg-indigo-500');
                    toggleThumb.classList.replace('translate-x-0', 'translate-x-5');
                } else {
                    toggleTrack.classList.replace('bg-indigo-500', 'bg-gray-300');
                    toggleThumb.classList.replace('translate-x-5', 'translate-x-0');
                }
            });
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