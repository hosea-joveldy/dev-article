<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreArtikelRequest;
use App\Http\Requests\UpdateArtikelRequest;
use App\Models\Artikel;
use App\Models\ArticleReaction;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ArtikelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Artikel::query()->with('category')->withCount(['likes', 'dislikes']);

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
            case 'latest':
            default:
                $query->latest();
                break;
        }

        $berita = $query->paginate(6)->withQueryString();

        // Current user's reactions for highlight on the list page (one query, no N+1)
        $userReactions = collect();
        if (auth()->check()) {
            $userReactions = ArticleReaction::where('user_id', auth()->id())
                ->whereIn('artikel_id', $berita->getCollection()->modelKeys())
                ->pluck('value', 'artikel_id');
        }

        return view('welcome', compact('berita', 'categories', 'activeCategory', 'userReactions'));
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
    public function store(StoreArtikelRequest $request) {
        $validated = $request->validated();

        // Save data to the database
        //Artikel::create($request->all());
        $baru = new Artikel();
        $baru->judul = $validated['judul'];         //Take from the "judul" field
        $baru->konten = $validated['konten'];   //Take from the "konten" field
        $baru->category_id = $validated['category_id'] ?? null;

        // Save the image to storage
        if ($request->hasFile('gambar')) {
            $baru->gambar = $request->file('gambar')->store('gambars', 'public');
        }

        $baru->save(); //PUSH to the database!

        // Redirect to the article list page
        return redirect('/')->with('success', 'Article created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $artikel = Artikel::with('category')->withCount(['likes', 'dislikes'])->findOrFail($id);
        $komentars = $artikel->comments()->with('user')->oldest()->paginate(10);
        $userReaction = $artikel->reactionFor(auth()->user());

        return view('artikel.detail', compact('artikel', 'komentars', 'userReaction'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id) {
        $artikel = Artikel::findOrFail($id);
        $categories = Category::orderBy('nama')->get();

        return view('artikel.edit', compact('artikel', 'categories'));
    }

    // Action 6: Persisting data changes
    public function update(UpdateArtikelRequest $request, $id) {
        $validated = $request->validated();

        // Find the record by ID
        $artikel = Artikel::findOrFail($id);
        $artikel->judul = $validated['judul'];
        $artikel->konten = $validated['konten'];
        $artikel->category_id = $validated['category_id'] ?? null;

        // If a new image was uploaded
        if ($request->hasFile('gambar')) {
            // Delete the old image
            if ($artikel->gambar) {
                Storage::disk('public')->delete($artikel->gambar);
            }

            // Save the new image
            $artikel->gambar = $request->file('gambar')
                ->store('gambars', 'public');
        }

        $artikel->save();

        return redirect('/artikel')->with('success', 'Article updated successfully!');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id) {
        $artikel = Artikel::findOrFail($id);
        $artikel->delete();
        return redirect('/artikel')->with('success', 'Article deleted successfully!');
    }
}
