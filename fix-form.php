<?php
$file = 'resources/views/dashboard.blade.php';
$content = file_get_contents($file);

$old = <<<HTML
                            <input type="text" name="author" required placeholder="Nama penulis..."
                                class="w-full border border-gray-300 px-4 py-2.5 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div class="grid grid-cols-2 gap-4">
HTML;

$new = <<<HTML
                            <input type="text" name="author" required placeholder="Nama penulis..."
                                class="w-full border border-gray-300 px-4 py-2.5 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Deskripsi Buku</label>
                            <textarea name="description" required rows="3" placeholder="Masukkan deskripsi singkat buku..."
                                class="w-full border border-gray-300 px-4 py-2.5 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
HTML;

$content = str_replace($old, $new, $content);
file_put_contents($file, $content);
?>
