<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('nota_penjualan')) {
            Schema::create('nota_penjualan', function (Blueprint $table) {
                $table->string('nomor_nota', 20)->primary();
                $table->date('tanggal_transaksi')->nullable();
                $table->string('metode_pembayaran', 30)->nullable();
                $table->decimal('total_biaya', 12, 2)->nullable();

                $table->string('id_staf', 10);
                $table->string('id_pelanggan', 10);
                $table->string('nomor_seri', 50);

                $table->foreign('id_staf')
                    ->references('id_staf')
                    ->on('staf_penjualan');

                $table->foreign('id_pelanggan')
                    ->references('id_pelanggan')
                    ->on('pelanggan');

                $table->foreign('nomor_seri')
                    ->references('nomor_seri')
                    ->on('perangkat');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('nota_penjualan');
    }
};
