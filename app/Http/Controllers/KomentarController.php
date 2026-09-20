<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Models\Artikel;
use App\Models\Comment;
use Illuminate\Http\RedirectResponse;

class KomentarController extends Controller
{
    /**
     * Simpan komentar baru (auth required via route middleware).
     */
    public function store(StoreCommentRequest $request, string $id): RedirectResponse
    {
        $artikel = Artikel::findOrFail($id);

        $artikel->comments()->create([
            'user_id' => auth()->id(),
            'body' => $request->validated()['body'],
        ]);

        return back()->with('success', 'Komentar berhasil ditambahkan!');
    }

    /**
     * Hapus komentar. Hanya penulis komentar (atau admin) yang boleh.
     */
    public function destroy(string $id): RedirectResponse
    {
        $comment = Comment::findOrFail($id);
        $user = auth()->user();

        $isAdmin = (bool) ($user->is_admin ?? false);
        if ($comment->user_id !== $user->id && ! $isAdmin) {
            abort(403, 'Anda tidak berhak menghapus komentar ini.');
        }

        $comment->delete();

        return back()->with('success', 'Komentar berhasil dihapus!');
    }
}
