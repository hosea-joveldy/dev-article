<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Models\Artikel;
use App\Models\Comment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class KomentarController extends Controller
{
    /**
     * Display a listing of comments for moderation (admin only).
     */
    public function index(Request $request)
    {
        $query = Comment::query()->with(['user', 'artikel'])->latest();

        if ($request->filled('q')) {
            $searchTerm = $request->input('q');
            $query->where(function ($q) use ($searchTerm) {
                $q->where('body', 'like', "%{$searchTerm}%")
                  ->orWhereHas('user', fn ($uq) => $uq->where('name', 'like', "%{$searchTerm}%"))
                  ->orWhereHas('artikel', fn ($aq) => $aq->where('judul', 'like', "%{$searchTerm}%"));
            });
        }

        $comments = $query->paginate(20)->withQueryString();

        return view('comments.index', compact('comments'));
    }

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