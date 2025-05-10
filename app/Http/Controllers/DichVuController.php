<?php

namespace App\Http\Controllers;

use App\Models\DichVu;
use App\Models\TrangThai;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DichVuController extends Controller
{
    public function index()
    {
        $dichVus = DichVu::with('trangThai')->get();
        $trangThais = TrangThai::all();
        return view('backend.dichvu.index', compact('dichVus', 'trangThais'));
    }

    public function create()
    {
        return view('backend.dichvu.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'MaDV' => 'required|string|unique:DICHVU,MaDV',
            'Tendichvu' => 'required|string|max:255',
            'Gia' => 'required|numeric|min:0',
            'MoTa' => 'nullable|string|max:500',
            'Image' => 'nullable|string|max:255',
            'Thoigian' => 'required|date_format:H:i',
        ], [
            'MaDV.required' => 'Mã dịch vụ không được để trống.',
            'MaDV.unique' => 'Mã dịch vụ đã tồn tại.',
            'Tendichvu.required' => 'Tên dịch vụ không được để trống.',
            'Tendichvu.max' => 'Tên dịch vụ không được vượt quá 255 ký tự.',
            'Gia.required' => 'Giá không được để trống.',
            'Gia.numeric' => 'Giá phải là số.',
            'Gia.min' => 'Giá không được nhỏ hơn 0.',
            'MoTa.max' => 'Mô tả không được vượt quá 500 ký tự.',
            'Image.max' => 'Đường dẫn hình ảnh không được vượt quá 255 ký tự.',
            'Thoigian.required' => 'Thời gian không được để trống.',
            'Thoigian.date_format' => 'Thời gian không đúng định dạng (HH:mm).',
        ]);

        // Chuyển đổi thời gian thành datetime
        $thoigian = Carbon::createFromFormat('H:i', $request->Thoigian);
        $thoigian->setDate(Carbon::now()->year, Carbon::now()->month, Carbon::now()->day);

        DichVu::create([
            'MaDV' => $request->MaDV,
            'Tendichvu' => $request->Tendichvu,
            'Gia' => $request->Gia,
            'MoTa' => $request->MoTa,
            'Image' => $request->Image,
            'Thoigian' => $thoigian,
        ]);

        return redirect()->route('admin.dichvu.index')->with('success', 'Thêm dịch vụ thành công!');
    }

    public function show($id)
    {
        $dichVu = DichVu::findOrFail($id);
        return view('backend.dichvu.show', compact('dichVu'));
    }

    public function edit($id)
    {
        $dichVu = DichVu::findOrFail($id);
        return view('backend.dichvu.edit', compact('dichVu'));
    }

    public function update(Request $request, $id)
    {
        $dichVu = DichVu::findOrFail($id);

        $request->validate([
            'MaDV' => 'required|string|unique:DICHVU,MaDV,' . $dichVu->MaDV . ',MaDV',
            'Tendichvu' => 'required|string|max:255',
            'Gia' => 'required|numeric|min:0',
            'MoTa' => 'nullable|string|max:500',
            'Image' => 'nullable|string|max:255',
            'Thoigian' => 'required|date_format:H:i',
        ], [
            'MaDV.required' => 'Mã dịch vụ không được để trống.',
            'MaDV.unique' => 'Mã dịch vụ đã tồn tại.',
            'Tendichvu.required' => 'Tên dịch vụ không được để trống.',
            'Tendichvu.max' => 'Tên dịch vụ không được vượt quá 255 ký tự.',
            'Gia.required' => 'Giá không được để trống.',
            'Gia.numeric' => 'Giá phải là số.',
            'Gia.min' => 'Giá không được nhỏ hơn 0.',
            'MoTa.max' => 'Mô tả không được vượt quá 500 ký tự.',
            'Image.max' => 'Đường dẫn hình ảnh không được vượt quá 255 ký tự.',
            'Thoigian.required' => 'Thời gian không được để trống.',
            'Thoigian.date_format' => 'Thời gian không đúng định dạng (HH:mm).',
        ]);

        // Chuyển đổi thời gian thành datetime
        $thoigian = Carbon::createFromFormat('H:i', $request->Thoigian);
        $thoigian->setDate(Carbon::now()->year, Carbon::now()->month, Carbon::now()->day);

        $dichVu->update([
            'MaDV' => $request->MaDV,
            'Tendichvu' => $request->Tendichvu,
            'Gia' => $request->Gia,
            'MoTa' => $request->MoTa,
            'Image' => $request->Image,
            'Thoigian' => $thoigian,
        ]);

        return redirect()->route('admin.dichvu.index')->with('success', 'Cập nhật dịch vụ thành công!');
    }

    public function confirmDestroy($id)
    {
        $dichVu = DichVu::findOrFail($id);
        return view('backend.dichvu.destroy', compact('dichVu'));
    }

    public function destroy($id)
    {
        $dichVu = DichVu::findOrFail($id);

        try {
            $dichVu->delete();
            return redirect()->route('admin.dichvu.index')->with('success', 'Xóa dịch vụ thành công!');
        } catch (\Exception $e) {
            return redirect()->route('admin.dichvu.index')->with('error', 'Không thể xóa dịch vụ vì có dữ liệu liên quan!');
        }
    }

    public function updateStatus(Request $request, $id)
    {
        $dichVu = DichVu::findOrFail($id);

        $request->validate([
            'Matrangthai' => 'required|exists:TRANGTHAI,Matrangthai',
        ], [
            'Matrangthai.required' => 'Vui lòng chọn trạng thái.',
            'Matrangthai.exists' => 'Trạng thái không hợp lệ.',
        ]);

        $dichVu->update([
            'Matrangthai' => $request->Matrangthai,
        ]);

        return redirect()->route('admin.dichvu.index')->with('success', 'Cập nhật trạng thái dịch vụ thành công!');
    }
}