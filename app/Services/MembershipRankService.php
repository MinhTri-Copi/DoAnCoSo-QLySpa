<?php

namespace App\Services;

use App\Models\HangThanhVien;
use App\Models\User;

class MembershipRankService
{
    public function updateMembershipRank(User $user)
    {
        $totalPoints = $user->getTotalPoints();
        $rankData = $this->determineRank($totalPoints);

        $membershipRank = $user->hangThanhVien;
        if (!$membershipRank) {
            $maxMahang = HangThanhVien::max('Mahang') ?? 0;
            $newMahang = $maxMahang + 1;

            $membershipRank = HangThanhVien::create([
                'Mahang' => $newMahang,
                'Tenhang' => $rankData['Tenhang'],
                'Mota' => $rankData['Mota'],
                'Manguoidung' => $user->Manguoidung,
            ]);
        } else {
            $membershipRank->update([
                'Tenhang' => $rankData['Tenhang'],
                'Mota' => $rankData['Mota'],
            ]);
        }

        return $membershipRank;
    }

    protected function determineRank($points)
    {
        if ($points >= 5000) {
            return [
                'Tenhang' => 'Thành viên Kim Cương',
                'Mota' => 'Hạng thành viên cao nhất dành cho người dùng xuất sắc',
            ];
        } elseif ($points >= 1000) {
            return [
                'Tenhang' => 'Thành viên Bạch Kim',
                'Mota' => 'Hạng thành viên dành cho người dùng cao cấp',
            ];
        } elseif ($points >= 100) {
            return [
                'Tenhang' => 'Thành viên Vàng',
                'Mota' => 'Hạng thành viên dành cho người dùng tích cực',
            ];
        } else {
            return [
                'Tenhang' => 'Thành viên Bạc',
                'Mota' => 'Hạng thành viên dành cho người mới',
            ];
        }
    }
    
    /**
     * Update membership ranks for all users
     * 
     * @return array Updated user counts by rank
     */
    public function updateAllMembershipRanks()
    {
        $stats = [
            'Thành viên Bạc' => 0,
            'Thành viên Vàng' => 0,
            'Thành viên Bạch Kim' => 0,
            'Thành viên Kim Cương' => 0,
            'total' => 0
        ];
        
        // Get all users
        $users = User::with('lsDiemThuong')->get();
        
        foreach ($users as $user) {
            $membershipRank = $this->updateMembershipRank($user);
            if ($membershipRank) {
                $stats[$membershipRank->Tenhang]++;
                $stats['total']++;
            }
        }
        
        return $stats;
    }
}