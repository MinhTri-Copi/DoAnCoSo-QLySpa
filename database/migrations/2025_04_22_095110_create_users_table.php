<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('USER', function (Blueprint $table) {
            $table->integer('Manguoidung')->primary();
            $table->integer('MaTK');
            $table->string('Hoten', 100)->nullable();
            $table->string('SDT', 15)->nullable();
            $table->string('DiaChi', 200)->nullable();
            $table->string('Email', 100)->nullable();
            $table->dateTime('Ngaysinh')->nullable();
            $table->string('Gioitinh', 10)->nullable();
            $table->foreign('MaTK')->references('MaTK')->on('ACCOUNT');

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('USER');
    }
};