<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Buku - BooSho</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-6">

    <div class="bg-white p-8 rounded-2xl shadow-md w-full max-w-lg">
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Edit Data Buku</h2>
            <p class="text-sm text-gray-500 mt-1">Perbarui informasi katalog buku BooSho di bawah ini.</p>
        </div>

        <form action="/books/{{ $book->id }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT') <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Judul Buku</label>
                <input type="text" name="title" value="{{ $book->title }}" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none transition text-gray-800">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Penulis / Pengarang</label>
                <input type="text" name="author" value="{{ $book->author }}" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none transition text-gray-800">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Harga (Rp)</label>
                    <input type="number" name="price" value="{{ $book->price }}" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none transition text-gray-800">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Stok Buku</label>
                    <input type="number" name="stock" value="{{ $book->stock }}" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none transition text-gray-800">
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Deskripsi Buku</label>
                <textarea name="description" rows="4"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none transition text-gray-800">{{ $book->description }}</textarea>
            </div>

            <div class="flex space-x-3 pt-2">
                <a href="/katalog" 
                    class="w-1/2 text-center bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-2 px-4 rounded-lg transition">
                    Batal
                </a>
                <button type="submit" 
                    class="w-1/2 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg shadow transition">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

</body>
</html>