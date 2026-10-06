<aside x-data="{ open: false }" class="hidden lg:block w-64 flex-shrink-0 bg-white border-r border-[#e5e5e5]">
    <div class="flex flex-col h-full">
        <!-- Admin Brand -->
        <div class="p-6 border-b border-[#e5e5e5]">
            <a href="{{ route('admin.dashboard') }}" class="font-serif text-xl font-bold text-[#191919]">
                Ruang Admin
            </a>
            <p class="mt-0.5 text-xs text-stone-500">Editorial Console</p>
        </div>

        <!-- Navigation Links -->
        <nav class="flex-1 p-4 space-y-1 overflow-y-auto">
            <!-- Dashboard -->
            <a href="{{ route('admin.dashboard') }}"
               class="flex items-center space-x-3 px-3.5 py-2.5 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-stone-100 text-[#191919] font-bold' : 'text-stone-600 hover:bg-stone-50' }}">
                <span>Overview</span>
            </a>

            <!-- Articles -->
            <a href="{{ route('artikel.index') }}"
               class="flex items-center space-x-3 px-3.5 py-2.5 rounded-md text-sm font-medium transition-colors text-stone-600 hover:bg-stone-50">
                <span>View Articles</span>
            </a>

            <!-- Categories -->
            <a href="{{ route('admin.categories.index') }}"
               class="flex items-center space-x-3 px-3.5 py-2.5 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('admin.categories.*') ? 'bg-stone-100 text-[#191919] font-bold' : 'text-stone-600 hover:bg-stone-50' }}">
                <span>Categories</span>
            </a>

            <!-- Users -->
            <a href="{{ route('admin.users.index') }}"
               class="flex items-center space-x-3 px-3.5 py-2.5 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('admin.users.*') ? 'bg-stone-100 text-[#191919] font-bold' : 'text-stone-600 hover:bg-stone-50' }}">
                <span>Users</span>
            </a>

            <!-- Comments Moderation -->
            <a href="{{ route('admin.comments.index') }}"
               class="flex items-center space-x-3 px-3.5 py-2.5 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('admin.comments.*') ? 'bg-stone-100 text-[#191919] font-bold' : 'text-stone-600 hover:bg-stone-50' }}">
                <span>Comments</span>
            </a>
        </nav>

        <!-- Footer -->
        <div class="p-4 border-t border-[#e5e5e5]">
            <a href="{{ route('artikel.index') }}" class="text-xs text-stone-500 block text-center hover:underline">
                ← Back to Stories
            </a>
        </div>
    </div>
</aside>