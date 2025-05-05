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

        $membershipRank = $user->membershipRank;
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
}