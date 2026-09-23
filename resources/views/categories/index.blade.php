@extends('layouts.app')

@section('title', 'Categories Management')

@section('content')
<div class="flex min-h-screen">
    @include('admin._sidebar')

    <main class="flex-1 p-8">
        <div class="max-w-6xl mx-auto">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h1 class="text-3xl font-bold font-mono" style="color: var(--text-main);">Categories</h1>
                    <p class="mt-1 text-sm" style="color: var(--text-muted);">Manage article categories</p>
                </div>
                <a href="{{ route('admin.categories.create') }}"
                   class="px-4 py-2 rounded-lg text-sm font-medium transition-colors"
                   style="background-color: var(--accent); color: var(--bg-main);">
                    Create Category
                </a>
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

            <div class="rounded-xl border overflow-hidden" style="background-color: var(--bg-surface); border-color: var(--border);">
                <table class="w-full">
                    <thead>
                        <tr class="text-left text-sm font-medium" style="background-color: var(--bg-surface-alt); color: var(--text-muted); border-bottom: 1px solid var(--border);">
                            <th class="p-4">Name</th>
                            <th class="p-4">Slug</th>
                            <th class="p-4">Articles</th>
                            <th class="p-4">Created</th>
                            <th class="p-4 w-32">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y" style="border-color: var(--border);">
                        @forelse ($categories as $category)
                            <tr class="hover:bg-opacity-5 transition-colors" style="background-color: var(--bg-surface);">
                                <td class="p-4 font-medium" style="color: var(--text-main);">{{ $category->nama }}</td>
                                <td class="p-4 font-mono text-sm" style="color: var(--text-muted);">{{ $category->slug }}</td>
                                <td class="p-4" style="color: var(--text-main);">{{ $category->artikels_count }}</td>
                                <td class="p-4 text-sm" style="color: var(--text-muted);">{{ $category->created_at->format('M d, Y') }}</td>
                                <td class="p-4">
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('admin.categories.edit', $category) }}"
                                           class="px-3 py-1.5 text-xs rounded font-medium transition-colors"
                                           style="background-color: var(--bg-surface-alt); color: var(--text-main); border: 1px solid var(--border);">
                                            Edit
                                        </a>
                                        <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Delete this category? Articles will be uncategorized.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="px-3 py-1.5 text-xs rounded font-medium transition-colors"
                                                    style="background-color: var(--danger); color: white; border: none;">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-8 text-center" style="color: var(--text-muted);">
                                    No categories yet. <a href="{{ route('admin.categories.create') }}" class="underline" style="color: var(--accent);">Create one</a>.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $categories->links() }}
        </div>
    </main>
</div>
@endsection