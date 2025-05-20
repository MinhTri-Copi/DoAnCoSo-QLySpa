<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\HangThanhVien;
use App\Models\User;
use App\Models\PointHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class HangThanhVienController extends Controller
{
    /**
     * Display the customer's membership information.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Không lấy dữ liệu từ DB, chỉ render view giao diện mẫu
        return view('customer.membership');
    }
    
    /**
     * Display the customer's point history.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function pointHistory(Request $request)
    {
        $user = Auth::user();
        $query = PointHistory::where('Manguoidung', $user->id)
            ->with(['hoaDon']);
        
        // Filter by date range if provided
        if ($request->has('start_date') && $request->start_date != '') {
            $query->whereDate('Thoigian', '>=', $request->start_date);
        }
        
        if ($request->has('end_date') && $request->end_date != '') {
            $query->whereDate('Thoigian', '<=', $request->end_date);
        }
        
        // Filter by type if provided
        if ($request->has('type')) {
            switch ($request->type) {
                case 'earned':
                    $query->where('Soluongdiem', '>', 0);
                    break;
                case 'used':
                    $query->where('Soluongdiem', '<', 0);
                    break;
                case 'expired':
                    $query->where('Trangthai', 'expired');
                    break;
            }
        }
        
        // Default sort by date (newest first)
        $pointHistory = $query->orderBy('Thoigian', 'desc')->paginate(10);
        
        // Calculate summary statistics
        $totalPoints = $user->Diemtichluy;
        $earnedPoints = PointHistory::where('Manguoidung', $user->id)
            ->where('Soluongdiem', '>', 0)
            ->sum('Soluongdiem');
        $usedPoints = abs(PointHistory::where('Manguoidung', $user->id)
            ->where('Soluongdiem', '<', 0)
            ->sum('Soluongdiem'));
        $expiredPoints = PointHistory::where('Manguoidung', $user->id)
            ->where('Trangthai', 'expired')
            ->sum('Soluongdiem');
        
        // Calculate points expiring soon
        $expiringPoints = PointHistory::where('Manguoidung', $user->id)
            ->where('Thoigianhethan', '>', Carbon::now())
            ->where('Thoigianhethan', '<', Carbon::now()->addDays(30))
            ->sum('Soluongdiem');
        
        return view('customer.thanhvien.point_history', compact(
            'pointHistory',
            'totalPoints',
            'earnedPoints',
            'usedPoints',
            'expiredPoints',
            'expiringPoints'
        ));
    }
    
    /**
     * Display all membership ranks and their benefits.
     *
     * @return \Illuminate\Http\Response
     */
    public function allRanks()
    {
        $user = Auth::user();
        $allRanks = HangThanhVien::orderBy('Diemtoithieu', 'asc')->get();
        
        // Determine user's current rank
        $currentRank = null;
        if (isset($user->Diemtichluy)) {
            $currentRank = HangThanhVien::where('Diemtoithieu', '<=', $user->Diemtichluy)
                ->orderBy('Diemtoithieu', 'desc')
                ->first();
        }
        
        return view('customer.thanhvien.ranks', compact('allRanks', 'currentRank', 'user'));
    }
} 