<?php
$files = [
    'resources/views/admin/orders.blade.php',
    'resources/views/dashboard.blade.php',
    'resources/views/detail.blade.php',
    'resources/views/katalog.blade.php',
    'resources/views/keranjang.blade.php'
];

// Profile Button Replacement Script
$newProfile = <<<'HTML'
        <!-- Profile Dropdown -->
        <div class="relative" x-data="{ open: false }">
            @if(Auth::check())
                <button @click="open = !open" @click.outside="open = false" class="flex items-center gap-2 focus:outline-none bg-indigo-50 hover:bg-indigo-100 border border-indigo-100 rounded-full pl-3 pr-1 py-1 transition group">
                    <span class="text-sm font-semibold text-indigo-700">{{ explode(' ', Auth::user()->name)[0] }}</span>
                    @if(Auth::user()->isAdmin())
                        <span class="text-[10px] bg-indigo-200 text-indigo-800 px-1.5 py-0.5 rounded-full font-bold uppercase tracking-wider">Admin</span>
                    @endif
                    <div class="w-8 h-8 rounded-full bg-indigo-200 flex items-center justify-center text-indigo-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </div>
                </button>

                <!-- Dropdown Menu -->
                <div x-show="open" 
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="transform opacity-0 scale-95"
                     x-transition:enter-end="transform opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-75"
                     x-transition:leave-start="transform opacity-100 scale-100"
                     x-transition:leave-end="transform opacity-0 scale-95"
                     class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-gray-100 py-1 z-50"
                     style="display: none;">
                    
                    <a href="#" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 hover:text-indigo-600 transition flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        My Account
                    </a>
                    
                    <hr class="border-gray-100 my-1">
                    
                    <form action="/logout" method="POST" class="w-full m-0" id="logout-form-dropdown">
                        @csrf
                        <button type="button" onclick="konfirmasiLogout()" class="w-full text-left px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                            Logout
                        </button>
                    </form>
                </div>
            @else
                <a href="/login" class="text-sm text-indigo-600 font-semibold hover:text-indigo-800 transition">Login</a>
            @endif
        </div>
HTML;

foreach ($files as $file) {
    if (!file_exists($file)) continue;
    $content = file_get_contents($file);
    
    // Different files might have slightly different wrapping divs or patterns, 
    // but they all have <div class="flex items-center gap-4"> containing the span with "Halo,"
    
    // Let's use regex to replace everything inside the <div class="flex items-center gap-4"> ... </div>
    // Note: this assumes the div is exactly like that.
    $pattern = '/<div class="flex items-center gap-4">.*?<\/form>\s*(?:<\/div>|@else\s*<a href="\{\{ route\(\'login\'\) \}\}"[^>]*>Login<\/a>\s*@endif\s*<\/div>)/is';
    
    // A safer pattern targeted specifically at the span to the form
    if (preg_match('/<div class="flex items-center gap-4">(.*?)<\/nav>/is', $content, $matches)) {
        // We replace everything between gap-4"> and </nav>
        $content = preg_replace(
            '/<div class="flex items-center gap-4">(.*?)<\/nav>/is', 
            '<div class="flex items-center gap-4">' . "\n" . $newProfile . "\n" . '    </div>' . "\n" . '</nav>', 
            $content
        );
        file_put_contents($file, $content);
        echo "Updated $file\n";
    }
}
?>
