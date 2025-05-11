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
            // Xóa cột Matrangthai
            if (Schema::hasColumn('DICHVU', 'Matrangthai')) {
                $table->dropColumn('Matrangthai');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('DICHVU', function (Blueprint $table) {
            // Thêm lại cột nếu cần
            if (!Schema::hasColumn('DICHVU', 'Matrangthai')) {
                $table->integer('Matrangthai')->nullable();
            }
        });
    }
}; 