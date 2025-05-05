<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('PHUONGTHUC', function (Blueprint $table) {
            $table->integer('MaPT')->primary();
            $table->string('TenPT', 100)->nullable();
            $table->string('Mota', 100)->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('PHUONGTHUC');
    }
};