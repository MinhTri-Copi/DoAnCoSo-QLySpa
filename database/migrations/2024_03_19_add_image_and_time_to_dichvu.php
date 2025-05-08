<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('DICHVU', function (Blueprint $table) {
            $table->string('Image', 255)->nullable()->after('MoTa');
            $table->dateTime('Thoigian')->nullable()->after('Image')->comment('Thời gian thực hiện dịch vụ');
        });
    }

    public function down(): void
    {
        Schema::table('DICHVU', function (Blueprint $table) {
            $table->dropColumn(['Image', 'Thoigian']);
        });
    }
}; 