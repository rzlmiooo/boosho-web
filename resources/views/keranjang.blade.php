<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Belanja - BooSho</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <style> body { font-family: 'Poppins', sans-serif; background-color: #f8fafc; } </style>
</head>
<body class="text-gray-800" x-data>

    <nav class="bg-white/90 backdrop-blur shadow-sm px-6 py-3 flex justify-between items-center border-b border-indigo-100 sticky top-0 z-50">
        <div class="flex items-center gap-8">
            <span class="text-2xl font-bold text-indigo-600 tracking-tight">BooSho<span class="text-indigo-400">.</span></span>
            <div class="hidden md:flex gap-5">
                <a href="{{ route('dashboard') }}" class="text-sm font-medium text-gray-500 hover:text-indigo-600 transition">Home</a>
                <a href="{{ route('katalog') }}" class="text-sm font-medium text-gray-500 hover:text-indigo-600 transition">Katalog Buku</a>

                @if(Auth::user()->isAdmin())
                    <a href="{{ route('admin.orders') }}" class="text-sm font-medium text-gray-500 hover:text-indigo-600 transition">📋 Daftar Pesanan</a>
                @else
                    <a href="{{ route('keranjang') }}" class="text-sm font-semibold text-indigo-600 border-b-2 border-indigo-500 pb-0.5">🛒 Keranjang</a>
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

    <div class="max-w-screen-lg mx-auto px-4 sm:px-6 py-8">

        <div class="flex items-center gap-4 mb-6">
            <a href="{{ route('katalog') }}" class="text-sm text-gray-500 hover:text-indigo-600 font-semibold flex items-center gap-1.5 hover:bg-gray-100 px-3 py-2 rounded-lg transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali Belanja
            </a>
            <div class="h-5 w-px bg-gray-200"></div>
            <div>
                <h1 class="text-xl font-bold text-gray-800">🛒 Keranjang Belanja</h1>
            </div>
        </div>

        @if($carts->isEmpty())
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-16 text-center">
                <div class="text-5xl mb-4">🛒</div>
                <h3 class="text-lg font-bold text-gray-700 mb-2">Keranjang Masih Kosong</h3>
                <p class="text-sm text-gray-400 mb-6">Tambahkan buku dari katalog untuk memulai belanja.</p>
                <a href="{{ route('katalog') }}" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold px-6 py-3 rounded-xl transition shadow-sm">
                    📖 Jelajahi Katalog Buku
                </a>
            </div>

        @else
            @php $totalHarga = 0; @endphp

            <div class="flex flex-col lg:flex-row gap-6">

                <div class="flex-1">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                            <h2 class="font-bold text-gray-800">Item Pesanan</h2>
                            <span class="text-xs text-gray-400 bg-gray-50 border border-gray-200 px-2.5 py-1 rounded-full font-semibold">{{ $carts->count() }} item</span>
                        </div>

                        <div class="p-4 flex flex-col gap-3">
                            @foreach($carts as $cart)
                            @php 
                                $subtotal = $cart->book->discounted_price * $cart->quantity;
                                $totalHarga += $subtotal; 
                            @endphp
                            <div class="flex flex-col sm:flex-row sm:items-center gap-4 border border-gray-100 rounded-xl p-4 hover:border-indigo-100 hover:bg-indigo-50/20 transition">

                                <div class="flex items-center gap-3 flex-1 min-w-0">
                                    <div class="w-11 h-11 rounded-xl bg-indigo-50 border border-indigo-100 flex items-center justify-center flex-shrink-0">
                                        @if($cart->book->cover)
                                            <img src="{{ asset('storage/' . $cart->book->cover) }}" class="w-full h-full object-cover rounded-xl">
                                        @else
                                            <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                            </svg>
                                        @endif
                                    </div>
                                    <div class="min-w-0">
                                        <h3 class="font-bold text-indigo-600 text-sm truncate">{{ $cart->book->title }}</h3>
                                        <p class="text-xs text-gray-400">{{ $cart->book->author }}</p>
                                        @if($cart->book->discounted_price < $cart->book->price)
                                            <span class="text-[10px] text-gray-400 line-through">Rp {{ number_format($cart->book->price, 0, ',', '.') }}</span>
                                            <p class="text-xs text-red-500 font-bold">Rp {{ number_format($cart->book->discounted_price, 0, ',', '.') }} / buku</p>
                                        @else
                                            <p class="text-xs text-gray-500 font-semibold mt-0.5">Rp {{ number_format($cart->book->price, 0, ',', '.') }} / buku</p>
                                        @endif
                                    </div>
                                </div>

                                <div class="flex items-center gap-4 sm:flex-shrink-0">
                                    <div class="flex items-center gap-1 bg-gray-50 border border-gray-200 rounded-xl p-1">
                                        <form action="/cart/{{ $cart->id }}/update" method="POST" class="inline">
                                            @csrf
                                            <input type="hidden" name="action" value="decrease">
                                            <button type="submit" class="w-8 h-8 rounded-lg bg-white border border-gray-200 text-gray-600 hover:bg-gray-100 active:scale-90 transition font-bold flex items-center justify-center shadow-sm text-base">
                                                −
                                            </button>
                                        </form>
                                        <span class="w-9 text-center font-bold text-gray-800 text-sm select-none">{{ $cart->quantity }}</span>
                                        <form action="/cart/{{ $cart->id }}/update" method="POST" class="inline">
                                            @csrf
                                            <input type="hidden" name="action" value="increase">
                                            <button type="submit" class="w-8 h-8 rounded-lg bg-white border border-gray-200 text-gray-600 hover:bg-gray-100 active:scale-90 transition font-bold flex items-center justify-center shadow-sm text-base">
                                                +
                                            </button>
                                        </form>
                                    </div>

                                    <div class="text-right w-28 flex-shrink-0">
                                        <p class="text-[10px] text-gray-400 uppercase tracking-wider">Subtotal</p>
                                        <p class="font-bold text-gray-800 text-sm">Rp {{ number_format($subtotal, 0, ',', '.') }}</p>
                                    </div>

                                    <form action="/cart/{{ $cart->id }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-2 text-red-400 hover:text-red-600 bg-red-50 hover:bg-red-100 border border-red-100 rounded-xl transition" title="Hapus dari keranjang">
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

                    <!-- Informasi Pengiriman -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mt-6">
                        <h3 class="font-bold text-gray-800 text-base mb-4 flex items-center gap-2">
                            📍 Informasi Pengiriman & Lokasi
                        </h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Alamat Pengiriman Lengkap</label>
                                <textarea id="address-input" rows="3" placeholder="Contoh: Jl. Sudirman No. 123, Blok C, Jakarta Selatan" class="w-full border border-gray-300 px-4 py-3 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"></textarea>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Pinpoint Koordinat (Klik pada peta)</label>
                                <div id="map" class="h-64 rounded-xl border border-gray-200" style="z-index: 10;"></div>
                                <div class="grid grid-cols-2 gap-3 mt-3">
                                    <div>
                                        <label class="block text-[10px] text-gray-400 font-bold uppercase mb-1">Latitude</label>
                                        <input type="text" id="latitude-input" readonly class="w-full bg-gray-50 border border-gray-200 px-3 py-2 rounded-lg text-xs font-mono text-gray-500">
                                    </div>
                                    <div>
                                        <label class="block text-[10px] text-gray-400 font-bold uppercase mb-1">Longitude</label>
                                        <input type="text" id="longitude-input" readonly class="w-full bg-gray-50 border border-gray-200 px-3 py-2 rounded-lg text-xs font-mono text-gray-500">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="lg:w-72 flex-shrink-0">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 sticky top-24">
                        <h3 class="font-bold text-gray-800 mb-4 pb-4 border-b border-gray-100">Ringkasan Pesanan</h3>

                        <div class="space-y-2 mb-4">
                            @foreach($carts as $cart)
                            @php $s = $cart->book->discounted_price * $cart->quantity; @endphp
                            <div class="flex justify-between items-center text-xs">
                                <span class="text-gray-500 truncate max-w-[140px]">{{ $cart->book->title }} (×{{ $cart->quantity }})</span>
                                <span class="text-gray-700 font-semibold flex-shrink-0 ml-2">Rp {{ number_format($s, 0, ',', '.') }}</span>
                            </div>
                            @endforeach
                        </div>

                        <div class="border-t border-gray-100 pt-4 mb-5">
                            <div class="flex justify-between items-center">
                                <span class="font-bold text-gray-700">Total</span>
                                <span class="font-bold text-xl text-indigo-600">Rp {{ number_format($totalHarga, 0, ',', '.') }}</span>
                            </div>
                            <p class="text-[10px] text-gray-400 mt-1">Kode pembayaran dikirim admin setelah checkout</p>
                        </div>

                        <form action="/checkout" method="POST" id="checkout-form">
                            @csrf
                            
                            @foreach($carts as $cart)
                                <input type="hidden" name="quantities[{{ $cart->id }}]" value="{{ $cart->quantity }}">
                            @endforeach

                            <input type="hidden" name="address" id="form-address">
                            <input type="hidden" name="latitude" id="form-latitude">
                            <input type="hidden" name="longitude" id="form-longitude">

                            <button type="button" onclick="konfirmasiCheckout()" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3.5 rounded-xl text-sm transition shadow-sm active:scale-95 flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Checkout Sekarang
                            </button>
                        </form>

                        <div class="mt-3 flex items-start gap-2 bg-indigo-50 border border-indigo-100 rounded-xl p-3">
                            <svg class="w-4 h-4 text-indigo-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="text-[10px] text-indigo-600 leading-relaxed">Setelah checkout, admin akan memberikan kode Virtual Account untuk menyelesaikan pembayaran.</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <script>
        @if(session('success')) Swal.fire({ icon: 'success', title: 'Berhasil!', text: '{{ session("success") }}', showConfirmButton: false, timer: 1800 }); @endif
        @if(session('error')) Swal.fire({ icon: 'error', title: 'Oops!', text: '{{ session("error") }}', confirmButtonColor: '#4f46e5' }); @endif

        // Leaflet Map Initialization
        let defaultLat = -6.200000;
        let defaultLng = 106.816666;
        
        let map = L.map('map').setView([defaultLat, defaultLng], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        let marker = L.marker([defaultLat, defaultLng], { draggable: true }).addTo(map);

        function updateCoords(lat, lng) {
            document.getElementById('latitude-input').value = lat.toFixed(8);
            document.getElementById('longitude-input').value = lng.toFixed(8);
        }

        // Set initial coordinates
        updateCoords(defaultLat, defaultLng);

        function fetchReverseGeocode(lat, lng) {
            const addressInput = document.getElementById('address-input');
            addressInput.value = 'Mengambil alamat otomatis...';
            
            fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lng}`, {
                headers: {
                    'Accept-Language': 'id'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data && data.display_name) {
                    addressInput.value = data.display_name;
                } else {
                    addressInput.value = '';
                    addressInput.placeholder = 'Gagal mengambil alamat otomatis, silakan isi manual.';
                }
            })
            .catch(err => {
                console.error(err);
                addressInput.value = '';
                addressInput.placeholder = 'Gagal mengambil alamat otomatis, silakan isi manual.';
            });
        }

        // Geolocation: Auto detect user position
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                let userLat = position.coords.latitude;
                let userLng = position.coords.longitude;
                map.setView([userLat, userLng], 15);
                marker.setLatLng([userLat, userLng]);
                updateCoords(userLat, userLng);
                fetchReverseGeocode(userLat, userLng);
            }, function(err) {
                console.warn('Geolocation blocked or unavailable: ', err);
                fetchReverseGeocode(defaultLat, defaultLng);
            });
        } else {
            fetchReverseGeocode(defaultLat, defaultLng);
        }

        marker.on('dragend', function(e) {
            let latLng = marker.getLatLng();
            updateCoords(latLng.lat, latLng.lng);
            fetchReverseGeocode(latLng.lat, latLng.lng);
        });

        map.on('click', function(e) {
            marker.setLatLng(e.latlng);
            updateCoords(e.latlng.lat, e.latlng.lng);
            fetchReverseGeocode(e.latlng.lat, e.latlng.lng);
        });

        function konfirmasiCheckout() {
            const address = document.getElementById('address-input').value.trim();
            const lat = document.getElementById('latitude-input').value;
            const lng = document.getElementById('longitude-input').value;

            if (!address) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Alamat Belum Lengkap',
                    text: 'Silakan isi Alamat Pengiriman Lengkap terlebih dahulu.',
                    confirmButtonColor: '#4f46e5'
                });
                return;
            }

            Swal.fire({
                title: '🛒 Konfirmasi Checkout',
                text: "Setelah checkout, pesanan akan diproses dan Anda bisa melihatnya di menu 'Pesanan Saya'.",
                icon: 'info', showCancelButton: true,
                confirmButtonColor: '#16a34a', cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Checkout!', cancelButtonText: 'Batal'
            }).then((r) => {
                if (r.isConfirmed) {
                    document.getElementById('form-address').value = address;
                    document.getElementById('form-latitude').value = lat;
                    document.getElementById('form-longitude').value = lng;
                    document.getElementById('checkout-form').submit();
                }
            })
        }

        function konfirmasiLogout() {
            Swal.fire({
                title: 'Logout?', icon: 'warning', showCancelButton: true,
                confirmButtonColor: '#d33', cancelButtonColor: '#3b82f6',
                confirmButtonText: 'Ya, Keluar!', cancelButtonText: 'Batal'
            }).then((r) => { if (r.isConfirmed) document.getElementById('logout-form').submit(); })
        }
    </script>
</body>
</html>