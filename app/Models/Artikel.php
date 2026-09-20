<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Artikel extends Model
{
    use HasFactory;
    protected $fillable = [
        'judul',
        'konten',
        'gambar',
        'category_id',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function reactions(): HasMany
    {
        return $this->hasMany(ArticleReaction::class);
    }

    public function likes(): HasMany
    {
        return $this->hasMany(ArticleReaction::class)->where('value', ArticleReaction::LIKE);
    }

    public function dislikes(): HasMany
    {
        return $this->hasMany(ArticleReaction::class)->where('value', ArticleReaction::DISLIKE);
    }

    public function reactionFor(?User $user): ?ArticleReaction
    {
        if ($user === null) {
            return null;
        }

        if ($this->relationLoaded('reactions')) {
            return $this->reactions->firstWhere('user_id', $user->id);
        }

        return $this->reactions()->where('user_id', $user->id)->first();
    }

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
