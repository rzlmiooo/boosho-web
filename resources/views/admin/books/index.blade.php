@extends('admin.layout')
@section('title', 'Manajemen Buku')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
    <div class="flex justify-between items-center mb-6 pb-5 border-b border-gray-100">
        <div>
            <h2 class="text-lg font-bold text-gray-800">📚 Daftar Katalog Buku</h2>
            <p class="text-xs text-gray-400 mt-0.5">{{ $books->total() }} buku terdaftar di sistem</p>
        </div>
        <a href="{{ route('admin.books.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl font-semibold text-sm transition shadow-sm active:scale-95 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
            Tambah Buku
        </a>
    </div>

    @if($books->isEmpty())
        <div class="text-center py-16 text-gray-400">
            <div class="text-4xl mb-3">📭</div>
            <p class="font-medium">Belum ada data buku. Silakan tambahkan buku baru!</p>
        </div>
    @else
        <div class="overflow-x-auto mb-6">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-gray-100 text-xs uppercase tracking-wider text-gray-500 font-semibold bg-gray-50/50">
                        <th class="px-6 py-4 rounded-tl-xl">Buku</th>
                        <th class="px-6 py-4">Kategori</th>
                        <th class="px-6 py-4">Harga</th>
                        <th class="px-6 py-4">Stok</th>
                        <th class="px-6 py-4 text-center rounded-tr-xl">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($books as $book)
                    <tr class="hover:bg-gray-50/50 transition">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-16 bg-gray-100 rounded-md overflow-hidden flex-shrink-0 border border-gray-200 flex items-center justify-center">
                                    @if($book->cover)
                                        <img src="{{ asset('storage/' . $book->cover) }}" class="w-full h-full object-cover">
                                    @else
                                        <span class="text-[10px] text-gray-400 font-medium">No Cover</span>
                                    @endif
                                </div>
                                <div>
                                    <p class="font-bold text-gray-800 text-sm mb-0.5 line-clamp-1">{{ $book->title }}</p>
                                    <p class="text-xs text-gray-500">{{ $book->author }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($book->category)
                                <span class="inline-block px-2.5 py-1 bg-indigo-50 text-indigo-700 text-xs font-bold rounded-md">{{ $book->category }}</span>
                            @else
                                <span class="text-gray-400 text-xs">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-700 text-sm">
                            Rp {{ number_format($book->price, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($book->stock > 5)
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-green-50 text-green-700 border border-green-100">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> {{ $book->stock }}
                                </span>
                            @elseif($book->stock > 0)
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-yellow-50 text-yellow-700 border border-yellow-100">
                                    <span class="w-1.5 h-1.5 rounded-full bg-yellow-500"></span> {{ $book->stock }}
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-red-50 text-red-700 border border-red-100">
                                    <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> Habis
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <div class="flex justify-center gap-2">
                                <a href="{{ route('admin.books.show', $book->id) }}" class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white flex items-center justify-center transition" title="Detail">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </a>
                                <a href="{{ route('admin.books.edit', $book->id) }}" class="w-8 h-8 rounded-lg bg-yellow-50 text-yellow-600 hover:bg-yellow-500 hover:text-white flex items-center justify-center transition" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                </a>
                                <button type="button" onclick="konfirmasiHapus({{ $book->id }})" class="w-8 h-8 rounded-lg bg-red-50 text-red-600 hover:bg-red-600 hover:text-white flex items-center justify-center transition" title="Hapus">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                                <form action="{{ route('admin.books.destroy', $book->id) }}" method="POST" id="delete-form-{{ $book->id }}" class="hidden">
                                    @csrf @method('DELETE')
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination Component (If Using Laravel Paginator) -->
        <div class="mt-4">
            {{ $books->links() }}
        </div>
    @endif
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