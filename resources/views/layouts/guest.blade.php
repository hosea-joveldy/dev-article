<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Ruang') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#f7f4ed] min-h-screen flex flex-col justify-between text-[#242424]">
    <header class="topbar bg-white">
        <div class="container nav">
            <a class="brand" href="{{ route('home') }}">Ruang.</a>
            <nav class="navlinks">
                @if (Route::has('login') && !request()->routeIs('login'))
                    <a href="{{ route('login') }}" class="pill-outline">Sign in</a>
                @endif
                @if (Route::has('register') && !request()->routeIs('register'))
                    <a href="{{ route('register') }}" class="pill">Get started</a>
                @endif
            </nav>
        </div>
    </header>

    <main class="container my-12 flex-1 flex items-center justify-center">
        <div class="w-full max-w-md bg-white p-8 sm:p-10 border border-[#e5e5e5] rounded-lg shadow-sm">
            {{ $slot }}
        </div>
    </main>

    <footer class="footer">
        <div class="container footer-inner">
            <span>© 2026 Ruang Editorial</span>
            <div class="footer-links">
                <a href="#">About</a>
                <a href="#">Privacy</a>
                <a href="#">Contact</a>
            </div>
        </div>
    </footer>
</body>
</html>
