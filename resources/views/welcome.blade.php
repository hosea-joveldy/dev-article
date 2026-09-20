@extends('artikel_layout')

@section('title', 'Index — dev.logs')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 py-10">
    <!-- Top Utility Bar: Branding + New Entry Button -->
    <header class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 pb-8 border-b" style="border-color: var(--border);">
        <div>
            <div class="flex items-center space-x-2.5">
                <span class="inline-block w-2.5 h-2.5 rounded-full" style="background-color: var(--accent);"></span>
                <span class="text-xs uppercase tracking-widest font-mono font-semibold" style="color: var(--accent);">dev.logs // feed</span>
            </div>
            <h1 class="text-3xl sm:text-4xl font-black tracking-tight mt-2">Articles & Engineering Logs</h1>
            <p class="text-xs sm:text-sm font-mono mt-2" style="color: var(--text-muted);">
                A home for developers &bull; Architectural decisions, field notes, and deep dives.
            </p>
        </div>

        <div>
            <a href="{{ route('artikel.create') }}" class="inline-flex items-center text-xs font-semibold px-4 py-2.5 rounded font-mono tracking-wide transition-opacity hover:opacity-90" style="background-color: var(--accent); color: var(--bg-main);">
                [+ New Entry]
            </a>
        </div>
    </header>

    <!-- Instant Search & Sort Filter (Auto-Submitting, No redundant button) -->
    <section class="mt-8 mb-10">
        <form id="filterForm" method="GET" action="{{ url('/') }}" class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
            <!-- Search Input -->
            <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none" style="color: var(--text-muted);">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <input 
                    type="text" 
                    name="q" 
                    value="{{ request('q') }}"
                    placeholder="Search keywords or press enter..." 
                    class="w-full text-xs font-mono pl-10 pr-4 py-2.5 rounded border focus:outline-none focus:ring-1"
                    style="background-color: var(--bg-surface); border-color: var(--border); color: var(--text-main); --tw-ring-color: var(--accent);"
                >
            </div>

            <!-- Sort Option (Submits on Change) -->
            <div class="relative sm:w-48">
                <select 
                    name="sort" 
                    onchange="document.getElementById('filterForm').submit()"
                    class="w-full text-xs font-mono py-2.5 px-3 rounded border appearance-none pr-8 cursor-pointer focus:outline-none" 
                    style="background-color: var(--bg-surface); border-color: var(--border); color: var(--text-main);"
                >
                    <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Sort: Latest</option>
                    <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Sort: Oldest</option>
                    <option value="title_asc" {{ request('sort') == 'title_asc' ? 'selected' : '' }}>Sort: Title (A-Z)</option>
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2.5" style="color: var(--text-muted);">
                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </div>
            </div>

            <!-- Clear Active Filters -->
            @if(request()->hasAny(['q', 'sort']))
                <a href="{{ url('/') }}" class="text-xs font-mono flex items-center justify-center px-3 py-2 rounded border hover:opacity-80 transition" style="background-color: var(--bg-surface-alt); border-color: var(--border); color: var(--text-muted);">
                    [Reset]
                </a>
            @endif
        </form>
    </section>

    <!-- Content Grid (1 col mobile, 2 col tablet, 3 col desktop) -->
    <main class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse ($berita as $artikel)
            <article class="flex flex-col rounded overflow-hidden border transition-all duration-200 hover:-translate-y-1" style="background-color: var(--bg-surface); border-color: var(--border);">
                <!-- Media / Header Frame -->
                @if ($artikel->gambar)
                    <div class="h-44 w-full overflow-hidden bg-black/20">
                        <img src="{{ asset('storage/' . $artikel->gambar) }}" alt="{{ $artikel->judul }}" class="w-full h-full object-cover">
                    </div>
                @else
                    <div class="h-24 w-full flex items-center px-4 font-mono text-xs border-b" style="background-color: var(--bg-surface-alt); border-color: var(--border); color: var(--text-muted);">
                        // no_attachment
                    </div>
                @endif

                <!-- Article Body -->
                <div class="p-5 flex-1 flex flex-col justify-between">
                    <div>
                        <time class="font-mono text-xs" style="color: var(--text-muted);">
                            {{ $artikel->created_at ? $artikel->created_at->format('Y-m-d') : 'Draft' }}
                        </time>
                        
                        <h2 class="text-base font-bold mt-2 leading-snug hover:underline">
                            <a href="{{ route('artikel.show', $artikel->id) }}">
                                {{ $artikel->judul }}
                            </a>
                        </h2>

                        <p class="mt-3 text-xs leading-relaxed line-clamp-3" style="color: var(--text-muted);">
                            {{ Str::limit(strip_tags($artikel->konten_html), 120, '...') }}
                        </p>
                    </div>

                    <!-- Actions -->
                    <div class="mt-6 pt-4 border-t flex items-center justify-between text-xs font-mono" style="border-color: var(--border);">
                        <a href="{{ route('artikel.show', $artikel->id) }}" class="font-bold hover:underline" style="color: var(--accent);">
                            Read ->
                        </a>

                        <div class="flex items-center gap-3">
                            <a href="{{ route('artikel.edit', $artikel->id) }}" style="color: var(--warning);" class="hover:underline">
                                Edit
                            </a>
                            <form action="{{ route('artikel.destroy', $artikel->id) }}" method="POST" onsubmit="return confirm('Delete this record?');" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="color: var(--danger);" class="hover:underline">
                                    Del
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </article>
        @empty
            <div class="col-span-full py-16 text-center border border-dashed rounded font-mono text-xs" style="border-color: var(--border); color: var(--text-muted);">
                // No entries found matching the query.
            </div>
        @endforelse
    </main>

    <!-- Clean Compact Pagination -->
    @php
        $berita->appends(request()->query());
    @endphp

    @if ($berita->hasPages())
        <nav class="mt-12 pt-6 border-t flex flex-col sm:flex-row items-center justify-between gap-4 font-mono text-xs" style="border-color: var(--border);">
            <div class="order-2 sm:order-1 text-center sm:text-left" style="color: var(--text-muted);">
                <span>PAGE {{ $berita->currentPage() }} / {{ $berita->lastPage() }}</span>
                <span class="mx-1.5">&bull;</span>
                <span>{{ $berita->total() }} ENTRIES</span>
            </div>

            <div class="order-1 sm:order-2 flex items-center gap-2">
                @if ($berita->onFirstPage())
                    <span class="px-3 py-1.5 rounded border opacity-40 cursor-not-allowed select-none" style="background-color: var(--bg-surface); border-color: var(--border); color: var(--text-muted);">
                        &larr; Prev
                    </span>
                @else
                    <a href="{{ $berita->previousPageUrl() }}" class="px-3 py-1.5 rounded border transition-colors hover:opacity-80" style="background-color: var(--bg-surface); border-color: var(--border); color: var(--accent);">
                        &larr; Prev
                    </a>
                @endif

                @if ($berita->hasMorePages())
                    <a href="{{ $berita->nextPageUrl() }}" class="px-3 py-1.5 rounded border transition-colors hover:opacity-80" style="background-color: var(--bg-surface); border-color: var(--border); color: var(--accent);">
                        Next &rarr;
                    </a>
                @else
                    <span class="px-3 py-1.5 rounded border opacity-40 cursor-not-allowed select-none" style="background-color: var(--bg-surface); border-color: var(--border); color: var(--text-muted);">
                        Next &rarr;
                    </span>
                @endif
            </div>
        </nav>
    @endif
</div>
@endsection