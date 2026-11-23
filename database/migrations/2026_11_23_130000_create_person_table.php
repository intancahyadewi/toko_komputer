<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('person')) {
            Schema::create('person', function (Blueprint $table) {
                $table->string('id_person', 10)->primary();
                $table->string('nama', 100);
                $table->string('no_telepon', 20)->nullable();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('person');
    }
};
