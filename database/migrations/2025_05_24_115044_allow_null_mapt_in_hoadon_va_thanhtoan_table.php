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
        Schema::table('HOADON_VA_THANHTOAN', function (Blueprint $table) {
            // Sửa đổi cột MaPT để cho phép giá trị NULL
            $table->integer('MaPT')->nullable()->change();
            
            // Đảm bảo Matrangthai không null, và mặc định là 6 (Chờ thanh toán)
            $table->integer('Matrangthai')->default(6)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('HOADON_VA_THANHTOAN', function (Blueprint $table) {
            // Khôi phục cột MaPT không cho phép NULL
            $table->integer('MaPT')->nullable(false)->change();
            
            // Khôi phục Matrangthai không có giá trị mặc định
            $table->integer('Matrangthai')->default(null)->change();
        });
    }
};
