<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ACCOUNT', function (Blueprint $table) {
            $table->integer('MaTK')->primary();
            $table->integer('RoleID');
            $table->string('Tendangnhap', 100)->nullable();
            $table->string('Matkhau', 100)->nullable();
            $table->foreign('RoleID')->references('RoleID')->on('ROLE');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ACCOUNT');
    }
};