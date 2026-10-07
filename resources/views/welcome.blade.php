<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Ruang — Stories & Ideas</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
<header class="topbar">
    <div class="container nav">
        <a class="brand" href="{{ route('home') }}">Ruang.</a>
        <nav class="navlinks">
            <a href="{{ route('artikel.index') }}">Explore</a>
            <a href="#about">About</a>
            <a href="{{ route('artikel.index') }}" class="pill">Start reading</a>
        </nav>
    </div>
</header>

<main>
<section class="hero">
    <div class="container hero-inner">
        <div>
            <div class="eyebrow">Independent editorial platform</div>
            <h1 class="serif">Ideas worth<br>taking your time.</h1>
            <p>Temukan perspektif, insight, dan cerita yang membantu Anda memahami bisnis, teknologi, kreativitas, dan dunia di sekitar kita.</p>
            <div class="hero-actions">
                <a href="{{ route('artikel.index') }}" class="pill">Mulai membaca</a>
                <a href="#latest" class="text-link">Lihat artikel terbaru &rarr;</a>
            </div>
        </div>
        <div class="hero-art" aria-hidden="true">
            <div class="orbit">
                <span class="dot"></span>
                <span class="dot two"></span>
                <span class="dot three"></span>
            </div>
        </div>
    </div>
</section>

<section class="feature-strip">
    <div class="container feature-grid">
        <article class="feature">
            <div class="feature-number">01 — CURATED</div>
            <h3>Dipilih untuk dibaca, bukan sekadar diklik.</h3>
            <p>Koleksi artikel yang mengutamakan gagasan, konteks, dan kualitas tulisan.</p>
        </article>
        <article class="feature">
            <div class="feature-number">02 — EDITORIAL</div>
            <h3>Ruang untuk sudut pandang yang berbeda.</h3>
            <p>Beragam topik dan perspektif disajikan dengan struktur yang mudah dipahami.</p>
        </article>
        <article class="feature">
            <div class="feature-number">03 — SIMPLE</div>
            <h3>Pengalaman membaca yang tenang.</h3>
            <p>Tanpa visual yang berlebihan. Fokus utama tetap pada cerita dan ide.</p>
        </article>
    </div>
</section>

@php
    $articles = $articles ?? $artikels ?? \App\Models\Artikel::with(['category', 'user'])->latest()->take(3)->get();
@endphp

<section class="section" id="latest">
    <div class="container">
        <div class="section-head">
            <h2>Latest stories</h2>
            <a href="{{ route('artikel.index') }}">Explore all &rarr;</a>
        </div>
        <div class="article-grid">
            @forelse ($articles as $artikel)
                <article class="card">
                    <div class="card-meta"><span>{{ $artikel->category ? strtoupper($artikel->category->nama) : 'ARTICLE' }}</span><span>{{ $artikel->reading_time }} min read</span></div>
                    <h3><a href="{{ route('artikel.show', $artikel->id) }}">{{ $artikel->judul }}</a></h3>
                    <p>{{ Str::limit(strip_tags($artikel->konten), 120) }}</p>
                    <div class="author">By {{ $artikel->user?->name ?? 'Ruang Writer' }} · {{ $artikel->created_at ? $artikel->created_at->format('M d') : 'Recent' }}</div>
                </article>
            @empty
                <div class="col-span-full py-8 text-stone-500">
                    <p>Belum ada artikel yang tersedia.</p>
                </div>
            @endforelse
        </div>
    </div>
</section>


<section class="cta" id="about">
    <div class="container cta-inner">
        <h2 class="serif">Baca lebih dalam. Pahami lebih jauh.</h2>
        <p>Ruang dibuat untuk pembaca yang ingin menemukan ide bernilai tanpa harus melewati pengalaman yang penuh distraksi.</p>
        <a href="{{ route('artikel.index') }}" class="pill">Explore stories</a>
    </div>
</section>
</main>

<footer class="footer">
    <div class="container footer-inner">
        <span>© 2026 Ruang Editorial</span>
        <div class="footer-links">
            <a href="#about">About</a>
            <a href="#">Privacy</a>
            <a href="#">Contact</a>
        </div>
    </div>
</footer>
</body>
</html>