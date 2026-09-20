@extends('artikel_layout')

@section('title', 'Edit Article')

@section('content')
    <h1 class="text-3xl font-bold mb-6">Edit Article</h1>

    <form action="{{ route('artikel.update', $artikel->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label class="block text-gray-700">Title</label>
            <input type="text" name="judul" value="{{ $artikel->judul }}" 
                   class="w-full border p-2 rounded-md mt-2">
        </div>

        <div class="mb-4">
            <label class="block text-gray-700">Content</label>
            <textarea name="konten" rows="8" class="w-full border p-2 rounded-md mt-2">{{ $artikel->konten }}</textarea>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700">Category (Optional)</label>
            <select name="category_id" class="w-full border p-2 rounded-md mt-2">
                <option value="">-- No category --</option>
                @foreach (($categories ?? collect()) as $cat)
                    <option value="{{ $cat->id }}" {{ old('category_id', $artikel->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->nama }}</option>
                @endforeach
            </select>
        </div>

        @if ($artikel->gambar)
            <div class="mb-4">
                <label class="block text-gray-700">Current Image</label>
                <img src="{{ asset('storage/' . $artikel->gambar) }}" alt="Article image" class="mt-2 w-64 h-auto">
            </div>
        @endif

        <div class="mb-4">
            <label class="block text-gray-700">Replace Image</label>
            <input type="file" name="gambar" class="w-full border p-2 rounded-md mt-2">
        </div>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition">
            Update Article
        </button>
        
        <a href="{{ route('artikel.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700 transition">
            Back
        </a>
    </form>
@endsection
