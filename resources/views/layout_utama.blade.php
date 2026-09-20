<!DOCTYPE html>
<html>
<head>
    <title>My Website</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">
    
    <!-- NAVBAR (Persistent) -->
    <nav class="bg-blue-600 text-white p-4 font-bold">
        <ul class="flex space-x-4">
            <li><a href="./beranda" class="hover:underline">Home</a></li>
            <li><a href="./about" class="hover:underline">About</a></li>
            <li><a href="./kontak" class="hover:underline">Contact</a></li>
        </ul>
    </nav>

    <!-- THIS IS THE EMPTY SLOT! -->
    <!-- It will be filled in turn by its child views -->
    <main class="container mx-auto mt-5">
        @yield('lubang_konten')
    </main>

    <!-- FOOTER (Persistent) -->
    <footer class="text-center p-4 mt-10">Copyright © 2026</footer>
</body>
</html>
