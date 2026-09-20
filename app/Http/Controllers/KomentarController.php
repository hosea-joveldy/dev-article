<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Models\Artikel;
use App\Models\Comment;
use Illuminate\Http\RedirectResponse;

class KomentarController extends Controller
{
    /**
     * Store a new comment (auth required via route middleware).
     */
    public function store(StoreCommentRequest $request, string $id): RedirectResponse
    {
        $artikel = Artikel::findOrFail($id);

        $artikel->comments()->create([
            'user_id' => auth()->id(),
            'body' => $request->validated()['body'],
        ]);

        return back()->with('success', 'Comment added successfully!');
    }

    /**
     * Delete a comment. Only the comment author (or an admin) may do so.
     */
    public function destroy(string $id): RedirectResponse
    {
        $comment = Comment::findOrFail($id);
        $user = auth()->user();

        $isAdmin = (bool) ($user->is_admin ?? false);
        if ($comment->user_id !== $user->id && ! $isAdmin) {
            abort(403, 'You are not authorized to delete this comment.');
        }

        $comment->delete();

        return back()->with('success', 'Comment deleted successfully!');
    }
}
