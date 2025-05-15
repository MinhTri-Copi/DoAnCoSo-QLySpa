<?php

namespace App\Http\Controllers;

use App\Models\Phong;
use App\Models\TrangThaiPhong;
use App\Models\HoaDonVaThanhToan;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class PhongController extends Controller
{
    public function index()
    {
        $phongs = Phong::with('trangThaiPhong')->get();
        
        $phongStats = HoaDonVaThanhToan::select('Maphong', DB::raw('count(*) as total_usage'))
            ->groupBy('Maphong')
            ->orderBy('total_usage', 'desc')
            ->get()
            ->keyBy('Maphong');
        
        $phongRevenue = HoaDonVaThanhToan::select('Maphong', DB::raw('sum(Tongtien) as total_revenue'))
            ->groupBy('Maphong')
            ->orderBy('total_revenue', 'desc')
            ->get()
            ->keyBy('Maphong');
            
        $topPhongs = $phongStats->take(5);
        
        return view('backend.phong.index', compact('phongs', 'phongStats', 'phongRevenue', 'topPhongs'));
    }

    public function create()
    {
        $trangThaiPhongs = TrangThaiPhong::all();
        $maxMaphong = Phong::max('Maphong') ?? 0;
        $suggestedMaphong = $maxMaphong + 1;

        return view('backend.phong.create', compact('trangThaiPhongs', 'suggestedMaphong'));
    }

    public function store(Request $request)
    {
        $maxMaphong = Phong::max('Maphong');
        if ($maxMaphong) {
            $number = (int) substr($maxMaphong, 2);
            $suggestedMaphong = 'P' . ($number + 1);
        } else {
            $suggestedMaphong = 'P1';
        }

        $request->validate([
            'Tenphong' => 'required|string|max:255',
            'Loaiphong' => 'required|string|max:255',
            'MatrangthaiP' => 'required|exists:TRANGTHAIPHONG,MatrangthaiP',
        ], [
            'Tenphong.required' => 'Tên phòng không được để trống.',
            'Tenphong.max' => 'Tên phòng không được vượt quá 255 ký tự.',
            'Loaiphong.required' => 'Loại phòng không được để trống.',
            'Loaiphong.max' => 'Loại phòng không được vượt quá 255 ký tự.',
            'MatrangthaiP.required' => 'Trạng thái phòng không được để trống.',
            'MatrangthaiP.exists' => 'Trạng thái phòng không hợp lệ.',
        ]);

        Phong::create([
            'Maphong' => $suggestedMaphong,
            'Tenphong' => $request->Tenphong,
            'Loaiphong' => $request->Loaiphong,
            'MatrangthaiP' => $request->MatrangthaiP,
        ]);

        return redirect()->route('admin.phong.index')->with('success', 'Thêm phòng thành công!');
    }

    public function show($id)
    {
        $phong = Phong::with('trangThaiPhong')->findOrFail($id);
        return view('backend.phong.show', compact('phong'));
    }

    public function edit($id)
    {
        $phong = Phong::findOrFail($id);
        $trangThaiPhongs = TrangThaiPhong::all();
        return view('backend.phong.edit', compact('phong', 'trangThaiPhongs'));
    }

    public function update(Request $request, $id)
    {
        $phong = Phong::findOrFail($id);

        $request->validate([
            'Tenphong' => 'required|string|max:255',
            'Loaiphong' => 'required|string|max:255',
            'MatrangthaiP' => 'required|exists:TRANGTHAIPHONG,MatrangthaiP',
        ], [
            'Tenphong.required' => 'Tên phòng không được để trống.',
            'Tenphong.max' => 'Tên phòng không được vượt quá 255 ký tự.',
            'Loaiphong.required' => 'Loại phòng không được để trống.',
            'Loaiphong.max' => 'Loại phòng không được vượt quá 255 ký tự.',
            'MatrangthaiP.required' => 'Trạng thái phòng không được để trống.',
            'MatrangthaiP.exists' => 'Trạng thái phòng không hợp lệ.',
        ]);

        $phong->update([
            'Tenphong' => $request->Tenphong,
            'Loaiphong' => $request->Loaiphong,
            'MatrangthaiP' => $request->MatrangthaiP,
        ]);

        return redirect()->route('admin.phong.index')->with('success', 'Cập nhật phòng thành công!');
    }

    public function confirmDestroy($id)
    {
        $phong = Phong::findOrFail($id);
        return view('backend.phong.destroy', compact('phong'));
    }

    public function destroy($id)
    {
        $phong = Phong::findOrFail($id);

        try {
            $phong->delete();
            return redirect()->route('admin.phong.index')->with('success', 'Xóa phòng thành công!');
        } catch (\Exception $e) {
            return redirect()->route('admin.phong.index')->with('error', 'Không thể xóa phòng vì có dữ liệu liên quan!');
        }
    }
}