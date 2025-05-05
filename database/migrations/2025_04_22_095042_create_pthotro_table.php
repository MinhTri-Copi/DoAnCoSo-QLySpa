<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('PTHOTRO', function (Blueprint $table) {
            $table->integer('MaPTHT')->primary();
            $table->string('TenPT', 50)->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('PTHOTRO');
    }
};