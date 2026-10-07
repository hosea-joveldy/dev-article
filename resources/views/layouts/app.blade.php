<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Ruang') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white min-h-screen flex flex-col justify-between text-[#242424]">
    <header class="topbar">
        <div class="container nav">
            <a class="brand" href="{{ route('home') }}">Ruang.</a>
            <nav class="navlinks">
                <a href="{{ route('artikel.index') }}">Explore</a>
                <a href="{{ route('artikel.create') }}">Write</a>
                @if(auth()->user()?->is_admin)
                    <a href="{{ route('admin.dashboard') }}">Admin</a>
                @endif
                <a href="{{ route('profile.edit') }}">Profile</a>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="pill">Log out</button>
                </form>
            </nav>
        </div>
    </header>

    @isset($header)
        <div class="border-b border-[#e5e5e5] bg-[#f7f4ed] py-8">
            <div class="container">
                {{ $header }}
            </div>
        </div>
    @endisset

    <main class="flex-1 py-10">
        <div class="container">
            {{ $slot }}
        </div>
    </main>

    <footer class="footer">
        <div class="container footer-inner">
            <span>© 2026 Ruang Editorial</span>
            <div class="footer-links">
                <a href="{{ route('feed') }}" target="_blank">RSS Feed</a>
                <a href="{{ route('sitemap') }}" target="_blank">Sitemap</a>
                <a href="#">Privacy</a>
                <a href="#">Contact</a>
            </div>
        </div>
    </footer>
</body>
</html>
