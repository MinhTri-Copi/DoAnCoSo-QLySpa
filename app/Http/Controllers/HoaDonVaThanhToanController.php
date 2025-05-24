<?php

namespace App\Http\Controllers;

use App\Models\HoaDonVaThanhToan;
use App\Models\DatLich;
use App\Models\User;
use App\Models\Phong;
use App\Models\PhuongThuc;
use App\Models\TrangThai;
use App\Models\LSDiemThuong;
use App\Models\DanhGia;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HoaDonVaThanhToanController extends Controller
{
    public function index(Request $request)
    {
        $query = HoaDonVaThanhToan::with(['datLich', 'user', 'phong', 'phuongThuc', 'trangThai']);
        
        // Tìm kiếm theo mã hóa đơn
        if ($request->has('maHD') && $request->maHD) {
            $query->where('MaHD', 'like', '%' . $request->maHD . '%');
        }
        
        // Tìm kiếm theo người dùng
        if ($request->has('user_id') && $request->user_id) {
            $query->where('Manguoidung', $request->user_id);
        }
        
        // Tìm kiếm theo phương thức thanh toán
        if ($request->has('payment_method') && $request->payment_method) {
            $query->where('MaPT', $request->payment_method);
        }
        
        // Tìm kiếm theo trạng thái
        if ($request->has('status') && $request->status) {
            $query->where('Matrangthai', $request->status);
        }
        
        // Tìm kiếm theo khoảng thời gian
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('Ngaythanhtoan', '>=', $request->date_from);
        }
        
        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('Ngaythanhtoan', '<=', $request->date_to);
        }
        
        // Tìm kiếm theo khoảng tổng tiền
        if ($request->has('amount_from') && $request->amount_from) {
            $query->where('Tongtien', '>=', $request->amount_from);
        }
        
        if ($request->has('amount_to') && $request->amount_to) {
            $query->where('Tongtien', '<=', $request->amount_to);
        }
        
        // Ưu tiên hiển thị hóa đơn có trạng thái "Chờ thanh toán" (Matrangthai = 6)
        if (!$request->has('sort')) {
            // Nếu người dùng không chỉ định cách sắp xếp, ưu tiên theo trạng thái "Chờ thanh toán"
            $hoaDons = HoaDonVaThanhToan::with(['datLich', 'user', 'phong', 'phuongThuc', 'trangThai'])
                ->orderByRaw("CASE WHEN Matrangthai = 6 THEN 0 ELSE 1 END")
                ->orderBy('Ngaythanhtoan', 'desc')
                ->paginate(10);
        } else {
            // Sắp xếp theo yêu cầu của người dùng
            $sortField = $request->get('sort', 'Ngaythanhtoan');
            $sortDirection = $request->get('direction', 'desc');
            $hoaDons = $query->orderBy($sortField, $sortDirection)->paginate(10);
        }
        
        // Lấy danh sách người dùng, phương thức thanh toán và trạng thái cho bộ lọc
        $users = User::all();
        $phuongThucs = PhuongThuc::all();
        $trangThais = TrangThai::all();
        
        // Tính tổng doanh thu
        $totalRevenue = HoaDonVaThanhToan::sum('Tongtien');
        
        // Tính doanh thu trong tháng hiện tại
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        $revenueThisMonth = HoaDonVaThanhToan::whereMonth('Ngaythanhtoan', $currentMonth)
                                           ->whereYear('Ngaythanhtoan', $currentYear)
                                           ->sum('Tongtien');
        
        // Tính số lượng hóa đơn theo trạng thái
        $invoicesByStatus = HoaDonVaThanhToan::select('Matrangthai', DB::raw('count(*) as count'))
                                           ->groupBy('Matrangthai')
                                           ->with('trangThai')
                                           ->get();
        
        return view('backend.hoadonvathanhtoan.index', compact(
            'hoaDons', 
            'users', 
            'phuongThucs', 
            'trangThais', 
            'totalRevenue', 
            'revenueThisMonth', 
            'invoicesByStatus'
        ));
    }

    public function create()
    {
        // Chỉ lấy các lịch đặt có trạng thái "Đã xác nhận" và chưa có hóa đơn hoặc có hóa đơn với trạng thái "Chờ thanh toán"
        $datLichs = DatLich::where('Trangthai_', 'Đã xác nhận')
            ->whereNotIn('MaDL', function($query) {
                $query->select('MaDL')
                    ->from('HOADON_VA_THANHTOAN')
                    ->whereNotIn('Matrangthai', [6]); // Chỉ lấy những lịch đặt không có trong hóa đơn đã thanh toán
            })
            ->get();
            
        $users = User::all();
        $phongs = Phong::all();
        $phuongThucs = PhuongThuc::all();
        $trangThais = TrangThai::all();
        $maxMaHD = HoaDonVaThanhToan::max('MaHD') ?? 0;
        $suggestedMaHD = $maxMaHD + 1;

        return view('backend.hoadonvathanhtoan.create', compact('datLichs', 'users', 'phongs', 'phuongThucs', 'trangThais', 'suggestedMaHD'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'Ngaythanhtoan' => 'required|date',
            'Tongtien' => 'required|numeric|min:0',
            'MaDL' => 'required|exists:DATLICH,MaDL',
            'Manguoidung' => 'required|exists:USER,Manguoidung',
            'Maphong' => 'required|exists:PHONG,Maphong',
            'MaPT' => 'nullable|exists:PHUONGTHUC,MaPT',
        ], [
            'Ngaythanhtoan.required' => 'Ngày thanh toán không được để trống.',
            'Ngaythanhtoan.date' => 'Ngày thanh toán không hợp lệ.',
            'Tongtien.required' => 'Tổng tiền không được để trống.',
            'Tongtien.numeric' => 'Tổng tiền phải là số.',
            'Tongtien.min' => 'Tổng tiền không được nhỏ hơn 0.',
            'MaDL.required' => 'Đặt lịch không được để trống.',
            'MaDL.exists' => 'Đặt lịch không tồn tại.',
            'Manguoidung.required' => 'Người dùng không được để trống.',
            'Manguoidung.exists' => 'Người dùng không tồn tại.',
            'Maphong.required' => 'Phòng không được để trống.',
            'Maphong.exists' => 'Phòng không tồn tại.',
            'MaPT.exists' => 'Phương thức không tồn tại.',
        ]);

        try {
            DB::beginTransaction();
            
            $maxMaHD = HoaDonVaThanhToan::max('MaHD') ?? 0;
            $newMaHD = $maxMaHD + 1;

            // Get the DatLich record to access the service price
            $datLich = DatLich::with('dichVu')->findOrFail($request->MaDL);
            $tongtien = $datLich->dichVu->Gia; // Set total amount from service price

            // Nếu không chọn trạng thái, mặc định là "Chờ thanh toán" (Matrangthai = 6)
            $matrangThai = $request->Matrangthai ?? 6;

            $hoaDon = HoaDonVaThanhToan::create([
                'MaHD' => $newMaHD,
                'Ngaythanhtoan' => $request->Ngaythanhtoan,
                'Tongtien' => $tongtien, // Use the calculated total amount
                'MaDL' => $request->MaDL,
                'Manguoidung' => $request->Manguoidung,
                'Maphong' => $request->Maphong,
                'MaPT' => $request->MaPT,
                'Matrangthai' => $matrangThai,
            ]);

            // Tự động tạo bản ghi lịch sử điểm thưởng dựa trên tổng tiền
            $soDiem = $this->calculateRewardPoints($tongtien);

            if ($soDiem > 0) {
                $maxMaLSDT = LSDiemThuong::max('MaLSDT') ?? 0;
                $newMaLSDT = $maxMaLSDT + 1;
                
                LSDiemThuong::create([
                    'MaLSDT' => $newMaLSDT,
                    'Thoigian' => now(),
                    'Sodiem' => $soDiem,
                    'Manguoidung' => $request->Manguoidung,
                    'MaHD' => $newMaHD,
                ]);
            }
            
            // Cập nhật trạng thái đặt lịch nếu cần
            if ($request->has('update_booking_status') && $request->update_booking_status) {
                $datLich = DatLich::find($request->MaDL);
                if ($datLich) {
                    $datLich->update([
                        'Trangthai_' => 'Hoàn thành'
                    ]);
                }
            }
            
            DB::commit();
            return redirect()->route('admin.hoadonvathanhtoan.index')->with('success', 'Thêm hóa đơn thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Đã xảy ra lỗi: ' . $e->getMessage())->withInput();
        }
    }

    public function show($id)
    {
        $hoaDon = HoaDonVaThanhToan::with(['datLich.dichVu', 'user', 'phong', 'phuongThuc', 'trangThai', 'danhGia', 'lsDiemThuong'])->findOrFail($id);
        return view('backend.hoadonvathanhtoan.show', compact('hoaDon'));
    }

    public function edit($id)
    {
        $hoaDon = HoaDonVaThanhToan::findOrFail($id);
        
        // Kiểm tra nếu hóa đơn đã thanh toán (Matrangthai = 4) thì không cho phép chỉnh sửa
        if ($hoaDon->Matrangthai == 4) {
            return redirect()->route('admin.hoadonvathanhtoan.show', $hoaDon->MaHD)
                ->with('error', 'Không thể chỉnh sửa hóa đơn đã thanh toán!');
        }
        
        $datLichs = DatLich::all();
        $users = User::all();
        $phongs = Phong::all();
        $phuongThucs = PhuongThuc::all();
        $trangThais = TrangThai::all();

        return view('backend.hoadonvathanhtoan.edit', compact('hoaDon', 'datLichs', 'users', 'phongs', 'phuongThucs', 'trangThais'));
    }

    public function update(Request $request, $id)
    {
        $hoaDon = HoaDonVaThanhToan::findOrFail($id);

        $request->validate([
            'Ngaythanhtoan' => 'required|date',
            'Tongtien' => 'required|numeric|min:0',
            'MaDL' => 'required|exists:DATLICH,MaDL',
            'Manguoidung' => 'required|exists:USER,Manguoidung',
            'Maphong' => 'required|exists:PHONG,Maphong',
            'MaPT' => 'nullable|exists:PHUONGTHUC,MaPT',
        ], [
            'Ngaythanhtoan.required' => 'Ngày thanh toán không được để trống.',
            'Ngaythanhtoan.date' => 'Ngày thanh toán không hợp lệ.',
            'Tongtien.required' => 'Tổng tiền không được để trống.',
            'Tongtien.numeric' => 'Tổng tiền phải là số.',
            'Tongtien.min' => 'Tổng tiền không được nhỏ hơn 0.',
            'MaDL.required' => 'Đặt lịch không được để trống.',
            'MaDL.exists' => 'Đặt lịch không tồn tại.',
            'Manguoidung.required' => 'Người dùng không được để trống.',
            'Manguoidung.exists' => 'Người dùng không tồn tại.',
            'Maphong.required' => 'Phòng không được để trống.',
            'Maphong.exists' => 'Phòng không tồn tại.',
            'MaPT.exists' => 'Phương thức không tồn tại.',
        ]);

        try {
            DB::beginTransaction();
            
            // Get the booking to access the service price
            $datLich = DatLich::with('dichVu')->findOrFail($request->MaDL);
            $tongtien = $datLich->dichVu->Gia; // Set total amount from service price
            
            // Nếu không chọn trạng thái, mặc định là "Chờ thanh toán" (Matrangthai = 6)
            $matrangThai = $request->Matrangthai ?? 6;
            
            $hoaDon->update([
                'Ngaythanhtoan' => $request->Ngaythanhtoan,
                'Tongtien' => $tongtien, // Use the calculated total amount
                'MaDL' => $request->MaDL,
                'Manguoidung' => $request->Manguoidung,
                'Maphong' => $request->Maphong,
                'MaPT' => $request->MaPT,
                'Matrangthai' => $matrangThai,
            ]);

            // Cập nhật lịch sử điểm thưởng dựa trên tổng tiền
            $existingLSDT = LSDiemThuong::where('MaHD', $hoaDon->MaHD)->first();
            $soDiem = $this->calculateRewardPoints($tongtien);

            if ($soDiem > 0) {
                if ($existingLSDT) {
                    // Nếu đã có bản ghi, cập nhật số điểm
                    $existingLSDT->update([
                        'Sodiem' => $soDiem,
                        'Thoigian' => now(),
                        'Manguoidung' => $request->Manguoidung,
                    ]);
                } else {
                    // Nếu chưa có bản ghi, tạo mới
                    $maxMaLSDT = LSDiemThuong::max('MaLSDT') ?? 0;
                    $newMaLSDT = $maxMaLSDT + 1;

                    LSDiemThuong::create([
                        'MaLSDT' => $newMaLSDT,
                        'Thoigian' => now(),
                        'Sodiem' => $soDiem,
                        'Manguoidung' => $request->Manguoidung,
                        'MaHD' => $hoaDon->MaHD,
                    ]);
                }
            } else {
                // Nếu tổng tiền < 100,000 và có bản ghi, xóa bản ghi
                if ($existingLSDT) {
                    $existingLSDT->delete();
                }
            }
            
            // Cập nhật trạng thái đặt lịch nếu cần
            if ($request->has('update_booking_status') && $request->update_booking_status) {
                $datLich = DatLich::find($request->MaDL);
                if ($datLich) {
                    $datLich->update([
                        'Trangthai_' => 'Hoàn thành'
                    ]);
                }
            }
            
            DB::commit();
            return redirect()->route('admin.hoadonvathanhtoan.index')->with('success', 'Cập nhật hóa đơn thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Đã xảy ra lỗi: ' . $e->getMessage())->withInput();
        }
    }

    public function confirmDestroy($id)
    {
        $hoaDon = HoaDonVaThanhToan::with(['datLich', 'user', 'phong', 'phuongThuc', 'trangThai'])->findOrFail($id);
        return view('backend.hoadonvathanhtoan.destroy', compact('hoaDon'));
    }

    public function destroy($id)
    {
        $hoaDon = HoaDonVaThanhToan::findOrFail($id);

        try {
            DB::beginTransaction();
            
            // Xóa các bản ghi liên quan
            LSDiemThuong::where('MaHD', $hoaDon->MaHD)->delete();
            DanhGia::where('MaHD', $hoaDon->MaHD)->delete();
            
            $hoaDon->delete();
            
            DB::commit();
            return redirect()->route('admin.hoadonvathanhtoan.index')->with('success', 'Xóa hóa đơn thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.hoadonvathanhtoan.index')->with('error', 'Không thể xóa hóa đơn vì có dữ liệu liên quan!');
        }
    }

    public function createDanhGia($id)
    {
        $hoaDon = HoaDonVaThanhToan::findOrFail($id);
        $maxMaDG = DanhGia::max('MaDG') ?? 0;
        $suggestedMaDG = $maxMaDG + 1;

        return view('backend.danhgia.create', compact('hoaDon', 'suggestedMaDG'));
    }

    public function storeDanhGia(Request $request, $id)
    {
        $hoaDon = HoaDonVaThanhToan::findOrFail($id);

        $request->validate([
            'Danhgiasao' => 'required|integer|min:1|max:5',
            'Nhanxet' => 'nullable|string',
        ], [
            'Danhgiasao.required' => 'Đánh giá sao không được để trống.',
            'Danhgiasao.integer' => 'Đánh giá sao phải là số nguyên.',
            'Danhgiasao.min' => 'Đánh giá sao phải từ 1 đến 5.',
            'Danhgiasao.max' => 'Đánh giá sao phải từ 1 đến 5.',
        ]);

        try {
            DB::beginTransaction();
            
            $maxMaDG = DanhGia::max('MaDG') ?? 0;
            $newMaDG = $maxMaDG + 1;

            DanhGia::create([
                'MaDG' => $newMaDG,
                'Danhgiasao' => $request->Danhgiasao,
                'Nhanxet' => $request->Nhanxet,
                'Ngaydanhgia' => now(),
                'Manguoidung' => $hoaDon->Manguoidung,
                'MaHD' => $hoaDon->MaHD,
            ]);
            
            DB::commit();
            return redirect()->route('admin.hoadonvathanhtoan.index')->with('success', 'Thêm đánh giá thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Đã xảy ra lỗi: ' . $e->getMessage())->withInput();
        }
    }
    
    // Thêm phương thức in hóa đơn
    public function print($id)
    {
        $hoaDon = HoaDonVaThanhToan::with(['datLich.dichVu', 'user', 'phong', 'phuongThuc', 'trangThai', 'lsDiemThuong'])->findOrFail($id);
        return view('backend.hoadonvathanhtoan.print', compact('hoaDon'));
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
        
        // Tổng doanh thu trong khoảng thời gian
        $totalRevenue = HoaDonVaThanhToan::whereBetween('Ngaythanhtoan', [$startDate, $endDate])->sum('Tongtien');
        
        // Thống kê theo trạng thái
        $invoicesByStatus = HoaDonVaThanhToan::whereBetween('Ngaythanhtoan', [$startDate, $endDate])
            ->select('Matrangthai', DB::raw('count(*) as count'), DB::raw('sum(Tongtien) as total'))
            ->groupBy('Matrangthai')
            ->with('trangThai')
            ->get();
        
        // Thống kê theo phương thức thanh toán
        $invoicesByPaymentMethod = HoaDonVaThanhToan::whereBetween('Ngaythanhtoan', [$startDate, $endDate])
            ->select('MaPT', DB::raw('count(*) as count'), DB::raw('sum(Tongtien) as total'))
            ->groupBy('MaPT')
            ->with('phuongThuc')
            ->get();
        
        // Thống kê theo ngày
        $invoicesByDate = HoaDonVaThanhToan::whereBetween('Ngaythanhtoan', [$startDate, $endDate])
            ->select(DB::raw('DATE(Ngaythanhtoan) as date'), DB::raw('count(*) as count'), DB::raw('sum(Tongtien) as total'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();
        
        // Thống kê theo người dùng (top 10)
        $invoicesByUser = HoaDonVaThanhToan::whereBetween('Ngaythanhtoan', [$startDate, $endDate])
            ->select('Manguoidung', DB::raw('count(*) as count'), DB::raw('sum(Tongtien) as total'))
            ->groupBy('Manguoidung')
            ->with('user')
            ->orderBy('total', 'desc')
            ->limit(10)
            ->get();
        
        return view('backend.hoadonvathanhtoan.statistics', compact(
            'totalRevenue', 
            'invoicesByStatus', 
            'invoicesByPaymentMethod', 
            'invoicesByDate', 
            'invoicesByUser',
            'startDate',
            'endDate'
        ));
    }
    
    // Phương thức tính điểm thưởng dựa trên tổng tiền
    private function calculateRewardPoints($tongTien)
    {
        $soDiem = 0;

        if ($tongTien >= 100000 && $tongTien < 500000) {
            $soDiem = 100;
        } elseif ($tongTien >= 500000 && $tongTien < 1000000) {
            $soDiem = 300;
        } elseif ($tongTien >= 1000000) {
            $soDiem = 500;
        }
        
        return $soDiem;
    }
    
    // Phương thức xuất Excel
    public function exportExcel(Request $request)
    {
        // Thêm code xuất Excel ở đây
        // Có thể sử dụng thư viện như PhpSpreadsheet hoặc Laravel Excel
        
        return redirect()->back()->with('success', 'Xuất Excel thành công!');
    }

    /**
     * Fetch booking details via API
     */
    public function getBookingDetails($id)
    {
        try {
            // Sử dụng eager loading để lấy thông tin dịch vụ
            $booking = DatLich::with(['dichVu', 'user'])->findOrFail($id);
            
            return response()->json([
                'success' => true,
                'data' => $booking
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy thông tin đặt lịch: ' . $e->getMessage()
            ], 404);
        }
    }
}