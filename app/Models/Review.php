<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    // Mengizinkan semua kolom diisi melalui perintah create()
    protected $guarded = [];

    // Relasi agar review tahu siapa user yang menulisnya
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi agar review tahu ini milik buku apa
    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}