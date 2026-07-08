<?php
$files = [
    'resources/views/admin/orders.blade.php',
    'resources/views/dashboard.blade.php',
    'resources/views/detail.blade.php',
    'resources/views/katalog.blade.php',
    'resources/views/keranjang.blade.php'
];

foreach ($files as $file) {
    if (!file_exists($file)) continue;
    $content = file_get_contents($file);
    
    // Replace <a href="#"> with <a href="/account">
    $content = str_replace('<a href="#" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 hover:text-indigo-600 transition flex items-center gap-2">', '<a href="{{ route(\'account\') }}" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 hover:text-indigo-600 transition flex items-center gap-2">', $content);
    
    file_put_contents($file, $content);
    echo "Updated dropdown in $file\n";
}
?>
