<!DOCTYPE html>
<html>
<head>
    <title>My Website</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 flex min-h-screen">
    
    <!-- NAVBAR (Persistent) -->
    <nav class="bg-blue-600 text-white p-4 font-bold">
        <ul class="flex space-x-4">
            <li><a href="./beranda" class="hover:underline">Home</a></li>
            <li><a href="./about" class="hover:underline">About</a></li>
            <li><a href="./kontak" class="hover:underline">Contact</a></li>
        </ul>
    </nav>

    <!-- Admin Sidebar (only for admin users) -->
    @auth
        @if (auth()->user()->is_admin)
            <aside class="hidden lg:block w-64 flex-shrink-0 bg-gray-800 text-white">
                <div class="p-4 border-b border-gray-700">
                    <a href="{{ route('admin.dashboard') }}" class="font-mono text-sm font-bold tracking-tight">Admin Panel</a>
                    <p class="mt-1 text-xs text-gray-400">Quick Access</p>
                </div>
                <nav class="p-3 space-y-1">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg text-sm font-medium hover:bg-gray-700 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" /></svg>
                        <span>Dashboard</span>
                    </a>
                    <a href="{{ route('admin.categories.index') }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg text-sm font-medium hover:bg-gray-700 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" /></svg>
                        <span>Categories</span>
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg text-sm font-medium hover:bg-gray-700 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                        <span>Users</span>
                    </a>
                    <a href="{{ route('admin.comments.index') }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg text-sm font-medium hover:bg-gray-700 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" /></svg>
                        <span>Comments</span>
                    </a>
                </nav>
            </aside>
        @endif
    @endauth

    <!-- THIS IS THE EMPTY SLOT! -->
    <!-- It will be filled in turn by its child views -->
    <main class="flex-1 container mx-auto mt-5 p-4">
        @yield('lubang_konten')
    </main>

    <!-- FOOTER (Persistent) -->
    <footer class="text-center p-4 mt-10 text-gray-500">Copyright &copy; 2026</footer>
</body>
</html>