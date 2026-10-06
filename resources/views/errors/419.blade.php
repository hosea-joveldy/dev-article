<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Ruang — 419 Page Expired</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white min-h-screen flex flex-col justify-between text-[#242424]">
<header class="topbar">
    <div class="container nav">
        <a class="brand" href="{{ route('home') }}">Ruang.</a>
        <nav class="navlinks">
            <a href="{{ route('artikel.index') }}" class="pill">Back to stories</a>
        </nav>
    </div>
</header>

<main class="container my-20 flex-1 flex flex-col items-center justify-center text-center">
    <div class="eyebrow">419 — SESSION EXPIRED</div>
    <h1 class="serif text-5xl sm:text-6xl mb-4 font-normal">Page session expired.</h1>
    <p class="text-stone-500 max-w-md mb-8">Sesi Anda telah berakhir. Silakan muat ulang halaman ini dan coba kembali.</p>
    <a href="{{ url()->previous() ?: route('home') }}" class="pill">Reload page</a>
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
