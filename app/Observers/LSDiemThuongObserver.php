<?php

namespace App\Observers;

use App\Models\LSDiemThuong;
use App\Services\MembershipRankService;

class LSDiemThuongObserver
{
    protected $membershipRankService;

    public function __construct(MembershipRankService $membershipRankService)
    {
        $this->membershipRankService = $membershipRankService;
    }

    /**
     * Handle the LSDiemThuong "created" event.
     *
     * @param  \App\Models\LSDiemThuong  $lsDiemThuong
     * @return void
     */
    public function created(LSDiemThuong $lsDiemThuong)
    {
        $this->updateUserRank($lsDiemThuong);
    }

    /**
     * Handle the LSDiemThuong "updated" event.
     *
     * @param  \App\Models\LSDiemThuong  $lsDiemThuong
     * @return void
     */
    public function updated(LSDiemThuong $lsDiemThuong)
    {
        $this->updateUserRank($lsDiemThuong);
    }

    /**
     * Handle the LSDiemThuong "deleted" event.
     *
     * @param  \App\Models\LSDiemThuong  $lsDiemThuong
     * @return void
     */
    public function deleted(LSDiemThuong $lsDiemThuong)
    {
        $this->updateUserRank($lsDiemThuong);
    }

    /**
     * Update the user's membership rank based on their points.
     *
     * @param  \App\Models\LSDiemThuong  $lsDiemThuong
     * @return void
     */
    private function updateUserRank(LSDiemThuong $lsDiemThuong)
    {
        // Get the user
        $user = $lsDiemThuong->user;
        
        if ($user) {
            // Update membership rank
            $this->membershipRankService->updateMembershipRank($user);
        }
    }
} 