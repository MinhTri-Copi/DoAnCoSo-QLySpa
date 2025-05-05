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
use Illuminate\Http\Request;

class HoaDonVaThanhToanController extends Controller
{
    public function index()
    {
        $hoaDons = HoaDonVaThanhToan::with(['datLich', 'user', 'phong', 'phuongThuc', 'trangThai'])->get();
        return view('backend.hoadonvathanhtoan.index', compact('hoaDons'));
    }

    public function create()
    {
        $datLichs = DatLich::all();
        $users = User::all();
        $phongs = Phong::all();
        $phuongThucs = PhuongThuc::all();
        $trangThais = TrangThai::all();
        $maxMahang = HoaDonVaThanhToan::max('MaHD') ?? 0;
        $suggestedMaHD = $maxMahang + 1;


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
            'MaPT' => 'required|exists:PHUONGTHUC,MaPT',
            'Matrangthai' => 'required|exists:TRANGTHAI,Matrangthai',
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
            'MaPT.required' => 'Phương thức không được để trống.',
            'MaPT.exists' => 'Phương thức không tồn tại.',
            'Matrangthai.required' => 'Trạng thái không được để trống.',
            'Matrangthai.exists' => 'Trạng thái không tồn tại.',
        ]);

        $maxMahang = HoaDonVaThanhToan::max('MaHD') ?? 0;
        $newMaHD = $maxMahang + 1;


        $hoaDon = HoaDonVaThanhToan::create([
            'MaHD' => $newMaHD,
            'Ngaythanhtoan' => $request->Ngaythanhtoan,
            'Tongtien' => $request->Tongtien,
            'MaDL' => $request->MaDL,
            'Manguoidung' => $request->Manguoidung,
            'Maphong' => $request->Maphong,
            'MaPT' => $request->MaPT,
            'Matrangthai' => $request->Matrangthai,
        ]);

        // Tự động tạo bản ghi lịch sử điểm thưởng dựa trên tổng tiền
        $tongTien = $request->Tongtien;
        $soDiem = 0;

        if ($tongTien >= 100000 && $tongTien < 500000) {
            $soDiem = 100;
        } elseif ($tongTien >= 500000 && $tongTien < 1000000) {
            $soDiem = 300;
        } elseif ($tongTien >= 1000000) {
            $soDiem = 500;
        }

        if ($soDiem > 0) {
            $maxMahang = LSDiemThuong::max('MaHD') ?? 0;
            $newMaLSDT = $maxMahang + 1;
            LSDiemThuong::create([
                'MaLSDT' => $newMaLSDT,
                'Thoigian' => now(),
                'Sodiem' => $soDiem,
                'Manguoidung' => $request->Manguoidung,
                'MaHD' => $newMaHD,
            ]);
        }

        return redirect()->route('admin.hoadonvathanhtoan.index')->with('success', 'Thêm hóa đơn thành công!');
    }

    public function show($id)
    {
        $hoaDon = HoaDonVaThanhToan::with(['datLich', 'user', 'phong', 'phuongThuc', 'trangThai'])->findOrFail($id);
        return view('backend.hoadonvathanhtoan.show', compact('hoaDon'));
    }

    public function edit($id)
    {
        $hoaDon = HoaDonVaThanhToan::findOrFail($id);
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
            'MaPT' => 'required|exists:PHUONGTHUC,MaPT',
            'Matrangthai' => 'required|exists:TRANGTHAI,Matrangthai',
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
            'MaPT.required' => 'Phương thức không được để trống.',
            'MaPT.exists' => 'Phương thức không tồn tại.',
            'Matrangthai.required' => 'Trạng thái không được để trống.',
            'Matrangthai.exists' => 'Trạng thái không tồn tại.',
        ]);

        $hoaDon->update([
            'Ngaythanhtoan' => $request->Ngaythanhtoan,
            'Tongtien' => $request->Tongtien,
            'MaDL' => $request->MaDL,
            'Manguoidung' => $request->Manguoidung,
            'Maphong' => $request->Maphong,
            'MaPT' => $request->MaPT,
            'Matrangthai' => $request->Matrangthai,
        ]);

        // Cập nhật lịch sử điểm thưởng dựa trên tổng tiền
        $existingLSDT = LSDiemThuong::where('MaHD', $hoaDon->MaHD)->first();
        $tongTien = $request->Tongtien;
        $soDiem = 0;

        if ($tongTien >= 100000 && $tongTien < 500000) {
            $soDiem = 100;
        } elseif ($tongTien >= 500000 && $tongTien < 1000000) {
            $soDiem = 300;
        } elseif ($tongTien >= 1000000) {
            $soDiem = 500;
        }

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
                $numberLSDT = $maxMaLSDT ? (int) substr($maxMaLSDT, 4) : 0;
                $newMaLSDT = 'LSDT' . ($numberLSDT + 1);

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

        return redirect()->route('admin.hoadonvathanhtoan.index')->with('success', 'Cập nhật hóa đơn thành công!');
    }

    public function confirmDestroy($id)
    {
        $hoaDon = HoaDonVaThanhToan::findOrFail($id);
        return view('backend.hoadonvathanhtoan.destroy', compact('hoaDon'));
    }

    public function destroy($id)
    {
        $hoaDon = HoaDonVaThanhToan::findOrFail($id);

        try {
            $hoaDon->delete();
            return redirect()->route('admin.hoadonvathanhtoan.index')->with('success', 'Xóa hóa đơn thành công!');
        } catch (\Exception $e) {
            return redirect()->route('admin.hoadonvathanhtoan.index')->with('error', 'Không thể xóa hóa đơn vì có dữ liệu liên quan!');
        }
    }

    public function createDanhGia($id)
    {
        $hoaDon = HoaDonVaThanhToan::findOrFail($id);
        $maxMaDG = DanhGia::max('MaDG') ?? 0;
        $number = $maxMaDG ? (int) substr($maxMaDG, 2) : 0;
        $suggestedMaDG = 'DG' . ($number + 1);

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

        $maxMaDG = DanhGia::max('MaDG') ?? 0;
        $number = $maxMaDG ? (int) substr($maxMaDG, 2) : 0;
        $newMaDG = 'DG' . ($number + 1);

        DanhGia::create([
            'MaDG' => $newMaDG,
            'Danhgiasao' => $request->Danhgiasao,
            'Nhanxet' => $request->Nhanxet,
            'Ngaydanhgia' => now(),
            'Manguoidung' => $hoaDon->Manguoidung,
            'MaHD' => $hoaDon->MaHD,
        ]);

        return redirect()->route('admin.hoadonvathanhtoan.index')->with('success', 'Thêm đánh giá thành công!');
    }
}