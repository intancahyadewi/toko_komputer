<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('perangkat')) {
            Schema::create('perangkat', function (Blueprint $table) {
                $table->string('nomor_seri', 50)->primary();
                $table->string('merek', 50)->nullable();
                $table->string('model', 50)->nullable();
                $table->string('kondisi', 100)->nullable();
                $table->string('status_kepemilikan', 50)->nullable();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('perangkat');
    }
};
