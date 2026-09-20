<!DOCTYPE html>
<html>
<head>
    <title>Website Saya</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">
    
    <!-- NAVBAR (Tetap) -->
    <nav class="bg-blue-600 text-white p-4 font-bold">
        <ul class="flex space-x-4">
            <li><a href="./beranda" class="hover:underline">Home</a></li>
            <li><a href="./about" class="hover:underline">About</a></li>
            <li><a href="./kontak" class="hover:underline">Kontak</a></li>
        </ul>
    </nav>

    <!-- INI ADALAH LUBANG KOSONG! -->
    <!-- Nanti lubang ini akan diisi oleh anak-anaknya secara bergantian -->
    <main class="container mx-auto mt-5">
        @yield('lubang_konten')
    </main>

    <!-- FOOTER (Tetap) -->
    <footer class="text-center p-4 mt-10">Hak Cipta © 2026</footer>
</body>
</html>
