<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Ruang — Explore Stories</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
<header class="topbar">
    <div class="container nav">
        <a class="brand" href="{{ route('home') }}">Ruang.</a>
        <nav class="navlinks">
            <a href="{{ route('artikel.index') }}">Explore</a>
            <a href="#topics">Topics</a>
            <a href="{{ route('artikel.create') }}">Write</a>
            @if(auth()->user()?->is_admin)
                <a href="{{ route('admin.dashboard') }}">Admin</a>
            @endif
            <a href="{{ route('profile.edit') }}">Profile</a>
            <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                <button type="submit" class="pill">Log out</button>
            </form>
        </nav>
    </div>
</header>

@if(session('success'))
    <div class="container mt-4">
        <div class="alert-success">
            {{ session('success') }}
        </div>
    </div>
@endif

<main class="main-shell">
    <div class="container main-layout">
        <section>
            <h1 class="page-title">Explore stories.</h1>
            <p class="page-subtitle">Gagasan terbaru tentang bisnis, teknologi, pekerjaan, dan cara kita memahami dunia.</p>

            <!-- Search Form -->
            <form method="GET" action="{{ route('artikel.index') }}" class="mb-4">
                @if(request('sort'))
                    <input type="hidden" name="sort" value="{{ request('sort') }}">
                @endif
                @if(request('category'))
                    <input type="hidden" name="category" value="{{ request('category') }}">
                @endif
                <input class="search" name="q" value="{{ request('q') }}" placeholder="Search stories, topics, or authors..." aria-label="Search stories">
            </form>

            @if(!empty($activeCategory))
                <div class="mb-6 text-xs flex items-center gap-2">
                    <span class="text-stone-500">Filtering by topic:</span>
                    <span class="px-2.5 py-1 bg-stone-100 rounded-full font-medium">{{ $activeCategory->nama }}</span>
                    <a href="{{ route('artikel.index', request()->except(['category', 'page'])) }}" class="text-stone-400 hover:text-stone-700 underline text-xs">Clear</a>
                </div>
            @endif

            <!-- Tabs -->
            @php
                $currentSort = request('sort', 'latest');
            @endphp
            <div class="tabs">
                <a class="tab {{ $currentSort === 'latest' ? 'active' : '' }}" href="{{ route('artikel.index', array_merge(request()->except(['sort', 'page']), ['sort' => 'latest'])) }}">Latest</a>
                <a class="tab {{ $currentSort === 'popular' ? 'active' : '' }}" href="{{ route('artikel.index', array_merge(request()->except(['sort', 'page']), ['sort' => 'popular'])) }}">Popular</a>
                <a class="tab {{ $currentSort === 'oldest' ? 'active' : '' }}" href="{{ route('artikel.index', array_merge(request()->except(['sort', 'page']), ['sort' => 'oldest'])) }}">Oldest</a>
                <a class="tab {{ $currentSort === 'title_asc' ? 'active' : '' }}" href="{{ route('artikel.index', array_merge(request()->except(['sort', 'page']), ['sort' => 'title_asc'])) }}">A–Z</a>
            </div>

            <!-- Feed -->
            <div class="feed">
                @forelse ($berita as $artikel)
                    <article class="feed-card">
                        <div>
                            <div class="meta">{{ strtoupper($artikel->category?->nama ?? 'GENERAL') }} · {{ $artikel->reading_time }} min read</div>
                            <h2><a href="{{ route('artikel.show', $artikel->id) }}">{{ $artikel->judul }}</a></h2>
                            <p>{{ Str::limit(strip_tags($artikel->konten), 160) }}</p>
                            <div class="meta flex flex-wrap items-center justify-between gap-2">
                                <span>{{ $artikel->user?->name ?? 'Ruang Writer' }} · {{ $artikel->created_at ? $artikel->created_at->format('M d, Y') : 'Draft' }}</span>
                                <div class="flex items-center gap-3">
                                    <span class="text-xs text-stone-400">♥ {{ $artikel->likes_count ?? 0 }}</span>
                                    @can('update', $artikel)
                                        <a href="{{ route('artikel.edit', $artikel->id) }}" class="underline text-stone-600 hover:text-stone-900">Edit</a>
                                    @endcan
                                    @can('delete', $artikel)
                                        <form action="{{ route('artikel.destroy', $artikel->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this article?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="underline text-red-600 hover:text-red-800">Delete</button>
                                        </form>
                                    @endcan
                                </div>
                            </div>
                        </div>
                        @if ($artikel->gambar)
                            <a href="{{ route('artikel.show', $artikel->id) }}" class="feed-img" aria-label="Read article">
                                <img src="{{ asset('storage/' . $artikel->gambar) }}" alt="{{ $artikel->judul }}">
                            </a>
                        @else
                            <a href="{{ route('artikel.show', $artikel->id) }}" class="feed-img" aria-label="Read article"></a>
                        @endif
                    </article>
                @empty
                    <div class="py-16 text-center text-stone-500">
                        <p class="serif text-2xl mb-2">No stories found{{ request('q') ? ' for "' . e(request('q')) . '"' : '' }}.</p>
                        <p class="text-sm text-stone-400">Try searching for other terms or explore all topics.</p>
                        @if(request()->hasAny(['q', 'category', 'sort']))
                            <div class="mt-4">
                                <a href="{{ route('artikel.index') }}" class="pill-outline text-xs">Reset filters</a>
                            </div>
                        @endif
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if ($berita->hasPages())
                <div class="mt-10 pt-6 border-t border-stone-200 flex items-center justify-between text-sm text-stone-500">
                    <div>
                        Showing page {{ $berita->currentPage() }} of {{ $berita->lastPage() }} ({{ $berita->total() }} stories)
                    </div>
                    <div class="flex items-center gap-2">
                        @if ($berita->onFirstPage())
                            <span class="pill-outline opacity-40 cursor-not-allowed text-xs">← Prev</span>
                        @else
                            <a href="{{ $berita->previousPageUrl() }}" class="pill-outline text-xs">← Prev</a>
                        @endif

                        @if ($berita->hasMorePages())
                            <a href="{{ $berita->nextPageUrl() }}" class="pill-outline text-xs">Next →</a>
                        @else
                            <span class="pill-outline opacity-40 cursor-not-allowed text-xs">Next →</span>
                        @endif
                    </div>
                </div>
            @endif
        </section>

        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="side-block">
                <div class="side-title">ABOUT RUANG</div>
                <p class="about">Platform artikel untuk insight yang thoughtful, praktis, dan relevan bagi pembaca modern.</p>
            </div>
            <div class="side-block" id="topics">
                <div class="side-title">RECOMMENDED TOPICS</div>
                <div class="topic-list">
                    @foreach (($categories ?? collect()) as $category)
                        <a href="{{ route('artikel.index', array_merge(request()->except(['category', 'page']), ['category' => $category->slug ?? $category->id])) }}" class="{{ (($activeCategory?->id ?? null) === $category->id) ? 'bg-stone-300 font-semibold' : '' }}">{{ $category->nama }}</a>
                    @endforeach
                </div>
            </div>
            <div class="newsletter">
                <h3>Stay curious.</h3>
                <p>Dapatkan artikel pilihan langsung ke inbox.</p>
                @if (session('newsletter_success'))
                    <div class="p-3 my-2 text-xs bg-emerald-50 text-emerald-800 border border-emerald-200 rounded">
                        {{ session('newsletter_success') }}
                    </div>
                @endif
                <form action="{{ route('newsletter.subscribe') }}" method="POST">
                    @csrf
                    <input type="email" name="email" placeholder="Email address" value="{{ auth()->user()->email ?? old('email') }}" required>
                    @error('email')
                        <p class="text-xs text-red-600 mb-2">{{ $message }}</p>
                    @enderror
                    <button class="pill" style="width:100%" type="submit">Subscribe</button>
                </form>
            </div>
        </aside>
    </div>
</main>

<footer class="footer">
    <div class="container footer-inner">
        <span>© 2026 Ruang Editorial</span>
        <div class="footer-links">
            <a href="{{ route('feed') }}" target="_blank">RSS Feed</a>
            <a href="{{ route('sitemap') }}" target="_blank">Sitemap</a>
            <a href="#">Privacy</a>
            <a href="#">Contact</a>
        </div>
    </div>
</footer>
</body>
</html>
