<?php

namespace App\Http\Controllers;

use App\Models\DichVu;
use Illuminate\Http\Request;

class DichVuController extends Controller
{
    public function index()
    {
        $dichVus = DichVu::all();
        return view('backend.dichvu.index', compact('dichVus'));
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
        ], [
            'MaDV.required' => 'Mã dịch vụ không được để trống.',
            'MaDV.unique' => 'Mã dịch vụ đã tồn tại.',
            'Tendichvu.required' => 'Tên dịch vụ không được để trống.',
            'Tendichvu.max' => 'Tên dịch vụ không được vượt quá 255 ký tự.',
            'Gia.required' => 'Giá không được để trống.',
            'Gia.numeric' => 'Giá phải là số.',
            'Gia.min' => 'Giá không được nhỏ hơn 0.',
            'MoTa.max' => 'Mô tả không được vượt quá 500 ký tự.',
        ]);

        DichVu::create([
            'MaDV' => $request->MaDV,
            'Tendichvu' => $request->Tendichvu,
            'Gia' => $request->Gia,
            'MoTa' => $request->MoTa,
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
        ], [
            'MaDV.required' => 'Mã dịch vụ không được để trống.',
            'MaDV.unique' => 'Mã dịch vụ đã tồn tại.',
            'Tendichvu.required' => 'Tên dịch vụ không được để trống.',
            'Tendichvu.max' => 'Tên dịch vụ không được vượt quá 255 ký tự.',
            'Gia.required' => 'Giá không được để trống.',
            'Gia.numeric' => 'Giá phải là số.',
            'Gia.min' => 'Giá không được nhỏ hơn 0.',
            'MoTa.max' => 'Mô tả không được vượt quá 500 ký tự.',
        ]);

        $dichVu->update([
            'MaDV' => $request->MaDV,
            'Tendichvu' => $request->Tendichvu,
            'Gia' => $request->Gia,
            'MoTa' => $request->MoTa,
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
}