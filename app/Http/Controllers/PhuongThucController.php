<?php

namespace App\Http\Controllers;

use App\Models\PhuongThuc;
use Illuminate\Http\Request;

class PhuongThucController extends Controller
{
    public function index()
    {
        $phuongThucs = PhuongThuc::all();
        return view('backend.phuongthuc.index', compact('phuongThucs'));
    }

    public function create()
    {
        $maxMaPT = PhuongThuc::max('MaPT') ?? 0;
        $suggestedMaPT = $maxMaPT + 1;

        return view('backend.phuongthuc.create', compact('suggestedMaPT'));
    }

    public function store(Request $request)
    {
        $maxMaPT = PhuongThuc::max('MaPT') ?? 0;
        $suggestedMaPT = $maxMaPT + 1;

        $request->validate([
            'TenPT' => 'required|string|max:255',
            'Mota' => 'nullable|string',
        ], [
            'TenPT.required' => 'Tên phương thức không được để trống.',
            'TenPT.max' => 'Tên phương thức không được vượt quá 255 ký tự.',
        ]);

        PhuongThuc::create([
            'MaPT' => $suggestedMaPT,
            'TenPT' => $request->TenPT,
            'Mota' => $request->Mota,
        ]);

        return redirect()->route('admin.phuongthuc.index')->with('success', 'Thêm phương thức thành công!');
    }

    public function show($id)
    {
        $phuongThuc = PhuongThuc::findOrFail($id);
        return view('backend.phuongthuc.show', compact('phuongThuc'));
    }

    public function edit($id)
    {
        $phuongThuc = PhuongThuc::findOrFail($id);
        return view('backend.phuongthuc.edit', compact('phuongThuc'));
    }

    public function update(Request $request, $id)
    {
        $phuongThuc = PhuongThuc::findOrFail($id);

        $request->validate([
            'TenPT' => 'required|string|max:255',
            'Mota' => 'nullable|string',
        ], [
            'TenPT.required' => 'Tên phương thức không được để trống.',
            'TenPT.max' => 'Tên phương thức không được vượt quá 255 ký tự.',
        ]);

        $phuongThuc->update([
            'TenPT' => $request->TenPT,
            'Mota' => $request->Mota,
        ]);

        return redirect()->route('admin.phuongthuc.index')->with('success', 'Cập nhật phương thức thành công!');
    }

    public function confirmDestroy($id)
    {
        $phuongThuc = PhuongThuc::findOrFail($id);
        return view('backend.phuongthuc.destroy', compact('phuongThuc'));
    }

    public function destroy($id)
    {
        $phuongThuc = PhuongThuc::findOrFail($id);

        try {
            $phuongThuc->delete();
            return redirect()->route('admin.phuongthuc.index')->with('success', 'Xóa phương thức thành công!');
        } catch (\Exception $e) {
            return redirect()->route('admin.phuongthuc.index')->with('error', 'Không thể xóa phương thức vì có dữ liệu liên quan!');
        }
    }
}