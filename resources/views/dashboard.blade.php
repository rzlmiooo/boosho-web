<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - BooSho</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f8fafc; }
    </style>
</head>
<body class="text-gray-800">

    @php
        $books = \App\Models\Book::latest()->get();
        if(!isset($previewBooks)) $previewBooks = $books->take(4);
    @endphp

    <nav class="bg-white/90 backdrop-blur shadow-sm px-6 py-3 flex justify-between items-center border-b border-indigo-100 sticky top-0 z-50">
        <div class="flex items-center gap-8">
            <h1 class="text-2xl font-bold text-indigo-600 tracking-tight">BooSho<span class="text-indigo-400">.</span></h1>
            <div class="hidden md:flex gap-5">
                <a href="{{ route('dashboard') }}" class="text-sm font-semibold text-indigo-600 border-b-2 border-indigo-500 pb-0.5">Dashboard</a>
                <a href="{{ route('katalog') }}" class="text-sm font-medium text-gray-500 hover:text-indigo-600 transition">Katalog Buku</a>

                @if(!Auth::user()->isAdmin())
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

    <div class="max-w-screen-xl mx-auto px-4 sm:px-6 py-8">

        @if(session('success'))
            <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-sm font-medium">✅ {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm font-medium">⚠️ {{ session('error') }}</div>
        @endif

        @if(Auth::user()->isAdmin())
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
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    @foreach($books as $book)
                    <div class="border border-gray-200 rounded-2xl hover:shadow-lg hover:-translate-y-1 transition duration-300 bg-white flex flex-col group overflow-hidden">
                        
                        <div class="w-full aspect-[16/10] bg-gray-100 relative overflow-hidden flex items-center justify-center">
                            @if(isset($book->cover) && $book->cover)
                                <img src="{{ asset('storage/' . $book->cover) }}" alt="Cover {{ $book->title }}" class="w-full h-full object-cover">
                            @else
                                <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                </svg>
                            @endif
                        </div>

                        <div class="p-5 flex flex-col flex-grow justify-between">
                            <div>
                                <h3 class="font-bold text-gray-800 text-sm leading-snug mb-1 line-clamp-2">{{ $book->title }}</h3>
                                <p class="text-xs text-gray-500 mb-3">{{ $book->author }}</p>
                            </div>

                            <div>
                                <div class="flex justify-between items-center mb-5">
                                    <span class="font-bold text-blue-600 text-sm">Rp {{ number_format($book->price, 0, ',', '.') }}</span>
                                    <span class="text-[10px] font-bold px-2.5 py-1 rounded-full {{ $book->stock > 5 ? 'bg-green-100 text-green-700' : ($book->stock > 0 ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                                        {{ $book->stock > 0 ? 'Stok: ' . $book->stock : 'Habis' }}
                                    </span>
                                </div>
                                <div class="pt-4 border-t border-gray-100 flex flex-col gap-2.5">
                                    <a href="/books/{{ $book->id }}/edit"
                                        class="block w-full py-2 bg-yellow-400 hover:bg-yellow-500 text-yellow-900 font-bold text-sm rounded-lg transition active:scale-95 shadow-sm text-center">
                                        Edit Buku
                                    </a>

                                    <form action="/books/{{ $book->id }}" method="POST" id="delete-form-{{ $book->id }}" class="w-full">
                                        @csrf @method('DELETE')
                                        <button type="button" onclick="konfirmasiHapus({{ $book->id }})"
                                            class="w-full py-2 bg-red-500 hover:bg-red-600 text-white font-bold text-sm rounded-lg transition active:scale-95 shadow-sm text-center">
                                            Hapus Buku
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>

        <div id="modalTambah" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 backdrop-blur-sm" style="display:none">
            <div class="bg-white rounded-2xl shadow-xl w-full max-w-md mx-4 border-t-4 border-blue-600">
                <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-blue-600">Tambah Buku Baru</h3>
                    <button onclick="tutupModal()" class="text-gray-400 hover:text-gray-600 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <form action="/books" method="POST" enctype="multipart/form-data" class="p-6">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Cover Buku (Opsional)</label>
                            <input type="file" name="cover" accept="image/*"
                                class="w-full border border-gray-300 px-4 py-2 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        </div>
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
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Deskripsi Buku</label>
                            <textarea name="description" rows="3" placeholder="Masukkan deskripsi singkat buku..."
                                class="w-full border border-gray-300 px-4 py-2.5 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
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
        <div class="w-full">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between mb-6 pb-5 border-b border-gray-100">
                    <div>
                        <h2 class="text-lg font-bold text-gray-800">📖 Katalog Buku Terbaru</h2>
                        <p class="text-xs text-gray-400 mt-0.5">Temukan berbagai koleksi buku menarik</p>
                    </div>
                </div>

                @if($previewBooks->isEmpty())
                    <div class="text-center py-16 text-gray-400">
                        <div class="text-4xl mb-3">📭</div>
                        <p class="font-medium">Belum ada buku tersedia saat ini.</p>
                    </div>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
                        @foreach($previewBooks as $book)
                        <div class="border border-gray-200 rounded-2xl hover:shadow-lg hover:-translate-y-1 transition duration-300 bg-white flex flex-col group overflow-hidden">
                            
                            <div class="w-full aspect-[16/10] bg-gray-100 relative overflow-hidden flex items-center justify-center">
                                @if(isset($book->cover) && $book->cover)
                                    <img src="{{ asset('storage/' . $book->cover) }}" alt="Cover {{ $book->title }}" class="w-full h-full object-cover">
                                @else
                                    <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                    </svg>
                                @endif
                            </div>

                            <div class="p-5 flex flex-col flex-grow justify-between">
                                <div>
                                    <h3 class="font-bold text-gray-800 text-sm leading-snug mb-1 line-clamp-2">{{ $book->title }}</h3>
                                    <p class="text-xs text-gray-500 mb-4">{{ $book->author }}</p>
                                </div>

                                <div>
                                    <div class="flex justify-between items-center mb-4">
                                        <span class="font-bold text-blue-600 text-sm">Rp {{ number_format($book->price, 0, ',', '.') }}</span>
                                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $book->stock > 5 ? 'bg-green-100 text-green-700' : ($book->stock > 0 ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                                            {{ $book->stock > 0 ? 'Stok: ' . $book->stock : 'Habis' }}
                                        </span>
                                    </div>

                                    <div class="pt-4 border-t border-gray-100">
                                        <a href="/books/{{ $book->id }}" class="block w-full text-center py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm rounded-lg transition shadow-sm">
                                            Detail Buku
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
        @endif
    </div>

    <script>
        @if(session('success')) Swal.fire({ icon: 'success', title: 'Berhasil!', text: '{{ session("success") }}', showConfirmButton: false, timer: 1800 }); @endif
        @if(session('error')) Swal.fire({ icon: 'error', title: 'Oops!', text: '{{ session("error") }}', confirmButtonColor: '#2563eb' }); @endif

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
    </script>
</body>
</html>