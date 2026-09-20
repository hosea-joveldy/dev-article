<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Publication Portal')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Fonts: Inter + JetBrains Mono for intentional editorial hierarchy -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;900&family=JetBrains+Mono:wght@400;500;700&display=swap" rel="stylesheet">

    <!-- Theme Fallback / Styles (if not compiled via app.css) -->
    <style>
        :root[data-theme="nord"], :root {
            --bg-main: #2e3440;
            --bg-surface: #3b4252;
            --bg-surface-alt: #434c5e;
            --border: #4c566a;
            --text-main: #eceff4;
            --text-muted: #d8dee9;
            --accent: #88c0d0;
            --accent-hover: #81a1c1;
            --danger: #bf616a;
            --warning: #ebcb8b;
        }

        :root[data-theme="gruvbox"] {
            --bg-main: #282828;
            --bg-surface: #3c3836;
            --bg-surface-alt: #504945;
            --border: #665c54;
            --text-main: #ebdbb2;
            --text-muted: #a89984;
            --accent: #fe8019;
            --accent-hover: #d65d0e;
            --danger: #fb4934;
            --warning: #fabd2f;
        }

        :root[data-theme="catppuccin"] {
            --bg-main: #1e1e2e;
            --bg-surface: #24273a;
            --bg-surface-alt: #363a4f;
            --border: #494d64;
            --text-main: #cad3f5;
            --text-muted: #a5adcb;
            --accent: #cba6f7;
            --accent-hover: #b4befe;
            --danger: #f38ba8;
            --warning: #f9e2af;
        }

        :root[data-theme="dracula"] {
            --bg-main: #282a36;
            --bg-surface: #343746;
            --bg-surface-alt: #44475a;
            --border: #6272a4;
            --text-main: #f8f8f2;
            --text-muted: #bd93f9;
            --accent: #50fa7b;
            --accent-hover: #8be9fd;
            --danger: #ff5555;
            --warning: #ffb86c;
        }

        :root[data-theme="monokai"] {
            --bg-main: #272822;
            --bg-surface: #3e3d32;
            --bg-surface-alt: #49483e;
            --border: #75715e;
            --text-main: #f8f8f2;
            --text-muted: #a6e22e;
            --accent: #fd971f;
            --accent-hover: #e6db74;
            --danger: #f92672;
            --warning: #e6db74;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-main);
            color: var(--text-main);
            transition: background-color 0.2s ease, color 0.2s ease;
        }

        .font-mono {
            font-family: 'JetBrains Mono', monospace;
        }
    </style>

    <script>
        (function() {
            const savedTheme = localStorage.getItem('selected_theme') || 'nord';
            document.documentElement.setAttribute('data-theme', savedTheme);
        })();
    </script>
</head>
<body class="min-h-screen flex flex-col antialiased selection:bg-cyan-500 selection:text-black">

    <nav class="border-b backdrop-blur-md sticky top-0 z-50 bg-opacity-90" style="background-color: var(--bg-main); border-color: var(--border);">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 h-16 flex items-center justify-between">
            <div class="flex items-center space-x-6">
                <a href="{{ url('/') }}" class="flex items-center space-x-2 font-mono text-sm font-bold tracking-tight">
                    <span class="w-2.5 h-2.5 rounded-sm inline-block" style="background-color: var(--accent);"></span>
                    <span style="color: var(--text-main);">dev.logs</span>
                </a>
                
                <div class="hidden sm:flex items-center space-x-4 text-xs font-mono" style="color: var(--text-muted);">
                    <a href="{{ url('/') }}" class="hover:underline">/articles</a>
                    <a href="{{ route('artikel.create') }}" class="hover:underline">/drafts</a>
                </div>
            </div>

            <div class="flex items-center space-x-3">
                <label for="globalThemeSelect" class="text-xs font-mono hidden md:inline" style="color: var(--text-muted);">theme:</label>
                    <select id="globalThemeSelect" onchange="applyGlobalTheme(this.value)" class="text-xs font-mono py-1.5 px-2.5 rounded border appearance-none pr-7 cursor-pointer focus:outline-none" style="background-color: var(--bg-surface); border-color: var(--border); color: var(--text-main);">
                        <option value="nord">Nord</option>
                        <option value="tokyo-night">Tokyo Night</option>
                        <option value="catppuccin">Catppuccin Mocha</option>
                        <option value="gruvbox">Gruvbox</option>
                        <option value="dracula">Dracula</option>
                        <option value="ayu">Ayu Mirage</option>
                        <option value="one-dark">One Dark</option>
                        <option value="rose-pine">Rose Pine</option>
                        <option value="everforest">Everforest</option>
                        <option value="github-dark">GitHub Dark</option>
                        <option value="solarized">Solarized Dark</option>
                        <option value="monokai">Monokai</option>
                    </select>
            </div>
        </div>
    </nav>

    <div class="flex-1">
        @yield('content')
    </div>

    <footer class="border-t mt-auto py-8 text-xs font-mono text-center" style="border-color: var(--border); color: var(--text-muted);">
        <div class="max-w-6xl mx-auto px-4">
            <p>System operational &bull; Built with Laravel & Tailwind</p>
        </div>
    </footer>

    <script>
        function applyGlobalTheme(theme) {
            document.documentElement.setAttribute('data-theme', theme);
            localStorage.setItem('selected_theme', theme);

            const pageThemeSelect = document.getElementById('themeSelect');
            if (pageThemeSelect) pageThemeSelect.value = theme;
        }

        document.addEventListener('DOMContentLoaded', () => {
            const currentTheme = localStorage.getItem('selected_theme') || 'nord';
            const globalSelect = document.getElementById('globalThemeSelect');
            if (globalSelect) globalSelect.value = currentTheme;
        });
    </script>
</body>
</html>