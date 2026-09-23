@extends('layouts.app')

@section('title', 'Create Category')

@section('content')
<div class="flex min-h-screen">
    @include('admin._sidebar')

    <main class="flex-1 p-8">
        <div class="max-w-2xl mx-auto">
            <div class="mb-8">
                <h1 class="text-3xl font-bold font-mono" style="color: var(--text-main);">Create Category</h1>
                <p class="mt-1 text-sm" style="color: var(--text-muted);">Add a new category for organizing articles</p>
            </div>

            @if ($errors->any())
                <div class="mb-6 p-4 rounded-lg text-sm" style="background-color: #7f1d1d; border: 1px solid #991b1b; color: #fca5a5;">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.categories.store') }}" method="POST" class="space-y-6">
                @csrf

                <div>
                    <label for="nama" class="block text-sm font-medium mb-1" style="color: var(--text-main);">Category Name</label>
                    <input type="text"
                           id="nama"
                           name="nama"
                           value="{{ old('nama') }}"
                           required
                           maxlength="100"
                           class="w-full px-4 py-2.5 rounded-lg border text-sm transition-colors
                                  @error('nama') border-red-500 @enderror"
                           style="background-color: var(--bg-main); border-color: var(--border); color: var(--text-main);"
                           placeholder="e.g., Technology, Tutorial, News">
                    @error('nama')
                        <p class="mt-1 text-sm" style="color: var(--danger);">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs" style="color: var(--text-muted);">Slug will be auto-generated from the name.</p>
                </div>

                <div class="flex items-center justify-end space-x-3 pt-4 border-t" style="border-color: var(--border);">
                    <a href="{{ route('admin.categories.index') }}"
                       class="px-4 py-2.5 rounded-lg text-sm font-medium transition-colors"
                       style="background-color: var(--bg-surface-alt); color: var(--text-main); border: 1px solid var(--border);">
                        Cancel
                    </a>
                    <button type="submit"
                            class="px-4 py-2.5 rounded-lg text-sm font-medium transition-colors"
                            style="background-color: var(--accent); color: var(--bg-main);">
                        Create Category
                    </button>
                </div>
            </form>
        </div>
    </main>
</div>
@endsection