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
    
    // Check if Alpine is already there
    if (strpos($content, 'alpinejs') === false) {
        // Insert it right after sweetalert script
        $content = preg_replace('/<script src="https:\/\/cdn.jsdelivr.net\/npm\/sweetalert2@11"><\/script>/i', '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>' . "\n" . '    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>', $content);
        file_put_contents($file, $content);
        echo "Added Alpine to $file\n";
    }
}
?>
