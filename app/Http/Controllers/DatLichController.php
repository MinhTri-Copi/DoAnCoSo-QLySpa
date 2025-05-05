<?php

namespace App\Http\Controllers;

use App\Models\DatLich;
use App\Models\User;
use App\Models\DichVu;
use Illuminate\Http\Request;

class DatLichController extends Controller
{
    public function index()
    {
        $datLichs = DatLich::with('user', 'dichVu')->get();
        return view('backend.datlich.index', compact('datLichs'));
    }

    public function create()
    {
        // Lấy giá trị MaDL lớn nhất
        $maxMaDL =DatLich::max('MaDL') ?? 0;
        $suggestedMaDL = $maxMaDL + 1;

        $users = User::all();
        $dichVus = DichVu::all();
        return view('backend.datlich.create', compact('suggestedMaDL', 'users', 'dichVus'));
    }

    public function store(Request $request)
    {
        // Lấy giá trị MaDL lớn nhất
        $maxMaDL = DatLich::max('MaDL');
        if ($maxMaDL) {
            $number = (int) substr($maxMaDL, 2);
            $suggestedMaDL = 'DL' . ($number + 1);
        } else {
            $suggestedMaDL = 'DL1';
        }

        $request->validate([
            'Manguoidung' => 'required|exists:USER,Manguoidung',
            'Thoigiandatlich' => 'required|date',
            'Trangthai_' => 'required|string|max:50',
            'MaDV' => 'required|exists:DICHVU,MaDV',
        ], [
            'Manguoidung.required' => 'Vui lòng chọn người dùng.',
            'Manguoidung.exists' => 'Người dùng không hợp lệ.',
            'Thoigiandatlich.required' => 'Thời gian đặt lịch không được để trống.',
            'Thoigiandatlich.date' => 'Thời gian đặt lịch không hợp lệ.',
            'Trangthai_.required' => 'Trạng thái không được để trống.',
            'Trangthai_.max' => 'Trạng thái không được vượt quá 50 ký tự.',
            'MaDV.required' => 'Vui lòng chọn dịch vụ.',
            'MaDV.exists' => 'Dịch vụ không hợp lệ.',
        ]);

        DatLich::create([
            'MaDL' => $suggestedMaDL, // Sử dụng MaDL đã gợi ý
            'Manguoidung' => $request->Manguoidung,
            'Thoigiandatlich' => $request->Thoigiandatlich,
            'Trangthai_' => $request->Trangthai_,
            'MaDV' => $request->MaDV,
        ]);

        return redirect()->route('admin.datlich.index')->with('success', 'Thêm đặt lịch thành công!');
    }

    public function show($id)
    {
        $datLich = DatLich::with('user', 'dichVu')->findOrFail($id);
        return view('backend.datlich.show', compact('datLich'));
    }

    public function edit($id)
    {
        $datLich = DatLich::findOrFail($id);
        $users = User::all();
        $dichVus = DichVu::all();
        return view('backend.datlich.edit', compact('datLich', 'users', 'dichVus'));
    }

    public function update(Request $request, $id)
    {
        $datLich = DatLich::findOrFail($id);

        $request->validate([
            'Manguoidung' => 'required|exists:USER,Manguoidung',
            'Thoigiandatlich' => 'required|date',
            'Trangthai_' => 'required|string|max:50',
            'MaDV' => 'required|exists:DICHVU,MaDV',
        ], [
            'Manguoidung.required' => 'Vui lòng chọn người dùng.',
            'Manguoidung.exists' => 'Người dùng không hợp lệ.',
            'Thoigiandatlich.required' => 'Thời gian đặt lịch không được để trống.',
            'Thoigiandatlich.date' => 'Thời gian đặt lịch không hợp lệ.',
            'Trangthai_.required' => 'Trạng thái không được để trống.',
            'Trangthai_.max' => 'Trạng thái không được vượt quá 50 ký tự.',
            'MaDV.required' => 'Vui lòng chọn dịch vụ.',
            'MaDV.exists' => 'Dịch vụ không hợp lệ.',
        ]);

        $datLich->update([
            'Manguoidung' => $request->Manguoidung,
            'Thoigiandatlich' => $request->Thoigiandatlich,
            'Trangthai_' => $request->Trangthai_,
            'MaDV' => $request->MaDV,
        ]);

        return redirect()->route('admin.datlich.index')->with('success', 'Cập nhật đặt lịch thành công!');
    }

    public function confirmDestroy($id)
    {
        $datLich = DatLich::with('user', 'dichVu')->findOrFail($id);
        return view('backend.datlich.destroy', compact('datLich'));
    }

    public function destroy($id)
    {
        $datLich = DatLich::findOrFail($id);

        try {
            $datLich->delete();
            return redirect()->route('admin.datlich.index')->with('success', 'Xóa đặt lịch thành công!');
        } catch (\Exception $e) {
            return redirect()->route('admin.datlich.index')->with('error', 'Không thể xóa đặt lịch vì có dữ liệu liên quan!');
        }
    }
}