<?php

namespace App\Http\Controllers;

use App\Models\LSDiemThuong;
use App\Models\User;
use App\Models\HoaDonVaThanhToan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PointHistoryController extends Controller
{
    public function index(Request $request)
    {
        $query = LSDiemThuong::with(['user', 'hoaDon']);
        
        // Tìm kiếm theo mã lịch sử điểm thưởng
        if ($request->has('maLSDT') && $request->maLSDT) {
            $query->where('MaLSDT', 'like', '%' . $request->maLSDT . '%');
        }
        
        // Tìm kiếm theo người dùng
        if ($request->has('user_id') && $request->user_id) {
            $query->where('Manguoidung', $request->user_id);
        }
        
        // Tìm kiếm theo hóa đơn
        if ($request->has('invoice_id') && $request->invoice_id) {
            $query->where('MaHD', $request->invoice_id);
        }
        
        // Tìm kiếm theo khoảng thời gian
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('Thoigian', '>=', $request->date_from);
        }
        
        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('Thoigian', '<=', $request->date_to);
        }
        
        // Tìm kiếm theo khoảng điểm
        if ($request->has('points_from') && $request->points_from) {
            $query->where('Sodiem', '>=', $request->points_from);
        }
        
        if ($request->has('points_to') && $request->points_to) {
            $query->where('Sodiem', '<=', $request->points_to);
        }
        
        // Sắp xếp
        $sortField = $request->get('sort', 'Thoigian');
        $sortDirection = $request->get('direction', 'desc');
        $query->orderBy($sortField, $sortDirection);
        
        $lsDiemThuongs = $query->paginate(10);
        
        // Lấy danh sách người dùng và hóa đơn cho bộ lọc
        $users = User::all();
        $hoaDons = HoaDonVaThanhToan::all();
        
        // Tính tổng điểm thưởng
        $totalPoints = LSDiemThuong::sum('Sodiem');
        
        // Tính điểm thưởng trong tháng hiện tại
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        $pointsThisMonth = LSDiemThuong::whereMonth('Thoigian', $currentMonth)
                                      ->whereYear('Thoigian', $currentYear)
                                      ->sum('Sodiem');
        
        // Tính số lượng người dùng có điểm thưởng
        $usersWithPoints = LSDiemThuong::select('Manguoidung')
                                      ->distinct()
                                      ->count();
        
        // Tính điểm thưởng theo người dùng
        $pointsByUser = LSDiemThuong::select('Manguoidung', DB::raw('sum(Sodiem) as total_points'))
                                   ->groupBy('Manguoidung')
                                   ->with('user')
                                   ->orderBy('total_points', 'desc')
                                   ->limit(5)
                                   ->get();
        
        return view('backend.lsdiemthuong.index', compact(
            'lsDiemThuongs', 
            'users', 
            'hoaDons', 
            'totalPoints', 
            'pointsThisMonth', 
            'usersWithPoints',
            'pointsByUser'
        ));
    }

    public function create()
    {
        $users = User::all();
        $hoaDons = HoaDonVaThanhToan::all();
        $maxMaLSDT = LSDiemThuong::max('MaLSDT') ?? 0;
        $suggestedMaLSDT = $maxMaLSDT + 1;

        return view('backend.lsdiemthuong.create', compact('users', 'hoaDons', 'suggestedMaLSDT'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'Thoigian' => 'required|date',
            'Sodiem' => 'required|integer',
            'Manguoidung' => 'required|exists:USER,Manguoidung',
            'MaHD' => 'nullable|exists:HOADON_VA_THANHTOAN,MaHD',
        ], [
            'Thoigian.required' => 'Thời gian không được để trống.',
            'Thoigian.date' => 'Thời gian không hợp lệ.',
            'Sodiem.required' => 'Số điểm không được để trống.',
            'Sodiem.integer' => 'Số điểm phải là số nguyên.',
            'Manguoidung.required' => 'Người dùng không được để trống.',
            'Manguoidung.exists' => 'Người dùng không tồn tại.',
            'MaHD.exists' => 'Hóa đơn không tồn tại.',
        ]);

        try {
            DB::beginTransaction();
            
            $maxMaLSDT = LSDiemThuong::max('MaLSDT') ?? 0;
            $newMaLSDT = $maxMaLSDT + 1;

            LSDiemThuong::create([
                'MaLSDT' => $newMaLSDT,
                'Thoigian' => $request->Thoigian,
                'Sodiem' => $request->Sodiem,
                'Manguoidung' => $request->Manguoidung,
                'MaHD' => $request->MaHD,
            ]);
            
            DB::commit();
            return redirect()->route('admin.lsdiemthuong.index')->with('success', 'Thêm lịch sử điểm thưởng thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Đã xảy ra lỗi: ' . $e->getMessage())->withInput();
        }
    }

    public function show($id)
    {
        $lsDiemThuong = LSDiemThuong::with(['user', 'hoaDon'])->findOrFail($id);
        return view('backend.lsdiemthuong.show', compact('lsDiemThuong'));
    }

    public function edit($id)
    {
        $lsDiemThuong = LSDiemThuong::findOrFail($id);
        $users = User::all();
        $hoaDons = HoaDonVaThanhToan::all();

        return view('backend.lsdiemthuong.edit', compact('lsDiemThuong', 'users', 'hoaDons'));
    }

    public function update(Request $request, $id)
    {
        $lsDiemThuong = LSDiemThuong::findOrFail($id);

        $request->validate([
            'Thoigian' => 'required|date',
            'Sodiem' => 'required|integer',
            'Manguoidung' => 'required|exists:USER,Manguoidung',
            'MaHD' => 'nullable|exists:HOADON_VA_THANHTOAN,MaHD',
        ], [
            'Thoigian.required' => 'Thời gian không được để trống.',
            'Thoigian.date' => 'Thời gian không hợp lệ.',
            'Sodiem.required' => 'Số điểm không được để trống.',
            'Sodiem.integer' => 'Số điểm phải là số nguyên.',
            'Manguoidung.required' => 'Người dùng không được để trống.',
            'Manguoidung.exists' => 'Người dùng không tồn tại.',
            'MaHD.exists' => 'Hóa đơn không tồn tại.',
        ]);

        try {
            DB::beginTransaction();
            
            $lsDiemThuong->update([
                'Thoigian' => $request->Thoigian,
                'Sodiem' => $request->Sodiem,
                'Manguoidung' => $request->Manguoidung,
                'MaHD' => $request->MaHD,
            ]);
            
            DB::commit();
            return redirect()->route('admin.lsdiemthuong.index')->with('success', 'Cập nhật lịch sử điểm thưởng thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Đã xảy ra lỗi: ' . $e->getMessage())->withInput();
        }
    }

    public function confirmDestroy($id)
    {
        $lsDiemThuong = LSDiemThuong::with(['user', 'hoaDon'])->findOrFail($id);
        return view('backend.lsdiemthuong.destroy', compact('lsDiemThuong'));
    }

    public function destroy($id)
    {
        $lsDiemThuong = LSDiemThuong::findOrFail($id);

        try {
            $lsDiemThuong->delete();
            return redirect()->route('admin.lsdiemthuong.index')->with('success', 'Xóa lịch sử điểm thưởng thành công!');
        } catch (\Exception $e) {
            return redirect()->route('admin.lsdiemthuong.index')->with('error', 'Không thể xóa lịch sử điểm thưởng!');
        }
    }
    
    // Thêm phương thức thống kê
    public function statistics(Request $request)
    {
        // Thống kê theo khoảng thời gian
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->format('Y-m-d'));
        
        // Chuyển đổi thành đối tượng Carbon
        $startDate = Carbon::parse($startDate)->startOfDay();
        $endDate = Carbon::parse($endDate)->endOfDay();
        
        // Tổng điểm trong khoảng thời gian
        $totalPoints = LSDiemThuong::whereBetween('Thoigian', [$startDate, $endDate])->sum('Sodiem');
        
        // Thống kê theo người dùng
        $pointsByUser = LSDiemThuong::whereBetween('Thoigian', [$startDate, $endDate])
            ->select('Manguoidung', DB::raw('sum(Sodiem) as total_points'), DB::raw('count(*) as count'))
            ->groupBy('Manguoidung')
            ->with('user')
            ->orderBy('total_points', 'desc')
            ->get();
        
        // Thống kê theo ngày
        $pointsByDate = LSDiemThuong::whereBetween('Thoigian', [$startDate, $endDate])
            ->select(DB::raw('DATE(Thoigian) as date'), DB::raw('sum(Sodiem) as total_points'), DB::raw('count(*) as count'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();
        
        // Thống kê theo hóa đơn
        $pointsByInvoice = LSDiemThuong::whereBetween('Thoigian', [$startDate, $endDate])
            ->whereNotNull('MaHD')
            ->select('MaHD', DB::raw('sum(Sodiem) as total_points'))
            ->groupBy('MaHD')
            ->with('hoaDon')
            ->orderBy('total_points', 'desc')
            ->limit(10)
            ->get();
        
        return view('backend.lsdiemthuong.statistics', compact(
            'totalPoints', 
            'pointsByUser', 
            'pointsByDate', 
            'pointsByInvoice',
            'startDate',
            'endDate'
        ));
    }
    
    // Phương thức xuất Excel
    public function exportExcel(Request $request)
    {
        // Thêm code xuất Excel ở đây
        // Có thể sử dụng thư viện như PhpSpreadsheet hoặc Laravel Excel
        
        return redirect()->back()->with('success', 'Xuất Excel thành công!');
    }
    
    // Phương thức lấy thông tin người dùng qua API
    public function getUserDetails($id)
    {
        try {
            $user = User::with(['account', 'hangThanhVien'])->findOrFail($id);
            
            // Tính tổng điểm của người dùng
            $totalPoints = LSDiemThuong::where('Manguoidung', $id)->sum('Sodiem');
            
            return response()->json([
                'success' => true,
                'data' => $user,
                'total_points' => $totalPoints
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy thông tin người dùng: ' . $e->getMessage()
            ], 404);
        }
    }
    
    // Phương thức lấy thông tin hóa đơn qua API
    public function getInvoiceDetails($id)
    {
        try {
            $invoice = HoaDonVaThanhToan::with(['user', 'datLich', 'phuongThuc'])->findOrFail($id);
            
            return response()->json([
                'success' => true,
                'data' => $invoice
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy thông tin hóa đơn: ' . $e->getMessage()
            ], 404);
        }
    }
}
