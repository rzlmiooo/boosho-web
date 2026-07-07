@extends('admin.layout')
@section('title', 'Tambah Buku Baru')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-gray-800 tracking-tight flex items-center gap-2">
            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            Tambah Buku Baru
        </h2>
        <a href="{{ route('admin.books.index') }}" class="text-sm text-gray-500 hover:text-indigo-600 font-semibold transition px-4 py-2 bg-white rounded-xl border border-gray-200 shadow-sm">Kembali ke Daftar</a>
    </div>

    @if($errors->any())
        <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-5 py-4 rounded-xl text-sm font-medium shadow-sm">
            <ul class="list-disc pl-5 space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <form action="{{ route('admin.books.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="p-6 md:p-8 space-y-6">
                <!-- Cover Preview Area -->
                <div class="flex flex-col sm:flex-row gap-6 items-start border-b border-gray-100 pb-6">
                    <div class="w-32 h-44 rounded-lg bg-gray-50 flex items-center justify-center overflow-hidden flex-shrink-0 border border-gray-200 shadow-inner relative group cursor-pointer" onclick="document.getElementById('coverInput').click()">
                        <img id="coverPreview" src="#" alt="Preview" class="w-full h-full object-cover hidden">
                        <div id="coverPlaceholder" class="flex flex-col items-center justify-center text-gray-400 group-hover:text-indigo-500 transition">
                            <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <span class="text-xs font-semibold">Upload Cover</span>
                        </div>
                    </div>
                    <div class="flex-grow pt-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Cover Buku (Opsional)</label>
                        <input type="file" name="cover" id="coverInput" accept="image/jpeg,image/png,image/jpg" onchange="previewImage(this)"
                            class="w-full border border-gray-300 px-4 py-2.5 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition cursor-pointer">
                        <p class="text-[11px] text-gray-500 mt-2 font-medium">Format: JPG, JPEG, PNG. Maksimal 2MB. Rasio ideal 3:4.</p>
                    </div>
                </div>

                <!-- Input Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Judul Buku <span class="text-red-500">*</span></label>
                        <input type="text" name="title" value="{{ old('title') }}" required placeholder="Masukkan judul buku..."
                            class="w-full border border-gray-300 px-4 py-3 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition shadow-sm bg-gray-50 focus:bg-white">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Penulis <span class="text-red-500">*</span></label>
                        <input type="text" name="author" value="{{ old('author') }}" required placeholder="Nama penulis..."
                            class="w-full border border-gray-300 px-4 py-3 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition shadow-sm bg-gray-50 focus:bg-white">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Kategori</label>
                        <input type="text" name="category" value="{{ old('category') }}" placeholder="Fiksi, Edukasi, Bisnis..."
                            class="w-full border border-gray-300 px-4 py-3 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition shadow-sm bg-gray-50 focus:bg-white">
                    </div>

                    <div class="grid grid-cols-2 gap-4 md:col-span-2">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Harga (Rp) <span class="text-red-500">*</span></label>
                            <input type="number" name="price" value="{{ old('price') }}" required min="0" placeholder="75000"
                                class="w-full border border-gray-300 px-4 py-3 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition shadow-sm bg-gray-50 focus:bg-white">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Stok <span class="text-red-500">*</span></label>
                            <input type="number" name="stock" value="{{ old('stock', 1) }}" required min="0" placeholder="10"
                                class="w-full border border-gray-300 px-4 py-3 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition shadow-sm bg-gray-50 focus:bg-white">
                        </div>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Deskripsi / Sinopsis Buku <span class="text-red-500">*</span></label>
                        <textarea name="description" required rows="5" placeholder="Tuliskan deskripsi atau sinopsis buku secara lengkap..."
                            class="w-full border border-gray-300 px-4 py-3 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition shadow-sm bg-gray-50 focus:bg-white">{{ old('description') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-end gap-3 rounded-b-2xl">
                <a href="{{ route('admin.books.index') }}" class="px-6 py-2.5 text-gray-600 bg-white border border-gray-300 hover:bg-gray-50 hover:text-gray-800 font-semibold rounded-xl text-sm transition shadow-sm">
                    Batal
                </a>
                <button type="submit" class="px-8 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl text-sm transition shadow-md active:scale-95 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Simpan Buku
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function previewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('coverPreview').src = e.target.result;
                document.getElementById('coverPreview').classList.remove('hidden');
                document.getElementById('coverPlaceholder').classList.add('hidden');
            }
            reader.readAsDataURL(input.files[0]);
        } else {
            document.getElementById('coverPreview').src = '#';
            document.getElementById('coverPreview').classList.add('hidden');
            document.getElementById('coverPlaceholder').classList.remove('hidden');
        }
    }
</script>
@endpush