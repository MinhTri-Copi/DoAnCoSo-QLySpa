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
            $table->text('available_days')->nullable()->after('Matrangthai')->comment('Các ngày dịch vụ có sẵn (JSON)');
            $table->boolean('featured')->default(false)->after('available_days')->comment('Dịch vụ nổi bật');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('DICHVU', function (Blueprint $table) {
            $table->dropColumn(['available_days', 'featured']);
        });
    }
};
