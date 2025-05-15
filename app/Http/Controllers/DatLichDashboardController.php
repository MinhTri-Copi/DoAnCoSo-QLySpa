<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DatLich;
use App\Models\User;
use App\Models\DichVu;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DatLichDashboardController extends Controller
{
    public function index(Request $request)
    {
        // Lấy khoảng thời gian từ request hoặc mặc định
        $startDate = $request->get('from_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('to_date', Carbon::now()->format('Y-m-d'));
        
        // Chuyển đổi thành đối tượng Carbon
        $startDate = Carbon::parse($startDate)->startOfDay();
        $endDate = Carbon::parse($endDate)->endOfDay();
        
        // Tổng số lịch đặt (không giới hạn theo thời gian)
        $totalBookings = DatLich::count();
        
        // Số lượng các trạng thái
        $pendingCount = DatLich::where('Trangthai_', 'Chờ xác nhận')->count();
        $confirmedCount = DatLich::where('Trangthai_', 'Đã xác nhận')->count();
        $cancelledCount = DatLich::where('Trangthai_', 'Đã hủy')->count();
        $completedCount = DatLich::where('Trangthai_', 'Hoàn thành')->count();
        
        // Dữ liệu biểu đồ
        $bookingsByDate = DatLich::select(DB::raw('DATE(Thoigiandatlich) as date'), DB::raw('count(*) as count'))
            ->whereBetween('Thoigiandatlich', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get();
        
        return view('backend.datlich.dashboard', compact(
            'totalBookings',
            'pendingCount',
            'confirmedCount',
            'cancelledCount',
            'completedCount',
            'bookingsByDate',
            'startDate',
            'endDate'
        ));
    }
} 