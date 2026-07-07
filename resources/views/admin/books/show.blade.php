@extends('admin.layout')
@section('title', 'Detail Buku')

@section('content')
<div class="max-w-5xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <a href="{{ route('admin.books.index') }}" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-indigo-600 transition font-medium bg-white px-4 py-2 rounded-xl shadow-sm border border-gray-100">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali ke Daftar
        </a>
        <div class="flex gap-2">
            <a href="{{ route('admin.books.edit', $book->id) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-yellow-50 text-yellow-600 hover:bg-yellow-500 hover:text-white rounded-xl font-semibold text-sm transition shadow-sm border border-yellow-200 hover:border-transparent">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                Edit Buku
            </a>
            <button type="button" onclick="konfirmasiHapus({{ $book->id }})" class="inline-flex items-center gap-2 px-4 py-2 bg-red-50 text-red-600 hover:bg-red-600 hover:text-white rounded-xl font-semibold text-sm transition shadow-sm border border-red-200 hover:border-transparent">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                Hapus
            </button>
            <form action="{{ route('admin.books.destroy', $book->id) }}" method="POST" id="delete-form-{{ $book->id }}" class="hidden">
                @csrf @method('DELETE')
            </form>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="flex flex-col md:flex-row">
            <!-- Cover Side -->
            <div class="w-full md:w-1/3 bg-gray-50/50 p-8 flex flex-col items-center justify-start border-b md:border-b-0 md:border-r border-gray-100">
                <div class="w-full max-w-[240px] aspect-[3/4] bg-white rounded-2xl shadow-md overflow-hidden flex items-center justify-center relative border border-gray-100 mb-6">
                    @if($book->cover)
                        <img src="{{ asset('storage/' . $book->cover) }}" alt="Cover {{ $book->title }}" class="w-full h-full object-cover">
                    @else
                        <div class="flex flex-col items-center justify-center text-gray-300">
                            <svg class="w-16 h-16 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                            <span class="text-xs font-medium">Tanpa Cover</span>
                        </div>
                    @endif
                </div>

                <div class="w-full space-y-3">
                    <div class="flex justify-between items-center py-2 border-b border-gray-200">
                        <span class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Dibuat</span>
                        <span class="text-sm text-gray-800 font-medium">{{ $book->created_at->format('d M Y, H:i') }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-200">
                        <span class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Diperbarui</span>
                        <span class="text-sm text-gray-800 font-medium">{{ $book->updated_at->format('d M Y, H:i') }}</span>
                    </div>
                </div>
            </div>

            <!-- Detail Side -->
            <div class="w-full md:w-2/3 p-8 lg:p-10">
                <div class="mb-6 border-b border-gray-100 pb-6">
                    <h1 class="text-3xl font-bold text-gray-900 leading-tight mb-2">{{ $book->title }}</h1>
                    <p class="text-lg text-indigo-600 font-semibold">{{ $book->author }}</p>
                </div>

                <div class="grid grid-cols-2 gap-y-6 gap-x-8 mb-8">
                    <div class="flex flex-col">
                        <span class="text-xs text-gray-400 font-bold mb-1 uppercase tracking-wider">Kategori</span>
                        <span class="text-base font-semibold text-gray-800 bg-gray-100 px-3 py-1 rounded-md w-fit">
                            {{ $book->category ?? 'Tidak ada kategori' }}
                        </span>
                    </div>

                    <div class="flex flex-col">
                        <span class="text-xs text-gray-400 font-bold mb-1 uppercase tracking-wider">Harga</span>
                        <span class="text-2xl font-bold text-gray-900">Rp {{ number_format($book->price, 0, ',', '.') }}</span>
                    </div>

                    <div class="flex flex-col">
                        <span class="text-xs text-gray-400 font-bold mb-1 uppercase tracking-wider">Status Stok</span>
                        <div class="flex items-center gap-2 mt-1">
                            @if($book->stock > 5)
                                <span class="flex h-3 w-3 relative">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
                                </span>
                                <span class="text-base font-bold text-green-600">{{ $book->stock }} Tersedia</span>
                            @elseif($book->stock > 0)
                                <span class="w-3 h-3 rounded-full bg-yellow-500"></span>
                                <span class="text-base font-bold text-yellow-600">{{ $book->stock }} Tersedia</span>
                            @else
                                <span class="w-3 h-3 rounded-full bg-red-500"></span>
                                <span class="text-base font-bold text-red-600">Habis</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="text-sm font-bold text-gray-800 mb-3 uppercase tracking-wider flex items-center gap-2 border-b border-gray-100 pb-2">
                        <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/></svg>
                        Deskripsi / Sinopsis
                    </h3>
                    <div class="text-sm text-gray-600 leading-relaxed text-justify whitespace-pre-line bg-gray-50 p-5 rounded-xl border border-gray-100">
                        {{ $book->description ?: 'Tidak ada deskripsi tersedia.' }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function konfirmasiHapus(id) {
        Swal.fire({
            title: 'Hapus Buku?',
            text: "Buku akan dihapus permanen dari sistem!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        });
    }
</script>
@endpush