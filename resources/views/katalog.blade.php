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
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Deskripsi Buku (Opsional)</label>
                    <textarea name="description" rows="4" placeholder="Masukkan sinopsis atau deskripsi singkat buku..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none transition text-gray-800"></textarea>
                </div>
                <div class="flex justify-end gap-2 mt-6">
                    <button type="button" onclick="tutupModal()" class="px-4 py-2 bg-gray-200 text-gray-700 font-semibold rounded hover:bg-gray-300">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white font-semibold rounded hover:bg-blue-700">Simpan</button>
                </div>
            </form>
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 p-6">

    @foreach ($books as $book)
    <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300 flex flex-col">
        
        <div class="h-48 bg-gray-200 flex items-center justify-center">
            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
        </div>

        <div class="p-5 flex-grow flex flex-col">
            <h3 class="text-lg font-bold text-gray-800 line-clamp-1">{{ $book->title }}</h3>
            <p class="text-sm text-gray-500 mt-1">{{ $book->author }}</p>
            
            <div class="mt-3 flex justify-between items-center">
                <span class="text-blue-600 font-extrabold text-lg">Rp {{ number_format($book->price, 0, ',', '.') }}</span>
                <span class="text-xs font-semibold px-2 py-1 bg-green-100 text-green-700 rounded-full">Stok: {{ $book->stock }}</span>
            </div>

            <p class="text-gray-600 text-sm mt-3 line-clamp-2">{{ $book->description }}</p>
        </div>

        <div class="p-5 pt-0 mt-auto">
            @if(Auth::user()->role === 'admin')
                <div class="flex space-x-2">
                    <a href="/books/{{ $book->id }}/edit" class="w-1/2 text-center bg-yellow-400 hover:bg-yellow-500 text-white font-semibold py-2 px-4 rounded transition">Edit</a>
                    <form action="/books/{{ $book->id }}" method="POST" class="w-1/2" id="form-hapus-{{ $book->id }}">
                        @csrf
                        @method('DELETE')
                        <button type="button" onclick="konfirmasiHapus({{ $book->id }})" class="w-full bg-red-500 hover:bg-red-600 text-white font-semibold py-2 px-4 rounded transition">
                            Hapus
                        </button>
                    </form>
                </div>
            @else
                <form action="/cart/add/{{ $book->id }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition flex justify-center items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        Ke Keranjang
                    </button>
                </form>
            @endif
        </div>

    </div>
    @endforeach

</div>

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