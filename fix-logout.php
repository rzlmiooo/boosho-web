<?php
$files = [
    'resources/views/admin/orders.blade.php',
    'resources/views/dashboard.blade.php',
    'resources/views/detail.blade.php',
    'resources/views/katalog.blade.php',
    'resources/views/keranjang.blade.php',
    'resources/views/account.blade.php'
];

foreach ($files as $file) {
    if (!file_exists($file)) continue;
    $content = file_get_contents($file);
    
    // Change id='logout-form-dropdown' to id='logout-form' so the JS can find it
    $content = str_replace('id="logout-form-dropdown"', 'id="logout-form"', $content);
    
    file_put_contents($file, $content);
    echo "Updated logout form ID in $file\n";
}
?>
