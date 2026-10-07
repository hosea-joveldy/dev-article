<x-app-layout>
    <div class="flex min-h-[700px] border border-[#e5e5e5] rounded-lg overflow-hidden bg-white">
        @include('admin._sidebar')

        <main class="flex-1 p-8 sm:p-10">
            <div class="max-w-5xl">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h1 class="serif text-3xl font-normal text-[#191919]">Categories</h1>
                        <p class="mt-1 text-sm text-stone-500">Manage categories used for story categorization.</p>
                    </div>
                    <a href="{{ route('admin.categories.create') }}" class="pill text-xs">
                        + New Category
                    </a>
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

                <div class="border border-[#e5e5e5] rounded-lg overflow-hidden">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-[#f7f4ed] border-b border-[#e5e5e5] text-xs font-semibold text-stone-600">
                            <tr>
                                <th class="p-4">Name</th>
                                <th class="p-4">Slug</th>
                                <th class="p-4">Articles</th>
                                <th class="p-4">Created</th>
                                <th class="p-4 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#e5e5e5]">
                            @forelse ($categories as $category)
                                <tr class="hover:bg-stone-50 transition-colors">
                                    <td class="p-4 font-medium text-[#191919]">{{ $category->nama }}</td>
                                    <td class="p-4 text-stone-500">{{ $category->slug }}</td>
                                    <td class="p-4 text-stone-700">{{ $category->artikels_count }}</td>
                                    <td class="p-4 text-stone-400 text-xs">{{ $category->created_at->format('M d, Y') }}</td>
                                    <td class="p-4 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('admin.categories.edit', $category) }}" class="pill-outline text-xs" style="padding: 6px 12px;">
                                                Edit
                                            </a>
                                            <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Delete this category? Articles will be uncategorized.');" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="pill-outline text-xs text-red-600 hover:text-red-800" style="padding: 6px 12px;">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="p-8 text-center text-stone-400 italic">
                                        No categories yet. <a href="{{ route('admin.categories.create') }}" class="underline text-stone-700">Create one</a>.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($categories->hasPages())
                    <div class="mt-6">
                        {{ $categories->links() }}
                    </div>
                @endif
            </div>
        </main>
    </div>
</x-app-layout>