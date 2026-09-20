<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ArticleReaction extends Model
{
    public const LIKE = 1;

    public const DISLIKE = -1;

    protected $fillable = [
        'artikel_id',
        'user_id',
        'value',
    ];

    protected $casts = [
        'value' => 'integer',
    ];

    public function artikel(): BelongsTo
    {
        return $this->belongsTo(Artikel::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
