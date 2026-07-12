<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Review Buku - BooSho</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body { font-family: 'Poppins', sans-serif; }
    </style>
</head>
<body class="bg-gray-100 min-h-screen p-6">

    <!-- Kumpulan Tombol Kembali -->
        <div class="flex flex-wrap gap-3 mb-6">
            
            <!-- Tombol 1: Kembali ke Detail Buku (Langkah sebelumnya) -->
            <a href="/books/{{ $book->id }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-blue-600 hover:border-blue-300 transition-all group">
                <svg class="w-4 h-4 mr-2 text-gray-400 group-hover:text-blue-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali ke Detail
            </a>

            <!-- Tombol 2: Kembali ke Katalog (Beranda) -->
            <a href="/katalog" class="inline-flex items-center px-4 py-2 bg-gray-50 border border-gray-200 rounded-lg shadow-sm text-sm font-medium text-gray-600 hover:bg-gray-100 hover:text-gray-900 transition-all group">
                <!-- Icon Grid / Katalog -->
                <svg class="w-4 h-4 mr-2 text-gray-500 group-hover:text-gray-700 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                Katalog Buku
            </a>

        </div>

        @if(Auth::user()->role !== 'admin')
            <div class="bg-white p-6 rounded-xl shadow-md mb-8 border-t-4 border-blue-600">
                <h2 class="text-lg font-bold text-gray-800 mb-4">Tulis Ulasan Anda</h2>
                <form action="/books/{{ $book->id }}/reviews" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Beri Rating (1-5)</label>
                        <input type="number" name="rating" min="1" max="5" value="5" required class="w-24 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Komentar</label>
                        <textarea name="comment" rows="3" required placeholder="Bagaimana pendapat Anda tentang buku ini?" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                    </div>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded transition">Kirim Ulasan</button>
                </form>
            </div>
        @else
            <div class="bg-blue-50 p-4 rounded-xl shadow-sm mb-8 border border-blue-100 flex items-start">
                <svg class="w-6 h-6 text-blue-500 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <div>
                    <h3 class="font-bold text-blue-800">Mode Moderasi Admin</h3>
                    <p class="text-sm text-blue-700 mt-1">Sebagai Admin, Anda tidak dapat menulis ulasan. Anda hanya memiliki akses untuk melihat, mengurutkan, dan menghapus ulasan pembaca.</p>
                </div>
            </div>
        @endif

        <div class="bg-white p-6 rounded-xl shadow-md">
            
            <div class="flex flex-col sm:flex-row justify-between items-center mb-6 border-b border-gray-100 pb-4">
                <h2 class="text-xl font-bold text-gray-800 mb-3 sm:mb-0">Ulasan Pembaca ({{ $reviews->count() }})</h2>
                
                <form action="/books/{{ $book->id }}/reviews" method="GET" class="flex items-center bg-gray-50 px-3 py-1.5 rounded-lg border border-gray-200">
                    <label for="sort" class="text-sm font-semibold text-gray-600 mr-2">Urutkan:</label>
                    <select name="sort" id="sort" onchange="this.form.submit()" class="bg-transparent text-sm text-gray-800 focus:outline-none cursor-pointer">
                        <option value="terbaru" {{ $sort == 'terbaru' ? 'selected' : '' }}>Waktu Terbaru</option>
                        <option value="terlama" {{ $sort == 'terlama' ? 'selected' : '' }}>Waktu Terlama</option>
                    </select>
                </form>
            </div>
            
            @if($reviews->isEmpty())
                <p class="text-gray-500 text-center py-8">Belum ada ulasan untuk buku ini.</p>
            @else
                <div class="space-y-6">
                    @foreach($reviews as $review)
                        <div class="border-b border-gray-100 pb-6 last:border-0 last:pb-0">
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    <h4 class="font-bold text-gray-800">{{ $review->user->name }}</h4>
                                    <p class="text-xs text-gray-400">{{ $review->created_at->diffForHumans() }}</p>
                                </div>
                                
                                <div class="flex flex-col items-end space-y-2">
                                    <span class="bg-yellow-100 text-yellow-700 text-xs font-bold px-2 py-1 rounded border border-yellow-300">
                                        ⭐ {{ $review->rating }}/5
                                    </span>
                                    
                                    @if(Auth::user()->role === 'admin')
                                        <form action="/reviews/{{ $review->id }}" method="POST" id="form-hapus-review-{{ $review->id }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" onclick="konfirmasiHapusReview({{ $review->id }})" class="text-xs font-semibold text-red-500 hover:text-red-700 hover:underline">
                                                Hapus Ulasan
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                            <p class="text-gray-700 text-sm mt-2">{{ $review->comment }}</p>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

    </div>

    <script>
        function konfirmasiHapusReview(idReview) {
            Swal.fire({
                title: 'Hapus ulasan ini?',
                text: "Ulasan yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('form-hapus-review-' + idReview).submit();
                }
            })
        }
    </script>
</body>
</html>