<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Ruang — Edit story</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .editor-shell {
            max-width: 860px;
            margin: 0 auto;
            padding: 40px 20px 100px;
        }
        .editor-top-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 32px;
            gap: 16px;
            flex-wrap: wrap;
        }
        .editor-category-select {
            border: 1px solid var(--line);
            background: var(--cream);
            padding: 8px 16px;
            border-radius: 999px;
            font-size: 13px;
            font-weight: 500;
            color: var(--ink);
            outline: none;
            cursor: pointer;
        }
        .editor-category-select:focus {
            border-color: var(--ink-strong);
        }
        .editor-title-input {
            width: 100%;
            border: none;
            background: transparent;
            font-family: Georgia, serif;
            font-size: clamp(34px, 5vw, 54px);
            line-height: 1.1;
            letter-spacing: -1.5px;
            color: var(--ink-strong);
            outline: none;
            padding: 0 0 16px 0;
            margin: 0 0 24px 0;
            border-bottom: 1px solid transparent;
            transition: border-color .2s;
        }
        .editor-title-input::placeholder {
            color: #ccc;
        }
        .editor-title-input:focus {
            border-bottom-color: var(--line);
        }
        .editor-cover-toggle {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            color: var(--muted);
            cursor: pointer;
            margin-bottom: 24px;
            user-select: none;
        }
        .editor-cover-toggle:hover {
            color: var(--ink);
        }
        .editor-cover-box {
            background: var(--soft);
            border: 1px dashed var(--line);
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 28px;
        }
        .editor-textarea {
            width: 100%;
            min-height: 480px;
            border: none;
            background: transparent;
            font-family: Inter, ui-sans-serif, system-ui, sans-serif;
            font-size: 18px;
            line-height: 1.8;
            color: #242424;
            outline: none;
            resize: vertical;
            padding: 10px 0;
            box-sizing: border-box;
        }
        .editor-textarea::placeholder {
            color: #a8a8a8;
            font-style: italic;
        }
        .editor-status-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-top: 1px solid var(--line);
            padding-top: 18px;
            margin-top: 36px;
            font-size: 12px;
            color: var(--muted);
        }
        .editor-status-stats {
            display: flex;
            align-items: center;
            gap: 16px;
        }
        .editor-status-hint {
            display: flex;
            align-items: center;
            gap: 6px;
            color: #777;
        }

        /* Sleek Right-Click Context Menu */
        .ruang-context-menu {
            position: fixed;
            z-index: 99999;
            background: #1e2024;
            border: 1px solid #2f343e;
            box-shadow: 0 16px 36px rgba(0, 0, 0, 0.45), 0 4px 12px rgba(0, 0, 0, 0.25);
            border-radius: 8px;
            min-width: 220px;
            padding: 6px;
            font-family: Inter, ui-sans-serif, system-ui, sans-serif;
            color: #e2e8f0;
            font-size: 13px;
            display: none;
            user-select: none;
        }
        .ruang-menu-item {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 7px 10px;
            border-radius: 5px;
            cursor: pointer;
            transition: background .12s, color .12s;
            color: #cbd5e1;
        }
        .ruang-menu-item:hover, .ruang-menu-item.active {
            background: #2e333d;
            color: #ffffff;
        }
        .ruang-menu-item-left {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .ruang-menu-item svg {
            width: 15px;
            height: 15px;
            stroke: currentColor;
            stroke-width: 2;
            fill: none;
            flex-shrink: 0;
        }
        .ruang-menu-divider {
            height: 1px;
            background: #2c3039;
            margin: 5px 0;
        }
        .ruang-submenu {
            position: absolute;
            top: -6px;
            left: 100%;
            margin-left: 6px;
            background: #1e2024;
            border: 1px solid #2f343e;
            box-shadow: 0 16px 36px rgba(0, 0, 0, 0.45);
            border-radius: 8px;
            min-width: 200px;
            padding: 6px;
            display: none;
            z-index: 100000;
        }
        .ruang-menu-item:hover > .ruang-submenu {
            display: block;
        }
        .ruang-submenu-check {
            font-size: 11px;
            color: #a0aec0;
        }
    </style>
</head>
<body>
<header class="topbar">
    <div class="container nav">
        <a class="brand" href="{{ route('home') }}">Ruang.</a>
        <nav class="navlinks">
            <a href="{{ route('artikel.index') }}">Explore</a>
            <a href="{{ route('artikel.show', $artikel->id) }}">View story</a>
            <a href="{{ route('artikel.index') }}" class="pill">Back to stories</a>
        </nav>
    </div>
</header>

<main class="editor-shell">
    <div class="mb-6">
        <h1 class="serif text-4xl mb-2 text-[#191919]">Edit story.</h1>
        <p class="text-stone-500 text-sm">Perbarui konten artikel dan simpan perubahan Anda.</p>
    </div>

    @if ($errors->any())
        <div class="alert-error mb-6">
            <p class="font-semibold mb-1">Please fix the following issues:</p>
            <ul class="list-disc list-inside text-xs">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('artikel.update', $artikel->id) }}" method="POST" enctype="multipart/form-data" id="storyForm">
        @csrf
        @method('PUT')

        <div class="editor-top-bar" style="justify-content: flex-end;">
            <div style="display:flex;align-items:center;gap:12px;">
                <a href="{{ route('artikel.show', $artikel->id) }}" class="pill-outline">Cancel</a>
                <button type="submit" class="pill">Save changes</button>
            </div>
        </div>

        <!-- Clean Title Input -->
        <div>
            <input
                type="text"
                name="judul"
                id="judul"
                value="{{ old('judul', $artikel->judul) }}"
                placeholder="Title"
                class="editor-title-input"
                required
            >
            @error('judul')
                <p class="text-xs text-red-600 mb-3">{{ $message }}</p>
            @enderror
        </div>

        <!-- Cover Image Section -->
        <details class="mb-4">
            <summary class="editor-cover-toggle">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
                Update featured cover image (optional)
            </summary>
            <div class="editor-cover-box">
                @if ($artikel->gambar)
                    <div class="mb-3 flex items-center gap-3">
                        <img src="{{ route('artikel.image', $artikel->id) }}" alt="{{ $artikel->judul }}" class="h-16 w-24 object-cover rounded border border-[#e5e5e5]">
                        <span class="text-xs text-stone-500">Current cover image</span>
                    </div>
                @endif
                <input
                    type="file"
                    name="gambar"
                    id="gambar"
                    accept="image/*"
                    class="form-input"
                >
                <div style="font-size:12px;color:var(--muted);margin-top:6px;">Leave blank to preserve current cover image. Upload JPG, PNG, or GIF up to 10MB.</div>
                @error('gambar')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
        </details>

        <!-- Markdown Textarea -->
        <div>
            <textarea
                name="konten"
                id="markdownEditor"
                class="editor-textarea"
                placeholder="Tell your story... (Supports raw Markdown; right-click for formatting)"
                required
            >{{ old('konten', $artikel->konten) }}</textarea>
            @error('konten')
                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Status Bar -->
        <div class="editor-status-bar">
            <div class="editor-status-stats">
                <span id="wordCount">0 words</span>
                <span>·</span>
                <span id="charCount">0 characters</span>
                <span>·</span>
                <span id="readingTime">1 min read</span>
            </div>
            <div class="editor-status-hint">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
                Right-click anywhere in editor for formatting
            </div>
        </div>
    </form>
</main>

<!-- Sleek Right-Click Context Menu -->
<div id="ruangContextMenu" class="ruang-context-menu" role="menu">
    <!-- Add Link -->
    <div class="ruang-menu-item" data-action="link">
        <div class="ruang-menu-item-left">
            <svg viewBox="0 0 24 24"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path></svg>
            <span>Add link</span>
        </div>
    </div>

    <!-- Add External Link -->
    <div class="ruang-menu-item" data-action="external-link">
        <div class="ruang-menu-item-left">
            <svg viewBox="0 0 24 24"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
            <span>Add external link</span>
        </div>
    </div>

    <div class="ruang-menu-divider"></div>

    <!-- Format Submenu -->
    <div class="ruang-menu-item">
        <div class="ruang-menu-item-left">
            <svg viewBox="0 0 24 24"><polyline points="4 7 4 4 20 4 20 7"></polyline><line x1="9" y1="20" x2="15" y2="20"></line><line x1="12" y1="4" x2="12" y2="20"></line></svg>
            <span>Format</span>
        </div>
        <span style="font-size:10px;opacity:.7;">›</span>

        <div class="ruang-submenu">
            <div class="ruang-menu-item" data-action="format-bold">
                <div class="ruang-menu-item-left">
                    <strong style="width:14px;text-align:center;">B</strong>
                    <span>Bold</span>
                </div>
                <span style="font-size:11px;color:#718096;">**text**</span>
            </div>
            <div class="ruang-menu-item" data-action="format-italic">
                <div class="ruang-menu-item-left">
                    <em style="width:14px;text-align:center;">I</em>
                    <span>Italic</span>
                </div>
                <span style="font-size:11px;color:#718096;">*text*</span>
            </div>
            <div class="ruang-menu-item" data-action="format-strike">
                <div class="ruang-menu-item-left">
                    <span style="width:14px;text-align:center;text-decoration:line-through;">S</span>
                    <span>Strikethrough</span>
                </div>
                <span style="font-size:11px;color:#718096;">~~text~~</span>
            </div>
            <div class="ruang-menu-item" data-action="format-code">
                <div class="ruang-menu-item-left">
                    <code style="font-size:12px;">&lt;&gt;</code>
                    <span>Inline Code</span>
                </div>
                <span style="font-size:11px;color:#718096;">`code`</span>
            </div>
            <div class="ruang-menu-item" data-action="format-codeblock">
                <div class="ruang-menu-item-left">
                    <svg viewBox="0 0 24 24"><polyline points="16 18 22 12 16 6"></polyline><polyline points="8 6 2 12 8 18"></polyline></svg>
                    <span>Code Block</span>
                </div>
                <span style="font-size:11px;color:#718096;">```</span>
            </div>
        </div>
    </div>

    <!-- Paragraph Submenu -->
    <div class="ruang-menu-item">
        <div class="ruang-menu-item-left">
            <svg viewBox="0 0 24 24"><path d="M13 4v16"></path><path d="M17 4v16"></path><path d="M19 4H9.5a4.5 4.5 0 0 0 0 9H13"></path></svg>
            <span>Paragraph</span>
        </div>
        <span style="font-size:10px;opacity:.7;">›</span>

        <div class="ruang-submenu">
            <div class="ruang-menu-item" data-action="para-bullet">
                <div class="ruang-menu-item-left">
                    <svg viewBox="0 0 24 24"><line x1="8" y1="6" x2="21" y2="6"></line><line x1="8" y1="12" x2="21" y2="12"></line><line x1="8" y1="18" x2="21" y2="18"></line><line x1="3" y1="6" x2="3.01" y2="6"></line><line x1="3" y1="12" x2="3.01" y2="12"></line><line x1="3" y1="18" x2="3.01" y2="18"></line></svg>
                    <span>Bullet list</span>
                </div>
            </div>
            <div class="ruang-menu-item" data-action="para-number">
                <div class="ruang-menu-item-left">
                    <svg viewBox="0 0 24 24"><line x1="10" y1="6" x2="21" y2="6"></line><line x1="10" y1="12" x2="21" y2="12"></line><line x1="10" y1="18" x2="21" y2="18"></line><path d="M4 6h1v4"></path><path d="M4 10h2"></path></svg>
                    <span>Numbered list</span>
                </div>
            </div>
            <div class="ruang-menu-item" data-action="para-task">
                <div class="ruang-menu-item-left">
                    <svg viewBox="0 0 24 24"><polyline points="9 11 12 14 22 4"></polyline><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path></svg>
                    <span>Task list</span>
                </div>
            </div>
            <div class="ruang-menu-divider"></div>
            <div class="ruang-menu-item" data-action="para-h1">
                <div class="ruang-menu-item-left"><span style="font-weight:700;font-size:12px;">H₁</span><span>Heading 1</span></div>
            </div>
            <div class="ruang-menu-item" data-action="para-h2">
                <div class="ruang-menu-item-left"><span style="font-weight:700;font-size:12px;">H₂</span><span>Heading 2</span></div>
            </div>
            <div class="ruang-menu-item" data-action="para-h3">
                <div class="ruang-menu-item-left"><span style="font-weight:700;font-size:12px;">H₃</span><span>Heading 3</span></div>
            </div>
            <div class="ruang-menu-item" data-action="para-h4">
                <div class="ruang-menu-item-left"><span style="font-weight:700;font-size:12px;">H₄</span><span>Heading 4</span></div>
            </div>
            <div class="ruang-menu-item" data-action="para-body">
                <div class="ruang-menu-item-left">
                    <svg viewBox="0 0 24 24"><line x1="4" y1="6" x2="20" y2="6"></line><line x1="4" y1="12" x2="20" y2="12"></line><line x1="4" y1="18" x2="20" y2="18"></line></svg>
                    <span>Body</span>
                </div>
                <span class="ruang-submenu-check">✓</span>
            </div>
            <div class="ruang-menu-item" data-action="para-quote">
                <div class="ruang-menu-item-left">
                    <span style="font-family:Georgia,serif;font-weight:bold;font-size:16px;">“</span>
                    <span>Quote</span>
                </div>
            </div>
        </div>
    </div>

    <div class="ruang-menu-divider"></div>

    <!-- Cut, Copy, Paste, Select All -->
    <div class="ruang-menu-item" data-action="cut">
        <div class="ruang-menu-item-left">
            <svg viewBox="0 0 24 24"><circle cx="6" cy="6" r="3"></circle><circle cx="6" cy="18" r="3"></circle><line x1="20" y1="4" x2="8.12" y2="15.88"></line><line x1="14.47" y1="14.48" x2="20" y2="20"></line><line x1="8.12" y1="8.12" x2="12" y2="12"></line></svg>
            <span>Cut</span>
        </div>
    </div>
    <div class="ruang-menu-item" data-action="copy">
        <div class="ruang-menu-item-left">
            <svg viewBox="0 0 24 24"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg>
            <span>Copy</span>
        </div>
    </div>
    <div class="ruang-menu-item" data-action="paste">
        <div class="ruang-menu-item-left">
            <svg viewBox="0 0 24 24"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path><rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect></svg>
            <span>Paste</span>
        </div>
    </div>
    <div class="ruang-menu-item" data-action="select-all">
        <div class="ruang-menu-item-left">
            <svg viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2" stroke-dasharray="3 3"></rect></svg>
            <span>Select all</span>
        </div>
    </div>
</div>

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

<script>
document.addEventListener('DOMContentLoaded', function() {
    const editor = document.getElementById('markdownEditor');
    const menu = document.getElementById('ruangContextMenu');
    const wordCount = document.getElementById('wordCount');
    const charCount = document.getElementById('charCount');
    const readingTime = document.getElementById('readingTime');

    if (!editor) return;

    // Word and character counting
    function updateStats() {
        const text = editor.value || '';
        const words = text.trim() ? text.trim().split(/\s+/).length : 0;
        const chars = text.length;
        const minutes = Math.max(1, Math.ceil(words / 200));

        if (wordCount) wordCount.textContent = words + (words === 1 ? ' word' : ' words');
        if (charCount) charCount.textContent = chars + (chars === 1 ? ' character' : ' characters');
        if (readingTime) readingTime.textContent = minutes + ' min read';
    }

    editor.addEventListener('input', updateStats);
    updateStats();

    // Tab key indent support
    editor.addEventListener('keydown', function(e) {
        if (e.key === 'Tab') {
            e.preventDefault();
            const start = this.selectionStart;
            const end = this.selectionEnd;
            this.value = this.value.substring(0, start) + '  ' + this.value.substring(end);
            this.selectionStart = this.selectionEnd = start + 2;
            updateStats();
        }
    });

    // Right-click context menu
    editor.addEventListener('contextmenu', function(e) {
        e.preventDefault();
        menu.style.display = 'block';

        const menuWidth = 230;
        const menuHeight = 310;
        let x = e.clientX;
        let y = e.clientY;

        if (x + menuWidth > window.innerWidth) x = window.innerWidth - menuWidth - 10;
        if (y + menuHeight > window.innerHeight) y = window.innerHeight - menuHeight - 10;

        menu.style.left = Math.max(10, x) + 'px';
        menu.style.top = Math.max(10, y) + 'px';
    });

    // Close on outside click or Esc
    document.addEventListener('click', function(e) {
        if (menu && !menu.contains(e.target)) menu.style.display = 'none';
    });
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && menu) menu.style.display = 'none';
    });

    function wrapSelection(before, after, defaultText) {
        const start = editor.selectionStart;
        const end = editor.selectionEnd;
        const val = editor.value;
        const selected = val.substring(start, end) || defaultText;
        const replacement = before + selected + after;
        editor.value = val.substring(0, start) + replacement + val.substring(end);
        editor.focus();
        editor.selectionStart = start + before.length;
        editor.selectionEnd = start + before.length + selected.length;
        updateStats();
    }

    function prefixCurrentLine(prefix) {
        const start = editor.selectionStart;
        const val = editor.value;
        const lineStart = val.lastIndexOf('\n', start - 1) + 1;
        let lineEnd = val.indexOf('\n', start);
        if (lineEnd === -1) lineEnd = val.length;
        let lineText = val.substring(lineStart, lineEnd);

        if (prefix === '') {
            lineText = lineText.replace(/^(\#{1,6}\s+|-\s+|\d+\.\s+|-\s*\[[ xX]\]\s+|>\s+)/, '');
        } else {
            lineText = prefix + lineText.replace(/^(\#{1,6}\s+|-\s+|\d+\.\s+|-\s*\[[ xX]\]\s+|>\s+)/, '');
        }

        editor.value = val.substring(0, lineStart) + lineText + val.substring(lineEnd);
        editor.focus();
        editor.selectionStart = editor.selectionEnd = lineStart + lineText.length;
        updateStats();
    }

    // Context menu handlers
    menu.addEventListener('click', function(e) {
        const item = e.target.closest('[data-action]');
        if (!item) return;
        const action = item.getAttribute('data-action');
        menu.style.display = 'none';

        switch(action) {
            case 'link':
                wrapSelection('[', '](https://example.com)', 'link text');
                break;
            case 'external-link':
                wrapSelection('[', '](https://external.com)', 'external link');
                break;
            case 'format-bold':
                wrapSelection('**', '**', 'bold text');
                break;
            case 'format-italic':
                wrapSelection('*', '*', 'italic text');
                break;
            case 'format-strike':
                wrapSelection('~~', '~~', 'strikethrough text');
                break;
            case 'format-code':
                wrapSelection('`', '`', 'code');
                break;
            case 'format-codeblock':
                wrapSelection('```\n', '\n```', 'code block');
                break;
            case 'para-bullet':
                prefixCurrentLine('- ');
                break;
            case 'para-number':
                prefixCurrentLine('1. ');
                break;
            case 'para-task':
                prefixCurrentLine('- [ ] ');
                break;
            case 'para-h1':
                prefixCurrentLine('# ');
                break;
            case 'para-h2':
                prefixCurrentLine('## ');
                break;
            case 'para-h3':
                prefixCurrentLine('### ');
                break;
            case 'para-h4':
                prefixCurrentLine('#### ');
                break;
            case 'para-body':
                prefixCurrentLine('');
                break;
            case 'para-quote':
                prefixCurrentLine('> ');
                break;
            case 'select-all':
                editor.focus();
                editor.select();
                break;
            case 'copy':
                editor.focus();
                document.execCommand('copy');
                break;
            case 'cut':
                editor.focus();
                document.execCommand('cut');
                updateStats();
                break;
            case 'paste':
                editor.focus();
                navigator.clipboard?.readText?.().then(text => {
                    if (text) wrapSelection('', '', text);
                }).catch(() => {});
                break;
        }
    });
});
</script>
</body>
</html>
