@extends('admin.layout')
@section('title', 'Dashboard')

@section('content')
<!-- Statistik Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

    <!-- Total Buku -->
    <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm hover:shadow-md transition group">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center group-hover:scale-110 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
            </div>
            <span class="text-sm font-medium text-gray-400">Judul Tersimpan</span>
        </div>
        <h3 class="text-3xl font-bold text-gray-800">{{ number_format($totalBooks) }}</h3>
        <p class="text-sm text-gray-500 mt-1 font-medium">Total Buku Terdaftar</p>
    </div>

    <!-- Total Stok -->
    <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm hover:shadow-md transition group">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center group-hover:scale-110 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg>
            </div>
            <span class="text-sm font-medium text-gray-400">Fisik Tersedia</span>
        </div>
        <h3 class="text-3xl font-bold text-gray-800">{{ number_format($totalStock) }}</h3>
        <p class="text-sm text-gray-500 mt-1 font-medium">Total Stok Seluruh Buku</p>
    </div>

    <!-- Total Nilai Stok -->
    <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm hover:shadow-md transition group">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center group-hover:scale-110 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <span class="text-sm font-medium text-gray-400">Estimasi Aset</span>
        </div>
        <h3 class="text-3xl font-bold text-gray-800">Rp {{ number_format($totalValue, 0, ',', '.') }}</h3>
        <p class="text-sm text-gray-500 mt-1 font-medium">Total Nilai (Harga &times; Stok)</p>
    </div>
</div>

<!-- Produk Terbaru -->
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
        <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
            <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            5 Produk Terbaru
        </h3>
        <a href="{{ route('admin.books.index') }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-800 transition">Lihat Semua &rarr;</a>
    </div>

    @if($latestBooks->isEmpty())
        <div class="text-center py-16">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 text-gray-400 mb-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
            </div>
            <h4 class="text-gray-900 font-bold mb-1">Belum Ada Buku</h4>
            <p class="text-gray-500 text-sm mb-4">Katalog buku masih kosong. Mulai tambahkan buku pertama Anda.</p>
            <a href="{{ route('admin.books.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white rounded-lg font-medium text-sm hover:bg-indigo-700 transition">
                + Tambah Buku
            </a>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-gray-100 text-xs uppercase tracking-wider text-gray-500 font-semibold bg-gray-50/50">
                        <th class="px-6 py-4">Buku</th>
                        <th class="px-6 py-4">Harga</th>
                        <th class="px-6 py-4">Stok</th>
                        <th class="px-6 py-4">Total Nilai</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($latestBooks as $book)
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
                                    <div class="flex items-center gap-2">
                                        <p class="text-xs text-gray-500">{{ $book->author }}</p>
                                        @if($book->category)
                                            <span class="inline-block px-2 py-0.5 bg-indigo-50 text-indigo-600 text-[10px] font-bold rounded-full">{{ $book->category }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
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
                        <td class="px-6 py-4 whitespace-nowrap font-bold text-indigo-600 text-sm">
                            Rp {{ number_format($book->price * $book->stock, 0, ',', '.') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
