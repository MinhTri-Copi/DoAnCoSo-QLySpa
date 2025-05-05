<?php

namespace App\Http\Controllers;

use App\Models\LSDiemThuong;
use App\Models\User;
use App\Models\HoaDonVaThanhToan;
use Illuminate\Http\Request;

class PointHistoryController extends Controller
{
    public function index()
    {
        $pointHistories = LSDiemThuong::with(['user', 'hoaDon'])->get();
        return view('backend.lsdiemthuong.index', compact('pointHistories'));
    }

    public function create()
    {
        $users = User::all();
        $hoaDons = HoaDonVaThanhToan::all();
        $maxMaLSDT = LSDiemThuong::max('MaLSDT') ?? 0;
        $number = $maxMaLSDT ? (int) substr($maxMaLSDT, 4) : 0;
        $suggestedMaLSDT = 'LSDT' . ($number + 1);

        return view('backend.lsdiemthuong.create', compact('users', 'hoaDons', 'suggestedMaLSDT'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'Manguoidung' => 'required|exists:USER,Manguoidung',
            'MaHD' => 'required|exists:HOADONVATHANHTOAN,MaHD',
            'Sodiem' => 'required|integer',
        ], [
            'Manguoidung.required' => 'Người dùng không được để trống.',
            'Manguoidung.exists' => 'Người dùng không tồn tại.',
            'MaHD.required' => 'Hóa đơn không được để trống.',
            'MaHD.exists' => 'Hóa đơn không tồn tại.',
            'Sodiem.required' => 'Số điểm không được để trống.',
            'Sodiem.integer' => 'Số điểm phải là số nguyên.',
        ]);

        $maxMaLSDT = LSDiemThuong::max('MaLSDT') ?? 0;
        $number = $maxMaLSDT ? (int) substr($maxMaLSDT, 4) : 0;
        $newMaLSDT = 'LSDT' . ($number + 1);

        LSDiemThuong::create([
            'MaLSDT' => $newMaLSDT,
            'Thoigian' => now(),
            'Sodiem' => $request->Sodiem,
            'Manguoidung' => $request->Manguoidung,
            'MaHD' => $request->MaHD,
        ]);

        return redirect()->route('admin.lsdiemthuong.index')->with('success', 'Thêm điểm thưởng thành công!');
    }

    public function show($id)
    {
        $pointHistory = LSDiemThuong::with(['user', 'hoaDon'])->findOrFail($id);
        return view('backend.lsdiemthuong.show', compact('pointHistory'));
    }

    public function edit($id)
    {
        $pointHistory = LSDiemThuong::findOrFail($id);
        $users = User::all();
        $hoaDons = HoaDonVaThanhToan::all();
        return view('backend.lsdiemthuong.edit', compact('pointHistory', 'users', 'hoaDons'));
    }

    public function update(Request $request, $id)
    {
        $pointHistory = LSDiemThuong::findOrFail($id);

        $request->validate([
            'Manguoidung' => 'required|exists:USER,Manguoidung',
            'MaHD' => 'required|exists:HOADONVATHANHTOAN,MaHD',
            'Sodiem' => 'required|integer',
        ], [
            'Manguoidung.required' => 'Người dùng không được để trống.',
            'Manguoidung.exists' => 'Người dùng không tồn tại.',
            'MaHD.required' => 'Hóa đơn không được để trống.',
            'MaHD.exists' => 'Hóa đơn không tồn tại.',
            'Sodiem.required' => 'Số điểm không được để trống.',
            'Sodiem.integer' => 'Số điểm phải là số nguyên.',
        ]);

        $pointHistory->update([
            'Sodiem' => $request->Sodiem,
            'Manguoidung' => $request->Manguoidung,
            'MaHD' => $request->MaHD,
            'Thoigian' => now(),
        ]);

        return redirect()->route('admin.lsdiemthuong.index')->with('success', 'Cập nhật điểm thưởng thành công!');
    }

    public function confirmDestroy($id)
    {
        $pointHistory = LSDiemThuong::findOrFail($id);
        return view('backend.lsdiemthuong.destroy', compact('pointHistory'));
    }

    public function destroy($id)
    {
        $pointHistory = LSDiemThuong::findOrFail($id);

        try {
            $pointHistory->delete();
            return redirect()->route('admin.lsdiemthuong.index')->with('success', 'Xóa điểm thưởng thành công!');
        } catch (\Exception $e) {
            return redirect()->route('admin.lsdiemthuong.index')->with('error', 'Không thể xóa điểm thưởng vì có dữ liệu liên quan!');
        }
    }
}