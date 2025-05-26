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
        Schema::table('DATLICH', function (Blueprint $table) {
            // Thêm cột Ghichu vào bảng DATLICH, cho phép null
            $table->text('Ghichu')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('DATLICH', function (Blueprint $table) {
            // Xóa cột Ghichu khi rollback
            $table->dropColumn('Ghichu');
        });
    }
};
