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

        <form action="/books/{{ $book->id }}" method="POST" enctype="multipart/form-data" class="space-y-5">
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

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Kategori</label>
                <input type="text" name="category" value="{{ $book->category }}" placeholder="Fiksi, Edukasi, Bisnis..."
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

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Ganti Cover Buku (Opsional)</label>
                <input type="file" name="cover" accept="image/jpeg,image/png,image/jpg"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none transition text-gray-800 file:mr-4 file:py-1 file:px-3 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                @if($book->cover)
                    <div class="mt-2 text-xs text-gray-500 flex items-center gap-2">
                        <span>Cover saat ini:</span>
                        <img src="{{ asset('storage/' . $book->cover) }}" class="w-8 h-12 object-cover rounded shadow-sm">
                    </div>
                @endif
            </div>

            <!-- Area UI Genre Buku -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Genre Buku</label>
                
                <!-- Tempat Munculnya Badge Genre -->
                <div id="genre-badges" class="flex flex-wrap gap-2 mb-3"></div>
                
                <!-- Tombol Buka Modal -->
                <button type="button" onclick="bukaModalGenre()" class="inline-flex items-center px-3 py-1.5 bg-indigo-50 text-indigo-600 border border-indigo-200 rounded-lg text-sm font-semibold hover:bg-indigo-100 transition-colors">
                    + Tambah Genre
                </button>

                <!-- Modal Pop-up Checkbox Genre (Tersembunyi by default) -->
                <div id="modal-genre" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center backdrop-blur-sm">
                    <div class="bg-white rounded-xl shadow-xl w-full max-w-md p-6">
                        <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Pilih Genre Buku</h3>
                        
                        <!-- Daftar Pilihan Genre Aman -->
                        <div class="grid grid-cols-2 gap-3 mb-6 max-h-60 overflow-y-auto" id="genre-checkboxes">
                            @php 
                                $daftarGenre = ['Fiksi', 'Non-Fiksi', 'Edukasi', 'Novel', 'Sejarah', 'Fantasi', 'Misteri', 'Biografi', 'Romantis', 'Teknologi', 'Sains', 'Agama'];
                                $genreTerpilih = $book->genres ?? []; 
                            @endphp
                            
                            @foreach($daftarGenre as $genre)
                            <label class="flex items-center space-x-2 cursor-pointer group">
                                <input type="checkbox" name="genres[]" value="{{ $genre }}" 
                                    class="genre-cb w-4 h-4 text-indigo-600 rounded border-gray-300 focus:ring-indigo-500"
                                    onchange="updateBadges()"
                                    {{ in_array($genre, $genreTerpilih) ? 'checked' : '' }}>
                                <span class="text-sm text-gray-700 group-hover:text-indigo-600 font-medium">{{ $genre }}</span>
                            </label>
                            @endforeach
                        </div>

                        <button type="button" onclick="tutupModalGenre()" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 rounded-lg transition-colors">
                            Selesai Memilih
                        </button>
                    </div>
                </div>
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

    <!-- Script Interaktif untuk Genre -->
    <script>
        const modal = document.getElementById('modal-genre');
        const badgeContainer = document.getElementById('genre-badges');

        function bukaModalGenre() { modal.classList.remove('hidden'); }
        function tutupModalGenre() { modal.classList.add('hidden'); }

        // Fungsi memperbarui tampilan badge jika checkbox diklik
        function updateBadges() {
            badgeContainer.innerHTML = ''; // Bersihkan kontainer
            document.querySelectorAll('.genre-cb:checked').forEach(cb => {
                const badge = document.createElement('span');
                badge.className = 'inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-indigo-100 text-indigo-700 border border-indigo-200';
                badge.innerHTML = `
                    ${cb.value}
                    <button type="button" onclick="hapusGenre('${cb.value}')" class="ml-1.5 text-indigo-400 hover:text-red-500 transition-colors">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                    </button>
                `;
                badgeContainer.appendChild(badge);
            });
        }

        // Fungsi menghapus genre lewat tombol silang 'x' di badge
        function hapusGenre(value) {
            const checkbox = document.querySelector(`.genre-cb[value="${value}"]`);
            if(checkbox) {
                checkbox.checked = false;
                updateBadges(); // Panggil ulang untuk update tampilan
            }
        }

        // Jalankan saat halaman pertama kali dimuat (untuk mengisi data lama)
        document.addEventListener("DOMContentLoaded", updateBadges);
    </script>
</body>
</html>