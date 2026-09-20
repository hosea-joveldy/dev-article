<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'slug',
    ];

    protected static function booted(): void
    {
        static::creating(function (Category $category) {
            if (empty($category->slug) && ! empty($category->nama)) {
                $category->slug = Str::slug($category->nama);
            }
        });

        static::updating(function (Category $category) {
            if ($category->isDirty('nama') && ! empty($category->nama)) {
                $category->slug = Str::slug($category->nama);
            }
        });
    }

    public function artikels(): HasMany
    {
        return $this->hasMany(Artikel::class);
    }
}
