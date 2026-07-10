<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') - BooSho</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f8fafc; }
        /* Hilangkan panah spinner pada input number */
        input[type="number"]::-webkit-inner-spin-button,
        input[type="number"]::-webkit-outer-spin-button { -webkit-appearance: none; margin: 0; }
    </style>
</head>
<body class="text-gray-800 flex h-screen overflow-hidden" x-data="{ sidebarOpen: true }">

    <!-- Sidebar -->
    <aside 
        class="bg-white border-r border-indigo-100 flex flex-col justify-between flex-shrink-0 h-full transition-all duration-300 ease-in-out z-20"
        :class="sidebarOpen ? 'w-64 translate-x-0' : 'w-0 -translate-x-full lg:w-0 overflow-hidden border-r-0'"
    >
        <div>
            <!-- Logo -->
            <div class="h-16 flex items-center justify-between px-6 border-b border-indigo-100">
                <h1 class="text-2xl font-bold text-indigo-600 tracking-tight">BooSho<span class="text-indigo-400">.</span></h1>
                <!-- Mobile close button -->
                <button @click="sidebarOpen = false" class="lg:hidden text-gray-400 hover:text-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <!-- Navigation Links -->
            <nav class="p-4 space-y-1">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ Route::is('dashboard') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-gray-500 hover:bg-gray-50 hover:text-indigo-600 font-medium' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    Dashboard
                </a>
                
                <a href="{{ route('admin.books.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ Route::is('admin.books.*') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-gray-500 hover:bg-gray-50 hover:text-indigo-600 font-medium' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    Manajemen Buku
                </a>

                <a href="{{ route('admin.orders') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ Route::is('admin.orders') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-gray-500 hover:bg-gray-50 hover:text-indigo-600 font-medium' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                    Daftar Pesanan
                </a>
            </nav>
        </div>

        <!-- Logout Form in Sidebar -->
        <div class="p-4 border-t border-indigo-100">
            <form action="{{ route('logout') }}" method="POST" id="logout-form">
                @csrf
                <button type="button" onclick="konfirmasiLogout()" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-red-600 hover:bg-red-50 transition font-medium">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    Logout
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content Area -->
    <main class="flex-1 flex flex-col h-full overflow-hidden relative">
        
        <!-- Navbar Minimalis -->
        <header class="h-16 bg-white/90 backdrop-blur shadow-sm px-8 flex justify-between items-center border-b border-indigo-100 flex-shrink-0 z-10">
            <div class="flex items-center gap-4">
                <!-- Hamburger menu button -->
                <button @click="sidebarOpen = !sidebarOpen" class="p-2 rounded-lg text-gray-500 hover:bg-indigo-50 hover:text-indigo-600 transition focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                <h2 class="text-lg font-bold text-gray-800">@yield('title')</h2>
            </div>
            
            <div class="flex items-center gap-3">
                <div class="text-right hidden sm:block">
                    <p class="text-sm font-bold text-gray-800 leading-tight">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-indigo-600 font-medium">Administrator</p>
                </div>
                <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 border border-indigo-200">
                    <span class="font-bold">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <div class="flex-1 overflow-auto p-8">
            @if(session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-sm font-medium shadow-sm flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm font-medium shadow-sm flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <!-- Global Scripts -->
    <script>
        function konfirmasiLogout() {
            Swal.fire({
                title: 'Konfirmasi Logout',
                text: "Anda yakin ingin keluar dari sesi ini?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Keluar',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('logout-form').submit();
                }
            });
        }
    </script>
    @stack('scripts')
</body>
</html>
