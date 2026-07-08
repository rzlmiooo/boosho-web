<?php
$file = 'resources/views/dashboard.blade.php';
$content = file_get_contents($file);

$old = <<<HTML
                            <div>
                                <div class="flex justify-between items-center mb-4">
                                    <span class="font-bold text-blue-600 text-sm">Rp {{ number_format(\$book->price, 0, ',', '.') }}</span>
                                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ \$book->stock > 5 ? 'bg-green-100 text-green-700' : (\$book->stock > 0 ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                                        {{ \$book->stock > 0 ? 'Stok: ' . \$book->stock : 'Habis' }}
                                    </span>
                                </div>
                                <div class="pt-3 border-t border-gray-100 flex justify-end">
                                    <form action="/books/{{ \$book->id }}" method="POST" id="delete-form-{{ \$book->id }}">
                                        @csrf @method('DELETE')
                                        <button type="button" onclick="konfirmasiHapus({{ \$book->id }})"
                                            class="text-xs text-red-500 hover:text-red-700 bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded-lg transition font-semibold">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </div>
HTML;

$new = <<<HTML
                            <div>
                                <div class="flex justify-between items-center mb-5">
                                    <span class="font-bold text-blue-600 text-sm">Rp {{ number_format(\$book->price, 0, ',', '.') }}</span>
                                    <span class="text-[10px] font-bold px-2.5 py-1 rounded-full {{ \$book->stock > 5 ? 'bg-green-100 text-green-700' : (\$book->stock > 0 ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                                        {{ \$book->stock > 0 ? 'Stok: ' . \$book->stock : 'Habis' }}
                                    </span>
                                </div>
                                <div class="pt-4 border-t border-gray-100 flex flex-col gap-2.5">
                                    <!-- Edit Button -->
                                    <button type="button" onclick="Swal.fire('Fitur Edit', 'Form Edit Buku sedang dalam tahap pengembangan.', 'info')"
                                        class="w-full py-2 bg-yellow-400 hover:bg-yellow-500 text-yellow-900 font-bold text-sm rounded-lg transition active:scale-95 shadow-sm text-center">
                                        Edit Buku
                                    </button>

                                    <!-- Delete Button -->
                                    <form action="/books/{{ \$book->id }}" method="POST" id="delete-form-{{ \$book->id }}" class="w-full">
                                        @csrf @method('DELETE')
                                        <button type="button" onclick="konfirmasiHapus({{ \$book->id }})"
                                            class="w-full py-2 bg-red-500 hover:bg-red-600 text-white font-bold text-sm rounded-lg transition active:scale-95 shadow-sm text-center">
                                            Hapus Buku
                                        </button>
                                    </form>
                                </div>
                            </div>
HTML;

$content = str_replace($old, $new, $content);
file_put_contents($file, $content);
echo "Replaced content successfully.\n";
?>
