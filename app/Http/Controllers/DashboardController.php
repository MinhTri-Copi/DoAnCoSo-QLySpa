<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\HoaDonVaThanhToan;
use App\Models\DatLich;
use App\Models\DichVu;
use App\Models\PhieuHoTro;
use App\Models\DanhGia;
use App\Models\Phong;
use App\Models\Account;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->input('period', 'daily');
        $currentTime = Carbon::now();
        
        // Xác định thời gian dựa vào period
        switch ($period) {
            case 'daily':
                $startDate = Carbon::today();
                $endDate = Carbon::today()->endOfDay();
                $compareStart = Carbon::yesterday();
                $compareEnd = Carbon::yesterday()->endOfDay();
                break;
            case 'weekly':
                $startDate = Carbon::now()->startOfWeek();
                $endDate = Carbon::now()->endOfWeek();
                $compareStart = Carbon::now()->subWeek()->startOfWeek();
                $compareEnd = Carbon::now()->subWeek()->endOfWeek();
                break;
            case 'quarterly':
                $startDate = Carbon::now()->startOfQuarter();
                $endDate = Carbon::now()->endOfQuarter();
                $compareStart = Carbon::now()->subQuarter()->startOfQuarter();
                $compareEnd = Carbon::now()->subQuarter()->endOfQuarter();
                break;
            case 'yearly':
                $startDate = Carbon::now()->startOfYear();
                $endDate = Carbon::now()->endOfYear();
                $compareStart = Carbon::now()->subYear()->startOfYear();
                $compareEnd = Carbon::now()->subYear()->endOfYear();
                break;
            case 'custom':
                if ($request->has('start_date') && $request->has('end_date')) {
                    $startDate = Carbon::parse($request->input('start_date'))->startOfDay();
                    $endDate = Carbon::parse($request->input('end_date'))->endOfDay();
                    
                    // Tính khoảng thời gian so sánh (cùng độ dài trước ngày bắt đầu)
                    $daysDiff = $startDate->diffInDays($endDate);
                    $compareEnd = $startDate->copy()->subDay();
                    $compareStart = $compareEnd->copy()->subDays($daysDiff);
                } else {
                    // Nếu không có ngày tùy chỉnh, sử dụng mặc định là tháng hiện tại
                    $startDate = Carbon::now()->startOfMonth();
                    $endDate = Carbon::now()->endOfMonth();
                    $compareStart = Carbon::now()->subMonth()->startOfMonth();
                    $compareEnd = Carbon::now()->subMonth()->endOfMonth();
                }
                break;
            case 'monthly':
            default:
                $startDate = Carbon::now()->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();
                $compareStart = Carbon::now()->subMonth()->startOfMonth();
                $compareEnd = Carbon::now()->subMonth()->endOfMonth();
                break;
        }
        
        // Thống kê tổng quan
        $stats = [
            'total_services' => DichVu::count(),
            'total_support_tickets' => PhieuHoTro::count(),
            'total_rooms' => Phong::count(),
        ];
        
        // Doanh thu vẫn tính từ hóa đơn đã thanh toán (Matrangthai = 4)
        $stats['total_revenue'] = HoaDonVaThanhToan::whereBetween('Ngaythanhtoan', [$startDate, $endDate])
            ->where('Matrangthai', 4)
            ->sum('Tongtien') ?? 0;
        
        // Đặt lịch tính từ bảng DATLICH với trạng thái đã xác nhận (Trangthai_ = 'Đã xác nhận')
        $stats['total_bookings'] = DatLich::whereBetween('Thoigiandatlich', [$startDate, $endDate])
            ->where('Trangthai_', 'Đã xác nhận')
            ->count();
        
        // Khách hàng tính từ tổng số user
        $stats['total_users'] = User::count();
        
        // Đánh giá tính từ bảng DANHGIA theo thời gian đánh giá
        $stats['total_reviews'] = DanhGia::whereBetween('Ngaydanhgia', [$startDate, $endDate])
            ->count();

        // Thống kê doanh thu theo tháng trong năm hiện tại
        $currentYear = Carbon::now()->year;
        $monthlyRevenue = HoaDonVaThanhToan::whereYear('Ngaythanhtoan', $currentYear)
            ->where('Matrangthai', 4)
            ->selectRaw('MONTH(Ngaythanhtoan) as month, SUM(Tongtien) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->pluck('total', 'month')
            ->toArray();

        // Chuẩn bị dữ liệu cho biểu đồ
        $chartData = [];
        for ($i = 1; $i <= 12; $i++) {
            $chartData[$i] = $monthlyRevenue[$i] ?? 0;
        }

        // Đặt lịch gần đây - chỉ hiển thị lịch đặt từ hôm nay và sắp xếp theo thứ tự thời gian
        $recentBookings = DatLich::with(['user', 'dichVu'])
            ->where('Thoigiandatlich', '>=', Carbon::today())
            ->orderBy('Thoigiandatlich', 'asc')
            ->take(10)
            ->get();
            
        // Đếm số lịch đặt sắp tới trong vòng 1 giờ tới chỉ trong ngày hôm nay
        $urgentBookingsCount = DatLich::where('Thoigiandatlich', '>=', Carbon::now())
            ->where('Thoigiandatlich', '<=', Carbon::now()->addHour())
            ->whereDate('Thoigiandatlich', Carbon::today())
            ->count();

        // Đánh giá gần đây
        try {
            $recentReviews = DanhGia::with('user')
                ->orderBy('MaDG', 'desc')
                ->take(5)
                ->get();
        } catch (\Exception $e) {
            $recentReviews = collect();
        }

        // Phiếu hỗ trợ đang mở
        try {
            $openSupportTickets = PhieuHoTro::where('Matrangthai', 1)
                ->with(['user', 'trangThai', 'ptHoTro'])
                ->orderBy('MaphieuHT', 'desc')
                ->take(5)
                ->get();
        } catch (\Exception $e) {
            $openSupportTickets = collect();
        }

        // Thống kê theo ngày trong tuần
        $dayOfWeekStats = DatLich::selectRaw('DAYOFWEEK(Thoigiandatlich) as day_of_week, COUNT(*) as count')
            ->groupBy('day_of_week')
            ->get()
            ->pluck('count', 'day_of_week')
            ->toArray();

        // Tỷ lệ đầy/trống của phòng
        $roomStats = [
            'available' => Phong::where('MatrangthaiP', 1)->count(),
            'occupied' => Phong::where('MatrangthaiP', 2)->count(),
            'maintenance' => Phong::where('MatrangthaiP', 3)->count()
        ];

        // Thống kê tài khoản theo vai trò
        $accountStats = Account::selectRaw('RoleID, COUNT(*) as count')
            ->groupBy('RoleID')
            ->get()
            ->pluck('count', 'RoleID')
            ->toArray();

        // Tỉ lệ hoạt động của phòng
        $stats['room_occupancy_rate'] = Phong::where('MatrangthaiP', 2)->count() / max(Phong::count(), 1) * 100;
        
        // PHÂN TÍCH DOANH THU - LUÔN SO SÁNH THÁNG HIỆN TẠI VỚI THÁNG TRƯỚC, KHÔNG PHỤ THUỘC VÀO BỘ LỌC
        
        // Thiết lập khoảng thời gian tháng hiện tại và tháng trước 
        $currentMonthStart = Carbon::now()->startOfMonth();
        $currentMonthEnd = Carbon::now()->endOfMonth();
        $previousMonthStart = Carbon::now()->subMonth()->startOfMonth();
        $previousMonthEnd = Carbon::now()->subMonth()->endOfMonth();
        
        // Doanh thu tháng hiện tại và tháng trước
        $currentMonthRevenue = HoaDonVaThanhToan::whereBetween('Ngaythanhtoan', [$currentMonthStart, $currentMonthEnd])
            ->where('Matrangthai', 4)
            ->sum('Tongtien') ?? 0;
            
        $previousMonthRevenue = HoaDonVaThanhToan::whereBetween('Ngaythanhtoan', [$previousMonthStart, $previousMonthEnd])
            ->where('Matrangthai', 4)
            ->sum('Tongtien') ?? 0;
        
        // Đặt lịch tháng hiện tại và tháng trước - CHỈ ĐẾM ĐƠN ĐẶT LỊCH ĐÃ XÁC NHẬN
        $currentMonthBookings = DatLich::whereBetween('Thoigiandatlich', [$currentMonthStart, $currentMonthEnd])
            ->where('Trangthai_', 'Đã xác nhận')
            ->count();
            
        $previousMonthBookings = DatLich::whereBetween('Thoigiandatlich', [$previousMonthStart, $previousMonthEnd])
            ->where('Trangthai_', 'Đã xác nhận')
            ->count();
        
        // Dịch vụ tháng hiện tại và tháng trước - TÍNH TẤT CẢ DỊCH VỤ, KHÔNG PHỤ THUỘC VÀO TRẠNG THÁI
        $currentMonthServices = DichVu::count(); // Tổng số dịch vụ hiện có
            
        $previousMonthServices = $currentMonthServices; // Giả sử số dịch vụ không thay đổi giữa các tháng
        
        // Tính tỷ lệ thay đổi cho phân tích doanh thu
        $revenueChange = $previousMonthRevenue > 0 ? round(($currentMonthRevenue - $previousMonthRevenue) / $previousMonthRevenue * 100) : 0;
        $bookingsChange = $previousMonthBookings > 0 ? round(($currentMonthBookings - $previousMonthBookings) / $previousMonthBookings * 100) : 0;
        $servicesChange = 0; // Không thay đổi vì chúng ta đang sử dụng tổng số dịch vụ

        return view('backend.dashboard', compact(
            'stats', 
            'chartData', 
            'recentBookings', 
            'recentReviews',
            'openSupportTickets',
            'dayOfWeekStats',
            'roomStats',
            'accountStats',
            'period',
            'urgentBookingsCount',
            'bookingsChange',
            'servicesChange',
            'revenueChange',
            'currentMonthBookings',  // Thêm biến này để hiển thị số lượng đúng
            'currentMonthServices',   // Thêm biến này để hiển thị số lượng đúng
            'currentMonthRevenue'    // Thêm biến này để hiển thị doanh thu đúng
        ));
    }

    public function getRevenueData(Request $request)
    {
        try {
            $period = $request->input('period', 'monthly');
            $data = [];
            $currentYear = Carbon::now()->year;
            $currentMonth = Carbon::now()->month;

            switch ($period) {
                case 'daily':
                    $startDate = Carbon::now()->subDays(30);
                    
                    // Query lấy dữ liệu - Chỉ tính hóa đơn với Matrangthai = 4
                    $revenueQuery = HoaDonVaThanhToan::where('Ngaythanhtoan', '>=', $startDate)
                        ->where('Matrangthai', 4)
                        ->selectRaw('DATE(Ngaythanhtoan) as date, SUM(Tongtien) as total')
                        ->groupBy('date')
                        ->orderBy('date');
                    
                    $realData = $revenueQuery->get();
                    
                    // Đảm bảo luôn có dữ liệu cho 30 ngày gần nhất
                    $dailyData = [];
                    for ($i = 29; $i >= 0; $i--) {
                        $date = Carbon::now()->subDays($i)->format('Y-m-d');
                        $dailyData[$date] = 0;
                    }
                    
                    foreach ($realData as $item) {
                        $dailyData[$item->date] = (int)$item->total;
                    }
                    
                    foreach ($dailyData as $date => $total) {
                        $data[] = [
                            'date' => $date,
                            'total' => $total
                        ];
                    }
                    
                    break;
                    
                case 'quarterly':
                    // Lấy dữ liệu doanh thu theo quý trong năm hiện tại
                    $revenueQuery = HoaDonVaThanhToan::whereYear('Ngaythanhtoan', $currentYear)
                        ->where('Matrangthai', 4)
                        ->selectRaw('QUARTER(Ngaythanhtoan) as quarter, SUM(Tongtien) as total')
                        ->groupBy('quarter')
                        ->orderBy('quarter');
                    
                    $realData = $revenueQuery->get();
                    
                    // Chuẩn bị dữ liệu cho 4 quý
                    $quarterlyData = [1 => 0, 2 => 0, 3 => 0, 4 => 0];
                    
                    foreach ($realData as $item) {
                        $quarterlyData[$item->quarter] = (int)$item->total;
                    }
                    
                    foreach ($quarterlyData as $quarter => $total) {
                        $data[] = [
                            'quarter' => $quarter,
                            'total' => $total,
                            'label' => 'Q' . $quarter
                        ];
                    }
                    
                    break;
                    
                case 'yearly':
                    $revenueQuery = HoaDonVaThanhToan::where('Matrangthai', 4)
                        ->selectRaw('YEAR(Ngaythanhtoan) as year, SUM(Tongtien) as total')
                        ->groupBy('year')
                        ->orderBy('year');
                    
                    $realData = $revenueQuery->get();
                    $years = $realData->pluck('year')->toArray();
                    
                    $yearlyData = [];
                    if (count($years) > 0) {
                        $minYear = min($years);
                        $maxYear = max($years);
                        
                        // Đảm bảo có ít nhất 5 năm dữ liệu
                        $minYear = min($minYear, Carbon::now()->year - 4);
                        $maxYear = max($maxYear, Carbon::now()->year);
                        
                        for ($year = $minYear; $year <= $maxYear; $year++) {
                            $yearlyData[$year] = 0;
                        }
                        
                        foreach ($realData as $item) {
                            $yearlyData[$item->year] = (int)$item->total;
                        }
                        
                        foreach ($yearlyData as $year => $total) {
                            $data[] = [
                                'year' => $year,
                                'total' => $total
                            ];
                        }
                    } else {
                        // Nếu không có dữ liệu, tạo dữ liệu mẫu cho 5 năm gần đây
                        for ($i = 4; $i >= 0; $i--) {
                            $year = Carbon::now()->year - $i;
                            $data[] = [
                                'year' => $year,
                                'total' => 0
                            ];
                        }
                    }
                    
                    break;
                
                case 'custom':
                    // Xử lý khoảng thời gian tùy chỉnh
                    if ($request->has('start_date') && $request->has('end_date')) {
                        $startDate = Carbon::parse($request->input('start_date'))->startOfDay();
                        $endDate = Carbon::parse($request->input('end_date'))->endOfDay();
                        
                        // Tính toán đơn vị thời gian phù hợp (ngày, tuần, tháng) dựa vào khoảng thời gian
                        $daysDiff = $startDate->diffInDays($endDate);
                        
                        if ($daysDiff <= 60) {
                            // Hiển thị theo ngày nếu dưới 60 ngày
                            $revenueQuery = HoaDonVaThanhToan::whereBetween('Ngaythanhtoan', [$startDate, $endDate])
                                ->where('Matrangthai', 4)
                                ->selectRaw('DATE(Ngaythanhtoan) as date, SUM(Tongtien) as total')
                                ->groupBy('date')
                                ->orderBy('date');
                            
                            $realData = $revenueQuery->get();
                            
                            // Chuẩn bị mảng ngày 
                            $customData = [];
                            $currentDate = $startDate->copy();
                            
                            while ($currentDate->lte($endDate)) {
                                $dateStr = $currentDate->format('Y-m-d');
                                $customData[$dateStr] = 0;
                                $currentDate->addDay();
                            }
                            
                            foreach ($realData as $item) {
                                $customData[$item->date] = (int)$item->total;
                            }
                            
                            foreach ($customData as $date => $total) {
                                $data[] = [
                                    'date' => $date,
                                    'label' => Carbon::parse($date)->format('d/m'),
                                    'total' => $total
                                ];
                            }
                        } else {
                            // Hiển thị theo tháng nếu trên 60 ngày
                            $revenueQuery = HoaDonVaThanhToan::whereBetween('Ngaythanhtoan', [$startDate, $endDate])
                                ->where('Matrangthai', 4)
                                ->selectRaw('YEAR(Ngaythanhtoan) as year, MONTH(Ngaythanhtoan) as month, SUM(Tongtien) as total')
                                ->groupBy('year', 'month')
                                ->orderBy('year')
                                ->orderBy('month');
                            
                            $realData = $revenueQuery->get();
                            
                            // Chuẩn bị mảng tháng
                            $customData = [];
                            $currentDate = $startDate->copy()->startOfMonth();
                            
                            while ($currentDate->lte($endDate)) {
                                $yearMonth = $currentDate->format('Y-m');
                                $customData[$yearMonth] = 0;
                                $currentDate->addMonth();
                            }
                            
                            foreach ($realData as $item) {
                                $month = str_pad($item->month, 2, '0', STR_PAD_LEFT);
                                $yearMonth = $item->year . '-' . $month;
                                $customData[$yearMonth] = (int)$item->total;
                            }
                            
                            foreach ($customData as $yearMonth => $total) {
                                $date = Carbon::parse($yearMonth . '-01');
                                $data[] = [
                                    'yearMonth' => $yearMonth,
                                    'label' => $date->format('m/Y'),
                                    'total' => $total
                                ];
                            }
                        }
                    } else {
                        // Trả về mảng trống nếu không có ngày bắt đầu hoặc kết thúc
                        $data = [];
                    }
                    
                    break;

                default: // monthly
                    $revenueQuery = HoaDonVaThanhToan::whereYear('Ngaythanhtoan', $currentYear)
                        ->where('Matrangthai', 4)
                        ->selectRaw('MONTH(Ngaythanhtoan) as month, SUM(Tongtien) as total')
                        ->groupBy('month')
                        ->orderBy('month');
                    
                    $realData = $revenueQuery->get();
                    
                    $monthlyData = [];
                    for ($i = 1; $i <= 12; $i++) {
                        $monthlyData[$i] = 0;
                    }
                    
                    foreach ($realData as $item) {
                        $monthlyData[$item->month] = (int)$item->total;
                    }
                    
                    foreach ($monthlyData as $month => $total) {
                        $data[] = [
                            'month' => $month,
                            'total' => $total
                        ];
                    }
                    
                    break;
            }
            
            return response()->json($data);
        } catch (\Exception $e) {
            \Log::error('Error in getRevenueData: ' . $e->getMessage());
            return response()->json([]);
        }
    }

    public function getServiceStats()
    {
        // Lấy thống kê dịch vụ được đặt nhiều nhất - chỉ tính dịch vụ đã được thanh toán
        $topServices = DatLich::join('HOADON_VA_THANHTOAN', 'DATLICH.MaDL', '=', 'HOADON_VA_THANHTOAN.MaDL')
            ->where('HOADON_VA_THANHTOAN.Matrangthai', 4)
            ->select('DATLICH.MaDV', DB::raw('COUNT(*) as count'))
            ->groupBy('DATLICH.MaDV')
            ->orderBy('count', 'desc')
            ->take(10)
            ->get();

        return response()->json($topServices);
    }

    public function getFilteredStats(Request $request)
    {
        $period = $request->input('period', 'monthly');
        $startDate = null;
        $endDate = null;
        $compareStart = null;
        $compareEnd = null;
        $stats = [];
        
        \Log::info('getFilteredStats called with period: ' . $period);
        
        // Xử lý trường hợp tùy chỉnh khoảng thời gian
        if ($period === 'custom' && $request->has('start_date') && $request->has('end_date')) {
            $startDate = Carbon::parse($request->input('start_date'))->startOfDay();
            $endDate = Carbon::parse($request->input('end_date'))->endOfDay();
            
            \Log::info("Custom date range: {$startDate->toDateTimeString()} to {$endDate->toDateTimeString()}");
            
            // Tính khoảng thời gian so sánh (cùng độ dài trước ngày bắt đầu)
            $daysDiff = $startDate->diffInDays($endDate);
            $compareEnd = $startDate->copy()->subDay();
            $compareStart = $compareEnd->copy()->subDays($daysDiff);
        } else {
            // Xác định thời gian dựa vào period
            switch ($period) {
                case 'daily':
                    $startDate = Carbon::today();
                    $endDate = Carbon::today()->endOfDay();
                    $compareStart = Carbon::yesterday();
                    $compareEnd = Carbon::yesterday()->endOfDay();
                    break;
                
                case 'weekly':
                    $startDate = Carbon::now()->startOfWeek();
                    $endDate = Carbon::now()->endOfWeek();
                    $compareStart = Carbon::now()->subWeek()->startOfWeek();
                    $compareEnd = Carbon::now()->subWeek()->endOfWeek();
                    break;
                
                case 'quarterly':
                    $startDate = Carbon::now()->startOfQuarter();
                    $endDate = Carbon::now()->endOfQuarter();
                    $compareStart = Carbon::now()->subQuarter()->startOfQuarter();
                    $compareEnd = Carbon::now()->subQuarter()->endOfQuarter();
                    break;
                
                case 'yearly':
                    $startDate = Carbon::now()->startOfYear();
                    $endDate = Carbon::now()->endOfYear();
                    $compareStart = Carbon::now()->subYear()->startOfYear();
                    $compareEnd = Carbon::now()->subYear()->endOfYear();
                    break;
                
                case 'monthly':
                default:
                    $startDate = Carbon::now()->startOfMonth();
                    $endDate = Carbon::now()->endOfMonth();
                    $compareStart = Carbon::now()->subMonth()->startOfMonth();
                    $compareEnd = Carbon::now()->subMonth()->endOfMonth();
                    break;
            }
        }
        
        try {
            // ===== THỐNG KÊ CHÍNH TRÊN DASHBOARD (không thay đổi) =====
            
            // Doanh thu cho thống kê chính - giữ nguyên logic cũ
            $dashboardRevenue = HoaDonVaThanhToan::whereBetween('Ngaythanhtoan', [$startDate, $endDate])
                ->where('Matrangthai', 4)
                ->sum('Tongtien') ?? 0;
                
            $previousDashboardRevenue = HoaDonVaThanhToan::whereBetween('Ngaythanhtoan', [$compareStart, $compareEnd])
                ->where('Matrangthai', 4)
                ->sum('Tongtien') ?? 0;
                
            // Đặt lịch cho thống kê chính - giữ nguyên logic cũ
            $dashboardBookings = DatLich::whereBetween('Thoigiandatlich', [$startDate, $endDate])
                ->count(); // đếm tất cả các lịch đặt, không phân biệt trạng thái
                
            $previousDashboardBookings = DatLich::whereBetween('Thoigiandatlich', [$compareStart, $compareEnd])
                ->count();
                
            // Thống kê khách hàng và đánh giá - giữ nguyên logic cũ
            $currentCustomers = User::count();
            $previousCustomers = $currentCustomers; // Giá trị không đổi nên tỷ lệ thay đổi là 0%
            
            $currentReviews = DanhGia::whereBetween('Ngaydanhgia', [$startDate, $endDate])
                ->count();
            
            $previousReviews = DanhGia::whereBetween('Ngaydanhgia', [$compareStart, $compareEnd])
                ->count();
                
            // ===== PHÂN TÍCH DOANH THU (cập nhật theo yêu cầu mới) =====
            
            // 3. Doanh thu trong phân tích - tính từ hóa đơn đã thanh toán (Matrangthai = 4)
            $currentRevenue = HoaDonVaThanhToan::whereBetween('Ngaythanhtoan', [$startDate, $endDate])
                ->where('Matrangthai', 4)
                ->sum('Tongtien') ?? 0;
            
            // Doanh thu kỳ trước
            $previousRevenue = HoaDonVaThanhToan::whereBetween('Ngaythanhtoan', [$compareStart, $compareEnd])
                ->where('Matrangthai', 4)
                ->sum('Tongtien') ?? 0;
            
            // 1. Đặt lịch trong phân tích - tính từ bảng DATLICH với trạng thái đã xác nhận
            $currentBookings = DatLich::whereBetween('Thoigiandatlich', [$startDate, $endDate])
                ->where('Trangthai_', 'Đã xác nhận')
                ->count();
            
            // Đặt lịch kỳ trước
            $previousBookings = DatLich::whereBetween('Thoigiandatlich', [$compareStart, $compareEnd])
                ->where('Trangthai_', 'Đã xác nhận')
                ->count();
            
            // 2. Tổng dịch vụ trong phân tích - đếm số lần dịch vụ được sử dụng
            $currentServices = DatLich::whereBetween('Thoigiandatlich', [$startDate, $endDate])
                ->where('Trangthai_', 'Đã xác nhận')
                ->count(); // Mỗi đặt lịch tương ứng với một lần sử dụng dịch vụ
            
            // Tổng dịch vụ kỳ trước
            $previousServices = DatLich::whereBetween('Thoigiandatlich', [$compareStart, $compareEnd])
                ->where('Trangthai_', 'Đã xác nhận')
                ->count();
            
            \Log::info("Revenue Analytics - Current: {$currentRevenue}, Previous: {$previousRevenue}");
            \Log::info("Booking Analytics - Current: {$currentBookings}, Previous: {$previousBookings}");
            \Log::info("Services Analytics - Current: {$currentServices}, Previous: {$previousServices}");
            
            // Tính tỷ lệ thay đổi cho thống kê chính
            $dashboardRevenueChange = $previousDashboardRevenue > 0 ? round(($dashboardRevenue - $previousDashboardRevenue) / $previousDashboardRevenue * 100) : 0;
            $dashboardBookingsChange = $previousDashboardBookings > 0 ? round(($dashboardBookings - $previousDashboardBookings) / $previousDashboardBookings * 100) : 0;
            $customersChange = 0; // Không thay đổi vì chỉ đếm tổng số user
            $reviewsChange = $previousReviews > 0 ? round(($currentReviews - $previousReviews) / $previousReviews * 100) : 0;
            
            // Tính tỷ lệ thay đổi cho phân tích doanh thu
            $revenueChange = $previousRevenue > 0 ? round(($currentRevenue - $previousRevenue) / $previousRevenue * 100) : 0;
            $bookingsChange = $previousBookings > 0 ? round(($currentBookings - $previousBookings) / $previousBookings * 100) : 0;
            $servicesChange = $previousServices > 0 ? round(($currentServices - $previousServices) / $previousServices * 100) : 0;
            
            // Định dạng nhãn cho bộ lọc
            $periodLabel = '';
            $periodText = '';
            
            // Xác định nhãn và text dựa vào period
            switch ($period) {
                case 'daily':
                    $periodLabel = 'Hôm nay';
                    $periodText = 'so với hôm qua';
                    break;
                case 'weekly':
                    $periodLabel = 'Tuần này';
                    $periodText = 'so với tuần trước';
                    break;
                case 'monthly':
                    $periodLabel = 'Tháng này';
                    $periodText = 'so với tháng trước';
                    break;
                case 'quarterly':
                    $periodLabel = 'Quý này';
                    $periodText = 'so với quý trước';
                    break;
                case 'yearly':
                    $periodLabel = 'Năm này';
                    $periodText = 'so với năm trước';
                    break;
                case 'custom':
                    $periodLabel = 'Tùy chỉnh';
                    $periodText = 'so với kỳ trước';
                    break;
            }
            
            $response = [
                'success' => true,
                // Thống kê chính cho dashboard
                'dashboard_revenue' => $dashboardRevenue,
                'dashboard_bookings' => $dashboardBookings,
                'dashboard_revenue_change' => $dashboardRevenueChange,
                'dashboard_bookings_change' => $dashboardBookingsChange,
                // Thống kê khách hàng và đánh giá (không thay đổi)
                'total_customers' => $currentCustomers,
                'total_reviews' => $currentReviews,
                'customers_change' => $customersChange,
                'reviews_change' => $reviewsChange,
                // Thông tin thời gian
                'period_label' => $periodLabel,
                'period_text' => $periodText
            ];
            
            \Log::info('Returning filtered stats data');
            
            return response()->json($response);
        } catch (\Exception $e) {
            \Log::error('Error in getFilteredStats: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
} 