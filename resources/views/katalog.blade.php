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
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f0f4ff; }
/* anune wahyu cilik */
        /* Search bar glow */
        #search-input:focus {
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.25);
        }

        /* Filter sidebar */
        .filter-card {
            background: white;
            border-radius: 16px;
            border: 1px solid #e0e7ff;
            box-shadow: 0 2px 12px rgba(99, 102, 241, 0.07);
        }

        /* Book card hover */
        .book-card {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .book-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 30px rgba(99, 102, 241, 0.15);
        }

        /* Price range input */
        input[type="range"] {
            accent-color: #6366f1;
        }

        /* Active filter badge */
        .filter-badge {
            animation: fadeIn 0.2s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: scale(0.9); }
            to   { opacity: 1; transform: scale(1); }
        }

        /* Loading skeleton */
        @keyframes shimmer {
            0%   { background-position: -400px 0; }
            100% { background-position: 400px 0; }
        }
        .skeleton {
            background: linear-gradient(90deg, #e8eaf6 25%, #c5cae9 50%, #e8eaf6 75%);
            background-size: 800px 100%;
            animation: shimmer 1.4s infinite;
            border-radius: 8px;
        }

        /* Scrollbar filter sidebar */
        .filter-scroll::-webkit-scrollbar { width: 4px; }
        .filter-scroll::-webkit-scrollbar-track { background: #f0f4ff; }
        .filter-scroll::-webkit-scrollbar-thumb { background: #c7d2fe; border-radius: 4px; }
    </style>
</head>
<body class="text-gray-800">

    {{-- ===== NAVBAR ===== --}}
    <nav class="bg-white/90 backdrop-blur shadow-sm px-6 py-3 flex justify-between items-center border-b border-indigo-100 sticky top-0 z-50">
        <div class="flex items-center gap-8">
            <h1 class="text-2xl font-bold text-indigo-600 tracking-tight">BooSho<span class="text-indigo-400">.</span></h1>
            <div class="hidden md:flex gap-5">
                <a href="{{ route('dashboard') }}" class="font-medium text-gray-500 hover:text-indigo-600 transition text-sm">Dashboard</a>
                <a href="{{ route('katalog') }}" class="font-semibold text-indigo-600 border-b-2 border-indigo-500 pb-0.5 text-sm">Katalog Buku</a>
                @if(!Auth::user()->isAdmin())
                    <a href="{{ route('keranjang') }}" class="font-medium text-gray-500 hover:text-indigo-600 transition text-sm">🛒 Keranjang</a>
                @endif
            </div>
        </div>
        <div class="flex items-center gap-4">
            <span class="font-medium text-gray-600 text-sm">
                Halo, <span class="font-semibold text-indigo-600">{{ Auth::user()->name }}</span>
                @if(Auth::user()->isAdmin())
                    <span class="ml-1 text-xs bg-indigo-100 text-indigo-700 px-2 py-0.5 rounded-full font-semibold">Admin</span>
                @endif
            </span>
            <form action="{{ route('logout') }}" method="POST" class="inline" id="logout-form">
                @csrf
                <button type="button" onclick="konfirmasiLogout()"
                    class="text-sm text-red-500 font-semibold hover:text-red-700 transition px-3 py-1.5 rounded-lg hover:bg-red-50">
                    Logout
                </button>
            </form>
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
                @if(Auth::user()->isAdmin())
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

                            {{-- TOMBOL APPLY (Mobile hidden, auto-submit via JS) --}}
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
                            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4 gap-5" id="book-grid">
                                @foreach($books as $book)
                                <div class="book-card bg-white border border-gray-100 rounded-2xl p-5 flex flex-col justify-between shadow-sm">
                                    {{-- Icon buku dekoratif --}}
                                    <div class="w-10 h-10 bg-indigo-50 rounded-xl flex items-center justify-center mb-4">
                                        <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                        </svg>
                                    </div>

                                    <div class="flex-1">
                                        <h3 class="font-bold text-base text-gray-800 leading-snug mb-1 line-clamp-2">
                                            @if(request('search'))
                                                {!! preg_replace('/(' . preg_quote(request('search'), '/') . ')/i', '<mark class="bg-yellow-100 text-yellow-800 rounded px-0.5">$1</mark>', e($book->title)) !!}
                                            @else
                                                {{ $book->title }}
                                            @endif
                                        </h3>
                                        <p class="text-xs text-indigo-500 font-semibold mb-2">
                                            @if(request('search'))
                                                {!! preg_replace('/(' . preg_quote(request('search'), '/') . ')/i', '<mark class="bg-yellow-100 text-yellow-800 rounded px-0.5">$1</mark>', e($book->author)) !!}
                                            @else
                                                {{ $book->author }}
                                            @endif
                                        </p>
                                        @if($book->description)
                                            <p class="text-xs text-gray-500 line-clamp-2 mb-3">{{ $book->description }}</p>
                                        @endif
                                    </div>

                                    <div>
                                        <div class="flex items-center justify-between mb-4 pt-3 border-t border-gray-100">
                                            <span class="font-bold text-gray-900 text-base">
                                                Rp {{ number_format($book->price, 0, ',', '.') }}
                                            </span>
                                            @if($book->stock > 0)
                                                <span class="text-xs bg-green-50 text-green-600 border border-green-200 px-2 py-0.5 rounded-full font-semibold">
                                                    Stok: {{ $book->stock }}
                                                </span>
                                            @else
                                                <span class="text-xs bg-red-50 text-red-500 border border-red-200 px-2 py-0.5 rounded-full font-semibold">
                                                    Habis
                                                </span>
                                            @endif
                                        </div>

                                        @if(Auth::user()->isAdmin())
                                            <form action="/books/{{ $book->id }}" method="POST" id="delete-form-{{ $book->id }}">
                                                @csrf @method('DELETE')
                                                <button type="button" onclick="konfirmasiHapus({{ $book->id }})"
                                                    class="w-full text-sm text-red-500 font-semibold hover:bg-red-50 border border-red-200 py-2 rounded-xl transition">
                                                    🗑 Hapus Buku
                                                </button>
                                            </form>
                                        @else
                                            @if($book->stock > 0)
                                                <form action="/cart/{{ $book->id }}" method="POST">
                                                    @csrf
                                                    <button type="submit"
                                                        class="w-full bg-indigo-600 text-white font-semibold text-sm py-2.5 rounded-xl hover:bg-indigo-700 transition shadow-sm flex items-center justify-center gap-2">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                                                        </svg>
                                                        + Keranjang
                                                    </button>
                                                </form>
                                            @else
                                                <button disabled
                                                    class="w-full bg-gray-100 text-gray-400 font-semibold text-sm py-2.5 rounded-xl cursor-not-allowed">
                                                    Stok Habis
                                                </button>
                                            @endif
                                        @endif
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
    @if(Auth::user()->isAdmin())
    <div id="modalTambah" class="fixed inset-0 bg-black/60 backdrop-blur-sm hidden items-center justify-center z-50 p-4">
        <div class="bg-white p-7 rounded-2xl shadow-2xl w-full max-w-md border-t-4 border-indigo-500 animate-in">
            <div class="flex items-center justify-between mb-5">
                <h3 class="text-xl font-bold text-gray-800">Tambah Buku Baru</h3>
                <button onclick="tutupModal()" class="text-gray-400 hover:text-gray-600 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <form action="/books" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-semibold mb-1.5 text-gray-700">Judul Buku</label>
                    <input type="text" name="title" required placeholder="Masukkan judul buku..."
                        class="w-full border border-gray-200 p-3 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-indigo-400 transition bg-gray-50">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-semibold mb-1.5 text-gray-700">Nama Penulis</label>
                    <input type="text" name="author" required placeholder="Masukkan nama penulis..."
                        class="w-full border border-gray-200 p-3 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-indigo-400 transition bg-gray-50">
                </div>
                <div class="mb-5 flex gap-4">
                    <div class="w-1/2">
                        <label class="block text-sm font-semibold mb-1.5 text-gray-700">Harga (Rp)</label>
                        <input type="number" name="price" required placeholder="Contoh: 75000"
                            class="w-full border border-gray-200 p-3 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-indigo-400 transition bg-gray-50">
                    </div>
                    <div class="w-1/2">
                        <label class="block text-sm font-semibold mb-1.5 text-gray-700">Stok</label>
                        <input type="number" name="stock" required placeholder="Contoh: 20"
                            class="w-full border border-gray-200 p-3 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-indigo-400 transition bg-gray-50">
                    </div>
                </div>
                <div class="flex justify-end gap-2">
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

    {{-- ===== JAVASCRIPT ===== --}}
    <script>
        // ---- SweetAlert notifications ----
        @if(session('success'))
            Swal.fire({ icon: 'success', title: 'Berhasil!', text: '{{ session("success") }}', showConfirmButton: false, timer: 1800, timerProgressBar: true });
        @endif
        @if(session('error'))
            Swal.fire({ icon: 'error', title: 'Oops...', text: '{{ session("error") }}', confirmButtonColor: '#6366f1' });
        @endif

        // ---- Modal ----
        function bukaModal() { document.getElementById('modalTambah').classList.remove('hidden'); document.getElementById('modalTambah').classList.add('flex'); }
        function tutupModal() { document.getElementById('modalTambah').classList.add('hidden'); document.getElementById('modalTambah').classList.remove('flex'); }

        // Tutup modal jika klik di luar
        document.getElementById('modalTambah')?.addEventListener('click', function(e) {
            if (e.target === this) tutupModal();
        });

        // ---- Konfirmasi hapus buku ----
        function konfirmasiHapus(id) {
            Swal.fire({
                title: 'Hapus Buku?', text: 'Data tidak bisa dikembalikan!',
                icon: 'warning', showCancelButton: true,
                confirmButtonColor: '#ef4444', cancelButtonColor: '#6366f1',
                confirmButtonText: 'Ya, Hapus!', cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) document.getElementById('delete-form-' + id).submit();
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
        function submitFilter() {
            document.getElementById('filter-form').submit();
        }

        // ---- Clear search ----
        function clearSearch() {
            document.getElementById('search-input').value = '';
            document.getElementById('filter-form').submit();
        }

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
</body>
</html>