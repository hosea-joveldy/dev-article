<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Artikel extends Model
{
    use HasFactory;
    protected $fillable = [
        'judul',
        'konten',
        'gambar'
    ];

public function getKontenHtmlAttribute(): string
    {
        return Str::markdown($this->konten ?? '', [
            'html_input' => 'strip', // Strips raw unsafe scripts/tags
            'allow_unsafe_links' => false,
        ]);
    }

    public function getReadingTimeAttribute(): int
    {
        $words = Str::wordCount(strip_tags($this->konten ?? ''));
        return max(1, (int) ceil($words / 200));
    }
}
