<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Buku - BooSho</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen p-6">

    <div class="max-w-5xl mx-auto">
        
        <a href="/katalog" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-blue-600 transition-all mb-6 group">
            <svg class="w-4 h-4 mr-2 text-gray-400 group-hover:text-blue-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali ke Katalog
        </a>

        <div class="bg-white rounded-2xl shadow-lg overflow-hidden flex flex-col md:flex-row">
            
            <div class="md:w-1/3 bg-gray-200 flex flex-col items-center justify-center p-12 border-b md:border-b-0 md:border-r border-gray-100">
                <svg class="w-32 h-32 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                <span class="text-sm text-gray-400 font-medium">Cover Buku</span>
            </div>

            <div class="md:w-2/3 p-8 flex flex-col">
                
                <div class="mb-4 flex flex-wrap gap-2">
                    @if(!empty($book->genres))
                        @foreach($book->genres as $genre)
                            <span class="bg-indigo-100 text-indigo-700 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wide border border-indigo-200">
                                {{ $genre }}
                            </span>
                        @endforeach
                    @else
                        <span class="bg-gray-100 text-gray-500 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wide border border-gray-200">
                            Tanpa Genre
                        </span>
                    @endif
                </div>

                <h1 class="text-3xl font-bold text-gray-900 leading-tight">{{ $book->title }}</h1>
                <p class="text-lg text-gray-500 mt-2 font-medium">Karya: <span class="text-blue-600">{{ $book->author }}</span></p>
                
                <div class="flex items-center gap-4 mt-6 p-4 bg-gray-50 rounded-xl border border-gray-100">
                    <div class="flex-1">
                        <p class="text-xs text-gray-500 font-semibold uppercase tracking-wider mb-1">Harga</p>
                        <p class="text-2xl font-black text-blue-600">Rp {{ number_format($book->price, 0, ',', '.') }}</p>
                    </div>
                    <div class="w-px h-12 bg-gray-200"></div>
                    <div class="flex-1">
                        <p class="text-xs text-gray-500 font-semibold uppercase tracking-wider mb-1">Ketersediaan</p>
                        <p class="text-lg font-bold text-green-600">{{ $book->stock }} Stok Tersisa</p>
                    </div>
                </div>

                <div class="mt-8 mb-8 flex-grow">
                    <h3 class="text-lg font-bold text-gray-800 mb-3 border-b border-gray-200 pb-2">Sinopsis Buku</h3>
                    <p class="text-gray-600 leading-relaxed text-justify">
                        {{ $book->description ?? 'Belum ada sinopsis atau deksripsi yang ditambahkan untuk buku ini.' }}
                    </p>
                </div>

                <div class="mt-auto border-t border-gray-100 pt-6">
                    <a href="/books/{{ $book->id }}/reviews" class="w-full md:w-auto inline-flex justify-center items-center bg-gray-900 hover:bg-gray-800 text-white font-semibold py-3 px-6 rounded-lg transition-colors shadow-md">
                        <svg class="w-5 h-5 mr-2 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        Lihat Ulasan Pembaca
                    </a>
                </div>

            </div>
        </div>
    </div>

</body>
</html>