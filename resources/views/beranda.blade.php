@extends('layout_utama')

@section('lubang_konten')
    <h1 class="text-3xl font-extrabold text-gray-800">
        This is the Home Page.
    </h1>
    <p class="text-gray-600 mt-2">
        This content is passed directly into the Master Layout.
    </p>

    <!-- Whenever you need a magic Tailwind button, just call this x- tag! -->
    <x-tombol-merah>Delete Data</x-tombol-merah>
    <x-tombol-merah>Cancel Transaction</x-tombol-merah>
@endsection