<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('PHIEUHOTRO', function (Blueprint $table) {
            $table->integer('MaphieuHT')->primary();
            $table->string('Noidungyeucau', 100)->nullable();
            $table->integer('Matrangthai');
            $table->integer('MaPTHT');
            $table->integer('Manguoidung');
            $table->foreign('Matrangthai')->references('Matrangthai')->on('TRANGTHAI');
            $table->foreign('MaPTHT')->references('MaPTHT')->on('PTHOTRO');
            $table->foreign('Manguoidung')->references('Manguoidung')->on('USER');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('PHIEUHOTRO');
    }
};