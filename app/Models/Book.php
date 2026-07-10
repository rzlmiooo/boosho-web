<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Book extends Model
{
    use SoftDeletes;
    // Mendaftarkan kolom mana saja yang boleh diisi data (mass assignment)
    protected $fillable = [
        'title',
        'author',
        'category',
        'cover',
        'description',
        'price',
        'stock',
        'genres'
    ];

    // Mengubah data JSON menjadi Array
    protected $casts = [
        'genres' => 'array',
    ];
}