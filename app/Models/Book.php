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
        'genres',
        'discount_percent',
        'discount_start',
        'discount_end'
    ];

    // Mengubah tipe kolom
    protected $casts = [
        'genres' => 'array',
        'discount_start' => 'datetime',
        'discount_end' => 'datetime'
    ];

    /**
     * Get the discounted price for the book based on discount validity period.
     */
    public function getDiscountedPriceAttribute()
    {
        $now = now();
        $hasValidDiscount = $this->discount_percent > 0 
            && (is_null($this->discount_start) || $this->discount_start <= $now)
            && (is_null($this->discount_end) || $this->discount_end >= $now);

        if ($hasValidDiscount) {
            return $this->price - ($this->price * $this->discount_percent / 100);
        }
        return $this->price;
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function getAverageRatingAttribute()
    {
        return round($this->reviews()->avg('rating') ?: 0.0, 1);
    }
}