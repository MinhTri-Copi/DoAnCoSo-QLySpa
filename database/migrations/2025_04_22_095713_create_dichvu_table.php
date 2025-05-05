<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('DICHVU', function (Blueprint $table) {
            $table->integer('MaDV')->primary();
            $table->string('Tendichvu', 100)->nullable();
            $table->decimal('Gia', 19, 4)->nullable();
            $table->string('MoTa', 100)->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('DICHVU');
    }
};