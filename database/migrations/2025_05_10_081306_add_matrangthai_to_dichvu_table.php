<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('DICHVU', function (Blueprint $table) {
            $table->integer('Matrangthai')->nullable()->after('MoTa');
            $table->foreign('Matrangthai')->references('Matrangthai')->on('TRANGTHAI');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('DICHVU', function (Blueprint $table) {
            $table->dropForeign(['Matrangthai']);
            $table->dropColumn('Matrangthai');
        });
    }
};
