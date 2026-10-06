<x-app-layout>
    <div class="flex min-h-[700px] border border-[#e5e5e5] rounded-lg overflow-hidden bg-white">
        @include('admin._sidebar')

        <main class="flex-1 p-8 sm:p-10">
            <div class="max-w-5xl">
                <div class="mb-8">
                    <h1 class="serif text-3xl font-normal text-[#191919]">Users Management</h1>
                    <p class="mt-1 text-sm text-stone-500">Manage reader and author accounts.</p>
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
                <form method="GET" action="{{ route('admin.users.index') }}" class="mb-6">
                    <div class="flex items-center gap-3">
                        <input type="text"
                               name="q"
                               value="{{ request('q') }}"
                               placeholder="Search users by name or email..."
                               class="input-ruang max-w-md">
                        @if (request('q'))
                            <a href="{{ route('admin.users.index') }}" class="pill-outline text-xs">
                                Clear
                            </a>
                        @endif
                    </div>
                </form>

                <div class="border border-[#e5e5e5] rounded-lg overflow-hidden">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-[#f7f4ed] border-b border-[#e5e5e5] text-xs font-semibold text-stone-600">
                            <tr>
                                <th class="p-4">Name</th>
                                <th class="p-4">Email</th>
                                <th class="p-4">Role</th>
                                <th class="p-4">Articles</th>
                                <th class="p-4">Comments</th>
                                <th class="p-4">Joined</th>
                                <th class="p-4 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#e5e5e5]">
                            @forelse ($users as $user)
                                <tr class="hover:bg-stone-50 transition-colors">
                                    <td class="p-4 font-medium text-[#191919]">{{ $user->name }}</td>
                                    <td class="p-4 text-stone-500">{{ $user->email }}</td>
                                    <td class="p-4">
                                        @if ($user->is_admin)
                                            <span class="px-2.5 py-0.5 text-xs rounded-full bg-stone-900 text-white font-medium">Admin</span>
                                        @else
                                            <span class="px-2.5 py-0.5 text-xs rounded-full bg-stone-100 text-stone-600 font-medium">User</span>
                                        @endif
                                    </td>
                                    <td class="p-4 text-stone-700">{{ $user->artikels_count }}</td>
                                    <td class="p-4 text-stone-700">{{ $user->comments_count }}</td>
                                    <td class="p-4 text-stone-400 text-xs">{{ $user->created_at->format('M d, Y') }}</td>
                                    <td class="p-4 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <form action="{{ route('admin.users.toggle-admin', $user) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="pill-outline text-xs" style="padding: 6px 12px;">
                                                    {{ $user->is_admin ? 'Demote' : 'Promote' }}
                                                </button>
                                            </form>
                                            @if ($user->id !== auth()->id())
                                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Delete this user account?');" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="pill-outline text-xs text-red-600 hover:text-red-800" style="padding: 6px 12px;">
                                                        Delete
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="p-8 text-center text-stone-400 italic">No users found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($users->hasPages())
                    <div class="mt-6">
                        {{ $users->links() }}
                    </div>
                @endif
            </div>
        </main>
    </div>
</x-app-layout>