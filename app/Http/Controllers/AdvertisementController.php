<?php

namespace App\Http\Controllers;

use App\Models\QuangCao;
use App\Models\TrangThaiQC;
use App\Models\User;
use Illuminate\Http\Request;

class AdvertisementController extends Controller
{
    public function index()
    {
        $advertisements = QuangCao::with(['trangThaiQC', 'user'])->get();
        return view('backend.advertisements.index', compact('advertisements'));
    }

    public function create()
    {
        $maxMaQC = QuangCao::max('MaQC') ?? 0;
        $suggestedMaQC = $maxMaQC + 1;

        $statuses = TrangThaiQC::all();
        $users = User::all();
        $adTypes = ['Khuyenmai', 'Sukien', 'Thongbao']; // Thay đổi để thống nhất với tiếng Việt không dấu
        return view('backend.advertisements.create', compact('suggestedMaQC', 'statuses', 'users', 'adTypes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'MaQC' => 'required|integer|unique:QUANGCAO,MaQC',
            'Tieude' => 'required|string|max:255',
            'Noidung' => 'required|string',
            'Image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'Manguoidung' => 'required|exists:USER,Manguoidung',
            'Loaiquangcao' => 'required|string|in:Khuyenmai,Sukien,Thongbao',
            'MaTTQC' => 'required|exists:TRANGTHAIQC,MaTTQC',
            'Ngaybatdau' => 'required|date',
            'Ngayketthuc' => 'required|date|after_or_equal:Ngaybatdau',
        ]);

        $imagePath = null;
        if ($request->hasFile('Image')) {
            $imagePath = $request->file('Image')->store('advertisements', 'public');
        }

        QuangCao::create([
            'MaQC' => $request->MaQC,
            'Tieude' => $request->Tieude,
            'Noidung' => $request->Noidung,
            'Image' => $imagePath,
            'Manguoidung' => $request->Manguoidung,
            'Loaiquangcao' => $request->Loaiquangcao,
            'MaTTQC' => $request->MaTTQC,
            'Ngaybatdau' => $request->Ngaybatdau,
            'Ngayketthuc' => $request->Ngayketthuc,
        ]);

        return redirect()->route('admin.advertisements.index')->with('success', 'Thêm quảng cáo thành công!');
    }

    public function show($id)
    {
        $advertisement = QuangCao::with(['trangThaiQC', 'user'])->findOrFail($id);
        return view('backend.advertisements.show', compact('advertisement'));
    }

    public function edit($id)
    {
        $advertisement = QuangCao::findOrFail($id);
        $statuses = TrangThaiQC::all();
        $users = User::all();
        $adTypes = ['Khuyenmai', 'Sukien', 'Thongbao'];
        return view('backend.advertisements.edit', compact('advertisement', 'statuses', 'users', 'adTypes'));
    }

    public function update(Request $request, $id)
    {
        $advertisement = QuangCao::findOrFail($id);

        $request->validate([
            'Tieude' => 'required|string|max:255',
            'Noidung' => 'required|string',
            'Image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'Manguoidung' => 'required|exists:USER,Manguoidung',
            'Loaiquangcao' => 'required|string|in:Khuyenmai,Sukien,Thongbao',
            'MaTTQC' => 'required|exists:TRANGTHAIQC,MaTTQC',
            'Ngaybatdau' => 'required|date',
            'Ngayketthuc' => 'required|date|after_or_equal:Ngaybatdau',
        ]);

        $imagePath = $advertisement->Image;
        if ($request->hasFile('Image')) {
            if ($imagePath && \Storage::disk('public')->exists($imagePath)) {
                \Storage::disk('public')->delete($imagePath);
            }
            $imagePath = $request->file('Image')->store('advertisements', 'public');
        }

        $advertisement->update([
            'Tieude' => $request->Tieude,
            'Noidung' => $request->Noidung,
            'Image' => $imagePath,
            'Manguoidung' => $request->Manguoidung,
            'Loaiquangcao' => $request->Loaiquangcao,
            'MaTTQC' => $request->MaTTQC,
            'Ngaybatdau' => $request->Ngaybatdau,
            'Ngayketthuc' => $request->Ngayketthuc,
        ]);

        return redirect()->route('admin.advertisements.index')->with('success', 'Cập nhật quảng cáo thành công!');
    }

    public function confirmDestroy($id)
    {
        $advertisement = QuangCao::findOrFail($id);
        return view('backend.advertisements.destroy', compact('advertisement'));
    }

    public function destroy($id)
    {
        $advertisement = QuangCao::findOrFail($id);

        if ($advertisement->Image && \Storage::disk('public')->exists($advertisement->Image)) {
            \Storage::disk('public')->delete($advertisement->Image);
        }

        $advertisement->delete();

        return redirect()->route('admin.advertisements.index')->with('success', 'Xóa quảng cáo thành công!');
    }
}