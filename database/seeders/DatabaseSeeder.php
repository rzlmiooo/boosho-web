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
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        // 2. Buat Akun User
        User::create([
            'name' => 'Pengguna Setia',
            'email' => 'user@boosho.com',
            'password' => Hash::make('password123'),
            'role' => 'user',
        ]);

        // 3. Buat Data Buku
        Book::create([
            'title' => 'Struktur Data & Algoritma',
            'author' => 'Budi Santoso',
            'description' => 'Buku ini membahas konsep dasar struktur data dan algoritma secara komprehensif, cocok untuk pemula.',
            'price' => 85000,
            'stock' => 20,
        ]);

        Book::create([
            'title' => 'Mastering Laravel 11',
            'author' => 'Eza Developer',
            'description' => 'Panduan lengkap dan praktis untuk menguasai framework Laravel versi terbaru.',
            'price' => 120000,
            'stock' => 15,
        ]);
    }
}