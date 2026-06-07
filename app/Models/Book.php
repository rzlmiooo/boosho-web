<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    // Mendaftarkan kolom mana saja yang boleh diisi data (mass assignment)
    protected $fillable = [
        'title',
        'author',
        'description',
        'price',
        'stock'
    ];
}
