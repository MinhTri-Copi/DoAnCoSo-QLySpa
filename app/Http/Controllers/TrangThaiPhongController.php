<?php

namespace App\Http\Controllers;

use App\Models\TrangThaiPhong;
use Illuminate\Http\Request;

class TrangThaiPhongController extends Controller
{
    public function index()
    {
        $trangThaiPhongs = TrangThaiPhong::all();
        return view('backend.trangthaiphong.index', compact('trangThaiPhongs'));
    }

    public function create()
    {
        // Lấy giá trị MatrangthaiP lớn nhất
        $maxMaTTP =TrangThaiPhong::max('MatrangthaiP') ?? 0;
        $suggestedMaTTP = $maxMaTTP + 1;

        return view('backend.trangthaiphong.create', compact('suggestedMaTTP'));
    }

    public function store(Request $request)
    {
        // Lấy giá trị MatrangthaiP lớn nhất
        $maxMatrangthaiP = TrangThaiPhong::max('MatrangthaiP');
        if ($maxMatrangthaiP) {
            $number = (int) substr($maxMatrangthaiP, 3);
            $suggestedMatrangthaiP = 'TTP' . ($number + 1);
        } else {
            $suggestedMatrangthaiP = 'TTP1';
        }

        $request->validate([
            'Tentrangthai' => 'required|string|max:255',
        ], [
            'Tentrangthai.required' => 'Tên trạng thái không được để trống.',
            'Tentrangthai.max' => 'Tên trạng thái không được vượt quá 255 ký tự.',
        ]);

        TrangThaiPhong::create([
            'MatrangthaiP' => $suggestedMatrangthaiP,
            'Tentrangthai' => $request->Tentrangthai,
        ]);

        return redirect()->route('admin.trangthaiphong.index')->with('success', 'Thêm trạng thái phòng thành công!');
    }

    public function show($id)
    {
        $trangThaiPhong = TrangThaiPhong::findOrFail($id);
        return view('backend.trangthaiphong.show', compact('trangThaiPhong'));
    }

    public function edit($id)
    {
        $trangThaiPhong = TrangThaiPhong::findOrFail($id);
        return view('backend.trangthaiphong.edit', compact('trangThaiPhong'));
    }

    public function update(Request $request, $id)
    {
        $trangThaiPhong = TrangThaiPhong::findOrFail($id);

        $request->validate([
            'Tentrangthai' => 'required|string|max:255',
        ], [
            'Tentrangthai.required' => 'Tên trạng thái không được để trống.',
            'Tentrangthai.max' => 'Tên trạng thái không được vượt quá 255 ký tự.',
        ]);

        $trangThaiPhong->update([
            'Tentrangthai' => $request->Tentrangthai,
        ]);

        return redirect()->route('admin.trangthaiphong.index')->with('success', 'Cập nhật trạng thái phòng thành công!');
    }

    public function confirmDestroy($id)
    {
        $trangThaiPhong = TrangThaiPhong::findOrFail($id);
        return view('backend.trangthaiphong.destroy', compact('trangThaiPhong'));
    }

    public function destroy($id)
    {
        $trangThaiPhong = TrangThaiPhong::findOrFail($id);

        try {
            $trangThaiPhong->delete();
            return redirect()->route('admin.trangthaiphong.index')->with('success', 'Xóa trạng thái phòng thành công!');
        } catch (\Exception $e) {
            return redirect()->route('admin.trangthaiphong.index')->with('error', 'Không thể xóa trạng thái phòng vì có dữ liệu liên quan!');
        }
    }
}