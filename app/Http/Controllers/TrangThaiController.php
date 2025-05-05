<?php

namespace App\Http\Controllers;

use App\Models\TrangThai;
use Illuminate\Http\Request;

class TrangThaiController extends Controller
{
    public function index()
    {
        $trangThais = TrangThai::all();
        return view('backend.trangthai.index', compact('trangThais'));
    }

    public function create()
    {
        $maxMatrangthai = TrangThai::max('Matrangthai') ?? 0;
        $suggestedMatrangthai = $maxMatrangthai + 1;
        return view('backend.trangthai.create', compact('suggestedMatrangthai'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'Matrangthai' => 'required|integer|unique:TRANGTHAI,Matrangthai',
            'Tentrangthai' => 'required|string|max:100',
        ], [
            'Matrangthai.required' => 'Mã trạng thái không được để trống.',
            'Matrangthai.unique' => 'Mã trạng thái đã tồn tại.',
            'Tentrangthai.required' => 'Tên trạng thái không được để trống.',
            'Tentrangthai.max' => 'Tên trạng thái không được vượt quá 100 ký tự.',
        ]);

        TrangThai::create([
            'Matrangthai' => $request->Matrangthai,
            'Tentrangthai' => $request->Tentrangthai,
        ]);

        return redirect()->route('admin.trangthai.index')->with('success', 'Thêm trạng thái thành công!');
    }

    public function show($id)
    {
        $trangThai = TrangThai::findOrFail($id);
        return view('backend.trangthai.show', compact('trangThai'));
    }

    public function edit($id)
    {
        $trangThai = TrangThai::findOrFail($id);
        return view('backend.trangthai.edit', compact('trangThai'));
    }

    public function update(Request $request, $id)
    {
        $trangThai = TrangThai::findOrFail($id);

        $request->validate([
            'Matrangthai' => 'required|integer|unique:TRANGTHAI,Matrangthai,' . $trangThai->Matrangthai . ',Matrangthai',
            'Tentrangthai' => 'required|string|max:100',
        ], [
            'Matrangthai.required' => 'Mã trạng thái không được để trống.',
            'Matrangthai.unique' => 'Mã trạng thái đã tồn tại.',
            'Tentrangthai.required' => 'Tên trạng thái không được để trống.',
            'Tentrangthai.max' => 'Tên trạng thái không được vượt quá 100 ký tự.',
        ]);

        $trangThai->update([
            'Matrangthai' => $request->Matrangthai,
            'Tentrangthai' => $request->Tentrangthai,
        ]);

        return redirect()->route('admin.trangthai.index')->with('success', 'Cập nhật trạng thái thành công!');
    }

    public function confirmDestroy($id)
    {
        $trangThai = TrangThai::findOrFail($id);
        return view('backend.trangthai.destroy', compact('trangThai'));
    }

    public function destroy($id)
    {
        $trangThai = TrangThai::findOrFail($id);

        try {
            $trangThai->delete();
            return redirect()->route('admin.trangthai.index')->with('success', 'Xóa trạng thái thành công!');
        } catch (\Exception $e) {
            return redirect()->route('admin.trangthai.index')->with('error', 'Không thể xóa trạng thái vì có dữ liệu liên quan!');
        }
    }
}