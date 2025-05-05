<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('DANHGIA', function (Blueprint $table) {
            $table->integer('MaDG')->primary();
            $table->float('Danhgiasao')->nullable();
            $table->string('Nhanxet', 100)->nullable();
            $table->dateTime('Ngaydanhgia')->nullable();
            $table->integer('Manguoidung');
            $table->integer('MaHD');
            $table->foreign('Manguoidung')->references('Manguoidung')->on('USER');
            $table->foreign('MaHD')->references('MaHD')->on('HOADON_VA_THANHTOAN');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('DANHGIA');
    }
};