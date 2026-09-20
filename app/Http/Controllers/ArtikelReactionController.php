<?php

namespace App\Http\Controllers;

use App\Models\Artikel;
use App\Models\ArticleReaction;
use Illuminate\Http\RedirectResponse;

class ArtikelReactionController extends Controller
{
    /**
     * Suka: toggle (klik lagi = batalkan), beralih dari tidak suka.
     */
    public function like(string $id): RedirectResponse
    {
        return $this->react($id, ArticleReaction::LIKE);
    }

    /**
     * Tidak suka: toggle (klik lagi = batalkan), beralih dari suka.
     */
    public function dislike(string $id): RedirectResponse
    {
        return $this->react($id, ArticleReaction::DISLIKE);
    }

    /**
     * Batalkan reaksi user pada artikel ini.
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
            // Toggle: reaksi yang sama diklik lagi -> batalkan
            $existing->delete();
        } else {
            // Buat baru atau beralih (suka <-> tidak suka), satu reaksi per user per artikel
            $artikel->reactions()->updateOrCreate(
                ['user_id' => auth()->id()],
                ['value' => $value]
            );
        }

        return back();
    }
}
