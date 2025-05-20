<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\LSDiemThuong;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class LSDiemThuongController extends Controller
{
    /**
     * Display a listing of the customer's reward points history.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = LSDiemThuong::with(['hoaDon'])
            ->where('Manguoidung', Auth::id());
        
        // Filter by date range if provided
        if ($request->has('start_date') && $request->start_date != '') {
            $query->where('Thoigian', '>=', $request->start_date . ' 00:00:00');
        }
        
        if ($request->has('end_date') && $request->end_date != '') {
            $query->where('Thoigian', '<=', $request->end_date . ' 23:59:59');
        }
        
        // Filter by point type (positive/negative)
        if ($request->has('point_type') && $request->point_type != '') {
            if ($request->point_type == 'earned') {
                $query->where('Sodiem', '>', 0);
            } elseif ($request->point_type == 'used') {
                $query->where('Sodiem', '<', 0);
            }
        }
        
        // Sort by date (default: newest first)
        $query->orderBy('Thoigian', $request->sort_order ?? 'desc');
        
        $pointsHistory = $query->paginate(10);
        
        // Get total points
        $user = User::find(Auth::id());
        $totalPoints = $user->getTotalPoints();
        
        // Get summary statistics
        $earnedPoints = LSDiemThuong::where('Manguoidung', Auth::id())
            ->where('Sodiem', '>', 0)
            ->sum('Sodiem');
            
        $usedPoints = LSDiemThuong::where('Manguoidung', Auth::id())
            ->where('Sodiem', '<', 0)
            ->sum('Sodiem');
        
        return view('customer.lsdiemthuong.index', compact(
            'pointsHistory', 
            'totalPoints', 
            'earnedPoints', 
            'usedPoints'
        ));
    }

    /**
     * Display the specified reward points history record.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $pointsRecord = LSDiemThuong::with(['hoaDon.datLich.dichVu', 'hoaDon.phuongThuc'])
            ->where('MaLSDT', $id)
            ->where('Manguoidung', Auth::id())
            ->firstOrFail();
            
        return view('customer.lsdiemthuong.show', compact('pointsRecord'));
    }
    
    /**
     * Display monthly summary of reward points.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function monthlySummary(Request $request)
    {
        // Get year parameter, default to current year
        $year = $request->year ?? date('Y');
        
        // Get monthly data
        $monthlyData = [];
        
        for ($month = 1; $month <= 12; $month++) {
            $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
            $endDate = Carbon::createFromDate($year, $month, 1)->endOfMonth();
            
            $earned = LSDiemThuong::where('Manguoidung', Auth::id())
                ->where('Sodiem', '>', 0)
                ->whereBetween('Thoigian', [$startDate, $endDate])
                ->sum('Sodiem');
                
            $used = LSDiemThuong::where('Manguoidung', Auth::id())
                ->where('Sodiem', '<', 0)
                ->whereBetween('Thoigian', [$startDate, $endDate])
                ->sum('Sodiem');
                
            $monthlyData[] = [
                'month' => $startDate->format('m/Y'),
                'earned' => $earned,
                'used' => abs($used),
                'net' => $earned + $used
            ];
        }
        
        // Get available years for filter
        $years = LSDiemThuong::where('Manguoidung', Auth::id())
            ->selectRaw('YEAR(Thoigian) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->toArray();
            
        if (!in_array(date('Y'), $years)) {
            $years[] = date('Y');
        }
        
        sort($years);
        
        return view('customer.lsdiemthuong.monthly-summary', compact('monthlyData', 'years', 'year'));
    }
    
    /**
     * Display recent reward points activities.
     *
     * @return \Illuminate\Http\Response
     */
    public function recent()
    {
        $recentActivities = LSDiemThuong::with(['hoaDon'])
            ->where('Manguoidung', Auth::id())
            ->orderBy('Thoigian', 'desc')
            ->limit(5)
            ->get();
            
        // Get total points
        $user = User::find(Auth::id());
        $totalPoints = $user->getTotalPoints();
        
        return view('customer.lsdiemthuong.recent', compact('recentActivities', 'totalPoints'));
    }
    
    /**
     * Display points earned from specific sources.
     *
     * @return \Illuminate\Http\Response
     */
    public function sources()
    {
        // Get points by source (invoice type or service)
        $pointsBySource = LSDiemThuong::with(['hoaDon.datLich.dichVu'])
            ->where('Manguoidung', Auth::id())
            ->where('Sodiem', '>', 0)
            ->get()
            ->groupBy(function($item) {
                // Group by service name if available, otherwise use "Other"
                if ($item->hoaDon && $item->hoaDon->datLich && $item->hoaDon->datLich->dichVu) {
                    return $item->hoaDon->datLich->dichVu->Tendichvu;
                }
                return 'Khác';
            })
            ->map(function($group) {
                return $group->sum('Sodiem');
            })
            ->sortDesc();
            
        return view('customer.lsdiemthuong.sources', compact('pointsBySource'));
    }
    
    /**
     * Search reward points history.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $query = LSDiemThuong::with(['hoaDon'])
            ->where('Manguoidung', Auth::id());
            
        // Search by invoice ID if provided
        if ($request->has('invoice_id') && $request->invoice_id != '') {
            $query->where('MaHD', 'like', '%' . $request->invoice_id . '%');
        }
        
        // Filter by point amount if provided
        if ($request->has('min_points') && $request->min_points != '') {
            $query->where('Sodiem', '>=', $request->min_points);
        }
        
        if ($request->has('max_points') && $request->max_points != '') {
            $query->where('Sodiem', '<=', $request->max_points);
        }
        
        // Sort by date (default: newest first)
        $query->orderBy('Thoigian', 'desc');
        
        $pointsHistory = $query->paginate(10);
        
        return view('customer.lsdiemthuong.search-results', compact('pointsHistory'));
    }
}