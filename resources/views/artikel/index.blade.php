@extends('artikel_layout')

@section('title', 'Article List')

@section('content')
    <h1 class="text-3xl font-bold mb-6">Article List</h1>

    <a href="{{ route('artikel.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition mb-4 inline-block">
        Add Article
    </a>

    <div class="space-y-4">
        @foreach ($berita as $artikel)
            <div class="bg-white p-4 rounded-md shadow-sm border">
                @if ($artikel->gambar)
                    <img src="{{ asset('storage/' . $artikel->gambar) }}" alt="Article image" class="w-full h-64 object-cover mb-4 rounded-md">
                @endif                

                <h3 class="text-xl font-bold mb-2">{{ $artikel->judul }}</h3>

                <div class="prose mb-4">
                    {{ $artikel->konten }}
                </div>

                <div class="flex space-x-2">
                    <a href="{{ route('artikel.show', $artikel->id) }}" class="bg-green-600 text-white px-3 py-1 rounded-md hover:bg-green-700 transition">
                        Read Details
                    </a>
                    <a href="{{ route('artikel.edit', $artikel->id) }}" class="bg-yellow-600 text-white px-3 py-1 rounded-md hover:bg-yellow-700 transition">
                        Edit
                    </a>
                    <form action="{{ route('artikel.destroy', $artikel->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-600 text-white px-3 py-1 rounded-md hover:bg-red-700 transition">
                            Delete
                        </button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>
@endsection
