<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Artikel;

class ArtikelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Artikel::query();

        // 1. Search filter
        if ($request->filled('q')) {
            $searchTerm = $request->input('q');
            $query->where(function ($q) use ($searchTerm) {
                $q->where('judul', 'like', "%{$searchTerm}%")
                  ->orWhere('konten', 'like', "%{$searchTerm}%");
            });
        }

        // 2. Sorting
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

        $berita = $query->paginate(6);

        return view('welcome', compact('berita'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('artikel.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {
        // Validasi Data
        $request->validate([
            'judul' => 'required',
            'konten' => 'required',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10000', // Validasi gambar opsional
        ]);

        // Simpan data ke database
        //Artikel::create($request->all());
        $baru = new Artikel();
        $baru->judul = $request->judul;         //Ambil dari Kotak "judul"
        $baru->konten = $request->konten;   //Ambil dari Kotak "konten"
        
        // Simpan Gambar ke Storage
        if ($request->hasFile('gambar')) {
            $baru->gambar = $request->file('gambar')->store('gambars', 'public');
        }
        
        $baru->save(); //DORONG ke Database!

        // Redirect ke halaman daftar artikel
        return redirect('/')->with('success', 'Artikel berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $artikel = Artikel::find($id);
        return view('artikel.detail', compact('artikel'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id) {
        $artikel = Artikel::find($id);
        return view('artikel.edit', compact('artikel'));
    }

    // Aksi 6: Menyimpan Perubahan Data
    public function update(Request $request, $id) {
        // Validasi Data
        $request->validate([
            'judul' => 'required',
            'konten' => 'required',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:4096', // Validasi gambar opsional
        ]);

        // Cari data berdasarkan ID
        $artikel = Artikel::find($id);
        $artikel->judul = $request->judul;
        $artikel->konten = $request->konten;

        // Jika ada gambar baru
        if ($request->hasFile('gambar')) {
            // Hapus gambar lama
            if ($artikel->gambar) {
                Storage::disk('public')->delete($artikel->gambar);
            }

            // Simpan gambar baru
            $artikel->gambar = $request->file('gambar')
                ->store('gambars', 'public');
        }

        $artikel->save();

        return redirect('/artikel')->with('success', 'Artikel berhasil diupdate!');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id) {
        $artikel = Artikel::find($id);
        $artikel->delete();
        return redirect('/artikel')->with('success', 'Artikel berhasil dihapus!');
    }
}
