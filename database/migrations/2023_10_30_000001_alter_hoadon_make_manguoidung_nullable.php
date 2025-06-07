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
        // Trước tiên, kiểm tra xem cột đã nullable chưa
        $nullable = DB::select("SELECT IS_NULLABLE FROM INFORMATION_SCHEMA.COLUMNS 
                              WHERE TABLE_NAME = 'HOADON_VA_THANHTOAN' AND COLUMN_NAME = 'Manguoidung'");
        
        // Chỉ thực hiện thay đổi nếu cột chưa nullable
        if ($nullable && $nullable[0]->IS_NULLABLE === 'NO') {
            Schema::table('HOADON_VA_THANHTOAN', function (Blueprint $table) {
                // Xóa ràng buộc khóa ngoại
                $table->dropForeign(['Manguoidung']);
                
                // Thay đổi cột thành nullable
                $table->integer('Manguoidung')->nullable()->change();
                
                // Thêm lại ràng buộc khóa ngoại với điều kiện nullable
                $table->foreign('Manguoidung')
                      ->references('Manguoidung')
                      ->on('USER')
                      ->onDelete('set null');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('HOADON_VA_THANHTOAN', function (Blueprint $table) {
            // Xóa ràng buộc khóa ngoại
            $table->dropForeign(['Manguoidung']);
            
            // Thay đổi cột trở lại không nullable
            $table->integer('Manguoidung')->nullable(false)->change();
            
            // Thêm lại ràng buộc khóa ngoại
            $table->foreign('Manguoidung')
                  ->references('Manguoidung')
                  ->on('USER');
        });
    }
}; 