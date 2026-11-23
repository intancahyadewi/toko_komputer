<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('pelanggan')) {
            Schema::create('pelanggan', function (Blueprint $table) {
                $table->string('id_pelanggan', 10)->primary();
                $table->string('email', 100)->nullable()->unique();
                $table->string('nama_jalan', 150)->nullable();
                $table->string('kota', 50)->nullable();
                $table->string('kode_pos', 10)->nullable();

                $table->foreign('id_pelanggan')
                      ->references('id_person')
                      ->on('person');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('pelanggan');
    }
};
