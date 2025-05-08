<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Đầu tiên, tạo cột tạm thời với kiểu datetime
        Schema::table('DICHVU', function (Blueprint $table) {
            $table->dateTime('Thoigian_new')->nullable()->after('Thoigian');
        });

        // Chuyển đổi dữ liệu từ phút sang datetime
        DB::statement('UPDATE DICHVU SET Thoigian_new = DATE_ADD(NOW(), INTERVAL Thoigian MINUTE)');

        // Xóa cột cũ
        Schema::table('DICHVU', function (Blueprint $table) {
            $table->dropColumn('Thoigian');
        });

        // Đổi tên cột mới thành Thoigian
        Schema::table('DICHVU', function (Blueprint $table) {
            $table->renameColumn('Thoigian_new', 'Thoigian');
        });
    }

    public function down(): void
    {
        // Đầu tiên, tạo cột tạm thời với kiểu integer
        Schema::table('DICHVU', function (Blueprint $table) {
            $table->integer('Thoigian_old')->nullable()->after('Thoigian');
        });

        // Chuyển đổi dữ liệu từ datetime sang phút
        DB::statement('UPDATE DICHVU SET Thoigian_old = TIMESTAMPDIFF(MINUTE, NOW(), Thoigian)');

        // Xóa cột cũ
        Schema::table('DICHVU', function (Blueprint $table) {
            $table->dropColumn('Thoigian');
        });

        // Đổi tên cột mới thành Thoigian
        Schema::table('DICHVU', function (Blueprint $table) {
            $table->renameColumn('Thoigian_old', 'Thoigian');
        });
    }
}; 