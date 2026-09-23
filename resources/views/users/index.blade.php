@extends('layouts.app')

@section('title', 'Users Management')

@section('content')
<div class="flex min-h-screen">
    @include('admin._sidebar')

    <main class="flex-1 p-8">
        <div class="max-w-6xl mx-auto">
            <div class="mb-8">
                <h1 class="text-3xl font-bold font-mono" style="color: var(--text-main);">Users Management</h1>
                <p class="mt-1 text-sm" style="color: var(--text-muted);">View and manage user accounts</p>
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
            <form method="GET" action="{{ route('admin.users.index') }}" class="mb-6">
                <div class="flex items-center space-x-3">
                    <input type="text"
                           name="q"
                           value="{{ request('q') }}"
                           placeholder="Search users by name or email..."
                           class="flex-1 max-w-md px-4 py-2.5 rounded-lg border text-sm transition-colors"
                           style="background-color: var(--bg-main); border-color: var(--border); color: var(--text-main);">
                    @if (request('q'))
                        <a href="{{ route('admin.users.index') }}"
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
                            <th class="p-4">Name</th>
                            <th class="p-4">Email</th>
                            <th class="p-4">Admin</th>
                            <th class="p-4">Articles</th>
                            <th class="p-4">Comments</th>
                            <th class="p-4">Joined</th>
                            <th class="p-4 w-40">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y" style="border-color: var(--border);">
                        @forelse ($users as $user)
                            <tr class="hover:bg-opacity-5 transition-colors" style="background-color: var(--bg-surface);">
                                <td class="p-4 font-medium" style="color: var(--text-main);">{{ $user->name }}</td>
                                <td class="p-4 text-sm" style="color: var(--text-muted);">{{ $user->email }}</td>
                                <td class="p-4">
                                    @if ($user->is_admin)
                                        <span class="px-2 py-1 text-xs rounded-full font-medium" style="background-color: var(--accent); color: var(--bg-main);">Admin</span>
                                    @else
                                        <span class="px-2 py-1 text-xs rounded-full font-medium" style="background-color: var(--bg-surface-alt); color: var(--text-muted);">User</span>
                                    @endif
                                </td>
                                <td class="p-4 font-mono text-sm" style="color: var(--text-main);">{{ $user->artikels_count }}</td>
                                <td class="p-4 font-mono text-sm" style="color: var(--text-main);">{{ $user->comments_count }}</td>
                                <td class="p-4 text-sm" style="color: var(--text-muted);">{{ $user->created_at->format('M d, Y') }}</td>
                                <td class="p-4">
                                    <div class="flex items-center space-x-2">
                                        <form action="{{ route('admin.users.toggle-admin', $user) }}" method="POST">
                                            @csrf
                                            <button type="submit"
                                                    class="px-3 py-1.5 text-xs rounded font-medium transition-colors"
                                                    style="{{ $user->is_admin
                                                            ? 'background-color: var(--warning); color: var(--bg-main);'
                                                            : 'background-color: var(--accent); color: var(--bg-main);' }}">
                                                {{ $user->is_admin ? 'Demote' : 'Promote' }}
                                            </button>
                                        </form>
                                        @if ($user->id !== auth()->id())
                                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Delete this user? This action cannot be undone.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="px-3 py-1.5 text-xs rounded font-medium transition-colors"
                                                        style="background-color: var(--danger); color: white; border: none;">
                                                    Delete
                                                </button>
                                            </form>
                                        @else
                                            <span class="px-3 py-1.5 text-xs rounded font-medium text-center" style="background-color: var(--bg-surface-alt); color: var(--text-muted);">Self</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="p-8 text-center" style="color: var(--text-muted);">No users found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $users->links() }}
        </div>
    </main>
</div>
@endsection