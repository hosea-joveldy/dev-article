<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Comment extends Model
{
    protected $fillable = [
        'artikel_id',
        'user_id',
        'body',
    ];

    public function artikel(): BelongsTo
    {
        return $this->belongsTo(Artikel::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getBodyHtmlAttribute(): string
    {
        return Str::markdown($this->body ?? '', [
            'html_input' => 'strip', // Strips raw unsafe scripts/tags
            'allow_unsafe_links' => false,
        ]);
    }
}
