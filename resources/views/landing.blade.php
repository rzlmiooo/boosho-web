<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BooSho - Toko Buku Digital</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-50">

    <!-- Navbar -->
    <nav class="bg-white shadow">
        <div class="container mx-auto px-6 py-4 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-blue-600">
                BooSho.
            </h1>

            <div class="space-x-4">
                <a href="{{ route('login') }}"
                    class="text-gray-700 hover:text-blue-600">
                    Login
                </a>

                <a href="{{ route('register') }}"
                    class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                    Daftar
                </a>
            </div>
        </div>
    </nav>

    <!-- Hero -->
    <section class="container mx-auto px-6 py-20">
        <div class="grid md:grid-cols-2 gap-10 items-center">

            <div>
                <h1 class="text-5xl font-bold text-gray-800 mb-6">
                    Temukan Buku Favoritmu di BooSho
                </h1>

                <p class="text-gray-600 text-lg mb-8">
                    Platform toko buku digital yang memudahkan pengguna
                    mencari, membeli, dan mengelola koleksi buku secara
                    online dengan cepat dan nyaman.
                </p>

                <div class="space-x-4">
                    <a href="{{ route('login') }}"
                        class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700">
                        Mulai Sekarang
                    </a>

                    <a href="#fitur"
                        class="border border-blue-600 text-blue-600 px-6 py-3 rounded-lg hover:bg-blue-50">
                        Pelajari Lebih Lanjut
                    </a>
                </div>
            </div>

            <div>
                <img src="https://images.unsplash.com/photo-1524995997946-a1c2e315a42f"
                    class="rounded-xl shadow-lg"
                    alt="Book Store">
            </div>

        </div>
    </section>

    <!-- Fitur -->
    <section id="fitur" class="bg-white py-20">
        <div class="container mx-auto px-6">

            <h2 class="text-3xl font-bold text-center mb-12">
                Fitur Utama BooSho
            </h2>

            <div class="grid md:grid-cols-3 gap-8">

                <div class="bg-gray-50 p-6 rounded-xl shadow">
                    <h3 class="font-bold text-xl mb-3">
                        Katalog Buku
                    </h3>

                    <p class="text-gray-600">
                        Menampilkan seluruh koleksi buku yang tersedia.
                    </p>
                </div>

                <div class="bg-gray-50 p-6 rounded-xl shadow">
                    <h3 class="font-bold text-xl mb-3">
                        Keranjang Belanja
                    </h3>

                    <p class="text-gray-600">
                        Tambahkan buku ke keranjang sebelum checkout.
                    </p>
                </div>

                <div class="bg-gray-50 p-6 rounded-xl shadow">
                    <h3 class="font-bold text-xl mb-3">
                        Manajemen Admin
                    </h3>

                    <p class="text-gray-600">
                        Kelola stok dan data buku dengan mudah.
                    </p>
                </div>

            </div>

        </div>
    </section>

    <!-- CTA -->
    <section class="py-20 bg-blue-600 text-white text-center">
        <h2 class="text-4xl font-bold mb-4">
            Mulai Jelajahi Ribuan Buku
        </h2>

        <p class="mb-8">
            Daftar sekarang dan nikmati pengalaman belanja buku yang lebih mudah.
        </p>

        <a href="{{ route('register') }}"
            class="bg-white text-blue-600 px-8 py-3 rounded-lg font-semibold">
            Daftar Gratis
        </a>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white text-center py-6">
        © {{ date('Y') }} BooSho. All Rights Reserved.
    </footer>

</body>
</html>