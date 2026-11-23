<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('suku_cadang')) {
            Schema::create('suku_cadang', function (Blueprint $table) {
                $table->string('kode_suku_cadang', 20)->primary();
                $table->string('nama_suku_cadang', 100);
                $table->string('kategori', 50)->nullable();
                $table->decimal('harga_satuan', 10, 2)->nullable();
                $table->integer('stok_tersedia')->nullable();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('suku_cadang');
    }
};
