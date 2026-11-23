<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('staf_penjualan')) {
            Schema::create('staf_penjualan', function (Blueprint $table) {
                $table->string('id_staf', 10)->primary();
                $table->date('tanggal_masuk')->nullable();
                $table->decimal('komisi_penjualan', 5, 2)->nullable();

                $table->foreign('id_staf')
                    ->references('id_person')
                    ->on('person');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('staf_penjualan');
    }
};
