<?php

namespace App\Http\Controllers;

use App\Models\TrangThaiQC;
use App\Models\QuangCao;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller; 
use Illuminate\Support\Facades\DB;

class AdStatusController extends Controller
{
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

        return redirect()->route('admin.ad-statuses.index')->with('success', 'Thêm trạng thái quảng cáo thành công!');
    }

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

        return redirect()->route('admin.ad-statuses.show', $id)->with('success', 'Cập nhật trạng thái quảng cáo thành công!');
    }

    public function confirmDestroy($id)
    {
        $status = TrangThaiQC::findOrFail($id);
        return view('backend.ad_statuses.destroy', compact('status'));
    }

    public function destroy($id)
    {
        $status = TrangThaiQC::findOrFail($id);
        $status->delete();
        return redirect()->route('admin.ad-statuses.index')->with('success', 'Xóa trạng thái quảng cáo thành công!');
    }
    
    // New method to get statistics
    public function statistics()
    {
        // Get count of ads by status
        $statusCounts = TrangThaiQC::withCount('quangCao')->get();
        
        // Get monthly trends
        $monthlyTrends = QuangCao::select(
                DB::raw('MONTH(created_at) as month'), 
                DB::raw('YEAR(created_at) as year'), 
                'MaTTQC',
                DB::raw('count(*) as count')
            )
            ->whereRaw('created_at >= DATE_SUB(NOW(), INTERVAL 12 MONTH)')
            ->groupBy('year', 'month', 'MaTTQC')
            ->orderBy('year')
            ->orderBy('month')
            ->get()
            ->groupBy('MaTTQC');
            
        // Format for chart
        $chartData = [];
        $monthlyTrends->each(function($items, $statusId) use (&$chartData) {
            $statusName = TrangThaiQC::find($statusId)->TenTT;
            $chartData[$statusId] = [
                'name' => $statusName,
                'data' => $items->map(function($item) {
                    return [
                        'x' => $item->year . '-' . str_pad($item->month, 2, '0', STR_PAD_LEFT),
                        'y' => $item->count
                    ];
                })->values()->toArray()
            ];
        });
        
        return view('backend.ad_statuses.statistics', [
            'statusCounts' => $statusCounts,
            'chartData' => json_encode(array_values($chartData))
        ]);
    }
}