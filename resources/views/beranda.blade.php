@extends('layout_utama')

@section('lubang_konten')
    <h1 class="text-3xl font-extrabold text-gray-800">
        Ini adalah Halaman Beranda.
    </h1>
    <p class="text-gray-600 mt-2">
        Konten ini dikirim langsung masuk ke dalam perut Master Layout. llololol
    </p>

    <!-- Kapanpun butuh tombol Tailwind sakti, cukup panggil tag x- ajaib ini! -->
    <x-tombol-merah>Hapus Data</x-tombol-merah>
    <x-tombol-merah>Batalkan Transaksi</x-tombol-merah>
@endsection