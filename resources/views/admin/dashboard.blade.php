@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="flex min-h-screen">
    @include('admin._sidebar')

    <main class="flex-1 p-8">
        <div class="max-w-6xl mx-auto">
            <div class="mb-8">
                <h1 class="text-3xl font-bold font-mono" style="color: var(--text-main);">Admin Dashboard</h1>
                <p class="mt-1 text-sm" style="color: var(--text-muted);">Welcome to the management console</p>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="p-6 rounded-xl border" style="background-color: var(--bg-surface); border-color: var(--border);">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium" style="color: var(--text-muted);">Total Articles</p>
                            <p class="text-3xl font-bold font-mono mt-1" style="color: var(--text-main);">{{ \App\Models\Artikel::count() }}</p>
                        </div>
                        <div class="p-3 rounded-lg" style="background-color: var(--accent); color: var(--bg-main);">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 01-2-2V7m2 13a2 2 0 01-2-2V7" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="p-6 rounded-xl border" style="background-color: var(--bg-surface); border-color: var(--border);">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium" style="color: var(--text-muted);">Categories</p>
                            <p class="text-3xl font-bold font-mono mt-1" style="color: var(--text-main);">{{ \App\Models\Category::count() }}</p>
                        </div>
                        <div class="p-3 rounded-lg" style="background-color: var(--accent); color: var(--bg-main);">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="p-6 rounded-xl border" style="background-color: var(--bg-surface); border-color: var(--border);">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium" style="color: var(--text-muted);">Users</p>
                            <p class="text-3xl font-bold font-mono mt-1" style="color: var(--text-main);">{{ \App\Models\User::count() }}</p>
                        </div>
                        <div class="p-3 rounded-lg" style="background-color: var(--accent); color: var(--bg-main);">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="p-6 rounded-xl border" style="background-color: var(--bg-surface); border-color: var(--border);">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium" style="color: var(--text-muted);">Comments</p>
                            <p class="text-3xl font-bold font-mono mt-1" style="color: var(--text-main);">{{ \App\Models\Comment::count() }}</p>
                        </div>
                        <div class="p-3 rounded-lg" style="background-color: var(--accent); color: var(--bg-main);">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <a href="{{ route('admin.categories.create') }}" class="p-6 rounded-xl border hover:shadow-lg transition-shadow" style="background-color: var(--bg-surface); border-color: var(--border);">
                    <h3 class="text-lg font-semibold mb-2" style="color: var(--text-main);">Create Category</h3>
                    <p class="text-sm" style="color: var(--text-muted);">Add a new category for organizing articles</p>
                </a>

                <a href="{{ route('admin.users.index') }}" class="p-6 rounded-xl border hover:shadow-lg transition-shadow" style="background-color: var(--bg-surface); border-color: var(--border);">
                    <h3 class="text-lg font-semibold mb-2" style="color: var(--text-main);">Manage Users</h3>
                    <p class="text-sm" style="color: var(--text-muted);">View users, toggle admin status, delete accounts</p>
                </a>

                <a href="{{ route('admin.comments.index') }}" class="p-6 rounded-xl border hover:shadow-lg transition-shadow" style="background-color: var(--bg-surface); border-color: var(--border);">
                    <h3 class="text-lg font-semibold mb-2" style="color: var(--text-main);">Moderate Comments</h3>
                    <p class="text-sm" style="color: var(--text-muted);">Review and delete inappropriate comments</p>
                </a>

                <a href="{{ route('artikel.create') }}" class="p-6 rounded-xl border hover:shadow-lg transition-shadow" style="background-color: var(--bg-surface); border-color: var(--border);">
                    <h3 class="text-lg font-semibold mb-2" style="color: var(--text-main);">Write Article</h3>
                    <p class="text-sm" style="color: var(--text-muted);">Create a new article for the site</p>
                </a>
            </div>
        </div>
    </main>
</div>
@endsection