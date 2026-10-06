<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Ruang — Edit story</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
<header class="topbar">
    <div class="container nav">
        <a class="brand" href="{{ route('home') }}">Ruang.</a>
        <nav class="navlinks">
            <a href="{{ route('artikel.index') }}">Explore</a>
            <a href="{{ route('artikel.show', $artikel->id) }}">View story</a>
            <a href="{{ route('artikel.index') }}" class="pill">Back to stories</a>
        </nav>
    </div>
</header>

<main class="article-wrap" style="max-width: 820px;">
    <div class="mb-8">
        <h1 class="serif text-4xl mb-2">Edit story.</h1>
        <p class="text-stone-500 text-sm">Perbarui konten artikel dan simpan perubahan Anda.</p>
    </div>

    @if ($errors->any())
        <div class="alert-error mb-6">
            <p class="font-semibold mb-1">Please fix the following issues:</p>
            <ul class="list-disc list-inside text-xs">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('artikel.update', $artikel->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <div>
            <label for="judul" class="label-ruang">Story Title *</label>
            <input
                type="text"
                name="judul"
                id="judul"
                value="{{ old('judul', $artikel->judul) }}"
                placeholder="Title"
                class="input-ruang text-lg font-medium"
                required
            >
            @error('judul')
                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="category_id" class="label-ruang">Topic / Category</label>
            <select name="category_id" id="category_id" class="input-ruang">
                <option value="">Select a topic (optional)</option>
                @foreach (($categories ?? collect()) as $category)
                    <option value="{{ $category->id }}" {{ old('category_id', $artikel->category_id) == $category->id ? 'selected' : '' }}>
                        {{ $category->nama }}
                    </option>
                @endforeach
            </select>
            @error('category_id')
                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="konten" class="label-ruang">Content * (Markdown supported)</label>
            <textarea
                name="konten"
                id="konten"
                rows="15"
                placeholder="Tell your story..."
                class="input-ruang font-sans leading-relaxed text-base"
                required
            >{{ old('konten', $artikel->konten) }}</textarea>
            @error('konten')
                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="gambar" class="label-ruang">Featured Image (optional)</label>
            @if ($artikel->gambar)
                <div class="mb-3">
                    <img src="{{ asset('storage/' . $artikel->gambar) }}" alt="{{ $artikel->judul }}" class="h-32 rounded object-cover border border-stone-200">
                </div>
            @endif
            <input
                type="file"
                name="gambar"
                id="gambar"
                accept="image/*"
                class="input-ruang"
            >
            @error('gambar')
                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="pt-6 border-t border-stone-200 flex items-center justify-between">
            <a href="{{ route('artikel.show', $artikel->id) }}" class="pill-outline">Cancel</a>
            <button type="submit" class="pill">Save changes</button>
        </div>
    </form>
</main>

<footer class="footer">
    <div class="container footer-inner">
        <span>© 2026 Ruang Editorial</span>
        <div class="footer-links">
            <a href="#">About</a>
            <a href="#">Privacy</a>
            <a href="#">Contact</a>
        </div>
    </div>
</footer>
</body>
</html>
