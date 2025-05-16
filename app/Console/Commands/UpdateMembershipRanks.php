<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\MembershipRankService;

class UpdateMembershipRanks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'membership:update-ranks';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update membership ranks for all users based on their reward points';

    /**
     * The membership rank service.
     *
     * @var \App\Services\MembershipRankService
     */
    protected $membershipRankService;

    /**
     * Create a new command instance.
     *
     * @param \App\Services\MembershipRankService $membershipRankService
     * @return void
     */
    public function __construct(MembershipRankService $membershipRankService)
    {
        parent::__construct();
        $this->membershipRankService = $membershipRankService;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Updating membership ranks for all users...');
        
        $stats = $this->membershipRankService->updateAllMembershipRanks();
        
        $this->info("Membership ranks updated for {$stats['total']} users:");
        $this->table(
            ['Rank', 'Count'],
            [
                ['Thành viên Bạc', $stats['Thành viên Bạc']],
                ['Thành viên Vàng', $stats['Thành viên Vàng']],
                ['Thành viên Bạch Kim', $stats['Thành viên Bạch Kim']],
                ['Thành viên Kim Cương', $stats['Thành viên Kim Cương']]
            ]
        );
        
        return Command::SUCCESS;
    }
} 