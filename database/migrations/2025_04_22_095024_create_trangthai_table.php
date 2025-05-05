<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('TRANGTHAI', function (Blueprint $table) {
            $table->integer('Matrangthai')->primary();
            $table->string('Tentrangthai', 100)->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('TRANGTHAI');
    }
};