<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('QUANGCAO', function (Blueprint $table) {
            $table->integer('MaQC')->primary();
            $table->string('Tieude', 100)->nullable();
            $table->string('Noidung', 200)->nullable();
            $table->string('Image', 100)->nullable();
            $table->string('Loaiquangcao', 100)->nullable();
            $table->dateTime('Ngaybatdau')->nullable();
            $table->dateTime('Ngayketthuc')->nullable();
            $table->integer('MaTTQC');
            $table->integer('Manguoidung');
            $table->foreign('MaTTQC')->references('MaTTQC')->on('TRANGTHAIQC');
            $table->foreign('Manguoidung')->references('Manguoidung')->on('USER');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('QUANGCAO');
    }
};