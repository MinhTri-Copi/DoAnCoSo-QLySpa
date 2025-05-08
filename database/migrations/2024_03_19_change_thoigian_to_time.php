<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('DICHVU', function (Blueprint $table) {
            // Tạo cột tạm thời với kiểu time
            $table->time('Thoigian_new')->nullable()->after('Image');
        });

        // Chuyển đổi dữ liệu từ datetime sang time
        DB::statement('UPDATE DICHVU SET Thoigian_new = TIME(Thoigian)');

        Schema::table('DICHVU', function (Blueprint $table) {
            // Xóa cột cũ
            $table->dropColumn('Thoigian');
            // Đổi tên cột mới
            $table->renameColumn('Thoigian_new', 'Thoigian');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('DICHVU', function (Blueprint $table) {
            // Tạo cột tạm thời với kiểu datetime
            $table->dateTime('Thoigian_old')->nullable()->after('Image');
        });

        // Chuyển đổi dữ liệu từ time sang datetime
        DB::statement('UPDATE DICHVU SET Thoigian_old = CONCAT(CURDATE(), " ", Thoigian)');

        Schema::table('DICHVU', function (Blueprint $table) {
            // Xóa cột cũ
            $table->dropColumn('Thoigian');
            // Đổi tên cột mới
            $table->renameColumn('Thoigian_old', 'Thoigian');
        });
    }
}; 