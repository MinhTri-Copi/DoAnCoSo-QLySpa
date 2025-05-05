<?php

namespace App\Http\Controllers;

use App\Models\PhieuHoTro;
use App\Models\TrangThai;
use App\Models\User;
use App\Models\Pthotro;
use Illuminate\Http\Request;

class PhieuHoTroController extends Controller
{
    public function index()
    {
        $phieuHoTros = PhieuHoTro::with('trangThai', 'user')->get();
        return view('backend.phieuhotro.index', compact('phieuHoTros'));
    }

    public function create()
    {
        $maxMaphieuHT = PhieuHoTro::max('MaphieuHT') ?? 0;
        $suggestedMaphieuHT = $maxMaphieuHT + 1;
        $trangThais = TrangThai::all();
        $users = User::all();
        $pthotros = Pthotro::all(); // Load danh sách phương thức hỗ trợ
        return view('backend.phieuhotro.create', compact('suggestedMaphieuHT', 'trangThais', 'users', 'pthotros'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'MaphieuHT' => 'required|integer|unique:PHIEUHOTRO,MaphieuHT',
            'Noidungyeucau' => 'required|string|max:255',
            'Matrangthai' => 'required|exists:TRANGTHAI,Matrangthai',
            'Manguoidung' => 'required|exists:USER,Manguoidung',
            'MaPTHT' => 'required|exists:PTHOTRO,MaPTHT', // Thêm validate cho MaPTHT
        ], [
            'MaphieuHT.required' => 'Mã phiếu hỗ trợ không được để trống.',
            'MaphieuHT.unique' => 'Mã phiếu hỗ trợ đã tồn tại.',
            'Noidungyeucau.required' => 'Nội dung yêu cầu không được để trống.',
            'Noidungyeucau.max' => 'Nội dung yêu cầu không được vượt quá 255 ký tự.',
            'Matrangthai.required' => 'Vui lòng chọn trạng thái.',
            'Matrangthai.exists' => 'Trạng thái không hợp lệ.',
            'Manguoidung.required' => 'Vui lòng chọn người dùng.',
            'Manguoidung.exists' => 'Người dùng không hợp lệ.',
            'MaPTHT.required' => 'Vui lòng chọn phương thức hỗ trợ.',
            'MaPTHT.exists' => 'Phương thức hỗ trợ không hợp lệ.',
        ]);

        PhieuHoTro::create([
            'MaphieuHT' => $request->MaphieuHT,
            'Noidungyeucau' => $request->Noidungyeucau,
            'Matrangthai' => $request->Matrangthai,
            'Manguoidung' => $request->Manguoidung,
            'MaPTHT' => $request->MaPTHT, // Lưu MaPTHT
        ]);

        return redirect()->route('admin.phieuhotro.index')->with('success', 'Thêm phiếu hỗ trợ thành công!');
    }

    public function show($id)
    {
        $phieuHoTro = PhieuHoTro::with('trangThai', 'user')->findOrFail($id);
        return view('backend.phieuhotro.show', compact('phieuHoTro'));
    }

    public function edit($id)
    {
        $phieuHoTro = PhieuHoTro::findOrFail($id);
        $trangThais = TrangThai::all();
        $users = User::all();
        $pthotros = Pthotro::all(); // Load danh sách phương thức hỗ trợ
        return view('backend.phieuhotro.edit', compact('phieuHoTro', 'trangThais', 'users', 'pthotros'));
    }

    public function update(Request $request, $id)
    {
        $phieuHoTro = PhieuHoTro::findOrFail($id);

        $request->validate([
            'MaphieuHT' => 'required|integer|unique:PHIEUHOTRO,MaphieuHT,' . $phieuHoTro->MaphieuHT . ',MaphieuHT',
            'Noidungyeucau' => 'required|string|max:255',
            'Matrangthai' => 'required|exists:TRANGTHAI,Matrangthai',
            'Manguoidung' => 'required|exists:USER,Manguoidung',
            'MaPTHT' => 'required|exists:PTHOTRO,MaPTHT', // Thêm validate cho MaPTHT
        ], [
            'MaphieuHT.required' => 'Mã phiếu hỗ trợ không được để trống.',
            'MaphieuHT.unique' => 'Mã phiếu hỗ trợ đã tồn tại.',
            'Noidungyeucau.required' => 'Nội dung yêu cầu không được để trống.',
            'Noidungyeucau.max' => 'Nội dung yêu cầu không được vượt quá 255 ký tự.',
            'Matrangthai.required' => 'Vui lòng chọn trạng thái.',
            'Matrangthai.exists' => 'Trạng thái không hợp lệ.',
            'Manguoidung.required' => 'Vui lòng chọn người dùng.',
            'Manguoidung.exists' => 'Người dùng không hợp lệ.',
            'MaPTHT.required' => 'Vui lòng chọn phương thức hỗ trợ.',
            'MaPTHT.exists' => 'Phương thức hỗ trợ không hợp lệ.',
        ]);

        $phieuHoTro->update([
            'MaphieuHT' => $request->MaphieuHT,
            'Noidungyeucau' => $request->Noidungyeucau,
            'Matrangthai' => $request->Matrangthai,
            'Manguoidung' => $request->Manguoidung,
            'MaPTHT' => $request->MaPTHT, // Cập nhật MaPTHT
        ]);

        return redirect()->route('admin.phieuhotro.index')->with('success', 'Cập nhật phiếu hỗ trợ thành công!');
    }

    public function confirmDestroy($id)
    {
        $phieuHoTro = PhieuHoTro::with('trangThai', 'user')->findOrFail($id);
        return view('backend.phieuhotro.destroy', compact('phieuHoTro'));
    }

    public function destroy($id)
    {
        $phieuHoTro = PhieuHoTro::findOrFail($id);

        try {
            $phieuHoTro->delete();
            return redirect()->route('admin.phieuhotro.index')->with('success', 'Xóa phiếu hỗ trợ thành công!');
        } catch (\Exception $e) {
            return redirect()->route('admin.phieuhotro.index')->with('error', 'Không thể xóa phiếu hỗ trợ vì có dữ liệu liên quan!');
        }
    }
}