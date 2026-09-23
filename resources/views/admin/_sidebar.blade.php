<aside x-data="{ open: false }" class="hidden lg:block w-64 flex-shrink-0" style="background-color: var(--bg-surface); border-right: 1px solid var(--border);">
    <div class="flex flex-col h-full">
        <!-- Admin Brand -->
        <div class="p-4 border-b" style="border-color: var(--border);">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-2 font-mono text-sm font-bold tracking-tight">
                <span class="w-2.5 h-2.5 rounded-sm inline-block" style="background-color: var(--accent);"></span>
                <span style="color: var(--text-main);">Admin Panel</span>
            </a>
            <p class="mt-1 text-xs font-mono" style="color: var(--text-muted);">Management Console</p>
        </div>

        <!-- Navigation Links -->
        <nav class="flex-1 p-3 space-y-1 overflow-y-auto">
            <!-- Dashboard -->
            <a href="{{ route('admin.dashboard') }}"
               class="flex items-center space-x-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors
                      {{ request()->routeIs('admin.dashboard')
                         ? 'bg-opacity-20'
                         : 'hover:bg-opacity-10' }}"
               style="{{ request()->routeIs('admin.dashboard')
                         ? 'background-color: var(--accent); color: var(--bg-main);'
                         : 'color: var(--text-main);' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                </svg>
                <span>Dashboard</span>
            </a>

            <!-- Articles -->
            <a href="{{ route('artikel.index') }}"
               class="flex items-center space-x-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors
                      {{ request()->routeIs('artikel.*') && !request()->routeIs('admin.*')
                         ? 'bg-opacity-20'
                         : 'hover:bg-opacity-10' }}"
               style="{{ request()->routeIs('artikel.*') && !request()->routeIs('admin.*')
                         ? 'background-color: var(--accent); color: var(--bg-main);'
                         : 'color: var(--text-main);' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 01-2-2V7m2 13a2 2 0 01-2-2V7" />
                </svg>
                <span>Articles</span>
            </a>

            <!-- Categories -->
            <a href="{{ route('admin.categories.index') }}"
               class="flex items-center space-x-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors
                      {{ request()->routeIs('admin.categories.*')
                         ? 'bg-opacity-20'
                         : 'hover:bg-opacity-10' }}"
               style="{{ request()->routeIs('admin.categories.*')
                         ? 'background-color: var(--accent); color: var(--bg-main);'
                         : 'color: var(--text-main);' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                </svg>
                <span>Categories</span>
            </a>

            <!-- Users -->
            <a href="{{ route('admin.users.index') }}"
               class="flex items-center space-x-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors
                      {{ request()->routeIs('admin.users.*')
                         ? 'bg-opacity-20'
                         : 'hover:bg-opacity-10' }}"
               style="{{ request()->routeIs('admin.users.*')
                         ? 'background-color: var(--accent); color: var(--bg-main);'
                         : 'color: var(--text-main);' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                <span>Users</span>
            </a>

            <!-- Comments Moderation -->
            <a href="{{ route('admin.comments.index') }}"
               class="flex items-center space-x-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors
                      {{ request()->routeIs('admin.comments.*')
                         ? 'bg-opacity-20'
                         : 'hover:bg-opacity-10' }}"
               style="{{ request()->routeIs('admin.comments.*')
                         ? 'background-color: var(--accent); color: var(--bg-main);'
                         : 'color: var(--text-main);' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                </svg>
                <span>Comments</span>
            </a>
        </nav>

        <!-- Footer -->
        <div class="p-3 border-t" style="border-color: var(--border);">
            <a href="{{ url('/') }}" class="text-xs font-mono block text-center hover:underline" style="color: var(--text-muted);">
                ← Back to Site
            </a>
        </div>
    </div>
</aside>

<!-- Mobile sidebar toggle button (for lg:hidden layouts) -->
<div class="lg:hidden fixed bottom-4 right-4 z-50">
    <button @click="open = !open" class="p-3 rounded-full shadow-lg transition-transform hover:scale-105" style="background-color: var(--accent); color: var(--bg-main);">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path :class="{'hidden': open, 'inline-flex': !open}" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            <path :class="{'hidden': !open, 'inline-flex': open}" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
    </button>
</div>

<!-- Mobile sidebar overlay -->
<div x-show="open" x-transition:enter="transition-opacity ease-linear duration-150"
     x-transition:leave="transition-opacity ease-linear duration-150"
     class="lg:hidden fixed inset-0 z-40 bg-black/50" style="display: none;" @click="open = false"></div>

<!-- Mobile sidebar panel -->
<aside x-show="open" x-transition:enter="transition transform ease-out duration-300"
       x-transition:leave="transition transform ease-in duration-200"
       x-cloak class="lg:hidden fixed inset-y-0 right-0 z-50 w-64 transform translate-x-full" style="background-color: var(--bg-surface); border-left: 1px solid var(--border); display: none;">
    <div class="flex flex-col h-full">
        <div class="p-4 border-b" style="border-color: var(--border);">
            <div class="flex items-center justify-between">
                <span class="font-mono text-sm font-bold tracking-tight" style="color: var(--text-main);">Admin Panel</span>
                <button @click="open = false" class="p-1 rounded hover:bg-opacity-10" style="color: var(--text-muted);">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
        <nav class="flex-1 p-3 space-y-1 overflow-y-auto">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg text-sm font-medium" style="color: var(--text-main);">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" /></svg>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('artikel.index') }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg text-sm font-medium" style="color: var(--text-main);">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 01-2-2V7m2 13a2 2 0 01-2-2V7" /></svg>
                <span>Articles</span>
            </a>
            <a href="{{ route('admin.categories.index') }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg text-sm font-medium" style="color: var(--text-main);">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" /></svg>
                <span>Categories</span>
            </a>
            <a href="{{ route('admin.users.index') }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg text-sm font-medium" style="color: var(--text-main);">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                <span>Users</span>
            </a>
            <a href="{{ route('admin.comments.index') }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg text-sm font-medium" style="color: var(--text-main);">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" /></svg>
                <span>Comments</span>
            </a>
        </nav>
    </div>
</aside>