<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Account - BooSho</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f8fafc; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="text-gray-800" x-data>

    @php
        if (!isset($orders)) {
            $orders = class_exists('\App\Models\Order') ? \App\Models\Order::with('items.book')->where('user_id', Auth::id())->latest()->get() : collect();
        }
    @endphp

    <nav class="bg-white/90 backdrop-blur shadow-sm px-6 py-3 flex justify-between items-center border-b border-indigo-100 sticky top-0 z-50">
        <div class="flex items-center gap-8">
            <h1 class="text-2xl font-bold text-indigo-600 tracking-tight">BooSho<span class="text-indigo-400">.</span></h1>
            <div class="hidden md:flex gap-5">
                <a href="{{ route('dashboard') }}" class="text-sm font-medium text-gray-500 hover:text-indigo-600 transition">Home</a>
                <a href="{{ route('katalog') }}" class="text-sm font-medium text-gray-500 hover:text-indigo-600 transition">Katalog Buku</a>

                @if(Auth::check() && !Auth::user()->isAdmin())
                    <a href="{{ route('keranjang') }}" class="text-sm font-medium text-gray-500 hover:text-indigo-600 transition">🛒 Keranjang</a>
                @elseif(Auth::check() && Auth::user()->isAdmin())
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

    <div class="max-w-screen-xl mx-auto px-4 sm:px-6 py-10" x-data="{ tab: 'profile' }">

        <div class="flex flex-col lg:flex-row gap-8">

            <div class="w-full lg:w-1/4">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden sticky top-24">
                    <div class="p-6 border-b border-gray-100 bg-gray-50/50 flex flex-col items-center">
                        <div class="w-20 h-20 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 mb-3 shadow-inner border-2 border-white">
                            @if(Auth::user()->avatar)
                                <img src="{{ asset('storage/' . Auth::user()->avatar) }}" class="w-full h-full rounded-full object-cover">
                            @else
                                <span class="text-2xl font-bold">{{ substr(Auth::user()->name, 0, 1) }}</span>
                            @endif
                        </div>
                        <h3 class="font-bold text-gray-800 text-center">{{ Auth::user()->name }}</h3>
                        <p class="text-xs text-gray-500 text-center">{{ Auth::user()->email }}</p>
                    </div>

                    <div class="p-3">
                        <button @click="tab = 'profile'"
                                :class="{ 'bg-indigo-50 text-indigo-700 font-semibold': tab === 'profile', 'text-gray-600 hover:bg-gray-50': tab !== 'profile' }"
                                class="w-full flex items-center gap-3 px-4 py-3 rounded-xl transition text-sm mb-1 text-left">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            Profile Saya
                        </button>

                        <button @click="tab = 'orders'"
                                :class="{ 'bg-indigo-50 text-indigo-700 font-semibold': tab === 'orders', 'text-gray-600 hover:bg-gray-50': tab !== 'orders' }"
                                class="w-full flex items-center gap-3 px-4 py-3 rounded-xl transition text-sm mb-1 text-left">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                            Pesanan Saya
                        </button>
                    </div>
                </div>
            </div>

            <div class="w-full lg:w-3/4">

                <div x-show="tab === 'profile'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0" x-cloak>
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-xl font-bold text-gray-800">Informasi Pribadi</h2>
                            <button onclick="Swal.fire('Fitur Edit', 'Fitur edit profil sedang dalam tahap pengembangan.', 'info')" class="px-4 py-2 text-sm font-semibold text-indigo-600 bg-indigo-50 hover:bg-indigo-100 rounded-lg transition">Edit Profil</button>
                        </div>

                        <div class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-y-2 border-b border-gray-50 pb-4">
                                <div class="text-sm font-medium text-gray-500">Foto Profil</div>
                                <div class="md:col-span-2 flex items-center gap-4">
                                    <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center border border-gray-200 overflow-hidden">
                                        @if(Auth::user()->avatar)
                                            <img src="{{ asset('storage/' . Auth::user()->avatar) }}" class="w-full h-full object-cover">
                                        @else
                                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-y-2 border-b border-gray-50 pb-4">
                                <div class="text-sm font-medium text-gray-500">Nama Lengkap</div>
                                <div class="md:col-span-2 text-sm text-gray-800 font-semibold">{{ Auth::user()->name }}</div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-y-2 border-b border-gray-50 pb-4">
                                <div class="text-sm font-medium text-gray-500">Email</div>
                                <div class="md:col-span-2 text-sm text-gray-800 font-semibold">{{ Auth::user()->email }}</div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-y-2 border-b border-gray-50 pb-4">
                                <div class="text-sm font-medium text-gray-500">Nomor Handphone</div>
                                <div class="md:col-span-2 text-sm text-gray-800 font-semibold">
                                    @if(Auth::user()->phone)
                                        {{ Auth::user()->phone }}
                                    @else
                                        <span class="text-gray-400 italic font-normal">Belum diatur</span>
                                    @endif
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-y-2">
                                <div class="text-sm font-medium text-gray-500">Alamat Lengkap</div>
                                <div class="md:col-span-2 text-sm text-gray-800 font-semibold">
                                    @if(Auth::user()->address)
                                        {{ Auth::user()->address }}
                                    @else
                                        <span class="text-gray-400 italic font-normal">Belum diatur</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div x-show="tab === 'orders'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0" x-cloak>
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
                        <div class="mb-6">
                            <h2 class="text-xl font-bold text-gray-800">Riwayat Pesanan</h2>
                            <p class="text-sm text-gray-500 mt-1">Lacak status pesanan dan transaksi belanja Anda.</p>
                        </div>

                        @if($orders->isEmpty())
                            <div class="text-center py-16 bg-gray-50/50 rounded-2xl border-2 border-dashed border-gray-200">
                                <div class="w-20 h-20 bg-white shadow-sm border border-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <span class="text-4xl">🛍️</span>
                                </div>
                                <h3 class="text-lg font-bold text-gray-700 mb-2">Belum ada riwayat pesanan</h3>
                                <p class="text-gray-500 text-sm mb-6 max-w-sm mx-auto">Anda belum pernah melakukan transaksi pembelian buku. Yuk, mulai belanja sekarang!</p>
                                <a href="{{ route('katalog') }}" class="inline-flex items-center gap-2 px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm rounded-xl transition shadow-sm hover:shadow-md">
                                    Lihat Katalog Buku
                                </a>
                            </div>
                        @else
                            <div class="space-y-4">
                                @foreach($orders as $order)
                                <div class="border border-gray-100 rounded-2xl p-5 hover:shadow-md hover:border-blue-100 transition duration-300 bg-white">
                                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 pb-4 border-b border-gray-50 gap-3">
                                        <div>
                                            <span class="font-bold text-sm text-blue-600 block mb-1">Order #{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</span>
                                            <span class="text-xs text-gray-500 font-medium flex items-center gap-1.5">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                                {{ $order->created_at->setTimezone('Asia/Jakarta')->format('d M Y, H:i') }} WIB
                                            </span>
                                        </div>
                                        <div>
                                            @if($order->status === 'pending')
                                                <span class="inline-flex items-center gap-1.5 text-xs bg-yellow-50 text-yellow-700 border border-yellow-200 font-bold px-3 py-1.5 rounded-full">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-yellow-500 animate-pulse"></span>
                                                    Menunggu Kode VA
                                                </span>
                                            @elseif($order->status === 'waiting_payment')
                                                <span class="inline-flex items-center gap-1.5 text-xs bg-blue-50 text-blue-700 border border-blue-200 font-bold px-3 py-1.5 rounded-full">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-pulse"></span>
                                                    Menunggu Pembayaran
                                                </span>
                                            @elseif($order->status === 'packing')
                                                <span class="inline-flex items-center gap-1.5 text-xs bg-orange-50 text-orange-700 border border-orange-200 font-bold px-3 py-1.5 rounded-full">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-orange-500 animate-pulse"></span>
                                                    Sedang Dikemas
                                                </span>
                                            @elseif($order->status === 'shipping')
                                                <span class="inline-flex items-center gap-1.5 text-xs bg-indigo-50 text-indigo-700 border border-indigo-200 font-bold px-3 py-1.5 rounded-full">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-indigo-500 animate-pulse"></span>
                                                    Dalam Pengiriman
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1.5 text-xs bg-green-50 text-green-700 border border-green-200 font-bold px-3 py-1.5 rounded-full">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                                    Pesanan Selesai
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Timeline Tracking Pengiriman -->
                                    <div class="mt-2 mb-6 bg-gray-50 border border-gray-100 rounded-2xl p-5">
                                        <div class="flex items-center justify-between text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-4">
                                            <span>Status Pelacakan Logistik</span>
                                            @if($order->shipping_resi)
                                                <span class="text-indigo-600 normal-case font-mono bg-indigo-50 border border-indigo-100 px-2 py-0.5 rounded">No. Resi: {{ $order->shipping_resi }}</span>
                                            @endif
                                        </div>
                                        
                                        <!-- Timeline Steps -->
                                        <div class="relative flex flex-col md:flex-row items-center justify-between gap-4 md:gap-0">
                                            <!-- Background connecting line -->
                                            <div class="absolute left-1/2 md:left-0 md:top-5 w-0.5 md:w-full h-[80%] md:h-0.5 bg-gray-200 -translate-x-1/2 md:translate-x-0 z-0"></div>
                                            
                                            @php
                                                $step1Active = in_array($order->status, ['pending', 'waiting_payment', 'packing', 'shipping', 'completed']);
                                                $step2Active = in_array($order->status, ['waiting_payment', 'packing', 'shipping', 'completed']);
                                                $step3Active = in_array($order->status, ['packing', 'shipping', 'completed']);
                                                $step4Active = in_array($order->status, ['shipping', 'completed']);
                                                $step5Active = $order->status === 'completed';
                                            @endphp
                                            
                                            <!-- Step 1: Pesanan Dibuat -->
                                            <div class="relative z-10 flex flex-col items-center md:w-1/5 text-center">
                                                <div class="w-10 h-10 rounded-full flex items-center justify-center border-2 transition-colors duration-300 {{ $step1Active ? 'bg-indigo-600 border-indigo-600 text-white shadow-sm' : 'bg-white border-gray-200 text-gray-400' }}">
                                                    📄
                                                </div>
                                                <span class="text-[10px] font-bold mt-2 {{ $step1Active ? 'text-indigo-700' : 'text-gray-400' }}">Dibuat</span>
                                            </div>
                                            
                                            <!-- Step 2: Menunggu Bayar -->
                                            <div class="relative z-10 flex flex-col items-center md:w-1/5 text-center">
                                                <div class="w-10 h-10 rounded-full flex items-center justify-center border-2 transition-colors duration-300 {{ $step2Active ? 'bg-indigo-600 border-indigo-600 text-white shadow-sm' : 'bg-white border-gray-200 text-gray-400' }}">
                                                    💳
                                                </div>
                                                <span class="text-[10px] font-bold mt-2 {{ $step2Active ? 'text-indigo-700' : 'text-gray-400' }}">Bayar</span>
                                            </div>
                                            
                                            <!-- Step 3: Sedang Dikemas -->
                                            <div class="relative z-10 flex flex-col items-center md:w-1/5 text-center">
                                                <div class="w-10 h-10 rounded-full flex items-center justify-center border-2 transition-colors duration-300 {{ $step3Active ? 'bg-indigo-600 border-indigo-600 text-white shadow-sm' : 'bg-white border-gray-200 text-gray-400' }}">
                                                    📦
                                                </div>
                                                <span class="text-[10px] font-bold mt-2 {{ $step3Active ? 'text-indigo-700' : 'text-gray-400' }}">Dikemas</span>
                                            </div>
                                            
                                            <!-- Step 4: Dalam Pengiriman -->
                                            <div class="relative z-10 flex flex-col items-center md:w-1/5 text-center">
                                                <div class="w-10 h-10 rounded-full flex items-center justify-center border-2 transition-colors duration-300 {{ $step4Active ? 'bg-indigo-600 border-indigo-600 text-white shadow-sm' : 'bg-white border-gray-200 text-gray-400' }}">
                                                    🚚
                                                </div>
                                                <span class="text-[10px] font-bold mt-2 {{ $step4Active ? 'text-indigo-700' : 'text-gray-400' }}">Dikirim</span>
                                            </div>
                                            
                                            <!-- Step 5: Selesai -->
                                            <div class="relative z-10 flex flex-col items-center md:w-1/5 text-center">
                                                <div class="w-10 h-10 rounded-full flex items-center justify-center border-2 transition-colors duration-300 {{ $step5Active ? 'bg-indigo-600 border-indigo-600 text-white shadow-sm' : 'bg-white border-gray-200 text-gray-400' }}">
                                                    🏁
                                                </div>
                                                <span class="text-[10px] font-bold mt-2 {{ $step5Active ? 'text-indigo-700' : 'text-gray-400' }}">Diterima</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Accordion Alamat & Peta Pinpoint -->
                                    @if($order->address)
                                        <div x-data="{ openMap: false }" class="mt-4 mb-5 border border-gray-100 rounded-2xl overflow-hidden bg-white">
                                            <button @click="openMap = !openMap; $nextTick(() => { if(openMap) initOrderMap({{ $order->id }}, {{ $order->latitude }}, {{ $order->longitude }}) })" class="w-full flex justify-between items-center px-4 py-3 bg-gray-50/50 hover:bg-gray-50 transition text-xs font-bold text-gray-600">
                                                <span class="flex items-center gap-1.5">📍 Lihat Detail Alamat & Lokasi Pengiriman</span>
                                                <svg class="w-4 h-4 transition-transform duration-200" :class="openMap ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                            </button>
                                            <div x-show="openMap" x-cloak class="p-4 border-t border-gray-50 space-y-3">
                                                <div>
                                                    <span class="block text-[10px] text-gray-400 font-bold uppercase mb-1">Alamat Penerima</span>
                                                    <p class="text-sm text-gray-750 leading-relaxed">{{ $order->address }}</p>
                                                </div>
                                                @if($order->latitude && $order->longitude)
                                                    <div>
                                                        <span class="block text-[10px] text-gray-400 font-bold uppercase mb-1.5">Koordinat Pinpoint</span>
                                                        <div id="order-map-{{ $order->id }}" class="h-48 rounded-xl border border-gray-200 z-10"></div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endif

                                    <div class="flex flex-col gap-3 mb-4">
                                        @foreach($order->items as $item)
                                        <div class="flex justify-between items-center bg-gray-50/50 p-3 rounded-xl border border-gray-50">
                                            <div class="flex items-center gap-3 overflow-hidden">
                                                <div class="w-10 h-10 rounded-lg bg-white border border-gray-100 flex items-center justify-center flex-shrink-0">
                                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                                </div>
                                                <div class="min-w-0">
                                                    <p class="text-sm font-semibold text-gray-800 truncate">{{ $item->book?->title ?? 'Buku telah dihapus' }}</p>
                                                    <p class="text-[11px] text-gray-500">{{ $item->quantity }} x Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                                                </div>
                                            </div>
                                            <div class="font-bold text-sm text-gray-700 whitespace-nowrap pl-2">
                                                Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>

                                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center pt-4 border-t border-gray-100 gap-4 bg-gray-50 -mx-5 -mb-5 px-5 py-4 rounded-b-2xl">

                                        @if($order->status === 'waiting_payment')
                                            <div class="w-full sm:w-auto flex items-center gap-3">
                                                <div class="bg-white px-3 py-1.5 border border-blue-100 rounded-lg shadow-sm">
                                                    <span class="text-[10px] text-blue-500 font-bold uppercase tracking-wider block leading-none mb-1">Kode VA</span>
                                                    <code class="font-mono font-bold text-blue-700 select-all">{{ $order->payment_code }}</code>
                                                </div>
                                                <form action="/orders/{{ $order->id }}/pay" method="POST" id="pay-form-{{ $order->id }}">
                                                    @csrf
                                                    <button type="button" onclick="konfirmasiBayar({{ $order->id }})" class="bg-green-600 hover:bg-green-700 text-white text-xs font-bold px-4 py-2.5 rounded-lg transition shadow-sm active:scale-95 whitespace-nowrap">
                                                        Konfirmasi Bayar
                                                    </button>
                                                </form>
                                            </div>
                                        @elseif($order->status === 'pending')
                                            <div class="w-full sm:w-auto">
                                                <p class="text-xs text-yellow-600 font-medium flex items-center gap-1.5 bg-yellow-50 border border-yellow-100 px-3 py-2 rounded-lg">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                    Menunggu admin generate kode pembayaran
                                                </p>
                                            </div>
                                        @elseif($order->status === 'packing')
                                            <div class="w-full sm:w-auto">
                                                <p class="text-xs text-orange-600 font-medium flex items-center gap-1.5 bg-orange-50 border border-orange-150 px-3 py-2 rounded-lg">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                                    Pembayaran Sukses! Buku sedang dikemas oleh admin.
                                                </p>
                                            </div>
                                        @elseif($order->status === 'shipping')
                                            <div class="w-full sm:w-auto flex items-center gap-3">
                                                <form action="/orders/{{ $order->id }}/receive" method="POST" id="receive-form-{{ $order->id }}">
                                                    @csrf
                                                    <button type="button" onclick="konfirmasiTerima({{ $order->id }})" class="bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold px-4 py-2.5 rounded-lg transition shadow-sm active:scale-95 whitespace-nowrap">
                                                        Konfirmasi Barang Diterima
                                                    </button>
                                                </form>
                                            </div>
                                        @else
                                            <div class="w-full sm:w-auto">
                                                <p class="text-xs text-green-600 font-medium flex items-center gap-1.5">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                    Pesanan Selesai / Diterima
                                                </p>
                                            </div>
                                        @endif

                                        <div class="w-full sm:w-auto flex justify-between items-center sm:block">
                                            <span class="text-xs text-gray-500 font-medium sm:hidden">Total Tagihan</span>
                                            <div class="text-right">
                                                <span class="text-[10px] text-gray-400 font-medium uppercase tracking-wider hidden sm:block mb-0.5">Total Belanja</span>
                                                <span class="font-bold text-gray-900 text-lg">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        @if(session('success')) Swal.fire({ icon: 'success', title: 'Berhasil!', text: '{{ session("success") }}', showConfirmButton: false, timer: 1800 }); @endif
        @if(session('error')) Swal.fire({ icon: 'error', title: 'Oops!', text: '{{ session("error") }}', confirmButtonColor: '#4f46e5' }); @endif

        function konfirmasiLogout() {
            Swal.fire({
                title: 'Logout?', icon: 'warning', showCancelButton: true,
                confirmButtonColor: '#d33', cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Keluar!', cancelButtonText: 'Batal'
            }).then((r) => { if (r.isConfirmed) document.getElementById('logout-form').submit(); })
        }

        function konfirmasiBayar(orderId) {
            Swal.fire({
                title: 'Konfirmasi Pembayaran',
                text: "Apakah Anda yakin sudah melakukan transfer ke Virtual Account tersebut?",
                icon: 'info', showCancelButton: true,
                confirmButtonColor: '#16a34a', cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Saya Sudah Bayar!', cancelButtonText: 'Batal'
            }).then((r) => { if (r.isConfirmed) document.getElementById('pay-form-' + orderId).submit(); })
        }

        function konfirmasiTerima(orderId) {
            Swal.fire({
                title: '📦 Konfirmasi Barang Diterima',
                text: "Apakah Anda yakin pesanan telah sampai dan Anda telah menerima barang dengan baik?",
                icon: 'question', showCancelButton: true,
                confirmButtonColor: '#4f46e5', cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Sudah Diterima!', cancelButtonText: 'Batal'
            }).then((r) => { if (r.isConfirmed) document.getElementById('receive-form-' + orderId).submit(); })
        }

        const activeMaps = {};
        function initOrderMap(orderId, lat, lng) {
            if (activeMaps[orderId]) {
                setTimeout(() => activeMaps[orderId].invalidateSize(), 100);
                return;
            }
            setTimeout(() => {
                let mapDivId = 'order-map-' + orderId;
                let oMap = L.map(mapDivId).setView([lat, lng], 15);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
                }).addTo(oMap);
                
                L.marker([lat, lng]).addTo(oMap);
                activeMaps[orderId] = oMap;
            }, 150);
        }
    </script>
</body>
</html>