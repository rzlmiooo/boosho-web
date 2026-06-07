<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog Lengkap - BooSho</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style> body { font-family: 'Poppins', sans-serif; background-color: #f8fafc; } </style>
</head>
<body class="text-gray-800">

    <nav class="bg-white shadow-sm p-4 flex justify-between items-center border-b-2 border-blue-100 sticky top-0 z-10">
        <div class="flex items-center gap-6">
            <h1 class="text-2xl font-bold text-blue-600 ml-4">BooSho.</h1>
            <div class="hidden md:flex gap-4">
                <a href="{{ route('dashboard') }}" class="font-semibold text-gray-500 hover:text-blue-600 transition">Dashboard</a>
                <a href="{{ route('katalog') }}" class="font-semibold text-blue-600 border-b-2 border-blue-600 pb-1">Katalog Buku</a>
                
                @if(!Auth::user()->isAdmin())
                    <a href="{{ route('keranjang') }}" class="font-semibold text-gray-500 hover:text-blue-600 transition">🛒 Keranjang</a>
                @endif
            </div>
        </div>
        <div class="mr-4 flex items-center gap-4">
            <span class="font-semibold text-gray-600">Halo, {{ Auth::user()->name }}</span>
            <form action="{{ route('logout') }}" method="POST" class="inline" id="logout-form">
                @csrf
                <button type="button" onclick="konfirmasiLogout()" class="text-red-500 font-semibold hover:text-red-700 transition">Logout</button>
            </form>
        </div>
    </nav>

    <div class="container mx-auto mt-8 p-4">
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <div class="flex justify-between items-center mb-6 border-b pb-4">
                <h2 class="text-xl font-bold text-gray-700">Daftar Seluruh Buku</h2>
                
                @if(Auth::user()->isAdmin())
                    <button onclick="bukaModal()" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition shadow-sm font-semibold">
                        + Tambah Buku
                    </button>
                @endif
            </div>

            @if($books->isEmpty())
                <div class="text-center py-10 text-gray-500">Belum ada data buku di database.</div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    @foreach($books as $book)
                    <div class="border border-gray-200 rounded-lg p-5 hover:shadow-lg transition bg-white flex flex-col justify-between">
                        <div>
                            <h3 class="font-bold text-lg text-blue-600">{{ $book->title }}</h3>
                            <p class="text-sm text-gray-500 mt-1">{{ $book->author }}</p>
                            <p class="mt-3 font-bold text-gray-800">Rp {{ number_format($book->price, 0, ',', '.') }}</p>
                        </div>
                        <div class="mt-4 pt-4 border-t flex justify-between items-center text-xs text-gray-500">
                            <span>Stok: <b class="text-gray-700">{{ $book->stock }}</b></span>
                            
                            @if(Auth::user()->isAdmin())
                                <form action="/books/{{ $book->id }}" method="POST" id="delete-form-{{ $book->id }}">
                                    @csrf @method('DELETE')
                                    <button type="button" onclick="konfirmasiHapus({{ $book->id }})" class="text-red-500 font-bold hover:underline bg-red-50 px-2 py-1 rounded">Hapus</button>
                                </form>
                            @else
                                @if($book->stock > 0)
                                    <form action="/cart/{{ $book->id }}" method="POST">
                                        @csrf
                                        <button type="submit" class="bg-blue-100 text-blue-700 font-bold hover:bg-blue-600 hover:text-white px-3 py-1 rounded transition">+ Keranjang</button>
                                    </form>
                                @else
                                    <span class="text-red-500 font-bold bg-red-50 px-2 py-1 rounded">Habis</span>
                                @endif
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    @if(Auth::user()->isAdmin())
    <div id="modalTambah" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
        <div class="bg-white p-6 rounded-xl shadow-lg w-full max-w-md border-t-4 border-blue-600">
            <h3 class="text-xl font-bold mb-4 text-blue-600">Tambah Buku</h3>
            <form action="/books" method="POST">
                @csrf
                <div class="mb-3"><label class="block text-sm font-semibold mb-1 text-gray-700">Judul Buku</label><input type="text" name="title" required class="w-full border border-gray-300 p-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"></div>
                <div class="mb-3"><label class="block text-sm font-semibold mb-1 text-gray-700">Nama Penulis</label><input type="text" name="author" required class="w-full border border-gray-300 p-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"></div>
                <div class="mb-4 flex gap-4">
                    <div class="w-1/2"><label class="block text-sm font-semibold mb-1 text-gray-700">Harga</label><input type="number" name="price" required class="w-full border border-gray-300 p-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"></div>
                    <div class="w-1/2"><label class="block text-sm font-semibold mb-1 text-gray-700">Stok</label><input type="number" name="stock" required class="w-full border border-gray-300 p-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"></div>
                </div>
                <div class="flex justify-end gap-2 mt-6">
                    <button type="button" onclick="tutupModal()" class="px-4 py-2 bg-gray-200 text-gray-700 font-semibold rounded hover:bg-gray-300">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white font-semibold rounded hover:bg-blue-700">Simpan</button>
                </div>
            </form>
        </div>
    </div>
    @endif

    <script>
        @if(session('success')) Swal.fire({ icon: 'success', title: 'Berhasil!', text: '{{ session("success") }}', showConfirmButton: false, timer: 1500 }); @endif
        @if(session('error')) Swal.fire({ icon: 'error', title: 'Oops...', text: '{{ session("error") }}', confirmButtonColor: '#2563eb' }); @endif

        function bukaModal() { document.getElementById('modalTambah').classList.remove('hidden'); }
        function tutupModal() { document.getElementById('modalTambah').classList.add('hidden'); }
        function konfirmasiHapus(id) {
            Swal.fire({ title: 'Hapus?', text: "Tidak bisa dikembalikan!", icon: 'warning', showCancelButton: true, confirmButtonColor: '#d33', cancelButtonColor: '#3085d6', confirmButtonText: 'Ya!' })
            .then((result) => { if (result.isConfirmed) document.getElementById('delete-form-' + id).submit(); })
        }
        function konfirmasiLogout() {
            Swal.fire({ title: 'Logout?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#d33', cancelButtonColor: '#3085d6', confirmButtonText: 'Ya!' })
            .then((result) => { if (result.isConfirmed) document.getElementById('logout-form').submit(); })
        }
    </script>
</body>
</html>