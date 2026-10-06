<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreArtikelRequest;
use App\Http\Requests\UpdateArtikelRequest;
use App\Models\Artikel;
use App\Models\ArticleReaction;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class ArtikelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Artikel::query()->with(['category', 'user'])->withCount(['likes', 'dislikes']);

        // 1. Search filter
        if ($request->filled('q')) {
            $searchTerm = $request->input('q');
            $query->where(function ($q) use ($searchTerm) {
                $q->where('judul', 'like', "%{$searchTerm}%")
                  ->orWhere('konten', 'like', "%{$searchTerm}%");
            });
        }

        // 2. Category filter (?category=<id|slug>), preserves q/sort via the filter form + withQueryString
        $categories = Category::orderBy('nama')->get();
        $activeCategory = null;
        if ($request->filled('category')) {
            $categoryParam = $request->input('category');
            $activeCategory = Category::where('id', $categoryParam)
                ->orWhere('slug', $categoryParam)
                ->first();
            if ($activeCategory) {
                $query->where('category_id', $activeCategory->id);
            }
        }

        // 3. Sorting
        switch ($request->input('sort')) {
            case 'oldest':
                $query->oldest();
                break;
            case 'title_asc':
                $query->orderBy('judul', 'asc');
                break;
            case 'popular':
                $query->orderByDesc('likes_count');
                break;
            case 'latest':
            default:
                $query->latest();
                break;
        }

        $berita = $query->paginate(6)->withQueryString();

        // Current user's reactions for highlight on the list page
        $userReactions = collect();
        if (auth()->check()) {
            $userReactions = ArticleReaction::where('user_id', auth()->id())
                ->whereIn('artikel_id', $berita->getCollection()->modelKeys())
                ->pluck('value', 'artikel_id');
        }

        return view('artikel.index', compact('berita', 'categories', 'activeCategory', 'userReactions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::orderBy('nama')->get();

        return view('artikel.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreArtikelRequest $request)
    {
        $validated = $request->validated();

        $baru = new Artikel();
        $baru->user_id = auth()->id();
        $baru->judul = $validated['judul'];
        $baru->konten = $validated['konten'];
        $baru->category_id = $validated['category_id'] ?? null;

        // Save the image to storage
        if ($request->hasFile('gambar')) {
            $baru->gambar = $request->file('gambar')->store('gambars', 'public');
        }

        $baru->save();

        return redirect()->route('artikel.show', $baru->id)->with('success', 'Article created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $artikel = Artikel::with(['category', 'user'])->withCount(['likes', 'dislikes'])->findOrFail($id);
        $komentars = $artikel->comments()->with('user')->oldest()->paginate(10);
        $userReaction = $artikel->reactionFor(auth()->user());

        // Related articles (same category if present, excluding current, up to 3)
        $relatedQuery = Artikel::where('id', '!=', $artikel->id)->with(['category', 'user'])->latest();
        if ($artikel->category_id) {
            $relatedArticles = (clone $relatedQuery)->where('category_id', $artikel->category_id)->take(3)->get();
            if ($relatedArticles->count() < 3) {
                $fallback = (clone $relatedQuery)->whereNotIn('id', $relatedArticles->pluck('id'))->take(3 - $relatedArticles->count())->get();
                $relatedArticles = $relatedArticles->merge($fallback);
            }
        } else {
            $relatedArticles = $relatedQuery->take(3)->get();
        }

        return view('artikel.detail', compact('artikel', 'komentars', 'userReaction', 'relatedArticles'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $artikel = Artikel::findOrFail($id);
        Gate::authorize('update', $artikel);

        $categories = Category::orderBy('nama')->get();

        return view('artikel.edit', compact('artikel', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateArtikelRequest $request, string $id)
    {
        $artikel = Artikel::findOrFail($id);
        Gate::authorize('update', $artikel);

        $validated = $request->validated();

        $artikel->judul = $validated['judul'];
        $artikel->konten = $validated['konten'];
        $artikel->category_id = $validated['category_id'] ?? null;

        // If a new image was uploaded
        if ($request->hasFile('gambar')) {
            if ($artikel->gambar) {
                Storage::disk('public')->delete($artikel->gambar);
            }
            $artikel->gambar = $request->file('gambar')->store('gambars', 'public');
        }

        $artikel->save();

        return redirect()->route('artikel.show', $artikel->id)->with('success', 'Article updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $artikel = Artikel::findOrFail($id);
        Gate::authorize('delete', $artikel);

        $artikel->delete();

        return redirect()->route('artikel.index')->with('success', 'Article deleted successfully!');
    }

    /**
     * Stream protected article image to authenticated users.
     */
    public function image(string $id): Response
    {
        $artikel = Artikel::findOrFail($id);

        if (! $artikel->gambar || ! Storage::disk('public')->exists($artikel->gambar)) {
            abort(404);
        }

        return Storage::disk('public')->response($artikel->gambar);
    }
}
