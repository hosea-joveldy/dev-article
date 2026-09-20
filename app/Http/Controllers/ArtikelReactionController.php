<?php

namespace App\Http\Controllers;

use App\Models\Artikel;
use App\Models\ArticleReaction;
use Illuminate\Http\RedirectResponse;

class ArtikelReactionController extends Controller
{
    /**
     * Like: toggle (clicking again = remove), switch from dislike.
     */
    public function like(string $id): RedirectResponse
    {
        return $this->react($id, ArticleReaction::LIKE);
    }

    /**
     * Dislike: toggle (clicking again = remove), switch from like.
     */
    public function dislike(string $id): RedirectResponse
    {
        return $this->react($id, ArticleReaction::DISLIKE);
    }

    /**
     * Remove the user's reaction on this article.
     */
    public function destroy(string $id): RedirectResponse
    {
        $artikel = Artikel::findOrFail($id);
        $artikel->reactions()->where('user_id', auth()->id())->delete();

        return back();
    }

    protected function react(string $id, int $value): RedirectResponse
    {
        $artikel = Artikel::findOrFail($id);

        $existing = $artikel->reactions()->where('user_id', auth()->id())->first();

        if ($existing && (int) $existing->value === $value) {
            // Toggle: clicking the same reaction again -> remove it
            $existing->delete();
        } else {
            // Create new or switch (like <-> dislike), one reaction per user per article
            $artikel->reactions()->updateOrCreate(
                ['user_id' => auth()->id()],
                ['value' => $value]
            );
        }

        return back();
    }
}
