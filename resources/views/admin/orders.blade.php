<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Pembelian - BooSho Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style> body { font-family: 'Poppins', sans-serif; background-color: #f8fafc; } </style>
</head>
<body class="text-gray-800">

    <!-- Navigasi -->
    <nav class="bg-white shadow-sm p-4 flex justify-between items-center border-b-2 border-blue-100 sticky top-0 z-10">
        <div class="flex items-center gap-6">
            <h1 class="text-2xl font-bold text-blue-600 ml-4">BooSho.</h1>
            <div class="hidden md:flex gap-4">
                <a href="{{ route('dashboard') }}" class="font-semibold text-gray-500 hover:text-blue-600 transition">Dashboard</a>
                <a href="{{ route('admin.orders') }}" class="font-semibold text-blue-600 border-b-2 border-blue-600 pb-1">📦 Daftar Pembelian</a>
            </div>
        </div>
        <div class="mr-4 flex items-center gap-4">
            <span class="font-semibold text-gray-600">Halo, {{ Auth::user()->name }} <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded-full ml-1">{{ Auth::user()->role }}</span></span>
            <form action="{{ route('logout') }}" method="POST" class="inline" id="logout-form">
                @csrf
                <button type="button" onclick="konfirmasiLogout()" class="text-red-500 font-semibold hover:text-red-700 transition">Logout</button>
            </form>
        </div>
    </nav>

    <!-- Konten Utama -->
    <div class="container mx-auto mt-8 p-4 max-w-5xl">
        <!-- Banner -->
        <div class="bg-gradient-to-r from-blue-700 to-indigo-500 p-8 rounded-xl shadow-md text-white mb-8 transition hover:shadow-lg">
            <h2 class="text-3xl font-bold mb-2">📦 Monitor Penjualan & Transaksi</h2>
            <p class="text-blue-50 max-w-2xl text-lg leading-relaxed">
                Di sini Anda dapat melacak seluruh transaksi pembelian buku yang dilakukan oleh pengguna secara real-time.
            </p>
        </div>

        <!-- Daftar Transaksi -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <h2 class="text-xl font-bold text-gray-700 border-b pb-4 mb-6">Riwayat Transaksi Masuk</h2>

            @if($orders->isEmpty())
                <div class="text-center py-12 text-gray-500">
                    <div class="text-5xl mb-4">🛒</div>
                    <p class="text-lg">Belum ada transaksi pembelian yang tercatat.</p>
                </div>
            @else
                <div class="flex flex-col gap-6">
                    @foreach($orders as $order)
                    <div class="border border-gray-200 rounded-xl bg-gray-50/50 hover:bg-white hover:shadow-md transition p-6 relative overflow-hidden">
                        <!-- Strip Status -->
                        @if($order->status === 'pending')
                            <div class="absolute left-0 top-0 bottom-0 w-2 bg-yellow-400"></div>
                        @elseif($order->status === 'waiting_payment')
                            <div class="absolute left-0 top-0 bottom-0 w-2 bg-blue-500"></div>
                        @else
                            <div class="absolute left-0 top-0 bottom-0 w-2 bg-green-500"></div>
                        @endif

                        <!-- Header Transaksi -->
                        <div class="flex flex-col md:flex-row md:justify-between md:items-center border-b pb-4 mb-4 gap-2">
                            <div>
                                <div class="flex items-center gap-2">
                                    <span class="font-bold text-lg text-blue-600">ID Transaksi: #{{ $order->id }}</span>
                                    
                                    @if($order->status === 'pending')
                                        <span class="text-xs bg-yellow-100 text-yellow-800 font-semibold px-2.5 py-0.5 rounded-full">Menunggu Kode VA</span>
                                    @elseif($order->status === 'waiting_payment')
                                        <span class="text-xs bg-blue-100 text-blue-800 font-semibold px-2.5 py-0.5 rounded-full">Menunggu Pembayaran</span>
                                    @else
                                        <span class="text-xs bg-green-100 text-green-800 font-semibold px-2.5 py-0.5 rounded-full">Selesai / Lunas</span>
                                    @endif
                                </div>
                                <p class="text-sm text-gray-500 mt-1">
                                    Dipesan pada: {{ $order->created_at->setTimezone('Asia/Jakarta')->format('d M Y, H:i') }} WIB
                                </p>
                            </div>
                            <div class="bg-blue-50 p-2.5 rounded-lg border border-blue-100 text-sm">
                                <span class="block text-xs text-gray-400 font-semibold">Pelanggan:</span>
                                <span class="font-bold text-gray-700">{{ $order->user->name }}</span> 
                                <span class="text-gray-500 font-normal">({{ $order->user->email }})</span>
                            </div>
                        </div>

                        <!-- Detail Item Buku -->
                        <div class="mb-4">
                            <h4 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Item yang Dibeli</h4>
                            <div class="divide-y divide-gray-100">
                                @foreach($order->items as $item)
                                <div class="py-2.5 flex justify-between items-center text-sm">
                                    <div>
                                        <span class="font-semibold text-gray-800">{{ $item->book->title ?? 'Buku Telah Dihapus' }}</span>
                                        <span class="text-xs text-gray-400 ml-1">oleh {{ $item->book->author ?? '-' }}</span>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-gray-500">{{ $item->quantity }}x</span>
                                        <span class="font-medium text-gray-700 ml-2">@ Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Panel Aksi Admin untuk Pembayaran -->
                        @if($order->status === 'pending')
                            <div class="mt-4 bg-yellow-50/50 border border-yellow-100 rounded-xl p-4 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                                <div class="max-w-md">
                                    <span class="font-bold text-xs text-yellow-800 uppercase tracking-wider block">🔑 Aksi Admin: Kirim Kode Pembayaran</span>
                                    <span class="text-xs text-gray-500 mt-0.5 block">Kirim kode Virtual Account / Bank Transfer agar pelanggan dapat menyimulasikan pembayaran di dashboard mereka.</span>
                                </div>
                                <form action="/admin/orders/{{ $order->id }}/assign-code" method="POST" class="flex gap-2 max-w-sm w-full">
                                    @csrf
                                    <input type="text" name="payment_code" placeholder="Contoh: VA-BRI-881023901" required class="flex-1 border border-gray-300 px-3 py-2 text-xs rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold px-4 py-2 rounded-lg transition active:scale-95 shadow-sm">Kirim Kode</button>
                                </form>
                            </div>
                        @elseif($order->status === 'waiting_payment')
                            <div class="mt-4 bg-blue-50/50 border border-blue-100 rounded-xl p-4 flex flex-col sm:flex-row sm:justify-between sm:items-center text-xs gap-2">
                                <div>
                                    <span class="font-bold text-blue-800 block">Kode Pembayaran Dikirim:</span>
                                    <code class="font-mono font-bold text-sm text-blue-800 bg-white border border-blue-150 px-2 py-0.5 rounded mt-1 inline-block">{{ $order->payment_code }}</code>
                                </div>
                                <span class="text-gray-500 font-medium italic">Menunggu pelanggan melakukan konfirmasi bayar di dashboard...</span>
                            </div>
                        @else
                            <div class="mt-4 bg-green-50/50 border border-green-150 rounded-xl p-4 flex flex-col sm:flex-row sm:justify-between sm:items-center text-xs gap-2">
                                <div>
                                    <span class="font-bold text-green-800 block">Kode Pembayaran Digunakan:</span>
                                    <code class="font-mono font-bold text-sm text-green-800 bg-white border border-green-150 px-2 py-0.5 rounded mt-1 inline-block">{{ $order->payment_code }}</code>
                                </div>
                                <span class="text-green-700 font-bold flex items-center gap-1 text-sm">✓ Lunas Terbayar</span>
                            </div>
                        @endif

                        <!-- Footer Transaksi -->
                        <div class="border-t pt-4 flex justify-between items-center bg-gray-50 -mx-6 -mb-6 p-6 mt-4">
                            <span class="text-sm font-semibold text-gray-500">Metode Pembayaran: <span class="text-gray-700 font-medium">Virtual Account / VA</span></span>
                            <span class="font-bold text-gray-700 text-lg">Total Transaksi: <span class="text-indigo-600">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span></span>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <script>
        @if(session('success')) Swal.fire({ icon: 'success', title: 'Berhasil!', text: '{{ session("success") }}', showConfirmButton: false, timer: 1500 }); @endif
        @if(session('error')) Swal.fire({ icon: 'error', title: 'Oops...', text: '{{ session("error") }}', confirmButtonColor: '#2563eb' }); @endif

        function konfirmasiLogout() {
            Swal.fire({
                title: 'Yakin ingin keluar?', icon: 'warning', showCancelButton: true,
                confirmButtonColor: '#d33', cancelButtonColor: '#3085d6', confirmButtonText: 'Ya, Logout!'
            }).then((result) => {
                if (result.isConfirmed) document.getElementById('logout-form').submit();
            })
        }
    </script>
</body>
</html>
