@extends('admin.layout')
@section('title', 'Daftar Pembelian')

@section('content')
<div class="max-w-6xl mx-auto">
    <!-- Banner -->
    <div class="bg-gradient-to-r from-blue-700 to-indigo-500 p-8 rounded-2xl shadow-sm text-white mb-8 transition hover:shadow-md border border-indigo-100">
        <h2 class="text-3xl font-bold mb-2 flex items-center gap-3">
            <svg class="w-8 h-8 opacity-90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
            Monitor Penjualan & Transaksi
        </h2>
        <p class="text-blue-50 max-w-2xl text-lg leading-relaxed font-medium">
            Di sini Anda dapat melacak seluruh transaksi pembelian buku yang dilakukan oleh pengguna secara real-time.
        </p>
    </div>

    <!-- Daftar Transaksi -->
    <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
        <h2 class="text-xl font-bold text-gray-800 border-b border-gray-100 pb-5 mb-6 flex items-center gap-2">
            Riwayat Transaksi Masuk
            <span class="bg-indigo-50 text-indigo-700 text-xs px-2.5 py-1 rounded-full">{{ $orders->count() }} Total</span>
        </h2>

        @if($orders->isEmpty())
            <div class="text-center py-16 text-gray-500">
                <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-gray-50 text-gray-400 mb-4 border border-gray-100">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
                <p class="text-lg font-medium text-gray-600">Belum ada transaksi pembelian yang tercatat.</p>
                <p class="text-sm text-gray-400 mt-1">Transaksi dari pengguna akan muncul di sini.</p>
            </div>
        @else
            <div class="flex flex-col gap-6">
                @foreach($orders as $order)
                <div class="border border-gray-100 rounded-2xl bg-white hover:border-gray-300 hover:shadow-md transition duration-300 p-6 relative overflow-hidden group">
                    <!-- Strip Status -->
                    @if($order->status === 'pending')
                        <div class="absolute left-0 top-0 bottom-0 w-2 bg-yellow-400"></div>
                    @elseif($order->status === 'waiting_payment')
                        <div class="absolute left-0 top-0 bottom-0 w-2 bg-blue-500"></div>
                    @elseif($order->status === 'packing')
                        <div class="absolute left-0 top-0 bottom-0 w-2 bg-orange-500"></div>
                    @elseif($order->status === 'shipping')
                        <div class="absolute left-0 top-0 bottom-0 w-2 bg-indigo-500"></div>
                    @else
                        <div class="absolute left-0 top-0 bottom-0 w-2 bg-green-500"></div>
                    @endif

                    <!-- Header Transaksi -->
                    <div class="flex flex-col md:flex-row md:justify-between md:items-center border-b border-gray-100 pb-5 mb-5 gap-4">
                        <div>
                            <div class="flex items-center gap-3">
                                <span class="font-bold text-lg text-indigo-700">ID Transaksi: #{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</span>

                                @if($order->status === 'pending')
                                    <span class="text-[11px] bg-yellow-50 text-yellow-700 font-bold px-3 py-1 rounded-full border border-yellow-100">Menunggu Kode VA</span>
                                @elseif($order->status === 'waiting_payment')
                                    <span class="text-[11px] bg-blue-50 text-blue-700 font-bold px-3 py-1 rounded-full border border-blue-100">Menunggu Pembayaran</span>
                                @elseif($order->status === 'packing')
                                    <span class="text-[11px] bg-orange-50 text-orange-700 font-bold px-3 py-1 rounded-full border border-orange-100">Sedang Dikemas</span>
                                @elseif($order->status === 'shipping')
                                    <span class="text-[11px] bg-indigo-50 text-indigo-700 font-bold px-3 py-1 rounded-full border border-indigo-100">Dalam Pengiriman</span>
                                @else
                                    <span class="text-[11px] bg-green-50 text-green-700 font-bold px-3 py-1 rounded-full border border-green-100 flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        Selesai / Lunas
                                    </span>
                                @endif
                            </div>
                            <p class="text-[13px] text-gray-500 mt-1.5 font-medium flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                {{ $order->created_at->setTimezone('Asia/Jakarta')->format('d M Y, H:i') }} WIB
                            </p>
                        </div>
                        <div class="bg-gray-50 p-3 rounded-xl border border-gray-100 text-sm flex items-center gap-3">
                            <div class="w-8 h-8 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center font-bold text-xs">
                                {{ strtoupper(substr($order->user->name ?? 'A', 0, 1)) }}
                            </div>
                            <div>
                                <span class="block text-[11px] text-gray-400 font-bold uppercase tracking-wider mb-0.5">Pelanggan</span>
                                <span class="font-bold text-gray-800">{{ $order->user->name ?? 'User dihapus' }}</span>
                                <span class="text-gray-500 font-medium text-xs">({{ $order->user->email ?? 'Email dihapus' }})</span>
                            </div>
                        </div>
                    </div>

                    <!-- Detail Item Buku -->
                    <div class="mb-5">
                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                            Item yang Dibeli ({{ $order->items->count() }})
                        </h4>
                        <div class="divide-y divide-gray-50 bg-gray-50/30 rounded-xl border border-gray-100 px-4">
                            @foreach($order->items as $item)
                            <div class="py-3 flex justify-between items-center text-sm">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-10 bg-white border border-gray-200 rounded flex-shrink-0 flex items-center justify-center overflow-hidden">
                                        @if($item->book?->cover)
                                            <img src="{{ asset('storage/' . $item->book->cover) }}" class="w-full h-full object-cover">
                                        @else
                                            <svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                        @endif
                                    </div>
                                    <div>
                                        <span class="font-bold text-gray-800 block leading-tight">{{ $item->book?->title ?? 'Buku Telah Dihapus' }}</span>
                                        <span class="text-[11px] text-gray-500 font-medium">oleh {{ $item->book?->author ?? '-' }}</span>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span class="text-gray-500 font-medium text-xs bg-white px-2 py-1 rounded border border-gray-100">{{ $item->quantity }}x</span>
                                    <span class="font-bold text-gray-800 ml-3">@ Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Accordion Alamat & Peta Pinpoint (Admin) -->
                    @if($order->address)
                        <div x-data="{ openMap: false }" class="mt-4 mb-5 border border-gray-150 rounded-2xl overflow-hidden bg-white">
                            <button @click="openMap = !openMap; $nextTick(() => { if(openMap) initAdminOrderMap({{ $order->id }}, {{ $order->latitude }}, {{ $order->longitude }}) })" class="w-full flex justify-between items-center px-4 py-3 bg-gray-50/50 hover:bg-gray-50 transition text-xs font-bold text-gray-650">
                                <span class="flex items-center gap-1.5">📍 Lihat Detail Alamat & Koordinat Tujuan Pengiriman</span>
                                <svg class="w-4 h-4 transition-transform duration-200" :class="openMap ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div x-show="openMap" x-cloak class="p-4 border-t border-gray-100 space-y-3">
                                <div>
                                    <span class="block text-[10px] text-gray-400 font-bold uppercase mb-1">Alamat Pengiriman Pelanggan</span>
                                    <p class="text-sm text-gray-750 leading-relaxed font-medium">{{ $order->address }}</p>
                                </div>
                                @if($order->latitude && $order->longitude)
                                    <div>
                                        <span class="block text-[10px] text-gray-400 font-bold uppercase mb-1.5">Koordinat Pinpoint</span>
                                        <div id="admin-order-map-{{ $order->id }}" class="h-48 rounded-xl border border-gray-200 z-10"></div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Panel Aksi Admin untuk Pembayaran & Logistik -->
                    @if($order->status === 'pending')
                        <div class="bg-yellow-50/80 border border-yellow-200 rounded-xl p-5 flex flex-col md:flex-row md:items-center md:justify-between gap-4 transition group-hover:bg-yellow-50">
                            <div class="max-w-md">
                                <span class="font-bold text-xs text-yellow-800 uppercase tracking-wider block mb-1 flex items-center gap-1.5">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                                    Aksi Admin: Kirim Kode Pembayaran
                                </span>
                                <span class="text-[13px] text-yellow-700 block leading-relaxed">Kirim kode Virtual Account / Bank Transfer agar pelanggan dapat menyimulasikan pembayaran di dashboard mereka.</span>
                            </div>
                            <form action="/admin/orders/{{ $order->id }}/assign-code" method="POST" class="flex gap-2 w-full md:max-w-[320px]">
                                @csrf
                                <input type="text" name="payment_code" placeholder="Contoh: VA-BRI-881023" required class="flex-1 border border-yellow-300 focus:border-yellow-500 px-4 py-2.5 text-sm rounded-xl focus:outline-none focus:ring-2 focus:ring-yellow-500/20 bg-white placeholder-yellow-400 font-medium transition shadow-sm">
                                <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white text-sm font-bold px-5 py-2.5 rounded-xl transition shadow-sm active:scale-95 flex items-center justify-center flex-shrink-0">Kirim</button>
                            </form>
                        </div>
                    @elseif($order->status === 'waiting_payment')
                        <div class="bg-blue-50/80 border border-blue-200 rounded-xl p-4 flex flex-col sm:flex-row sm:justify-between sm:items-center text-sm gap-3">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 flex-shrink-0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                </div>
                                <div>
                                    <span class="font-bold text-blue-800 text-xs block uppercase tracking-wider mb-0.5">Kode Pembayaran Dikirim</span>
                                    <code class="font-mono font-bold text-sm text-blue-900 bg-white border border-blue-200 px-2.5 py-1 rounded-md">{{ $order->payment_code }}</code>
                                </div>
                            </div>
                            <span class="text-blue-600 font-medium text-[13px] bg-white px-3 py-1.5 rounded-lg border border-blue-100 shadow-sm flex items-center gap-2">
                                <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Menunggu User
                            </span>
                        </div>
                    @elseif($order->status === 'packing')
                        <div class="bg-orange-50/80 border border-orange-200 rounded-xl p-5 flex flex-col md:flex-row md:items-center md:justify-between gap-4 transition group-hover:bg-orange-50">
                            <div class="max-w-md">
                                <span class="font-bold text-xs text-orange-850 uppercase tracking-wider block mb-1 flex items-center gap-1.5">
                                    📦 Aksi Admin: Kemas & Kirim Barang
                                </span>
                                <span class="text-[13px] text-orange-700 block leading-relaxed">Pembayaran telah terkonfirmasi. Input nomor resi pengiriman untuk mengirimkan barang dan memperbarui status pesanan.</span>
                            </div>
                            <form action="/admin/orders/{{ $order->id }}/ship" method="POST" class="flex gap-2 w-full md:max-w-[320px]">
                                @csrf
                                <input type="text" name="shipping_resi" placeholder="Contoh No. Resi: JNE-882103" required class="flex-1 border border-orange-300 focus:border-orange-500 px-4 py-2.5 text-sm rounded-xl focus:outline-none focus:ring-2 focus:ring-orange-500/20 bg-white placeholder-orange-400 font-medium transition shadow-sm">
                                <button type="submit" class="bg-orange-500 hover:bg-orange-600 text-white text-sm font-bold px-5 py-2.5 rounded-xl transition shadow-sm active:scale-95 flex items-center justify-center flex-shrink-0">Kirim Barang</button>
                            </form>
                        </div>
                    @elseif($order->status === 'shipping')
                        <div class="bg-indigo-50/80 border border-indigo-200 rounded-xl p-4 flex flex-col sm:flex-row sm:justify-between sm:items-center text-sm gap-3">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-605 flex-shrink-0">
                                    🚚
                                </div>
                                <div>
                                    <span class="font-bold text-indigo-805 text-xs block uppercase tracking-wider mb-0.5">Barang Sedang Dikirim</span>
                                    <span class="text-sm font-medium text-gray-700">Resi Pengiriman: <code class="font-mono font-bold bg-white border border-indigo-200 px-2 py-0.5 rounded text-indigo-900">{{ $order->shipping_resi }}</code></span>
                                </div>
                            </div>
                            <span class="text-indigo-750 font-bold flex items-center gap-1.5 bg-white px-4 py-1.5 rounded-lg border border-indigo-200 shadow-sm">
                                Dalam Pengiriman
                            </span>
                        </div>
                    @else
                        <div class="bg-green-50 border border-green-200 rounded-xl p-4 flex flex-col sm:flex-row sm:justify-between sm:items-center text-sm gap-3">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center text-green-600 flex-shrink-0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                                <div>
                                    <span class="font-bold text-green-805 text-xs block uppercase tracking-wider mb-0.5">Transaksi & Pengiriman Selesai</span>
                                    @if($order->shipping_resi)
                                        <span class="text-xs font-medium text-gray-500">Resi: <code class="font-mono">{{ $order->shipping_resi }}</code></span>
                                    @endif
                                </div>
                            </div>
                            <span class="text-green-700 font-bold flex items-center gap-1.5 bg-white px-4 py-1.5 rounded-lg border border-green-200 shadow-sm">
                                ✓ Selesai
                            </span>
                        </div>
                    @endif

                    <!-- Footer Transaksi -->
                    <div class="border-t border-gray-100 pt-5 mt-5 flex justify-between items-center bg-gray-50/50 -mx-6 -mb-6 p-6">
                        <span class="text-[13px] font-bold text-gray-400 uppercase tracking-wider flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                            Virtual Account
                        </span>
                        <div class="text-right">
                            <span class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-0.5">Total Transaksi</span>
                            <span class="font-bold text-indigo-700 text-xl tracking-tight">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    const activeAdminMaps = {};
    function initAdminOrderMap(orderId, lat, lng) {
        if (activeAdminMaps[orderId]) {
            setTimeout(() => activeAdminMaps[orderId].invalidateSize(), 100);
            return;
        }
        setTimeout(() => {
            let mapDivId = 'admin-order-map-' + orderId;
            let oMap = L.map(mapDivId).setView([lat, lng], 15);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
            }).addTo(oMap);
            
            L.marker([lat, lng]).addTo(oMap);
            activeAdminMaps[orderId] = oMap;
        }, 150);
    }
</script>
@endpush
