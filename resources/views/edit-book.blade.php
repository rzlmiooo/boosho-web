<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Buku: {{ $book->title }} - Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f8fafc; }
        /* Hilangkan panah pada input number */
        input[type="number"]::-webkit-inner-spin-button,
        input[type="number"]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
    </style>
</head>
<body class="text-gray-800">

    <!-- Navigasi -->
    <nav class="bg-white/90 backdrop-blur shadow-sm px-6 py-3 flex justify-between items-center border-b border-indigo-100 sticky top-0 z-50">
        <div class="flex items-center gap-8">
            <h1 class="text-2xl font-bold text-indigo-600 tracking-tight">BooSho<span class="text-indigo-400">.</span></h1>
            <div class="hidden md:flex gap-5">
                <a href="{{ route('dashboard') }}" class="text-sm font-medium text-gray-500 hover:text-indigo-600 transition">Dashboard</a>
                <a href="{{ route('katalog') }}" class="text-sm font-medium text-gray-500 hover:text-indigo-600 transition">Katalog Buku</a>
                <a href="{{ route('admin.orders') }}" class="text-sm font-medium text-gray-500 hover:text-indigo-600 transition">📦 Daftar Pembelian</a>
            </div>
        </div>
        <div class="flex items-center gap-4">
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" @click.outside="open = false" class="flex items-center gap-2 focus:outline-none bg-indigo-50 hover:bg-indigo-100 border border-indigo-100 rounded-full pl-3 pr-1 py-1 transition group">
                    <span class="text-sm font-semibold text-indigo-700">{{ explode(' ', Auth::user()->name)[0] }}</span>
                    <span class="text-[10px] bg-indigo-200 text-indigo-800 px-1.5 py-0.5 rounded-full font-bold uppercase tracking-wider">Admin</span>
                    <div class="w-8 h-8 rounded-full bg-indigo-200 flex items-center justify-center text-indigo-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </div>
                </button>
                <div x-show="open" class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-gray-100 py-1 z-50" style="display: none;">
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
            </div>
        </div>
    </nav>

    <!-- Konten Utama -->
    <div class="max-w-3xl mx-auto px-4 sm:px-6 py-10">

        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-800 tracking-tight flex items-center gap-2">
                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Edit Data Buku
            </h2>
            <a href="javascript:history.back()" class="text-sm text-gray-500 hover:text-indigo-600 font-semibold transition px-4 py-2 bg-white rounded-lg border border-gray-200 shadow-sm">Batal & Kembali</a>
        </div>

        @if($errors->any())
            <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-5 py-4 rounded-xl text-sm font-medium">
                <ul class="list-disc pl-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <form action="/books/{{ $book->id }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="p-6 md:p-8 space-y-6">

                    <!-- Cover Area -->
                    <div class="flex flex-col sm:flex-row gap-6 items-start border-b border-gray-100 pb-6">
                        <div class="w-32 h-44 rounded-lg bg-gray-100 flex items-center justify-center overflow-hidden flex-shrink-0 border border-gray-200 shadow-sm">
                            @if($book->cover)
                                <img src="{{ asset('storage/' . $book->cover) }}" alt="Cover" class="w-full h-full object-cover">
                            @else
                                <span class="text-xs text-gray-400 font-medium text-center">Tanpa<br>Cover</span>
                            @endif
                        </div>
                        <div class="flex-grow">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Ganti Cover Buku (Opsional)</label>
                            <input type="file" name="cover" accept="image/*"
                                class="w-full border border-gray-300 px-4 py-2.5 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition">
                            <p class="text-[11px] text-gray-500 mt-2">Biarkan kosong jika tidak ingin mengubah cover gambar saat ini. (Maks 2MB, format jpg/png/jpeg).</p>
                        </div>
                    </div>

                    <!-- Input Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Judul Buku <span class="text-red-500">*</span></label>
                            <input type="text" name="title" value="{{ old('title', $book->title) }}" required placeholder="Masukkan judul buku..."
                                class="w-full border border-gray-300 px-4 py-3 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition shadow-sm">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Penulis <span class="text-red-500">*</span></label>
                            <input type="text" name="author" value="{{ old('author', $book->author) }}" required placeholder="Nama penulis..."
                                class="w-full border border-gray-300 px-4 py-3 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition shadow-sm">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Kategori</label>
                            <input type="text" name="category" value="{{ old('category', $book->category) }}" placeholder="Fiksi, Edukasi, Bisnis..."
                                class="w-full border border-gray-300 px-4 py-3 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition shadow-sm">
                        </div>

                        <div class="grid grid-cols-2 gap-4 md:col-span-2">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Harga (Rp) <span class="text-red-500">*</span></label>
                                <input type="number" name="price" value="{{ old('price', $book->price) }}" required min="0" placeholder="75000"
                                    class="w-full border border-gray-300 px-4 py-3 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition shadow-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Stok <span class="text-red-500">*</span></label>
                                <input type="number" name="stock" value="{{ old('stock', $book->stock) }}" required min="0" placeholder="10"
                                    class="w-full border border-gray-300 px-4 py-3 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition shadow-sm">
                            </div>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Deskripsi / Sinopsis Buku <span class="text-red-500">*</span></label>
                            <textarea name="description" required rows="5" placeholder="Tuliskan deskripsi atau sinopsis buku secara lengkap..."
                                class="w-full border border-gray-300 px-4 py-3 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition shadow-sm">{{ old('description', $book->description) }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-end gap-3 rounded-b-2xl">
                    <a href="javascript:history.back()" class="px-6 py-2.5 text-gray-600 bg-white border border-gray-300 hover:bg-gray-50 hover:text-gray-800 font-semibold rounded-xl text-sm transition shadow-sm">
                        Batal
                    </a>
                    <button type="submit" class="px-8 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl text-sm transition shadow-md active:scale-95 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
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