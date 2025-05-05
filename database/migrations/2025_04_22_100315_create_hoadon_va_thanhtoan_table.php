<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('HOADON_VA_THANHTOAN', function (Blueprint $table) {
            $table->integer('MaHD')->primary();
            $table->dateTime('Ngaythanhtoan')->nullable();
            $table->decimal('Tongtien', 18, 2)->nullable();
            $table->integer('MaDL');
            $table->integer('Manguoidung');
            $table->integer('Maphong');
            $table->integer('MaPT');
            $table->integer('Matrangthai');
            $table->foreign('MaDL')->references('MaDL')->on('DATLICH');
            $table->foreign('Manguoidung')->references('Manguoidung')->on('USER');
            $table->foreign('Maphong')->references('Maphong')->on('PHONG');
            $table->foreign('MaPT')->references('MaPT')->on('PHUONGTHUC');
            $table->foreign('Matrangthai')->references('Matrangthai')->on('TRANGTHAI');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('HOADON_VA_THANHTOAN');
    }
};