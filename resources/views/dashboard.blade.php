<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - BooSho</title>
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
                   class="px-4 py-2 rounded-lg text-sm font-semibold transition
                   {{ Route::is('dashboard') ? 'bg-blue-50 text-blue-600 border border-blue-100' : 'text-gray-500 hover:text-blue-600 hover:bg-gray-50' }}">
                    Dashboard
                </a>
                @if(Auth::user()->isAdmin())
                    <a href="{{ route('admin.orders') }}"
                       class="px-4 py-2 rounded-lg text-sm font-semibold transition
                       {{ Route::is('admin.orders') ? 'bg-blue-50 text-blue-600 border border-blue-100' : 'text-gray-500 hover:text-blue-600 hover:bg-gray-50' }}">
                        Daftar Pembelian
                    </a>
                @else
                    <a href="{{ route('keranjang') }}"
                       class="px-4 py-2 rounded-lg text-sm font-semibold transition
                       {{ Route::is('keranjang') ? 'bg-blue-50 text-blue-600 border border-blue-100' : 'text-gray-500 hover:text-blue-600 hover:bg-gray-50' }}">
                        Keranjang
                    </a>
                @endif
            </div>
        </div>
        <div class="flex items-center gap-3">
            <span class="font-semibold text-gray-600 text-sm">
                Halo, {{ Auth::user()->name }}
                <span class="ml-1 text-xs font-bold px-2 py-0.5 rounded-full
                    {{ Auth::user()->isAdmin() ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700' }}">
                    {{ Auth::user()->role }}
                </span>
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

    <!-- Konten Utama -->
    <div class="max-w-screen-xl mx-auto px-4 sm:px-6 py-8">

        <!-- Banner -->
        <div class="bg-gradient-to-r from-blue-600 to-indigo-500 rounded-2xl p-8 mb-8 text-white shadow-md">
            @if(Auth::user()->isAdmin())
                <h2 class="text-2xl font-bold mb-1">👋 Selamat Datang, Admin BooSho!</h2>
                <p class="text-blue-100 text-sm leading-relaxed max-w-xl">
                    Kelola data katalog buku, pantau ketersediaan stok, dan proses transaksi pembayaran pengguna dari satu panel terpusat.
                </p>
            @else
                <h2 class="text-2xl font-bold mb-1">👋 Temukan Buku Favoritmu di BooSho!</h2>
                <p class="text-blue-100 text-sm leading-relaxed max-w-xl">
                    Jelajahi berbagai buku terbaik kami, atur jumlah pembelian, dan lakukan transaksi belanja dengan nyaman dalam satu dashboard.
                </p>
            @endif
        </div>

        @if(session('success'))
            <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-sm font-medium">✅ {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm font-medium">⚠️ {{ session('error') }}</div>
        @endif

        @if(Auth::user()->isAdmin())
        <!-- ========================== TAMPILAN ADMIN ========================== -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex justify-between items-center mb-6 pb-5 border-b border-gray-100">
                <div>
                    <h2 class="text-lg font-bold text-gray-800">📚 Manajemen Katalog Buku</h2>
                    <p class="text-xs text-gray-400 mt-0.5">{{ $books->count() }} buku terdaftar di toko</p>
                </div>
                <button onclick="bukaModal()"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl font-semibold text-sm transition shadow-sm active:scale-95">
                    + Tambah Buku Baru
                </button>
            </div>

            @if($books->isEmpty())
                <div class="text-center py-16 text-gray-400">
                    <div class="text-4xl mb-3">📭</div>
                    <p class="font-medium">Belum ada data buku. Silakan tambahkan!</p>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5">
                    @foreach($books as $book)
                    <div class="border border-gray-200 rounded-xl p-5 hover:shadow-md hover:border-blue-200 transition bg-white flex flex-col justify-between group">
                        <div>
                            <h3 class="font-bold text-blue-600 group-hover:text-blue-700 transition text-sm leading-snug mb-1">{{ $book->title }}</h3>
                            <p class="text-xs text-gray-400 mb-3">{{ $book->author }}</p>
                            <p class="font-bold text-gray-800">Rp {{ number_format($book->price, 0, ',', '.') }}</p>
                        </div>
                        <div class="mt-4 pt-4 border-t border-gray-100 flex justify-between items-center">
                            <div class="flex items-center gap-1.5 text-xs text-gray-500">
                                <span class="w-2 h-2 rounded-full {{ $book->stock > 5 ? 'bg-green-400' : ($book->stock > 0 ? 'bg-yellow-400' : 'bg-red-400') }}"></span>
                                Stok: <b class="text-gray-700">{{ $book->stock }}</b>
                            </div>
                            <form action="/books/{{ $book->id }}" method="POST" id="delete-form-{{ $book->id }}">
                                @csrf @method('DELETE')
                                <button type="button" onclick="konfirmasiHapus({{ $book->id }})"
                                    class="text-xs text-red-500 hover:text-red-700 bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded-lg transition font-semibold">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Modal Tambah Buku -->
        <div id="modalTambah" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 backdrop-blur-sm" style="display:none">
            <div class="bg-white rounded-2xl shadow-xl w-full max-w-md mx-4 border-t-4 border-blue-600">
                <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-blue-600">Tambah Buku Baru</h3>
                    <button onclick="tutupModal()" class="text-gray-400 hover:text-gray-600 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <form action="/books" method="POST" class="p-6">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Judul Buku</label>
                            <input type="text" name="title" required placeholder="Masukkan judul buku..."
                                class="w-full border border-gray-300 px-4 py-2.5 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Penulis</label>
                            <input type="text" name="author" required placeholder="Nama penulis..."
                                class="w-full border border-gray-300 px-4 py-2.5 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Harga (Rp)</label>
                                <input type="number" name="price" required placeholder="75000"
                                    class="w-full border border-gray-300 px-4 py-2.5 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Stok</label>
                                <input type="number" name="stock" required placeholder="10"
                                    class="w-full border border-gray-300 px-4 py-2.5 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                        </div>
                    </div>
                    <div class="flex gap-3 mt-6">
                        <button type="button" onclick="tutupModal()"
                            class="flex-1 px-4 py-2.5 bg-gray-100 text-gray-700 font-semibold rounded-xl hover:bg-gray-200 text-sm transition">Batal</button>
                        <button type="submit"
                            class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl py-2.5 text-sm transition">Simpan Buku</button>
                    </div>
                </form>
            </div>
        </div>

        @else
        <!-- ========================== TAMPILAN USER ========================== -->
        <div class="flex flex-col xl:flex-row gap-6">

            <!-- Kolom Kiri: Katalog Buku -->
            <div class="w-full xl:w-[65%]">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-center justify-between mb-6 pb-5 border-b border-gray-100">
                        <div>
                            <h2 class="text-lg font-bold text-gray-800">📖 Katalog Buku</h2>
                            <p class="text-xs text-gray-400 mt-0.5">Pilih jumlah & tambahkan ke keranjang</p>
                        </div>
                        <a href="{{ route('keranjang') }}"
                            class="flex items-center gap-2 px-4 py-2 bg-gray-50 border border-gray-200 hover:bg-blue-50 hover:border-blue-200 hover:text-blue-600 rounded-xl text-sm text-gray-600 font-semibold transition">
                            🛒 Lihat Keranjang
                        </a>
                    </div>

                    @if($books->isEmpty())
                        <div class="text-center py-16 text-gray-400">
                            <div class="text-4xl mb-3">📭</div>
                            <p class="font-medium">Belum ada buku tersedia saat ini.</p>
                        </div>
                    @else
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                            @foreach($books as $book)
                            <div class="border border-gray-200 rounded-xl p-5 hover:shadow-md hover:border-blue-200 transition bg-white flex flex-col justify-between group">
                                <div>
                                    <div class="flex items-start justify-between mb-3">
                                        <div class="w-9 h-9 rounded-lg bg-blue-50 border border-blue-100 flex items-center justify-center">
                                            <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                            </svg>
                                        </div>
                                        @if($book->stock <= 0)
                                            <span class="text-[10px] font-bold bg-red-50 text-red-500 border border-red-100 px-2 py-0.5 rounded-full">Habis</span>
                                        @elseif($book->stock <= 3)
                                            <span class="text-[10px] font-bold bg-yellow-50 text-yellow-600 border border-yellow-100 px-2 py-0.5 rounded-full">Sisa {{ $book->stock }}</span>
                                        @endif
                                    </div>
                                    <h3 class="font-bold text-blue-600 group-hover:text-blue-700 transition text-sm leading-snug mb-1">{{ $book->title }}</h3>
                                    <p class="text-xs text-gray-400 mb-2">{{ $book->author }}</p>
                                    <p class="font-bold text-gray-800">Rp {{ number_format($book->price, 0, ',', '.') }}</p>
                                </div>

                                <div class="mt-4 pt-4 border-t border-gray-100 flex flex-col gap-2.5">
                                    <div class="text-xs text-gray-400 flex items-center gap-1.5">
                                        <span class="w-2 h-2 rounded-full {{ $book->stock > 5 ? 'bg-green-400' : ($book->stock > 0 ? 'bg-yellow-400' : 'bg-red-400') }}"></span>
                                        Stok: <b class="text-gray-600">{{ $book->stock }}</b>
                                    </div>

                                    @if($book->stock > 0)
                                        <form action="/cart/{{ $book->id }}" method="POST" class="flex flex-col gap-2">
                                            @csrf
                                            <!-- Qty Selector -->
                                            <div class="flex items-center justify-between bg-gray-50 border border-gray-200 rounded-lg px-3 py-2">
                                                <span class="text-xs text-gray-500 font-semibold">Qty:</span>
                                                <div class="flex items-center gap-2">
                                                    <button type="button" onclick="adjustQty({{ $book->id }}, -1)"
                                                        class="w-6 h-6 rounded-md bg-white border border-gray-300 text-gray-600 hover:bg-gray-100 active:scale-90 transition font-bold text-sm flex items-center justify-center shadow-sm">−</button>
                                                    <input type="number" id="qty-{{ $book->id }}" name="quantity" value="1" min="1" max="{{ $book->stock }}"
                                                        class="w-8 text-center bg-transparent border-0 font-bold text-gray-800 text-sm focus:outline-none" readonly>
                                                    <button type="button" onclick="adjustQty({{ $book->id }}, 1, {{ $book->stock }})"
                                                        class="w-6 h-6 rounded-md bg-white border border-gray-300 text-gray-600 hover:bg-gray-100 active:scale-90 transition font-bold text-sm flex items-center justify-center shadow-sm">+</button>
                                                </div>
                                            </div>
                                            <button type="submit"
                                                class="w-full py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm rounded-lg transition active:scale-95 shadow-sm">
                                                + Keranjang
                                            </button>
                                        </form>
                                    @else
                                        <div class="py-2 text-center text-xs text-red-400 bg-red-50 border border-red-100 rounded-lg font-semibold">
                                            Stok Habis
                                        </div>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <!-- Kolom Kanan: Tracker Pesanan -->
            <div class="w-full xl:w-[35%]">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sticky top-24">
                    <div class="flex items-center justify-between mb-5 pb-5 border-b border-gray-100">
                        <div>
                            <h3 class="text-lg font-bold text-gray-800">📋 Pesanan Saya</h3>
                            <p class="text-xs text-gray-400 mt-0.5">Lacak status transaksi Anda</p>
                        </div>
                        @if($orders->isNotEmpty())
                            <span class="text-xs font-bold bg-blue-50 text-blue-600 border border-blue-100 px-2.5 py-1 rounded-full">{{ $orders->count() }} Pesanan</span>
                        @endif
                    </div>

                    @if($orders->isEmpty())
                        <div class="text-center py-12 bg-gray-50 rounded-2xl border-2 border-dashed border-gray-200">
                            <div class="text-3xl mb-2">📦</div>
                            <p class="text-sm font-medium text-gray-500">Belum ada riwayat pesanan.</p>
                            <p class="text-xs text-gray-400 mt-1">Buku yang Anda checkout akan tampil di sini.</p>
                        </div>
                    @else
                        <div class="flex flex-col gap-4 max-h-[580px] overflow-y-auto pr-1">
                            @foreach($orders as $order)
                            <div class="border border-gray-200 rounded-xl p-4 hover:shadow-sm hover:border-blue-100 transition">
                                <!-- Header -->
                                <div class="flex justify-between items-start mb-3">
                                    <div>
                                        <span class="font-bold text-sm text-blue-600">Order #{{ $order->id }}</span>
                                        <span class="block text-[10px] text-gray-400 mt-0.5">
                                            {{ $order->created_at->setTimezone('Asia/Jakarta')->format('d M Y, H:i') }} WIB
                                        </span>
                                    </div>
                                    @if($order->status === 'pending')
                                        <span class="text-[10px] bg-yellow-50 text-yellow-700 border border-yellow-200 font-bold px-2.5 py-1 rounded-full">⏳ Menunggu Kode</span>
                                    @elseif($order->status === 'waiting_payment')
                                        <span class="text-[10px] bg-blue-50 text-blue-700 border border-blue-200 font-bold px-2.5 py-1 rounded-full">💳 Menunggu Bayar</span>
                                    @else
                                        <span class="text-[10px] bg-green-50 text-green-700 border border-green-200 font-bold px-2.5 py-1 rounded-full">✓ Lunas</span>
                                    @endif
                                </div>

                                <!-- Items -->
                                <div class="border-t border-b border-gray-100 py-3 mb-3 flex flex-col gap-1.5">
                                    @foreach($order->items as $item)
                                    <div class="flex justify-between items-center text-xs">
                                        <span class="text-gray-700 truncate max-w-[160px] font-medium">{{ $item->book->title ?? 'Buku dihapus' }}</span>
                                        <span class="text-gray-400 flex-shrink-0 ml-2">{{ $item->quantity }}× Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                                    </div>
                                    @endforeach
                                </div>

                                <!-- Total -->
                                <div class="flex justify-between items-center mb-3">
                                    <span class="text-xs text-gray-500 font-semibold">Total Tagihan</span>
                                    <span class="font-bold text-gray-800">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                                </div>

                                <!-- Status Action -->
                                @if($order->status === 'waiting_payment')
                                    <div class="bg-blue-50 border border-blue-100 rounded-xl p-3">
                                        <p class="text-[10px] text-blue-500 font-bold uppercase tracking-wider mb-2">💳 Kode Pembayaran VA:</p>
                                        <div class="flex justify-between items-center gap-2">
                                            <code class="font-mono font-bold text-sm text-blue-700 bg-white border border-blue-100 px-2.5 py-1.5 rounded-lg select-all flex-1 truncate">
                                                {{ $order->payment_code }}
                                            </code>
                                            <form action="/orders/{{ $order->id }}/pay" method="POST" id="pay-form-{{ $order->id }}">
                                                @csrf
                                                <button type="button" onclick="konfirmasiBayar({{ $order->id }})"
                                                    class="bg-green-600 hover:bg-green-700 text-white text-[11px] font-bold px-3 py-2 rounded-lg transition active:scale-95 flex-shrink-0">
                                                    Bayar
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @elseif($order->status === 'pending')
                                    <div class="bg-yellow-50 border border-yellow-100 rounded-xl p-3 text-center">
                                        <p class="text-[11px] text-yellow-600 font-medium">⏳ Kode pembayaran sedang disiapkan admin...</p>
                                    </div>
                                @else
                                    <div class="bg-green-50 border border-green-100 rounded-xl p-3 text-center">
                                        <p class="text-[11px] text-green-600 font-medium">✓ Pembayaran berhasil terkonfirmasi</p>
                                    </div>
                                @endif
                            </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

        </div>
        @endif
    </div>

    <script>
        @if(session('success')) Swal.fire({ icon: 'success', title: 'Berhasil!', text: '{{ session("success") }}', showConfirmButton: false, timer: 1800 }); @endif
        @if(session('error')) Swal.fire({ icon: 'error', title: 'Oops!', text: '{{ session("error") }}', confirmButtonColor: '#2563eb' }); @endif

        function adjustQty(bookId, delta, maxStock = 9999) {
            const input = document.getElementById('qty-' + bookId);
            let val = parseInt(input.value) + delta;
            if (val < 1) val = 1;
            if (val > maxStock) val = maxStock;
            input.value = val;
        }

        function bukaModal() { document.getElementById('modalTambah').style.display = 'flex'; }
        function tutupModal() { document.getElementById('modalTambah').style.display = 'none'; }
        document.getElementById('modalTambah')?.addEventListener('click', function(e) {
            if (e.target === this) tutupModal();
        });

        function konfirmasiHapus(id) {
            Swal.fire({
                title: 'Hapus Buku?', text: "Buku akan dihapus permanen dari toko!", icon: 'warning',
                showCancelButton: true, confirmButtonColor: '#d33', cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!', cancelButtonText: 'Batal'
            }).then((r) => { if (r.isConfirmed) document.getElementById('delete-form-' + id).submit(); })
        }

        function konfirmasiLogout() {
            Swal.fire({
                title: 'Logout?', icon: 'warning', showCancelButton: true,
                confirmButtonColor: '#d33', cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Keluar!', cancelButtonText: 'Batal'
            }).then((r) => { if (r.isConfirmed) document.getElementById('logout-form').submit(); })
        }

        function konfirmasiBayar(orderId) {
            Swal.fire({
                title: '💳 Konfirmasi Pembayaran',
                text: "Pastikan Anda sudah menyalin kode VA dan ingin mengkonfirmasi pembayaran.",
                icon: 'info', showCancelButton: true,
                confirmButtonColor: '#16a34a', cancelButtonColor: '#3085d6',
                confirmButtonText: '✓ Ya, Bayar Sekarang!', cancelButtonText: 'Batal'
            }).then((r) => { if (r.isConfirmed) document.getElementById('pay-form-' + orderId).submit(); })
        }
    </script>
</body>
</html>