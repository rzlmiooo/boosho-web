<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Book;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat Akun Admin
        User::create([
            'name' => 'Administrator BooSho',
            'email' => 'admin@boosho.com',
            'password' => Hash::make('123'),
            'role' => 'admin',
        ]);

        // 2. Buat Akun User
        User::create([
            'name' => 'Pengguna Setia',
            'email' => 'user@boosho.com',
            'password' => Hash::make('123'),
            'role' => 'user',
        ]);

        // 3. Buat 20 Buku Baru dengan Beragam Kategori, Genre, & Diskon
        $books = [
            [
                'title' => 'Struktur Data & Algoritma',
                'author' => 'Budi Santoso',
                'category' => 'Teknologi',
                'cover' => 'covers/struktur_data.jpg',
                'description' => 'Buku ini membahas konsep dasar struktur data dan algoritma secara komprehensif, cocok untuk pemula.',
                'price' => 85000,
                'stock' => 20,
                'genres' => ['Teknologi', 'Edukasi'],
                'discount_percent' => 15,
                'discount_start' => now()->subDay(),
                'discount_end' => now()->addDays(5),
            ],
            [
                'title' => 'Mastering Laravel 11',
                'author' => 'Eza Developer',
                'category' => 'Teknologi',
                'cover' => 'covers/mastering_laravel.jpg',
                'description' => 'Panduan lengkap dan praktis untuk menguasai framework Laravel versi terbaru.',
                'price' => 120000,
                'stock' => 15,
                'genres' => ['Teknologi', 'Edukasi'],
                'discount_percent' => 10,
                'discount_start' => now()->subDays(2),
                'discount_end' => now()->addDays(10),
            ],
            [
                'title' => 'Laskar Pelangi',
                'author' => 'Andrea Hirata',
                'category' => 'Fiksi',
                'cover' => 'covers/laskar_pelangi.jpg',
                'description' => 'Sebuah novel luar biasa tentang kisah perjuangan anak-anak Belitong dalam menuntut ilmu.',
                'price' => 79000,
                'stock' => 25,
                'genres' => ['Novel', 'Fiksi'],
                'discount_percent' => 0,
                'discount_start' => null,
                'discount_end' => null,
            ],
            [
                'title' => 'Bumi',
                'author' => 'Tere Liye',
                'category' => 'Fiksi',
                'cover' => 'covers/bumi.jpg',
                'description' => 'Petualangan seru tiga sekawan di dunia paralel yang penuh dengan misteri dan kekuatan menakjubkan.',
                'price' => 95000,
                'stock' => 18,
                'genres' => ['Novel', 'Fantasi', 'Misteri'],
                'discount_percent' => 20,
                'discount_start' => now()->subDay(),
                'discount_end' => now()->addDays(3),
            ],
            [
                'title' => 'Sejarah Dunia yang Disembunyikan',
                'author' => 'Jonathan Black',
                'category' => 'Sejarah',
                'cover' => 'covers/sejarah_dunia_disembunyikan.jpg',
                'description' => 'Mengungkap mitos-mitos kuno dan sejarah dunia dari perspektif aliran mistis serta persaudaraan rahasia.',
                'price' => 135000,
                'stock' => 12,
                'genres' => ['Sejarah', 'Non-Fiksi'],
                'discount_percent' => 0,
                'discount_start' => null,
                'discount_end' => null,
            ],
            [
                'title' => 'Filosofi Teras',
                'author' => 'Henry Manampiring',
                'category' => 'Edukasi',
                'cover' => 'covers/filosofi_teras.jpg',
                'description' => 'Filsafat Yunani-Romawi Kuno Stoikisme untuk membantu mengatasi emosi negatif dan menciptakan kedamaian mental.',
                'price' => 88000,
                'stock' => 30,
                'genres' => ['Edukasi', 'Non-Fiksi'],
                'discount_percent' => 12,
                'discount_start' => now()->subDay(),
                'discount_end' => now()->addDays(8),
            ],
            [
                'title' => 'Dilan: Dia adalah Dilanku Tahun 1990',
                'author' => 'Pidi Baiq',
                'category' => 'Fiksi',
                'cover' => 'covers/dilan_1990.jpg',
                'description' => 'Kisah cinta SMA antara Dilan yang unik dan Milea yang membuat hati pembaca berbunga-bunga.',
                'price' => 69000,
                'stock' => 22,
                'genres' => ['Novel', 'Romantis'],
                'discount_percent' => 0,
                'discount_start' => null,
                'discount_end' => null,
            ],
            [
                'title' => 'Sains dalam Al-Quran',
                'author' => 'Dr. Zakir Naik',
                'category' => 'Sains',
                'cover' => 'covers/sains_alquran.jpg',
                'description' => 'Pembahasan mendalam tentang kecocokan antara ayat-ayat suci Al-Quran dengan penemuan sains modern.',
                'price' => 75000,
                'stock' => 14,
                'genres' => ['Sains', 'Agama'],
                'discount_percent' => 25,
                'discount_start' => now()->subDays(3),
                'discount_end' => now()->addDays(4),
            ],
            [
                'title' => 'Atomic Habits',
                'author' => 'James Clear',
                'category' => 'Edukasi',
                'cover' => 'covers/atomic_habits.jpg',
                'description' => 'Cara termudah dan terbukti untuk membangun kebiasaan baik dan menghilangkan kebiasaan buruk.',
                'price' => 110000,
                'stock' => 40,
                'genres' => ['Edukasi', 'Non-Fiksi'],
                'discount_percent' => 15,
                'discount_start' => now()->subDay(),
                'discount_end' => now()->addDays(7),
            ],
            [
                'title' => 'Gadis Kretek',
                'author' => 'Ratih Kumala',
                'category' => 'Fiksi',
                'cover' => 'covers/gadis_kretek.jpg',
                'description' => 'Kisah sejarah industri kretek di Indonesia dibalut romansa yang mengharukan dan pencarian jati diri.',
                'price' => 85000,
                'stock' => 10,
                'genres' => ['Novel', 'Sejarah', 'Romantis'],
                'discount_percent' => 0,
                'discount_start' => null,
                'discount_end' => null,
            ],
            [
                'title' => 'Habibie & Ainun',
                'author' => 'B.J. Habibie',
                'category' => 'Biografi',
                'cover' => 'covers/habibie_ainun.jpg',
                'description' => 'Kisah cinta abadi antara Presiden RI ketiga B.J. Habibie dan sang istri tercinta Ainun Habibie.',
                'price' => 90000,
                'stock' => 8,
                'genres' => ['Biografi', 'Romantis'],
                'discount_percent' => 30,
                'discount_start' => now()->subDay(),
                'discount_end' => now()->addDays(2),
            ],
            [
                'title' => 'Sherlock Holmes: A Study in Scarlet',
                'author' => 'Arthur Conan Doyle',
                'category' => 'Fiksi',
                'cover' => 'covers/sherlock_holmes.jpg',
                'description' => 'Kasus pertama detektif jenius Sherlock Holmes dalam mengungkap pembunuhan misterius di London.',
                'price' => 65000,
                'stock' => 16,
                'genres' => ['Novel', 'Misteri', 'Fantasi'],
                'discount_percent' => 0,
                'discount_start' => null,
                'discount_end' => null,
            ],
            [
                'title' => 'Steve Jobs',
                'author' => 'Walter Isaacson',
                'category' => 'Biografi',
                'cover' => 'covers/steve_jobs.jpg',
                'description' => 'Biografi eksklusif pendiri Apple, Steve Jobs, yang menggambarkan inovasi dan kepemimpinannya.',
                'price' => 150000,
                'stock' => 5,
                'genres' => ['Biografi', 'Teknologi'],
                'discount_percent' => 50,
                'discount_start' => now()->subDays(1),
                'discount_end' => now()->addDay(),
            ],
            [
                'title' => 'Cosmos',
                'author' => 'Carl Sagan',
                'category' => 'Sains',
                'cover' => 'covers/cosmos.jpg',
                'description' => 'Penjelajahan alam semesta yang menakjubkan dari salah satu ilmuwan astronomi terbesar di dunia.',
                'price' => 125000,
                'stock' => 11,
                'genres' => ['Sains', 'Non-Fiksi'],
                'discount_percent' => 0,
                'discount_start' => null,
                'discount_end' => null,
            ],
            [
                'title' => 'Negeri 5 Menara',
                'author' => 'Ahmad Fuadi',
                'category' => 'Fiksi',
                'cover' => 'covers/negeri_5_menara.jpg',
                'description' => 'Kisah persahabatan anak-anak pesantren dengan moto legendaris Man Jadda Wajada.',
                'price' => 78000,
                'stock' => 20,
                'genres' => ['Novel', 'Edukasi', 'Agama'],
                'discount_percent' => 10,
                'discount_start' => now()->subDay(),
                'discount_end' => now()->addDays(6),
            ],
            [
                'title' => 'Pulang',
                'author' => 'Leila S. Chudori',
                'category' => 'Fiksi',
                'cover' => 'covers/pulang.jpg',
                'description' => 'Drama keluarga berlatar belakang tragedi sejarah Indonesia tahun 1965 di Paris dan Jakarta.',
                'price' => 99000,
                'stock' => 13,
                'genres' => ['Novel', 'Sejarah'],
                'discount_percent' => 0,
                'discount_start' => null,
                'discount_end' => null,
            ],
            [
                'title' => 'Brief Answers to the Big Questions',
                'author' => 'Stephen Hawking',
                'category' => 'Sains',
                'cover' => 'covers/brief_answers.jpg',
                'description' => 'Pandangan terakhir fisikawan Stephen Hawking tentang pertanyaan-pertanyaan terbesar umat manusia.',
                'price' => 140000,
                'stock' => 9,
                'genres' => ['Sains', 'Non-Fiksi'],
                'discount_percent' => 20,
                'discount_start' => now()->subDays(4),
                'discount_end' => now()->addDays(12),
            ],
            [
                'title' => 'Supernova: Ksatria, Puteri, dan Bintang Jatuh',
                'author' => 'Dee Lestari',
                'category' => 'Fiksi',
                'cover' => 'covers/supernova.jpg',
                'description' => 'Novel fiksi ilmiah romantis karya Dee Lestari yang memadukan sains, cinta, dan spiritualitas.',
                'price' => 89000,
                'stock' => 15,
                'genres' => ['Novel', 'Sains', 'Romantis'],
                'discount_percent' => 0,
                'discount_start' => null,
                'discount_end' => null,
            ],
            [
                'title' => 'Madilog',
                'author' => 'Tan Malaka',
                'category' => 'Sejarah',
                'cover' => 'covers/madilog.jpg',
                'description' => 'Karya filsafat legendaris Tan Malaka tentang Materialisme, Dialektika, dan Logika.',
                'price' => 105000,
                'stock' => 7,
                'genres' => ['Sejarah', 'Non-Fiksi'],
                'discount_percent' => 0,
                'discount_start' => null,
                'discount_end' => null,
            ],
            [
                'title' => 'The Lean Startup',
                'author' => 'Eric Ries',
                'category' => 'Teknologi',
                'cover' => 'covers/lean_startup.jpg',
                'description' => 'Bagaimana wirausaha masa kini menggunakan inovasi terus-menerus untuk menciptakan bisnis yang sukses.',
                'price' => 130000,
                'stock' => 17,
                'genres' => ['Teknologi', 'Non-Fiksi'],
                'discount_percent' => 15,
                'discount_start' => now()->subDay(),
                'discount_end' => now()->addDays(10),
            ]
        ];

        $users = [];
        for ($i = 1; $i <= 3; $i++) {
            $users[] = User::create([
                'name' => "Reviewer $i",
                'email' => "reviewer$i@boosho.com",
                'password' => Hash::make('123'),
                'role' => 'user'
            ])->id;
        }

        $posComments = [
            'Buku yang sangat bagus dan mantap, rekomendasi untuk dibaca!',
            'Wah keren sekali, isinya sangat menarik dan luar biasa.',
            'Cukup puas, cepat paham, sangat membantu dan bermanfaat.'
        ];
        
        $critComments = [
            'Jelek dan sangat mengecewakan, kertasnya tipis dan sobek.',
            'Kurang bagus, isinya membosankan dan lambat dipahami.',
            'Harga mahal tapi kualitas buruk, sungguh kecewa.'
        ];

        foreach ($books as $bookData) {
            $b = Book::create($bookData);
            
            // Generate 2 positive reviews
            \App\Models\Review::create([
                'user_id' => $users[0],
                'book_id' => $b->id,
                'rating' => rand(4, 5),
                'comment' => $posComments[array_rand($posComments)]
            ]);

            \App\Models\Review::create([
                'user_id' => $users[1],
                'book_id' => $b->id,
                'rating' => rand(4, 5),
                'comment' => $posComments[array_rand($posComments)]
            ]);

            // Generate 1 critical review
            \App\Models\Review::create([
                'user_id' => $users[2],
                'book_id' => $b->id,
                'rating' => rand(1, 3),
                'comment' => $critComments[array_rand($critComments)]
            ]);
        }
    }
}