<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Review;
use App\Models\Book;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Pastikan ada minimal 1 akun User (Bukan Admin) untuk jadi penulis ulasan
        // Sistem akan mencari user pertama. Jika database kosong, dia akan membuat 1 akun otomatis.
        $user = User::where('role', '!=', 'admin')->first();
        
        if (!$user) {
            $user = User::create([
                'name' => 'Pembaca Setia BooSho',
                'email' => 'pembaca@boosho.com',
                'password' => Hash::make('password123'),
                'role' => 'user' // Pastikan role-nya 'user' agar diizinkan menulis review
            ]);
        }

        // 2. Ambil semua data buku yang baru saja kita seed sebelumnya
        $books = Book::all();

        if ($books->isEmpty()) {
            return; // Hentikan jika tidak ada buku sama sekali
        }

        // 3. Kumpulan template komentar acak agar terlihat natural
        $komentarBagus = [
            'Buku yang sangat luar biasa! Bahasanya mudah dipahami dan sangat membantu saya.',
            'Wah, akhirnya nemu buku yang bahas materi ini dengan sangat detail. Recommended!',
            'Kualitas isi sangat baik, sesuai dengan harganya. Sangat memuaskan.',
            'Bintang lima! Pengirimannya cepat (ceritanya) dan bukunya original.'
        ];

        $komentarKritik = [
            'Isinya lumayan bagus, tapi ada beberapa typo di pertengahan bab.',
            'Cukup oke untuk pemula, tapi kurang mendalam untuk tingkat lanjut.',
            'Bagus, tapi ekspektasi saya sedikit lebih tinggi setelah baca sinopsisnya.'
        ];

        // 4. Looping: Berikan 2 ulasan untuk setiap buku secara otomatis
        foreach ($books as $book) {
            
            // Ulasan Pertama (Pasti Bagus)
            Review::create([
                'user_id' => $user->id,
                'book_id' => $book->id,
                'rating' => rand(4, 5), // Rating acak antara 4 atau 5
                'comment' => $komentarBagus[array_rand($komentarBagus)]
            ]);

            // Ulasan Kedua (Bisa Bagus, Bisa Kritik ringan)
            Review::create([
                'user_id' => $user->id,
                'book_id' => $book->id,
                'rating' => rand(3, 4), // Rating acak antara 3 atau 4
                'comment' => $komentarKritik[array_rand($komentarKritik)]
            ]);
        }
    }
}