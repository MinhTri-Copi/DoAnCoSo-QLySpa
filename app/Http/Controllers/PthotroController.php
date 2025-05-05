<?php

namespace App\Http\Controllers;

use App\Models\PTHoTro;
use Illuminate\Http\Request;

class PthotroController extends Controller
{
    public function index()
    {
        $pthotros = PTHoTro::all();
        return view('backend.pthotro.index', compact('pthotros'));
    }

    public function create()
    {
        return view('backend.pthotro.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'MaPTHT' => 'required|string|unique:PTHOTRO,MaPTHT',
            'TenPT' => 'required|string|max:255',
        ], [
            'MaPTHT.required' => 'Mã phương thức hỗ trợ không được để trống.',
            'MaPTHT.unique' => 'Mã phương thức hỗ trợ đã tồn tại.',
            'TenPT.required' => 'Tên phương thức hỗ trợ không được để trống.',
            'TenPT.max' => 'Tên phương thức hỗ trợ không được vượt quá 255 ký tự.',
        ]);

        PTHoTro::create([
            'MaPTHT' => $request->MaPTHT,
            'TenPT' => $request->TenPT,
        ]);

        return redirect()->route('admin.pthotro.index')->with('success', 'Thêm phương thức hỗ trợ thành công!');
    }

    public function show($id)
    {
        $pthotro = PTHoTro::findOrFail($id);
        return view('backend.pthotro.show', compact('pthotro'));
    }

    public function edit($id)
    {
        $pthotro = PTHoTro::findOrFail($id);
        return view('backend.pthotro.edit', compact('pthotro'));
    }

    public function update(Request $request, $id)
    {
        $pthotro = PTHoTro::findOrFail($id);

        $request->validate([
            'MaPTHT' => 'required|string|unique:PTHOTRO,MaPTHT,' . $pthotro->MaPTHT . ',MaPTHT',
            'TenPT' => 'required|string|max:255',
        ], [
            'MaPTHT.required' => 'Mã phương thức hỗ trợ không được để trống.',
            'MaPTHT.unique' => 'Mã phương thức hỗ trợ đã tồn tại.',
            'TenPT.required' => 'Tên phương thức hỗ trợ không được để trống.',
            'TenPT.max' => 'Tên phương thức hỗ trợ không được vượt quá 255 ký tự.',
        ]);

        $pthotro->update([
            'MaPTHT' => $request->MaPTHT,
            'TenPT' => $request->TenPT,
        ]);

        return redirect()->route('admin.pthotro.index')->with('success', 'Cập nhật phương thức hỗ trợ thành công!');
    }

    public function confirmDestroy($id)
    {
        $pthotro = PTHoTro::findOrFail($id);
        return view('backend.pthotro.destroy', compact('pthotro'));
    }

    public function destroy($id)
    {
        $pthotro = PTHoTro::findOrFail($id);

        try {
            $pthotro->delete();
            return redirect()->route('admin.pthotro.index')->with('success', 'Xóa phương thức hỗ trợ thành công!');
        } catch (\Exception $e) {
            return redirect()->route('admin.pthotro.index')->with('error', 'Không thể xóa phương thức hỗ trợ vì có dữ liệu liên quan!');
        }
    }
}