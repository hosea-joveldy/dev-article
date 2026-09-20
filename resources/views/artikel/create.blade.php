@extends('artikel_layout')

@section('title', 'New Log Entry — dev.logs')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 py-10">
    <!-- Back to Feed -->
    <div class="mb-6">
        <a href="{{ url('/') }}" class="inline-flex items-center gap-2 text-xs font-mono font-medium hover:underline transition-opacity hover:opacity-80" style="color: var(--accent);">
            &larr; Cancel and return to feed
        </a>
    </div>

    <!-- Page Header -->
    <header class="pb-6 mb-8 border-b" style="border-color: var(--border);">
        <div class="flex items-center space-x-2 font-mono text-xs" style="color: var(--accent);">
            <span>[+]</span>
            <span class="uppercase tracking-widest font-semibold">New Publication</span>
        </div>
        <h1 class="text-3xl sm:text-4xl font-black tracking-tight mt-1">Create Article Entry</h1>
        <p class="text-xs font-mono mt-2" style="color: var(--text-muted);">
            Author markdown or formatted text. Output renders responsively with syntax styling.
        </p>
    </header>

    @if ($errors->any())
        <div class="p-4 mb-6 rounded border font-mono text-xs" style="background-color: var(--bg-surface); border-color: var(--danger); color: var(--danger);">
            <p class="font-bold mb-2">// Validation Errors:</p>
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('artikel.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8" id="articleForm">
        @csrf

        <!-- 1. Title Input -->
        <div>
            <label for="judul" class="block text-xs font-mono uppercase tracking-wider font-semibold mb-2" style="color: var(--text-main);">
                Article Title <span style="color: var(--danger);">*</span>
            </label>
            <input 
                type="text" 
                name="judul" 
                id="judul" 
                value="{{ old('judul') }}"
                placeholder="e.g. Scaling PostgreSQL: Connection Pooling with PgBouncer" 
                class="w-full text-base font-semibold px-4 py-3 rounded border focus:outline-none focus:ring-1 transition-all"
                style="background-color: var(--bg-surface); border-color: var(--border); color: var(--text-main); --tw-ring-color: var(--accent);"
                required
            >
            @error('judul')
                <p class="text-xs font-mono mt-1.5" style="color: var(--danger);">{{ $message }}</p>
            @enderror
        </div>

        <!-- 1b. Category Select (Optional) -->
        <div>
            <label for="category_id" class="block text-xs font-mono uppercase tracking-wider font-semibold mb-2" style="color: var(--text-main);">
                Category <span class="normal-case font-normal" style="color: var(--text-muted);">(Optional)</span>
            </label>
            <select
                name="category_id"
                id="category_id"
                class="w-full text-sm px-4 py-3 rounded border focus:outline-none focus:ring-1 cursor-pointer"
                style="background-color: var(--bg-surface); border-color: var(--border); color: var(--text-main); --tw-ring-color: var(--accent);"
            >
                <option value="">-- No category --</option>
                @foreach (($categories ?? collect()) as $cat)
                    <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->nama }}</option>
                @endforeach
            </select>
            @error('category_id')
                <p class="text-xs font-mono mt-1.5" style="color: var(--danger);">{{ $message }}</p>
            @enderror
        </div>

        <!-- 2. Interactive Rich Content Editor -->
        <div>
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-2">
                <label for="konten" class="block text-xs font-mono uppercase tracking-wider font-semibold" style="color: var(--text-main);">
                    Article Content <span style="color: var(--danger);">*</span>
                </label>
                <!-- Editor Mode Tabs & Live Stats -->
                <div class="flex items-center space-x-3 text-xs font-mono" style="color: var(--text-muted);">
                    <span id="wordCount">0 words</span>
                    <span>&bull;</span>
                    <span id="readingTime">~1 min read</span>
                    <span class="text-gray-500">|</span>
                    <button type="button" id="tabWrite" onclick="switchEditorTab('write')" class="font-bold underline cursor-pointer" style="color: var(--accent);">Write</button>
                    <button type="button" id="tabPreview" onclick="switchEditorTab('preview')" class="hover:underline cursor-pointer">Preview</button>
                </div>
            </div>

            <div class="rounded border overflow-hidden" style="border-color: var(--border); background-color: var(--bg-surface);">
                <!-- Editor Toolbar -->
                <div id="editorToolbar" class="flex flex-wrap items-center gap-1 p-2 border-b text-xs font-mono" style="border-color: var(--border); background-color: var(--bg-surface-alt);">
                    <button type="button" onclick="formatDoc('bold')" title="Bold" class="toolbar-btn"><b>B</b></button>
                    <button type="button" onclick="formatDoc('italic')" title="Italic" class="toolbar-btn"><i>I</i></button>
                    <button type="button" onclick="formatDoc('strike')" title="Strikethrough" class="toolbar-btn"><s>S</s></button>
                    <span class="toolbar-sep">|</span>
                    <button type="button" onclick="formatDoc('h2')" title="Heading 2" class="toolbar-btn">H2</button>
                    <button type="button" onclick="formatDoc('h3')" title="Heading 3" class="toolbar-btn">H3</button>
                    <span class="toolbar-sep">|</span>
                    <button type="button" onclick="formatDoc('quote')" title="Blockquote" class="toolbar-btn">&ldquo;&rdquo;</button>
                    <button type="button" onclick="formatDoc('code')" title="Inline Code" class="toolbar-btn">&lt;/&gt;</button>
                    <button type="button" onclick="formatDoc('codeblock')" title="Code Block" class="toolbar-btn">{ code }</button>
                    <span class="toolbar-sep">|</span>
                    <button type="button" onclick="formatDoc('ul')" title="Bullet List" class="toolbar-btn">&bull; List</button>
                    <button type="button" onclick="formatDoc('ol')" title="Numbered List" class="toolbar-btn">1. List</button>
                    <button type="button" onclick="formatDoc('hr')" title="Divider" class="toolbar-btn">&mdash;</button>
                </div>

                <!-- Textarea (Write Tab) -->
                <textarea 
                    name="konten" 
                    id="konten" 
                    rows="14" 
                    placeholder="Write in Markdown or clean HTML...

## Overview
Describe your implementation or findings...

```python
def benchmark():
    return True
```" 
                    class="w-full text-sm font-mono leading-relaxed p-4 focus:outline-none resize-y border-none"
                    style="background-color: var(--bg-surface); color: var(--text-main);"
                    oninput="updateEditorStats()"
                    required
                >{{ old('konten') }}</textarea>

                <!-- Live Preview Pane (Preview Tab) -->
                <div 
                    id="previewPane" 
                    class="hidden p-6 text-sm leading-relaxed overflow-y-auto article-content min-h-[350px]"
                    style="background-color: var(--bg-surface); color: var(--text-main);"
                ></div>
            </div>
            @error('konten')
                <p class="text-xs font-mono mt-1.5" style="color: var(--danger);">{{ $message }}</p>
            @enderror
        </div>

        <!-- 3. Featured Image Upload with Interactive Preview Dropzone -->
        <div>
            <label class="block text-xs font-mono uppercase tracking-wider font-semibold mb-2" style="color: var(--text-main);">
                Featured Banner / Screenshot <span class="text-xs normal-case" style="color: var(--text-muted);">(Optional &bull; Max 2MB)</span>
            </label>

            <div 
                id="dropZone"
                class="relative border-2 border-dashed rounded-lg p-6 text-center transition-colors cursor-pointer hover:opacity-90"
                style="border-color: var(--border); background-color: var(--bg-surface);"
                onclick="document.getElementById('gambar').click()"
            >
                <!-- File Input (Hidden, triggered by dropzone) -->
                <input 
                    type="file" 
                    name="gambar" 
                    id="gambar" 
                    accept="image/png, image/jpeg, image/webp, image/gif"
                    class="hidden"
                    onchange="handleImageSelected(this)"
                >

                <!-- Initial Empty State -->
                <div id="dropZoneEmpty" class="flex flex-col items-center justify-center space-y-2 font-mono text-xs" style="color: var(--text-muted);">
                    <svg class="w-8 h-8 opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <p><span class="font-bold underline" style="color: var(--accent);">Click to select</span> or drag and drop an image</p>
                    <p class="text-[10px] text-gray-500">PNG, JPG, WEBP up to 2MB</p>
                </div>

                <!-- Selected Image Preview State -->
                <div id="dropZonePreview" class="hidden flex-col items-center space-y-3">
                    <img id="previewImg" src="#" alt="Preview" class="max-h-52 rounded border object-contain" style="border-color: var(--border);">
                    <div class="flex items-center gap-3 font-mono text-xs">
                        <span id="fileName" style="color: var(--text-muted);"></span>
                        <button type="button" onclick="removeSelectedImage(event)" class="font-bold underline hover:opacity-80" style="color: var(--danger);">
                            [Remove]
                        </button>
                    </div>
                </div>
            </div>
            @error('gambar')
                <p class="text-xs font-mono mt-1.5" style="color: var(--danger);">{{ $message }}</p>
            @enderror
        </div>

        <!-- 4. Action Bar -->
        <div class="pt-6 border-t flex items-center justify-between font-mono text-xs" style="border-color: var(--border);">
            <a href="{{ url('/') }}" class="hover:underline" style="color: var(--text-muted);">
                Cancel
            </a>
            <button 
                type="submit" 
                class="px-6 py-3 rounded font-bold transition-transform active:scale-95 hover:opacity-90 flex items-center gap-2 cursor-pointer"
                style="background-color: var(--accent); color: var(--bg-main);"
            >
                <span>Publish Entry</span>
                <span>&rarr;</span>
            </button>
        </div>
    </form>
</div>

<!-- Scoped Styles for Editor Toolbar & Preview -->
<style>
    .toolbar-btn {
        padding: 0.35rem 0.65rem;
        border-radius: 4px;
        color: var(--text-main);
        background: transparent;
        transition: background 0.15s ease;
        border: 1px solid transparent;
        cursor: pointer;
    }
    .toolbar-btn:hover {
        background: var(--bg-surface);
        border-color: var(--border);
        color: var(--accent);
    }
    .toolbar-sep {
        color: var(--border);
        margin: 0 0.25rem;
        user-select: none;
    }
    /* Preview pane typography matching theme */
    #previewPane h2 { font-size: 1.5rem; font-weight: 700; margin-top: 1.5rem; margin-bottom: 0.75rem; }
    #previewPane h3 { font-size: 1.25rem; font-weight: 700; margin-top: 1.25rem; margin-bottom: 0.5rem; }
    #previewPane p { margin-bottom: 1rem; line-height: 1.75; }
    #previewPane ul { list-style-type: disc; padding-left: 1.5rem; margin-bottom: 1rem; }
    #previewPane ol { list-style-type: decimal; padding-left: 1.5rem; margin-bottom: 1rem; }
    #previewPane blockquote { border-left: 3px solid var(--accent); padding-left: 1rem; color: var(--text-muted); margin: 1rem 0; font-style: italic; }
    #previewPane pre { background-color: var(--bg-main); border: 1px solid var(--border); border-radius: 6px; padding: 1rem; overflow-x: auto; font-family: monospace; margin-bottom: 1rem; }
    #previewPane code { background-color: var(--bg-surface-alt); padding: 0.2rem 0.4rem; border-radius: 4px; font-family: monospace; font-size: 0.9em; }
    #previewPane pre code { background: transparent; padding: 0; }
    #previewPane hr { border: none; border-top: 1px solid var(--border); margin: 2rem 0; }
</style>

<!-- Editor Scripts -->
<script>
    // 1. Text Insertion & Formatting
    function formatDoc(command) {
        const textarea = document.getElementById('konten');
        const start = textarea.selectionStart;
        const end = textarea.selectionEnd;
        const selectedText = textarea.value.substring(start, end);
        let replacement = '';

        switch(command) {
            case 'bold':
                replacement = `**${selectedText || 'bold text'}**`;
                break;
            case 'italic':
                replacement = `*${selectedText || 'italic text'}*`;
                break;
            case 'strike':
                replacement = `~~${selectedText || 'strikethrough'}~~`;
                break;
            case 'h2':
                replacement = `\n## ${selectedText || 'Heading 2'}\n`;
                break;
            case 'h3':
                replacement = `\n### ${selectedText || 'Heading 3'}\n`;
                break;
            case 'quote':
                replacement = `\n> ${selectedText || 'Quote text'}\n`;
                break;
            case 'code':
                replacement = `\`${selectedText || 'code'}\``;
                break;
            case 'codeblock':
                replacement = `\n\`\`\`javascript\n${selectedText || '// code here'}\n\`\`\`\n`;
                break;
            case 'ul':
                replacement = `\n- ${selectedText || 'List item'}\n`;
                break;
            case 'ol':
                replacement = `\n1. ${selectedText || 'First item'}\n`;
                break;
            case 'hr':
                replacement = `\n\n---\n\n`;
                break;
        }

        textarea.setRangeText(replacement, start, end, 'select');
        textarea.focus();
        updateEditorStats();
    }

    // 2. Tab Switching (Write vs. Preview)
    function switchEditorTab(mode) {
        const textarea = document.getElementById('konten');
        const preview = document.getElementById('previewPane');
        const tabWrite = document.getElementById('tabWrite');
        const tabPreview = document.getElementById('tabPreview');
        const toolbar = document.getElementById('editorToolbar');

        if (mode === 'preview') {
            tabWrite.classList.remove('font-bold', 'underline');
            tabWrite.style.color = 'var(--text-muted)';
            tabPreview.classList.add('font-bold', 'underline');
            tabPreview.style.color = 'var(--accent)';
            toolbar.style.opacity = '0.35';
            toolbar.style.pointerEvents = 'none';

            // Lightweight parser for previewing Markdown in the browser
            let raw = textarea.value;
            let html = raw
                .replace(/^### (.*$)/gim, '<h3>$1</h3>')
                .replace(/^## (.*$)/gim, '<h2>$1</h2>')
                .replace(/^# (.*$)/gim, '<h1>$1</h1>')
                .replace(/^\> (.*$)/gim, '<blockquote>$1</blockquote>')
                .replace(/\*\*(.*)\*\*/gim, '<b>$1</b>')
                .replace(/\*(.*)\*/gim, '<i>$1</i>')
                .replace(/~~(.*)~~/gim, '<s>$1</s>')
                .replace(/```([\s\S]*?)```/gm, '<pre><code>$1</code></pre>')
                .replace(/`([^`]+)`/g, '<code>$1</code>')
                .replace(/^\- (.*$)/gim, '<ul><li>$1</li></ul>')
                .replace(/^\d+\. (.*$)/gim, '<ol><li>$1</li></ol>')
                .replace(/^---$/gim, '<hr>')
                .replace(/\n$/gim, '<br />')
                .split('\n\n').map(p => p.startsWith('<') ? p : `<p>${p}</p>`).join('');

            preview.innerHTML = html || '<p class="text-gray-500 font-mono italic">// Nothing to preview</p>';
            textarea.classList.add('hidden');
            preview.classList.remove('hidden');
        } else {
            tabPreview.classList.remove('font-bold', 'underline');
            tabPreview.style.color = 'var(--text-muted)';
            tabWrite.classList.add('font-bold', 'underline');
            tabWrite.style.color = 'var(--accent)';
            toolbar.style.opacity = '1';
            toolbar.style.pointerEvents = 'auto';

            preview.classList.add('hidden');
            textarea.classList.remove('hidden');
            textarea.focus();
        }
    }

    // 3. Word Count & Reading Time Estimation
    function updateEditorStats() {
        const text = document.getElementById('konten').value.trim();
        const words = text ? text.split(/\s+/).filter(w => w.length > 0).length : 0;
        const readTime = Math.max(1, Math.ceil(words / 200));

        document.getElementById('wordCount').innerText = `${words} words`;
        document.getElementById('readingTime').innerText = `~${readTime} min read`;
    }

    // 4. Image Upload Drag & Drop & Live Thumbnail
    function handleImageSelected(input) {
        if (input.files && input.files[0]) {
            const file = input.files[0];

            if (file.size > 2 * 1024 * 1024) {
                alert('Selected image exceeds 2MB limit. Please choose a smaller file.');
                input.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('previewImg').src = e.target.result;
                document.getElementById('fileName').innerText = `${file.name} (${(file.size / 1024).toFixed(1)} KB)`;
                document.getElementById('dropZoneEmpty').classList.add('hidden');
                document.getElementById('dropZonePreview').classList.remove('hidden');
                document.getElementById('dropZonePreview').classList.add('flex');
            }
            reader.readAsDataURL(file);
        }
    }

    function removeSelectedImage(e) {
        e.stopPropagation();
        const input = document.getElementById('gambar');
        input.value = '';
        document.getElementById('previewImg').src = '#';
        document.getElementById('dropZonePreview').classList.add('hidden');
        document.getElementById('dropZonePreview').classList.remove('flex');
        document.getElementById('dropZoneEmpty').classList.remove('hidden');
    }

    // Initialize stats on load
    document.addEventListener('DOMContentLoaded', updateEditorStats);
</script>
@endsection