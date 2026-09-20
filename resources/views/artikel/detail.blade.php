@extends('artikel_layout')

@section('title', $artikel->judul . ' — dev.logs')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 py-10">
    <!-- Back Navigation -->
    <div class="mb-8">
        <a href="{{ url('/') }}" class="inline-flex items-center gap-2 text-xs font-mono font-medium hover:underline transition-opacity hover:opacity-80" style="color: var(--accent);">
            &larr; Return to feed
        </a>
    </div>

    <!-- Article Header -->
    <header class="pb-8 border-b" style="border-color: var(--border);">
        <div class="flex flex-wrap items-center gap-3 text-xs font-mono mb-4" style="color: var(--text-muted);">
            <span class="px-2 py-0.5 rounded border" style="background-color: var(--bg-surface); border-color: var(--border);">
                ENTRY #{{ str_pad($artikel->id, 4, '0', STR_PAD_LEFT) }}
            </span>
            <span>&bull;</span>
            <time datetime="{{ $artikel->created_at->toIso8601String() }}">
                Published {{ $artikel->created_at->format('M d, Y \a\t H:i') }}
            </time>
            <span>&bull;</span>
            <span style="color: var(--accent);">
                {{ $artikel->reading_time }} min read
            </span>
            @if ($artikel->category)
                <span>&bull;</span>
                <a href="{{ url('/') . '?category=' . $artikel->category->id }}" class="px-2 py-0.5 rounded border hover:opacity-80" style="background-color: var(--bg-surface); border-color: var(--border); color: var(--accent);">
                    {{ $artikel->category->nama }}
                </a>
            @endif
            @if ($artikel->updated_at && $artikel->updated_at->ne($artikel->created_at))
                <span>&bull;</span>
                <span class="italic">Updated {{ $artikel->updated_at->diffForHumans() }}</span>
            @endif
        </div>

        <!-- Title -->
        <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold tracking-tight leading-tight">
            {{ $artikel->judul }}
        </h1>

        <!-- Action Bar (Edit & Delete) -->
        <div class="flex items-center justify-between mt-6 pt-4 border-t text-xs font-mono" style="border-color: var(--border);">
            <div class="flex items-center gap-4">
                <a href="{{ route('artikel.edit', $artikel->id) }}" class="hover:underline font-medium" style="color: var(--warning);">
                    [Edit Entry]
                </a>
                <form action="{{ route('artikel.destroy', $artikel->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to permanently delete this entry?');" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="hover:underline font-medium" style="color: var(--danger);">
                        [Delete]
                    </button>
                </form>
            </div>

            <span class="text-xs" style="color: var(--text-muted);">
                {{ Str::wordCount(strip_tags($artikel->konten)) }} words
            </span>
        </div>
    </header>

    <!-- Main Image (If Available) -->
    @if ($artikel->gambar)
        <div class="my-8 rounded overflow-hidden border bg-black/20" style="border-color: var(--border);">
            <img 
                src="{{ asset('storage/' . $artikel->gambar) }}" 
                alt="{{ $artikel->judul }}" 
                class="w-full max-h-[500px] object-cover"
            >
        </div>
    @endif

    <!-- Article Content Body -->
    <main class="py-8 text-base leading-relaxed space-y-6">
        <div class="article-content font-sans">
            {!! $artikel->konten_html !!}
        </div>
    </main>

    <!-- Reactions: Suka / Tidak Suka -->
    @php
        $myValue = (int) (($userReaction?->value) ?? 0);
        $detailLikeClass = $myValue === 1 ? 'reaction-liked' : 'reaction-off';
        $detailDislikeClass = $myValue === -1 ? 'reaction-disliked' : 'reaction-off';
    @endphp
    <section class="mt-4 py-6 border-t flex flex-wrap items-center gap-3 font-mono text-xs" style="border-color: var(--border);">
        <span style="color: var(--text-muted);">// reactions</span>
        @auth
            <form action="{{ route('artikel.like', $artikel->id) }}" method="POST" class="inline">
                @csrf
                <button type="submit" title="Suka" class="px-3 py-1.5 rounded border hover:opacity-80 {{ $detailLikeClass }}" style="background-color: var(--bg-surface); border-color: var(--border);">
                    &#9650; Suka ({{ $artikel->likes_count ?? 0 }})
                </button>
            </form>
            <form action="{{ route('artikel.dislike', $artikel->id) }}" method="POST" class="inline">
                @csrf
                <button type="submit" title="Tidak suka" class="px-3 py-1.5 rounded border hover:opacity-80 {{ $detailDislikeClass }}" style="background-color: var(--bg-surface); border-color: var(--border);">
                    &#9660; Tidak suka ({{ $artikel->dislikes_count ?? 0 }})
                </button>
            </form>
            @if ($userReaction)
                <form action="{{ route('artikel.reaction.destroy', $artikel->id) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="hover:underline" style="color: var(--text-muted);">
                        [Batalkan reaksi]
                    </button>
                </form>
            @endif
        @else
            <span class="{{ $detailLikeClass }}">&#9650; Suka ({{ $artikel->likes_count ?? 0 }})</span>
            <span class="{{ $detailDislikeClass }}">&#9660; Tidak suka ({{ $artikel->dislikes_count ?? 0 }})</span>
            <a href="{{ route('login') }}" class="hover:underline" style="color: var(--accent);">Login untuk memberi reaksi</a>
        @endauth
    </section>

    <!-- Comments -->
    <section id="komentar" class="mt-4 pt-8 border-t" style="border-color: var(--border);">
        <h2 class="text-xl font-bold">
            Komentar <span class="text-xs font-mono font-normal" style="color: var(--text-muted);">({{ $komentars->total() }})</span>
        </h2>

        @if (session('success'))
            <p class="mt-3 p-3 rounded border font-mono text-xs" style="background-color: var(--bg-surface); border-color: var(--accent); color: var(--accent);">
                {{ session('success') }}
            </p>
        @endif

        @auth
            <form action="{{ route('komentar.store', $artikel->id) }}" method="POST" class="mt-6">
                @csrf
                <label for="body" class="block text-xs font-mono uppercase tracking-wider font-semibold mb-2" style="color: var(--text-main);">
                    Tulis Komentar
                </label>
                <textarea
                    name="body"
                    id="body"
                    rows="3"
                    maxlength="1000"
                    placeholder="Tulis komentar Anda di sini..."
                    class="w-full text-sm p-3 rounded border focus:outline-none focus:ring-1 resize-y"
                    style="background-color: var(--bg-surface); border-color: var(--border); color: var(--text-main); --tw-ring-color: var(--accent);"
                    required
                >{{ old('body') }}</textarea>
                @error('body')
                    <p class="text-xs font-mono mt-1.5" style="color: var(--danger);">{{ $message }}</p>
                @enderror
                <button
                    type="submit"
                    class="mt-3 px-5 py-2.5 rounded font-mono text-xs font-bold transition-transform active:scale-95 hover:opacity-90"
                    style="background-color: var(--accent); color: var(--bg-main);"
                >
                    Kirim Komentar
                </button>
            </form>
        @else
            <p class="mt-6 font-mono text-xs" style="color: var(--text-muted);">
                <a href="{{ route('login') }}" class="hover:underline font-bold" style="color: var(--accent);">Login</a>
                untuk menulis komentar.
            </p>
        @endauth

        <div class="mt-8 space-y-4">
            @forelse ($komentars as $komentar)
                <div class="p-4 rounded border" style="background-color: var(--bg-surface); border-color: var(--border);">
                    <div class="flex items-center justify-between gap-3 font-mono text-xs" style="color: var(--text-muted);">
                        <span class="font-semibold" style="color: var(--text-main);">
                            {{ $komentar->user?->name ?? 'Pengguna' }}
                        </span>
                        <span>{{ $komentar->created_at ? $komentar->created_at->diffForHumans() : '' }}</span>
                    </div>
                    <div class="comment-body mt-2 text-sm leading-relaxed">
                        {!! $komentar->body_html !!}
                    </div>
                    @if (auth()->id() === $komentar->user_id || (bool) (auth()->user()?->is_admin ?? false))
                        <form action="{{ route('komentar.destroy', $komentar->id) }}" method="POST" onsubmit="return confirm('Hapus komentar ini?');" class="mt-2">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="font-mono text-xs hover:underline" style="color: var(--danger);">
                                [Hapus]
                            </button>
                        </form>
                    @endif
                </div>
            @empty
                <p class="py-8 text-center border border-dashed rounded font-mono text-xs" style="border-color: var(--border); color: var(--text-muted);">
                    // Belum ada komentar. Jadilah yang pertama!
                </p>
            @endforelse
        </div>

        @if ($komentars->hasPages())
            <div class="mt-6">
                {{ $komentars->links() }}
            </div>
        @endif
    </section>

    <!-- Post Footer -->
    <footer class="mt-12 pt-8 border-t flex flex-col sm:flex-row items-center justify-between gap-4 font-mono text-xs" style="border-color: var(--border); color: var(--text-muted);">
        <p>// End of file</p>
        <a href="{{ url('/') }}" class="hover:underline" style="color: var(--accent);">
            Back to top &uarr;
        </a>
    </footer>
</div>

<!-- Scoped Styling to handle raw rich text / HTML output seamlessly across themes -->
<style>
    .reaction-off { color: var(--text-muted); }
    .reaction-liked { color: var(--accent); font-weight: 700; }
    .reaction-disliked { color: var(--danger); font-weight: 700; }
    .comment-body {
        color: var(--text-main);
    }
    .comment-body p {
        margin-bottom: 0.75rem;
        line-height: 1.7;
    }
    .comment-body p:last-child {
        margin-bottom: 0;
    }
    .comment-body a {
        color: var(--accent);
        text-decoration: underline;
    }
    .comment-body code {
        font-family: 'JetBrains Mono', monospace;
        background-color: var(--bg-surface-alt);
        border: 1px solid var(--border);
        border-radius: 4px;
        padding: 0.15rem 0.35rem;
        font-size: 0.875em;
    }
    .comment-body pre {
        background-color: var(--bg-main);
        border: 1px solid var(--border);
        border-radius: 6px;
        padding: 0.75rem;
        overflow-x: auto;
        margin-bottom: 0.75rem;
        font-family: 'JetBrains Mono', monospace;
        font-size: 0.875em;
    }
    .comment-body pre code {
        background: transparent;
        border: none;
        padding: 0;
    }
    .comment-body ul {
        list-style-type: disc;
        padding-left: 1.5rem;
        margin-bottom: 0.75rem;
    }
    .comment-body ol {
        list-style-type: decimal;
        padding-left: 1.5rem;
        margin-bottom: 0.75rem;
    }
    .comment-body blockquote {
        border-left: 3px solid var(--accent);
        padding-left: 0.75rem;
        font-style: italic;
        color: var(--text-muted);
        margin: 0.75rem 0;
    }
    .article-content {
        color: var(--text-main);
    }
    .article-content p {
        margin-bottom: 1.5rem;
        line-height: 1.8;
    }
    .article-content h1, 
    .article-content h2, 
    .article-content h3 {
        font-weight: 700;
        margin-top: 2rem;
        margin-bottom: 1rem;
        color: var(--text-main);
    }
    .article-content h1 { font-size: 1.875rem; }
    .article-content h2 { font-size: 1.5rem; }
    .article-content h3 { font-size: 1.25rem; }
    .article-content a {
        color: var(--accent);
        text-decoration: underline;
    }
    .article-content ul {
        list-style-type: disc;
        padding-left: 1.5rem;
        margin-bottom: 1.5rem;
    }
    .article-content ol {
        list-style-type: decimal;
        padding-left: 1.5rem;
        margin-bottom: 1.5rem;
    }
    .article-content li {
        margin-bottom: 0.5rem;
    }
    .article-content blockquote {
        border-left: 3px solid var(--accent);
        padding-left: 1rem;
        font-style: italic;
        color: var(--text-muted);
        margin: 1.5rem 0;
    }
    .article-content pre, 
    .article-content code {
        font-family: 'JetBrains Mono', monospace;
        background-color: var(--bg-surface);
        border: 1px solid var(--border);
        border-radius: 4px;
    }
    .article-content code {
        padding: 0.2rem 0.4rem;
        font-size: 0.875em;
    }
    .article-content pre {
        padding: 1rem;
        overflow-x: auto;
        margin-bottom: 1.5rem;
    }
    .article-content pre code {
        background: transparent;
        border: none;
        padding: 0;
    }
</style>
@endsection