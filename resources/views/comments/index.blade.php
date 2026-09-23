@extends('layouts.app')

@section('title', 'Comments Moderation')

@section('content')
<div class="flex min-h-screen">
    @include('admin._sidebar')

    <main class="flex-1 p-8">
        <div class="max-w-6xl mx-auto">
            <div class="mb-8">
                <h1 class="text-3xl font-bold font-mono" style="color: var(--text-main);">Comments Moderation</h1>
                <p class="mt-1 text-sm" style="color: var(--text-muted);">Review and manage all comments</p>
            </div>

            @if (session('success'))
                <div class="mb-6 p-4 rounded-lg text-sm" style="background-color: #064e3b; border: 1px solid #065f46; color: #a7f3d0;">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 p-4 rounded-lg text-sm" style="background-color: #7f1d1d; border: 1px solid #991b1b; color: #fca5a5;">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Search -->
            <form method="GET" action="{{ route('admin.comments.index') }}" class="mb-6">
                <div class="flex items-center space-x-3">
                    <input type="text"
                           name="q"
                           value="{{ request('q') }}"
                           placeholder="Search comments, authors, or articles..."
                           class="flex-1 max-w-md px-4 py-2.5 rounded-lg border text-sm transition-colors"
                           style="background-color: var(--bg-main); border-color: var(--border); color: var(--text-main);">
                    @if (request('q'))
                        <a href="{{ route('admin.comments.index') }}"
                           class="px-4 py-2.5 rounded-lg text-sm font-medium transition-colors"
                           style="background-color: var(--bg-surface-alt); color: var(--text-main); border: 1px solid var(--border);">
                            Clear
                        </a>
                    @endif
                </div>
            </form>

            <div class="rounded-xl border overflow-hidden" style="background-color: var(--bg-surface); border-color: var(--border);">
                <table class="w-full">
                    <thead>
                        <tr class="text-left text-sm font-medium" style="background-color: var(--bg-surface-alt); color: var(--text-muted); border-bottom: 1px solid var(--border);">
                            <th class="p-4">Comment</th>
                            <th class="p-4">Author</th>
                            <th class="p-4">Article</th>
                            <th class="p-4">Date</th>
                            <th class="p-4 w-24">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y" style="border-color: var(--border);">
                        @forelse ($comments as $comment)
                            <tr class="hover:bg-opacity-5 transition-colors" style="background-color: var(--bg-surface);">
                                <td class="p-4 max-w-xs truncate" style="color: var(--text-main);">
                                    {{ Str::limit($comment->body, 100) }}
                                </td>
                                <td class="p-4">
                                    <div>
                                        <p class="font-medium" style="color: var(--text-main);">{{ $comment->user->name }}</p>
                                        <p class="text-xs font-mono" style="color: var(--text-muted);">{{ $comment->user->email }}</p>
                                        @if ($comment->user->is_admin)
                                            <span class="px-1.5 py-0.5 text-xs rounded font-medium" style="background-color: var(--accent); color: var(--bg-main);">Admin</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="p-4">
                                    <a href="{{ route('artikel.show', $comment->artikel) }}"
                                       class="font-medium hover:underline truncate block max-w-xs"
                                       style="color: var(--accent);">
                                        {{ Str::limit($comment->artikel->judul, 50) }}
                                    </a>
                                </td>
                                <td class="p-4 text-sm" style="color: var(--text-muted);">{{ $comment->created_at->format('M d, Y H:i') }}</td>
                                <td class="p-4">
                                    <form action="{{ route('admin.comments.destroy', $comment) }}" method="POST" onsubmit="return confirm('Delete this comment?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="px-3 py-1.5 text-xs rounded font-medium transition-colors w-full"
                                                style="background-color: var(--danger); color: white; border: none;">
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-8 text-center" style="color: var(--text-muted);">No comments found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $comments->links() }}
        </div>
    </main>
</div>
@endsection