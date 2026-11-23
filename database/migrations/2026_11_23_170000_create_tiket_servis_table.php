<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('tiket_servis')) {
            Schema::create('tiket_servis', function (Blueprint $table) {
                $table->string('nomor_tiket', 20)->primary();
                $table->date('tanggal_masuk')->nullable();
                $table->date('tanggal_selesai')->nullable();
                $table->text('keluhan_kerusakan')->nullable();

                $table->string('id_pelanggan', 10);
                $table->string('id_teknisi', 10);
                $table->string('nomor_seri', 50);

                $table->foreign('id_pelanggan')
                    ->references('id_pelanggan')
                    ->on('pelanggan');

                $table->foreign('id_teknisi')
                    ->references('id_teknisi')
                    ->on('teknisi');

                $table->foreign('nomor_seri')
                    ->references('nomor_seri')
                    ->on('perangkat');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('tiket_servis');
    }
};
