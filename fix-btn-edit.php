<?php
$file = 'resources/views/dashboard.blade.php';
$content = file_get_contents($file);

$old = <<<HTML
                                <div class="pt-4 border-t border-gray-100 flex flex-col gap-2.5">
                                    <!-- Edit Button -->
                                    <button type="button" onclick="Swal.fire('Fitur Edit', 'Form Edit Buku sedang dalam tahap pengembangan.', 'info')"
                                        class="w-full py-2 bg-yellow-400 hover:bg-yellow-500 text-yellow-900 font-bold text-sm rounded-lg transition active:scale-95 shadow-sm text-center">
                                        Edit Buku
                                    </button>

                                    <!-- Delete Button -->
HTML;

$new = <<<HTML
                                <div class="pt-4 border-t border-gray-100 flex flex-col gap-2.5">
                                    <!-- Edit Button -->
                                    <a href="{{ route('book.edit', \$book->id) }}"
                                        class="block w-full py-2 bg-yellow-400 hover:bg-yellow-500 text-yellow-900 font-bold text-sm rounded-lg transition active:scale-95 shadow-sm text-center">
                                        Edit Buku
                                    </a>

                                    <!-- Delete Button -->
HTML;

$content = str_replace($old, $new, $content);
file_put_contents($file, $content);
?>
