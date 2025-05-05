<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('TRANGTHAIPHONG', function (Blueprint $table) {
            $table->integer('MatrangthaiP')->primary();
            $table->string('Tentrangthai', 100)->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('TRANGTHAIPHONG');
    }
};