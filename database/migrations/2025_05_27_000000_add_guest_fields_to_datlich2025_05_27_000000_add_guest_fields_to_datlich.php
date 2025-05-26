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
            // Thêm cột số điện thoại và họ tên cho khách vãng lai
            $table->string('SDT_khach', 15)->nullable()->comment('Số điện thoại của khách vãng lai');
            $table->string('Hoten_khach')->nullable()->comment('Họ tên của khách vãng lai');
            
            // Chỉnh sửa cột Manguoidung thành khóa ngoại nullable kiểu int
            $table->integer('Manguoidung')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('DATLICH', function (Blueprint $table) {
            // Xóa các cột đã thêm
            $table->dropColumn(['SDT_khach', 'Hoten_khach']);
            
            // Khôi phục cột Manguoidung thành not null
            $table->integer('Manguoidung')->nullable(false)->change();
        });
    }
};