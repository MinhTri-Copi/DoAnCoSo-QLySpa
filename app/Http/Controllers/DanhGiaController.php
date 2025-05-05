<?php

namespace App\Http\Controllers;

use App\Models\DanhGia;
use App\Models\HoaDonVaThanhToan;
use App\Models\User;
use Illuminate\Http\Request;

class DanhGiaController extends Controller
{
    public function index()
    {
        $danhGias = DanhGia::with(['user', 'hoaDon'])->get();
        return view('backend.danhgia.index', compact('danhGias'));
    }

    public function show($id)
    {
        $danhGia = DanhGia::with(['user', 'hoaDon'])->findOrFail($id);
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
}