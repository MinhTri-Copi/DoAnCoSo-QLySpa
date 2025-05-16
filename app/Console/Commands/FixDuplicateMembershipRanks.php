<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\HangThanhVien;
use App\Models\User;

class FixDuplicateMembershipRanks extends Command
{
    protected $signature = 'membership:fix-duplicates';
    protected $description = 'Fix duplicate membership ranks by keeping only the latest one for each user';

    public function handle()
    {
        $this->info('Checking for duplicate membership ranks...');
        
        // Get all users with more than one membership rank
        $users = DB::table('HANGTHANHVIEN')
            ->select('Manguoidung', DB::raw('count(*) as total'))
            ->groupBy('Manguoidung')
            ->having('total', '>', 1)
            ->get();
            
        if ($users->count() === 0) {
            $this->info('No duplicate membership ranks found.');
            return Command::SUCCESS;
        }
        
        $this->info('Found ' . $users->count() . ' users with duplicate membership ranks. Fixing...');
        
        $total = 0;
        
        foreach ($users as $user) {
            // Get all membership ranks for this user, ordered by Mahang desc
            $ranks = DB::table('HANGTHANHVIEN')
                ->where('Manguoidung', $user->Manguoidung)
                ->orderBy('Mahang', 'desc')
                ->get();
                
            // Keep the first one (latest) and delete the rest
            $keepRankId = $ranks->first()->Mahang;
            
            $deleted = DB::table('HANGTHANHVIEN')
                ->where('Manguoidung', $user->Manguoidung)
                ->where('Mahang', '!=', $keepRankId)
                ->delete();
                
            $total += $deleted;
            
            $this->line("User ID {$user->Manguoidung}: Deleted {$deleted} duplicate ranks, kept rank #{$keepRankId}");
        }
        
        $this->info("Cleanup complete. Deleted {$total} duplicate membership ranks.");
        
        return Command::SUCCESS;
    }
} 