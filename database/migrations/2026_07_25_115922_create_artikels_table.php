<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('artikels', function (Blueprint $table) {
        // $table->tipeData('nama_kolom')
        $table->id(); // Membuat kolom ID otomatis (Primary Key)
        $table->string('judul', 150); // Kolom Teks Pendek (Maks 150 Karakter)
        $table->text('konten'); // Kolom Teks Panjang tak terbatas

        // PENTING: Timestamp otomatis membuat kolom 'created_at' dan 'updated_at'
        $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('artikels');
    }
};
