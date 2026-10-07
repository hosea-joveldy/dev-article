<x-app-layout>
    <div class="flex min-h-[700px] border border-[#e5e5e5] rounded-lg overflow-hidden bg-white">
        @include('admin._sidebar')

        <main class="flex-1 p-8 sm:p-10">
            <div class="max-w-5xl">
                <div class="mb-8">
                    <h1 class="serif text-3xl font-normal text-[#191919]">Admin Overview</h1>
                    <p class="mt-1 text-sm text-stone-500">Manage categories, accounts, and comments across Ruang.</p>
                </div>

                <!-- Stats Cards -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-10">
                    <div class="p-6 rounded-lg border border-[#e5e5e5] bg-[#f7f4ed]">
                        <p class="text-xs uppercase tracking-wider font-semibold text-stone-500">Total Stories</p>
                        <p class="serif text-3xl font-normal mt-2 text-[#191919]">{{ \App\Models\Artikel::count() }}</p>
                    </div>

                    <div class="p-6 rounded-lg border border-[#e5e5e5] bg-[#f7f4ed]">
                        <p class="text-xs uppercase tracking-wider font-semibold text-stone-500">Categories</p>
                        <p class="serif text-3xl font-normal mt-2 text-[#191919]">{{ \App\Models\Category::count() }}</p>
                    </div>

                    <div class="p-6 rounded-lg border border-[#e5e5e5] bg-[#f7f4ed]">
                        <p class="text-xs uppercase tracking-wider font-semibold text-stone-500">Users</p>
                        <p class="serif text-3xl font-normal mt-2 text-[#191919]">{{ \App\Models\User::count() }}</p>
                    </div>

                    <div class="p-6 rounded-lg border border-[#e5e5e5] bg-[#f7f4ed]">
                        <p class="text-xs uppercase tracking-wider font-semibold text-stone-500">Comments</p>
                        <p class="serif text-3xl font-normal mt-2 text-[#191919]">{{ \App\Models\Comment::count() }}</p>
                    </div>
                </div>

                <!-- Quick Actions -->
                <h2 class="serif text-xl font-normal mb-4 text-[#191919]">Quick Actions</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <a href="{{ route('admin.categories.create') }}" class="p-6 rounded-lg border border-[#e5e5e5] hover:border-stone-400 transition-colors">
                        <h3 class="font-semibold text-base mb-1 text-[#191919]">Create Category</h3>
                        <p class="text-xs text-stone-500">Add a new category for stories</p>
                    </a>

                    <a href="{{ route('admin.users.index') }}" class="p-6 rounded-lg border border-[#e5e5e5] hover:border-stone-400 transition-colors">
                        <h3 class="font-semibold text-base mb-1 text-[#191919]">Manage Users</h3>
                        <p class="text-xs text-stone-500">Review users, elevate to admin, or remove accounts</p>
                    </a>

                    <a href="{{ route('admin.comments.index') }}" class="p-6 rounded-lg border border-[#e5e5e5] hover:border-stone-400 transition-colors">
                        <h3 class="font-semibold text-base mb-1 text-[#191919]">Moderate Comments</h3>
                        <p class="text-xs text-stone-500">Review reader responses and manage feedback</p>
                    </a>

                    <a href="{{ route('artikel.create') }}" class="p-6 rounded-lg border border-[#e5e5e5] hover:border-stone-400 transition-colors">
                        <h3 class="font-semibold text-base mb-1 text-[#191919]">Write New Story</h3>
                        <p class="text-xs text-stone-500">Publish a new editorial article</p>
                    </a>
                </div>
            </div>
        </main>
    </div>
</x-app-layout>