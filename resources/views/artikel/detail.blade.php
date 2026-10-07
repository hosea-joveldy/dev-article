<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Ruang — {{ $artikel->judul }}</title>

    <!-- Open Graph / Twitter Meta (Feature 5) -->
    <meta property="og:title" content="{{ $artikel->judul }}">
    <meta property="og:description" content="{{ Str::limit(strip_tags($artikel->konten), 160) }}">
    <meta property="og:type" content="article">
    <meta property="og:url" content="{{ route('artikel.show', $artikel->id) }}">
    @if ($artikel->gambar)
        <meta property="og:image" content="{{ asset('storage/' . $artikel->gambar) }}">
    @endif
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $artikel->judul }}">
    <meta name="twitter:description" content="{{ Str::limit(strip_tags($artikel->konten), 160) }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
<header class="topbar">
    <div class="container nav">
        <a class="brand" href="{{ route('home') }}">Ruang.</a>
        <nav class="navlinks">
            <a href="{{ route('artikel.index') }}">Explore</a>
            <a href="{{ route('artikel.index') }}#topics">Topics</a>
            <a href="{{ route('artikel.create') }}">Write</a>
            @if(auth()->user()?->is_admin)
                <a href="{{ route('admin.dashboard') }}">Admin</a>
            @endif
            <a href="{{ route('artikel.index') }}" class="pill">Back to stories</a>
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

<main class="article-wrap">
    <div class="article-category">
        {{ strtoupper($artikel->category?->nama ?? 'GENERAL') }}
    </div>

    <h1 class="article-title">{{ $artikel->judul }}</h1>

    <div class="article-byline flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="avatar">
                {{ strtoupper(substr($artikel->user?->name ?? 'R', 0, 2)) }}
            </div>
            <div>
                <strong>{{ $artikel->user?->name ?? 'Ruang Writer' }}</strong><br>
                <span class="meta">{{ $artikel->created_at ? $artikel->created_at->format('M d, Y') : 'Draft' }} · {{ $artikel->reading_time }} min read</span>
            </div>
        </div>

        <div class="flex items-center gap-3 text-xs">
            @can('update', $artikel)
                <a href="{{ route('artikel.edit', $artikel->id) }}" class="pill-outline text-xs">Edit story</a>
            @endcan
            @can('delete', $artikel)
                <form action="{{ route('artikel.destroy', $artikel->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this story?');" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="pill-outline text-xs text-red-600 hover:text-red-700">Delete</button>
                </form>
            @endcan
        </div>
    </div>

    @if ($artikel->gambar)
        <div class="my-8 rounded overflow-hidden border border-stone-200">
            <img src="{{ route('artikel.image', $artikel->id) }}" alt="{{ $artikel->judul }}" class="w-full max-h-[500px] object-cover">
        </div>
    @endif

    <article class="article-body">
        {!! $artikel->konten_html !!}
    </article>

    <!-- Reactions -->
    @php
        $myReaction = $userReaction?->value ?? 0;
    @endphp
    <div class="my-8 pt-6 pb-6 border-t border-b border-stone-200 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <form action="{{ route('artikel.like', $artikel->id) }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="pill-outline text-xs {{ $myReaction === 1 ? 'bg-stone-900 text-white hover:bg-black' : '' }}">
                    ♥ Like ({{ $artikel->likes_count ?? 0 }})
                </button>
            </form>
            <form action="{{ route('artikel.dislike', $artikel->id) }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="pill-outline text-xs {{ $myReaction === -1 ? 'bg-stone-900 text-white hover:bg-black' : '' }}">
                    ▼ Dislike ({{ $artikel->dislikes_count ?? 0 }})
                </button>
            </form>
            @if ($userReaction)
                <form action="{{ route('artikel.reaction.destroy', $artikel->id) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-xs text-stone-400 hover:text-stone-700 underline">Reset</button>
                </form>
            @endif
        </div>

        <!-- Share: Copy link only -->
        <div x-data="{ copied: false }" class="flex items-center text-xs text-stone-500">
            <button
                type="button"
                @click="navigator.clipboard.writeText(window.location.href); copied = true; setTimeout(() => copied = false, 2000)"
                class="pill-outline text-xs flex items-center gap-1.5"
                style="padding: 6px 14px;"
            >
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                    <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                </svg>
                <span x-text="copied ? 'Copied to clipboard!' : 'Copy link'">Copy link</span>
            </button>
        </div>
    </div>

    <!-- Article Footer -->
    <div class="article-footer">
        <a href="{{ route('artikel.index') }}">← More stories</a>
        <span class="meta">Ruang Editorial · {{ date('Y') }}</span>
    </div>

    <!-- Related Articles (Feature 5) -->
    @if(isset($relatedArticles) && $relatedArticles->count() > 0)
        <section class="mt-16 pt-12 border-t border-stone-200">
            <h2 class="serif text-2xl mb-8">Related stories</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($relatedArticles as $related)
                    <article class="card">
                        <div class="card-meta">
                            <span>{{ strtoupper($related->category?->nama ?? 'GENERAL') }}</span>
                            <span>{{ $related->reading_time }} min read</span>
                        </div>
                        <h3><a href="{{ route('artikel.show', $related->id) }}">{{ $related->judul }}</a></h3>
                        <p>{{ Str::limit(strip_tags($related->konten), 90) }}</p>
                        <div class="author">By {{ $related->user?->name ?? 'Ruang Writer' }}</div>
                    </article>
                @endforeach
            </div>
        </section>
    @endif

    <!-- Comments Section (Feature 4) -->
    <section class="mt-16 pt-12 border-t border-stone-200" id="comments">
        <h2 class="serif text-2xl mb-6">Responses ({{ $komentars->total() }})</h2>

        <!-- Comment Form -->
        <form action="{{ route('komentar.store', $artikel->id) }}" method="POST" class="mb-10">
            @csrf
            <div class="mb-3">
                <textarea
                    name="body"
                    rows="3"
                    maxlength="1000"
                    placeholder="What are your thoughts?"
                    class="input-ruang"
                    required
                >{{ old('body') }}</textarea>
                @error('body')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="flex justify-end">
                <button type="submit" class="pill text-xs">Publish response</button>
            </div>
        </form>

        <!-- Comments List -->
        <div class="space-y-6">
            @forelse ($komentars as $komentar)
                <div class="p-5 border border-stone-200 rounded-md bg-stone-50/50">
                    <div class="flex items-center justify-between mb-3 text-xs">
                        <div class="flex items-center gap-2">
                            <div class="avatar" style="width:26px;height:26px;font-size:10px;">
                                {{ strtoupper(substr($komentar->user?->name ?? 'U', 0, 2)) }}
                            </div>
                            <span class="font-semibold text-stone-900">{{ $komentar->user?->name ?? 'Reader' }}</span>
                            <span class="text-stone-400">· {{ $komentar->created_at ? $komentar->created_at->diffForHumans() : '' }}</span>
                        </div>
                        @if (auth()->id() === $komentar->user_id || auth()->user()?->is_admin)
                            <form action="{{ route('komentar.destroy', $komentar->id) }}" method="POST" onsubmit="return confirm('Delete this response?');" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-stone-400 hover:text-red-600 underline">Delete</button>
                            </form>
                        @endif
                    </div>
                    <div class="text-sm text-stone-800 leading-relaxed">
                        {!! $komentar->body_html !!}
                    </div>
                </div>
            @empty
                <p class="text-sm text-stone-400 py-6 text-center italic">No responses yet. Be the first to start the conversation.</p>
            @endforelse
        </div>

        @if ($komentars->hasPages())
            <div class="mt-8">
                {{ $komentars->links() }}
            </div>
        @endif
    </section>
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