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