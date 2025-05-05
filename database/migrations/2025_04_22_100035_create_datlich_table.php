<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('DATLICH', function (Blueprint $table) {
            $table->integer('MaDL')->primary();
            $table->integer('Manguoidung');
            $table->dateTime('Thoigiandatlich')->nullable();
            $table->string('Trangthai_', 100)->nullable();
            $table->integer('MaDV');
            $table->foreign('Manguoidung')->references('Manguoidung')->on('USER');
            $table->foreign('MaDV')->references('MaDV')->on('DICHVU');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('DATLICH');
    }
};