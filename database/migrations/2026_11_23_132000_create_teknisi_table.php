<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('teknisi')) {
            Schema::create('teknisi', function (Blueprint $table) {
                $table->string('id_teknisi', 10)->primary();
                $table->string('bidang_keahlian', 100)->nullable();
                $table->string('status_ketersediaan', 30)->nullable();

                $table->foreign('id_teknisi')
                      ->references('id_person')
                      ->on('person');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('teknisi');
    }
};
