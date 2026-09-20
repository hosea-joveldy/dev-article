<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('artikels', function (Blueprint $table) {
            // Tambahkan field gambar setelah field konten
            $table->string('gambar')->nullable()->after('konten');
        });
    }

    public function down(): void
    {
        Schema::table('artikels', function (Blueprint $table) {
            // Hapus field gambar jika migration di-rollback
            $table->dropColumn('gambar');
        });
    }
};
