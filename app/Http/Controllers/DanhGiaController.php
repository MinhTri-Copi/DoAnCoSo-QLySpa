<?php

namespace App\Http\Controllers;

use App\Models\DanhGia;
use App\Models\HoaDonVaThanhToan;
use App\Models\User;
use App\Models\Phong;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DanhGiaController extends Controller
{
    public function index(Request $request)
    {
        // Lọc đánh giá theo số sao nếu có
        $query = DanhGia::with(['user', 'hoaDon', 'hoaDon.phong']);
        
        if ($request->has('star_rating') && $request->star_rating != '') {
            $query->where('Danhgiasao', $request->star_rating);
        }
        
        // Lọc theo khoảng thời gian
        if ($request->has('from_date') && $request->from_date != '') {
            $query->whereDate('Ngaydanhgia', '>=', $request->from_date);
        }
        
        if ($request->has('to_date') && $request->to_date != '') {
            $query->whereDate('Ngaydanhgia', '<=', $request->to_date);
        }
        
        // Lọc theo phòng
        if ($request->has('room_id') && $request->room_id != '') {
            $query->whereHas('hoaDon', function($q) use ($request) {
                $q->where('Maphong', $request->room_id);
            });
        }
        
        $danhGias = $query->orderBy('Ngaydanhgia', 'desc')->paginate(10);
        
        // Thống kê
        $totalReviews = DanhGia::count();
        $averageRating = DanhGia::avg('Danhgiasao');
        
        // Thống kê đánh giá theo tháng hiện tại
        $currentMonthReviews = DanhGia::whereMonth('Ngaydanhgia', now()->month)
                                ->whereYear('Ngaydanhgia', now()->year)
                                ->count();
        
        // Phần trăm đánh giá tốt (4-5 sao)
        $goodReviews = DanhGia::where('Danhgiasao', '>=', 4)->count();
        $goodReviewPercentage = $totalReviews > 0 ? round(($goodReviews / $totalReviews) * 100) : 0;
        
        // Danh sách phòng để lọc
        $rooms = Phong::all();
        
        return view('backend.danhgia.index', compact(
            'danhGias', 
            'totalReviews', 
            'averageRating', 
            'currentMonthReviews', 
            'goodReviewPercentage',
            'rooms'
        ));
    }

    public function show($id)
    {
        $danhGia = DanhGia::with(['user', 'hoaDon', 'hoaDon.phong', 'hoaDon.datLich', 'hoaDon.phuongThuc', 'hoaDon.trangThai'])->findOrFail($id);
        return view('backend.danhgia.show', compact('danhGia'));
    }

    public function edit($id)
    {
        $danhGia = DanhGia::findOrFail($id);
        $users = User::all();
        $hoaDons = HoaDonVaThanhToan::all();
        return view('backend.danhgia.edit', compact('danhGia', 'users', 'hoaDons'));
    }

    public function update(Request $request, $id)
    {
        $danhGia = DanhGia::findOrFail($id);

        $request->validate([
            'Danhgiasao' => 'required|integer|min:1|max:5',
            'Nhanxet' => 'nullable|string',
            'Manguoidung' => 'required|exists:USER,Manguoidung',
            'MaHD' => 'required|exists:HOADON_VA_THANHTOAN,MaHD',
        ], [
            'Danhgiasao.required' => 'Đánh giá sao không được để trống.',
            'Danhgiasao.integer' => 'Đánh giá sao phải là số nguyên.',
            'Danhgiasao.min' => 'Đánh giá sao phải từ 1 đến 5.',
            'Danhgiasao.max' => 'Đánh giá sao phải từ 1 đến 5.',
            'Manguoidung.required' => 'Người dùng không được để trống.',
            'Manguoidung.exists' => 'Người dùng không tồn tại.',
            'MaHD.required' => 'Hóa đơn không được để trống.',
            'MaHD.exists' => 'Hóa đơn không tồn tại.',
        ]);

        $danhGia->update([
            'Danhgiasao' => $request->Danhgiasao,
            'Nhanxet' => $request->Nhanxet,
            'Ngaydanhgia' => now(),
            'Manguoidung' => $request->Manguoidung,
            'MaHD' => $request->MaHD,
        ]);

        return redirect()->route('admin.danhgia.index')->with('success', 'Cập nhật đánh giá thành công!');
    }

    public function confirmDestroy($id)
    {
        $danhGia = DanhGia::findOrFail($id);
        return view('backend.danhgia.destroy', compact('danhGia'));
    }

    public function destroy($id)
    {
        $danhGia = DanhGia::findOrFail($id);

        try {
            $danhGia->delete();
            return redirect()->route('admin.danhgia.index')->with('success', 'Xóa đánh giá thành công!');
        } catch (\Exception $e) {
            return redirect()->route('admin.danhgia.index')->with('error', 'Không thể xóa đánh giá vì có dữ liệu liên quan!');
        }
    }
    
    public function reply(Request $request, $id)
    {
        $danhGia = DanhGia::findOrFail($id);
        
        $request->validate([
            'reply' => 'required|string|max:500',
        ], [
            'reply.required' => 'Nội dung phản hồi không được để trống.',
            'reply.max' => 'Nội dung phản hồi không được vượt quá 500 ký tự.',
        ]);
        
        // Cập nhật phản hồi - Giả sử bạn đã thêm cột PhanHoi và NgayPhanHoi vào bảng DANHGIA
        $danhGia->update([
            'PhanHoi' => $request->reply,
            'NgayPhanHoi' => now(),
        ]);
        
        return redirect()->route('admin.danhgia.show', $id)->with('success', 'Phản hồi đánh giá thành công!');
    }
    
    public function export()
    {
        // Lấy tất cả đánh giá với thông tin liên quan
        $danhGias = DanhGia::with(['user', 'hoaDon', 'hoaDon.phong'])->get();
        
        // Tạo mảng dữ liệu để xuất Excel
        $exportData = [];
        
        foreach ($danhGias as $danhGia) {
            $exportData[] = [
                'Mã đánh giá' => $danhGia->MaDG,
                'Khách hàng' => $danhGia->user ? $danhGia->user->Hoten : 'N/A',
                'Số điện thoại' => $danhGia->user ? $danhGia->user->Sodienthoai : 'N/A',
                'Email' => $danhGia->user ? $danhGia->user->Email : 'N/A',
                'Đánh giá sao' => $danhGia->Danhgiasao,
                'Nhận xét' => $danhGia->Nhanxet,
                'Phòng' => $danhGia->hoaDon && $danhGia->hoaDon->phong ? $danhGia->hoaDon->phong->Tenphong : 'N/A',
                'Ngày đánh giá' => $danhGia->Ngaydanhgia,
                'Phản hồi' => $danhGia->PhanHoi ?? 'Chưa phản hồi',
                'Ngày phản hồi' => $danhGia->NgayPhanHoi ?? 'N/A',
            ];
        }
        
        // Xuất dữ liệu ra file Excel
        // Bạn có thể sử dụng thư viện như maatwebsite/excel để thực hiện chức năng này
        // Ví dụ: return Excel::download(new DanhGiaExport($exportData), 'danh_gia.xlsx');
        
        // Tạm thời chỉ trả về thông báo thành công
        return redirect()->route('admin.danhgia.index')->with('success', 'Xuất dữ liệu đánh giá thành công!');
    }
    
    public function getStatistics()
    {
        // Thống kê tổng quan
        $statistics = [
            'total' => DanhGia::count(),
            'average' => number_format(DanhGia::avg('Danhgiasao'), 1),
            'distribution' => [
                5 => DanhGia::where('Danhgiasao', 5)->count(),
                4 => DanhGia::where('Danhgiasao', 4)->count(),
                3 => DanhGia::where('Danhgiasao', 3)->count(),
                2 => DanhGia::where('Danhgiasao', 2)->count(),
                1 => DanhGia::where('Danhgiasao', 1)->count(),
            ],
            'monthly' => DanhGia::whereMonth('Ngaydanhgia', now()->month)
                        ->whereYear('Ngaydanhgia', now()->year)
                        ->count(),
        ];
        
        // Thống kê theo phòng
        $roomStats = DB::table('DANHGIA')
                    ->join('HOADON_VA_THANHTOAN', 'DANHGIA.MaHD', '=', 'HOADON_VA_THANHTOAN.MaHD')
                    ->join('PHONG', 'HOADON_VA_THANHTOAN.Maphong', '=', 'PHONG.Maphong')
                    ->select('PHONG.Maphong', 'PHONG.Tenphong', 
                            DB::raw('COUNT(DANHGIA.MaDG) as total_reviews'),
                            DB::raw('AVG(DANHGIA.Danhgiasao) as average_rating'))
                    ->groupBy('PHONG.Maphong', 'PHONG.Tenphong')
                    ->orderBy('average_rating', 'desc')
                    ->get();
        
        $statistics['rooms'] = $roomStats;
        
        return response()->json($statistics);
    }

    public function create()
    {
        $users = User::all();
        // Không tải tất cả hóa đơn ngay từ đầu, để tránh hiển thị tất cả hóa đơn
        $hoaDons = collect(); // Empty collection initially
        return view('backend.danhgia.create', compact('users', 'hoaDons'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'Danhgiasao' => 'required|integer|min:1|max:5',
            'Nhanxet' => 'nullable|string',
            'Manguoidung' => 'required|exists:USER,Manguoidung',
            'MaHD' => 'required|exists:HOADON_VA_THANHTOAN,MaHD',
        ], [
            'Danhgiasao.required' => 'Đánh giá sao không được để trống.',
            'Danhgiasao.integer' => 'Đánh giá sao phải là số nguyên.',
            'Danhgiasao.min' => 'Đánh giá sao phải từ 1 đến 5.',
            'Danhgiasao.max' => 'Đánh giá sao phải từ 1 đến 5.',
            'Manguoidung.required' => 'Người dùng không được để trống.',
            'Manguoidung.exists' => 'Người dùng không tồn tại.',
            'MaHD.required' => 'Hóa đơn không được để trống.',
            'MaHD.exists' => 'Hóa đơn không tồn tại.',
        ]);

        // Tạo mã đánh giá mới
        $lastDanhGia = DanhGia::orderBy('MaDG', 'desc')->first();
        $newMaDG = 1;
        
        if ($lastDanhGia) {
            // Lấy mã số cuối cùng và tăng lên 1
            $newMaDG = (int)$lastDanhGia->MaDG + 1;
        }

        DanhGia::create([
            'MaDG' => $newMaDG,
            'Danhgiasao' => $request->Danhgiasao,
            'Nhanxet' => $request->Nhanxet,
            'Ngaydanhgia' => now(),
            'Manguoidung' => $request->Manguoidung,
            'MaHD' => $request->MaHD,
        ]);

        return redirect()->route('admin.danhgia.index')->with('success', 'Thêm đánh giá thành công!');
    }

    // Thêm phương thức để lấy hóa đơn theo người dùng
    public function getUserInvoices($userId)
    {
        $hoaDons = HoaDonVaThanhToan::with(['phong', 'datLich'])
                    ->where('Manguoidung', $userId)
                    ->get();
        
        return response()->json([
            'success' => true,
            'hoaDons' => $hoaDons
        ]);
    }
}