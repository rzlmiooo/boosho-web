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

    /**
     * Indonesian NLP Lexicon-based Sentiment Classifier.
     */
    public function getSentimentAttribute()
    {
        $text = strtolower($this->comment);
        
        $positiveWords = ['bagus', 'keren', 'suka', 'mantap', 'baik', 'rekomendasi', 'puas', 'cepat', 'asli', 'original', 'menarik', 'indah', 'inspiratif', 'luar biasa', 'top', 'membantu', 'bermanfaat', 'paham', 'mudah', 'jelas', 'cinta'];
        $criticalWords = ['jelek', 'kecewa', 'kurang', 'lambat', 'buruk', 'rusak', 'robek', 'cacat', 'lama', 'tipis', 'mahal', 'sulit', 'bingung', 'bosan', 'salah', 'mengecewakan', 'rugi', 'parah', 'kotor'];
        
        $posCount = 0;
        $negCount = 0;
        
        foreach ($positiveWords as $word) {
            if (str_contains($text, $word)) $posCount++;
        }
        foreach ($criticalWords as $word) {
            if (str_contains($text, $word)) $negCount++;
        }
        
        $sentimentScore = $posCount - $negCount;
        
        if ($sentimentScore > 0) return 'positif';
        if ($sentimentScore < 0) return 'kritis';
        
        return $this->rating >= 4 ? 'positif' : 'kritis';
    }
}