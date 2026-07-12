<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 Halaman Tidak Ditemukan - BooSho</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Poppins', sans-serif; }
    </style>
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen flex items-center justify-center p-6 relative overflow-hidden">

    <!-- Background Decorative Image Overlay -->
    <div class="absolute inset-0 bg-cover bg-center opacity-20 pointer-events-none" style="background-image: url('https://images.unsplash.com/photo-1524995997946-a1c2e315a42f'); filter: grayscale(50%) blur(3px);"></div>
    <div class="absolute inset-0 bg-gradient-to-tr from-gray-955 via-gray-900/90 to-blue-900/30 pointer-events-none"></div>

    <div class="relative z-10 max-w-lg w-full text-center bg-white/10 backdrop-blur-md p-8 md:p-12 rounded-3xl border border-white/20 shadow-2xl">
        <!-- Icon/Visual -->
        <div class="mb-8 relative inline-block">
            <span class="text-8xl md:text-9xl font-extrabold text-blue-500/80 tracking-widest drop-shadow-[0_5px_15px_rgba(59,130,246,0.3)]">404</span>
            <div class="absolute -bottom-2 left-1/2 -translate-x-1/2 bg-yellow-400 text-gray-900 text-[10px] md:text-xs font-bold uppercase tracking-widest px-3 py-1 rounded-full shadow-md whitespace-nowrap">
                Halaman Terbawa Angin
            </div>
        </div>

        <h1 class="text-2xl md:text-3xl font-bold text-white mb-4">Oops! Lembaran Hilang</h1>
        <p class="text-gray-300 text-sm md:text-base mb-8 leading-relaxed">
            Halaman yang Anda cari tidak ditemukan. Mungkin halaman tersebut telah dipindahkan, dihapus, atau belum pernah ditulis dalam katalog kami.
        </p>

        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ url('/') }}" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl text-sm transition shadow-lg shadow-blue-600/30 hover:scale-105 active:scale-95">
                Kembali ke Beranda
            </a>
            <a href="{{ route('katalog') }}" class="px-6 py-3 bg-white/10 hover:bg-white/20 text-white font-semibold rounded-xl text-sm transition border border-white/20 hover:scale-105 active:scale-95">
                Cari Buku Lain
            </a>
        </div>
    </div>

</body>
</html>
