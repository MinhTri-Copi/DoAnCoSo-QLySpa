<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('PHONG', function (Blueprint $table) {
            $table->integer('Maphong')->primary();
            $table->string('Tenphong', 100)->nullable();
            $table->string('Loaiphong', 100)->nullable();
            $table->integer('MatrangthaiP');
            $table->foreign('MatrangthaiP')->references('MatrangthaiP')->on('TRANGTHAIPHONG');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('PHONG');
    }
};