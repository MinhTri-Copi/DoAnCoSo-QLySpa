<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\HangThanhVien;
use App\Models\User;

return new class extends Migration
{
    /**
     * Run the migration.
     */
    public function up(): void
    {
        // Get all users with more than one membership rank
        $users = DB::table('HANGTHANHVIEN')
            ->select('Manguoidung', DB::raw('count(*) as total'))
            ->groupBy('Manguoidung')
            ->having('total', '>', 1)
            ->get();
            
        foreach ($users as $user) {
            // Get all membership ranks for this user, ordered by created_at or Mahang
            // (assuming higher Mahang means more recent)
            $ranks = DB::table('HANGTHANHVIEN')
                ->where('Manguoidung', $user->Manguoidung)
                ->orderBy('Mahang', 'desc')
                ->get();
                
            // Keep the first one (latest) and delete the rest
            $keepRankId = $ranks->first()->Mahang;
            
            DB::table('HANGTHANHVIEN')
                ->where('Manguoidung', $user->Manguoidung)
                ->where('Mahang', '!=', $keepRankId)
                ->delete();
        }
        
        // Add a unique constraint on Manguoidung
        Schema::table('HANGTHANHVIEN', function (Blueprint $table) {
            $table->unique('Manguoidung');
        });
    }

    /**
     * Reverse the migration.
     */
    public function down(): void
    {
        // Remove the unique constraint
        Schema::table('HANGTHANHVIEN', function (Blueprint $table) {
            $table->dropUnique(['Manguoidung']);
        });
    }
}; 