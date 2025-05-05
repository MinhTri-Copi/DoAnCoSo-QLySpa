<?php

namespace App\Http\Controllers;

use App\Models\TrangThaiQC;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller; 

class AdStatusController extends Controller
{
    public function __construct()
    {
        
    }

    public function index()
    {
        $statuses = TrangThaiQC::all();
        return view('backend.ad_statuses.index', compact('statuses'));
    }

    public function create()
    {
        $maxMaTTQC = TrangThaiQC::max('MaTTQC') ?? 0;
        $suggestedMaTTQC = $maxMaTTQC + 1;

        return view('backend.ad_statuses.create', compact('suggestedMaTTQC'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'MaTTQC' => 'required|integer|unique:TRANGTHAIQC,MaTTQC',
            'TenTT' => 'required|string|max:100',
        ]);

        TrangThaiQC::create([
            'MaTTQC' => $request->MaTTQC,
            'TenTT' => $request->TenTT,
        ]);

        return redirect()->route('ad-statuses.index')->with('success', 'Thêm trạng thái quảng cáo thành công!');
    }

    // Thêm phương thức xem chi tiết
    public function show($id)
    {
        $status = TrangThaiQC::findOrFail($id);
        return view('backend.ad_statuses.show', compact('status'));
    }

    public function edit($id)
    {
        $status = TrangThaiQC::findOrFail($id);
        return view('backend.ad_statuses.edit', compact('status'));
    }

    public function update(Request $request, $id)
    {
        $status = TrangThaiQC::findOrFail($id);

        $request->validate([
            'TenTT' => 'required|string|max:100',
        ]);

        $status->update([
            'TenTT' => $request->TenTT,
        ]);

        return redirect()->route('ad-statuses.index')->with('success', 'Cập nhật trạng thái quảng cáo thành công!');
    }

    // Hiển thị view xác nhận xóa
    public function confirmDestroy($id)
    {
        $status = TrangThaiQC::findOrFail($id);
        return view('backend.ad_statuses.destroy', compact('status'));
    }

    // Xử lý xóa
    public function destroy($id)
    {
        $status = TrangThaiQC::findOrFail($id);

        if ($status->quangCao()->count() > 0) {
            return redirect()->route('ad-statuses.index')->with('error', 'Không thể xóa trạng thái này vì nó đang được sử dụng!');
        }

        $status->delete();

        return redirect()->route('ad-statuses.index')->with('success', 'Xóa trạng thái quảng cáo thành công!');
    }
}