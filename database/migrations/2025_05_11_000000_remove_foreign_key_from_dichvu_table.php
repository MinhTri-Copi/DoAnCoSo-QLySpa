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
        // Bỏ ràng buộc khóa ngoại nếu có bằng cách sử dụng SQL trực tiếp
        try {
            // Kiểm tra xem hệ quản trị CSDL có phải là MySQL không
            if (DB::connection()->getDriverName() === 'mysql') {
                // Lấy các khóa ngoại từ INFORMATION_SCHEMA
                $foreignKeys = DB::select("
                    SELECT CONSTRAINT_NAME
                    FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
                    WHERE TABLE_NAME = 'DICHVU'
                    AND COLUMN_NAME = 'Matrangthai'
                    AND CONSTRAINT_NAME != 'PRIMARY'
                    AND REFERENCED_TABLE_NAME IS NOT NULL
                ");

                // Xóa các khóa ngoại tìm thấy
                foreach ($foreignKeys as $foreignKey) {
                    DB::statement("ALTER TABLE DICHVU DROP FOREIGN KEY " . $foreignKey->CONSTRAINT_NAME);
                }
            }
        } catch (\Exception $e) {
            // Ghi log lỗi nếu có
            \Log::error('Lỗi khi xóa khóa ngoại: ' . $e->getMessage());
        }

        // Xóa cột duration_minutes nếu tồn tại
        if (Schema::hasColumn('DICHVU', 'duration_minutes')) {
            Schema::table('DICHVU', function (Blueprint $table) {
                $table->dropColumn('duration_minutes');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Không cần khôi phục vì chúng ta cố ý xóa các ràng buộc này
    }
}; 