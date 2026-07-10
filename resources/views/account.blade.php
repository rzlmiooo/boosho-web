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
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f8fafc; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<!-- Alpine.js x-data dipindah ke body agar Navbar bisa mengontrol Tab -->
<body class="text-gray-800" x-data="{ tab: new URLSearchParams(location.search).get('tab') || 'profile' }">

    @php
        // Logika Pengambilan Data:
        // Admin mengambil SEMUA order (untuk dikonfirmasi). User mengambil ordernya SENDIRI.
        if (!isset($orders)) {
            if (Auth::check() && Auth::user()->isAdmin()) {
                $orders = class_exists('\App\Models\Order') ? \App\Models\Order::with(['items.book', 'user'])->latest()->get() : collect();
            } else {
                $orders = class_exists('\App\Models\Order') ? \App\Models\Order::with('items.book')->where('user_id', Auth::id())->latest()->get() : collect();
            }
        }
    @endphp

    <!-- Navigasi -->
    <nav class="bg-white/90 backdrop-blur shadow-sm px-6 py-3 flex justify-between items-center border-b border-indigo-100 sticky top-0 z-50">
        <div class="flex items-center gap-8">
            <h1 class="text-2xl font-bold text-indigo-600 tracking-tight">BooSho<span class="text-indigo-400">.</span></h1>
            <div class="hidden md:flex gap-5">
                <a href="{{ route('dashboard') }}" class="text-sm font-medium text-gray-500 hover:text-indigo-600 transition">Dashboard</a>
                <a href="{{ route('katalog') }}" class="text-sm font-medium text-gray-500 hover:text-indigo-600 transition">Katalog Buku</a>

                @if(Auth::check() && Auth::user()->isAdmin())
                    <button @click="tab = 'admin_orders'" class="text-sm font-medium text-gray-500 hover:text-indigo-600 transition focus:outline-none">📋 Konfirmasi Pesanan</button>
                @elseif(Auth::check())
                    <a href="{{ route('keranjang') }}" class="text-sm font-medium text-gray-500 hover:text-indigo-600 transition">🛒 Keranjang</a>
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

    <div class="max-w-screen-xl mx-auto px-4 sm:px-6 py-10">
        <div class="flex flex-col lg:flex-row gap-8">

            <!-- Sidebar Kiri -->
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

                        @if(Auth::user()->isAdmin())
                            <!-- Menu Tab Khusus Admin -->
                            <button @click="tab = 'admin_orders'"
                                    :class="{ 'bg-indigo-50 text-indigo-700 font-semibold': tab === 'admin_orders', 'text-gray-600 hover:bg-gray-50': tab !== 'admin_orders' }"
                                    class="w-full flex items-center gap-3 px-4 py-3 rounded-xl transition text-sm mb-1 text-left">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Konfirmasi Pesanan
                            </button>
                        @else
                            <!-- Menu Tab Khusus User Biasa -->
                            <button @click="tab = 'user_orders'"
                                    :class="{ 'bg-indigo-50 text-indigo-700 font-semibold': tab === 'user_orders', 'text-gray-600 hover:bg-gray-50': tab !== 'user_orders' }"
                                    class="w-full flex items-center gap-3 px-4 py-3 rounded-xl transition text-sm mb-1 text-left">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                                Pesanan Saya
                            </button>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Konten Kanan -->
            <div class="w-full lg:w-3/4">

                <!-- TAB 1: PROFILE (Untuk Admin & User) -->
                <div x-show="tab === 'profile'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0" x-cloak>
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-xl font-bold text-gray-800">Informasi Pribadi</h2>
                            <button onclick="Swal.fire('Fitur Edit', 'Fitur edit profil sedang dalam tahap pengembangan.', 'info')" class="px-4 py-2 text-sm font-semibold text-indigo-600 bg-indigo-50 hover:bg-indigo-100 rounded-lg transition">Edit Profil</button>
                        </div>
                        <div class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-y-2 border-b border-gray-50 pb-4">
                                <div class="text-sm font-medium text-gray-500">Nama Lengkap</div>
                                <div class="md:col-span-2 text-sm text-gray-800 font-semibold">{{ Auth::user()->name }}</div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-y-2 border-b border-gray-50 pb-4">
                                <div class="text-sm font-medium text-gray-500">Email</div>
                                <div class="md:col-span-2 text-sm text-gray-800 font-semibold">{{ Auth::user()->email }}</div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-y-2">
                                <div class="text-sm font-medium text-gray-500">Peran Akun</div>
                                <div class="md:col-span-2 text-sm text-gray-800 font-semibold uppercase">{{ Auth::user()->role }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                @if(Auth::user()->isAdmin())
                <!-- TAB 2: KONFIRMASI PESANAN (KHUSUS ADMIN) -->
                <div x-show="tab === 'admin_orders'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0" x-cloak>
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
                        <div class="mb-6">
                            <h2 class="text-xl font-bold text-gray-800">Manajemen & Konfirmasi Pesanan</h2>
                            <p class="text-sm text-gray-500 mt-1">Daftar pesanan dari seluruh pengguna yang perlu diperiksa dan dikonfirmasi pembayarannya.</p>
                        </div>

                        @if($orders->isEmpty())
                            <div class="text-center py-16 bg-gray-50/50 rounded-2xl border-2 border-dashed border-gray-200">
                                <span class="text-4xl">📬</span>
                                <h3 class="text-lg font-bold text-gray-700 mt-4">Belum ada pesanan masuk</h3>
                                <p class="text-gray-500 text-sm">Saat ini belum ada pengguna yang melakukan transaksi.</p>
                            </div>
                        @else
                            <div class="space-y-5">
                                @foreach($orders as $order)
                                <div class="border border-indigo-50 rounded-2xl p-5 shadow-sm bg-white relative overflow-hidden">
                                    <!-- Garis warna status di kiri -->
                                    <div class="absolute left-0 top-0 bottom-0 w-1.5 {{ $order->status === 'completed' ? 'bg-green-500' : 'bg-yellow-400' }}"></div>
                                    
                                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 pb-4 border-b border-gray-50 gap-3">
                                        <div>
                                            <span class="font-bold text-sm text-indigo-600 block mb-1">Order #{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</span>
                                            <span class="text-xs text-gray-500 font-medium">
                                                Pembeli: <strong class="text-gray-800">{{ $order->user->name ?? 'Guest' }}</strong>
                                            </span>
                                        </div>
                                        <div class="text-right">
                                            <span class="text-xs text-gray-400 block mb-1">{{ $order->created_at->setTimezone('Asia/Jakarta')->format('d M Y, H:i') }}</span>
                                            @if($order->status === 'completed')
                                                <span class="text-xs bg-green-50 text-green-700 border border-green-200 font-bold px-3 py-1 rounded-full">Selesai</span>
                                            @else
                                                <span class="text-xs bg-yellow-50 text-yellow-700 border border-yellow-200 font-bold px-3 py-1 rounded-full animate-pulse">Menunggu Konfirmasi</span>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Daftar Buku -->
                                    <div class="flex flex-col gap-2 mb-4">
                                        @foreach($order->items as $item)
                                        <div class="flex justify-between items-center bg-gray-50 px-3 py-2 rounded-lg text-sm">
                                            <span class="font-medium text-gray-700">{{ $item->book->title ?? 'Buku Dihapus' }} <span class="text-gray-400">(x{{ $item->quantity }})</span></span>
                                            <span class="font-bold text-gray-800">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</span>
                                        </div>
                                        @endforeach
                                    </div>

                                    <div class="flex flex-col sm:flex-row justify-between items-center pt-4 border-t border-gray-100 gap-4">
                                        <div>
                                            <span class="text-xs text-gray-500 block">Total Pembayaran</span>
                                            <span class="font-bold text-lg text-gray-900">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                                        </div>
                                        
                                        <!-- Tombol Aksi Admin -->
                                        @if($order->status !== 'completed')
                                            <form action="/admin/orders/{{ $order->id }}/confirm" method="POST" id="confirm-form-{{ $order->id }}">
                                                @csrf
                                                <button type="button" onclick="konfirmasiAdmin({{ $order->id }})" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold px-5 py-2.5 rounded-xl transition shadow-sm text-sm">
                                                    Terima & Selesaikan Pesanan
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-sm font-bold text-green-600 flex items-center gap-1.5">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                Pesanan Tuntas
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                @else
                <!-- TAB 3: PESANAN SAYA (KHUSUS USER) -->
                <div x-show="tab === 'user_orders'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0" x-cloak>
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
                        <div class="mb-6">
                            <h2 class="text-xl font-bold text-gray-800">Riwayat Pesanan Saya</h2>
                            <p class="text-sm text-gray-500 mt-1">Lacak status pesanan dan transaksi belanja Anda.</p>
                        </div>

                        @if($orders->isEmpty())
                            <div class="text-center py-16 bg-gray-50/50 rounded-2xl border-2 border-dashed border-gray-200">
                                <span class="text-4xl">🛍️</span>
                                <h3 class="text-lg font-bold text-gray-700 mt-4 mb-2">Belum ada riwayat pesanan</h3>
                                <p class="text-gray-500 text-sm mb-6">Yuk, mulai belanja sekarang!</p>
                                <a href="{{ route('katalog') }}" class="inline-flex px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm rounded-xl transition">Lihat Katalog Buku</a>
                            </div>
                        @else
                            <!-- Render Pesanan User (Sama seperti desain user sebelumnya) -->
                            <div class="space-y-4">
                                @foreach($orders as $order)
                                <div class="border border-gray-100 rounded-2xl p-5 hover:shadow-md transition bg-white">
                                    <div class="flex justify-between items-center mb-4 pb-4 border-b border-gray-50">
                                        <div>
                                            <span class="font-bold text-sm text-blue-600 block">Order #{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</span>
                                            <span class="text-xs text-gray-500">{{ $order->created_at->setTimezone('Asia/Jakarta')->format('d M Y, H:i') }} WIB</span>
                                        </div>
                                        <div>
                                            @if($order->status === 'completed')
                                                <span class="text-xs bg-green-50 text-green-700 font-bold px-3 py-1.5 rounded-full">Pesanan Selesai</span>
                                            @else
                                                <span class="text-xs bg-blue-50 text-blue-700 font-bold px-3 py-1.5 rounded-full animate-pulse">Menunggu Konfirmasi Admin</span>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <div class="flex flex-col gap-2 mb-4">
                                        @foreach($order->items as $item)
                                        <div class="flex justify-between items-center bg-gray-50 p-3 rounded-xl">
                                            <span class="text-sm font-semibold text-gray-800">{{ $item->book->title }} (x{{ $item->quantity }})</span>
                                            <span class="text-sm font-bold text-gray-700">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</span>
                                        </div>
                                        @endforeach
                                    </div>

                                    <div class="flex justify-between items-center pt-4 border-t border-gray-100">
                                        <span class="text-sm font-bold text-gray-500">Total Belanja</span>
                                        <span class="text-lg font-bold text-gray-900">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
                @endif

            </div>
        </div>
    </div>

    <script>
        @if(session('success')) Swal.fire({ icon: 'success', title: 'Berhasil!', text: '{{ session("success") }}', showConfirmButton: false, timer: 1800 }); @endif

        function konfirmasiLogout() {
            Swal.fire({
                title: 'Logout?', icon: 'warning', showCancelButton: true,
                confirmButtonColor: '#d33', cancelButtonColor: '#3085d6', confirmButtonText: 'Ya, Keluar!'
            }).then((r) => { if (r.isConfirmed) document.getElementById('logout-form').submit(); })
        }

        // Fungsi khusus konfirmasi Admin
        function konfirmasiAdmin(orderId) {
            Swal.fire({
                title: 'Selesaikan Pesanan?',
                text: "Pastikan dana sudah masuk. Status pesanan akan diubah menjadi Selesai.",
                icon: 'question', showCancelButton: true,
                confirmButtonColor: '#4f46e5', cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Konfirmasi!', cancelButtonText: 'Batal'
            }).then((r) => { if (r.isConfirmed) document.getElementById('confirm-form-' + orderId).submit(); })
        }
    </script>
</body>
</html>