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

        // 3. Buat Data Buku
        Book::create([
            'title' => 'Struktur Data & Algoritma',
            'author' => 'Budi Santoso',
            'price' => 85000,
            'stock' => 20,
            'description' => 'Ini buku belajar Struktur Data & Algoritma',
            'genres' => ['Teknologi', 'Edukasi'],
        ]);
        
        Book::create([
            'title' => 'Mastering Laravel 11',
            'author' => 'Eza Developer',
            'price' => 120000,
            'stock' => 15,
            'description' => 'Ini buku belajar Laravel 11',
            'genres' => ['Teknologi', 'Edukasi'],
        ]);
    }
}