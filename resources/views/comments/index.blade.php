<x-app-layout>
    <div class="flex min-h-[700px] border border-[#e5e5e5] rounded-lg overflow-hidden bg-white">
        @include('admin._sidebar')

        <main class="flex-1 p-8 sm:p-10">
            <div class="max-w-5xl">
                <div class="mb-8">
                    <h1 class="serif text-3xl font-normal text-[#191919]">Comments Moderation</h1>
                    <p class="mt-1 text-sm text-stone-500">Review and moderate user comments.</p>
                </div>

                @if (session('success'))
                    <div class="alert-success mb-6">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert-error mb-6">
                        {{ session('error') }}
                    </div>
                @endif

                <!-- Search -->
                <form method="GET" action="{{ route('admin.comments.index') }}" class="mb-6">
                    <div class="flex items-center gap-3">
                        <input type="text"
                               name="q"
                               value="{{ request('q') }}"
                               placeholder="Search comments, authors, or articles..."
                               class="input-ruang max-w-md">
                        @if (request('q'))
                            <a href="{{ route('admin.comments.index') }}" class="pill-outline text-xs">
                                Clear
                            </a>
                        @endif
                    </div>
                </form>

                <div class="border border-[#e5e5e5] rounded-lg overflow-hidden">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-[#f7f4ed] border-b border-[#e5e5e5] text-xs font-semibold text-stone-600">
                            <tr>
                                <th class="p-4">Comment</th>
                                <th class="p-4">Author</th>
                                <th class="p-4">Story</th>
                                <th class="p-4">Date</th>
                                <th class="p-4 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#e5e5e5]">
                            @forelse ($comments as $comment)
                                <tr class="hover:bg-stone-50 transition-colors">
                                    <td class="p-4 max-w-xs text-[#191919]">
                                        <p class="line-clamp-2 text-xs leading-relaxed">{{ $comment->body }}</p>
                                    </td>
                                    <td class="p-4">
                                        <div class="text-xs">
                                            <p class="font-medium text-[#191919]">{{ $comment->user?->name ?? 'Reader' }}</p>
                                            <p class="text-stone-400">{{ $comment->user?->email ?? '' }}</p>
                                        </div>
                                    </td>
                                    <td class="p-4 max-w-xs">
                                        <a href="{{ route('artikel.show', $comment->artikel_id) }}" class="underline text-xs text-stone-700 hover:text-stone-900 truncate block">
                                            {{ $comment->artikel?->judul ?? 'Story #' . $comment->artikel_id }}
                                        </a>
                                    </td>
                                    <td class="p-4 text-stone-400 text-xs whitespace-nowrap">{{ $comment->created_at->format('M d, Y') }}</td>
                                    <td class="p-4 text-right">
                                        <form action="{{ route('admin.comments.destroy', $comment) }}" method="POST" onsubmit="return confirm('Delete this comment?');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="pill-outline text-xs text-red-600 hover:text-red-800" style="padding: 6px 12px;">
                                                Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="p-8 text-center text-stone-400 italic">No comments found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($comments->hasPages())
                    <div class="mt-6">
                        {{ $comments->links() }}
                    </div>
                @endif
            </div>
        </main>
    </div>
</x-app-layout>