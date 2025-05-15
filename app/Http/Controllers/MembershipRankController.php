<?php

namespace App\Http\Controllers;

use App\Models\HangThanhVien;
use App\Models\User;
use Illuminate\Http\Request;

class MembershipRankController extends Controller
{
    protected $rankDescriptions = [
        'Thành viên Bạc' => 'Hạng thành viên dành cho người mới',
        'Thành viên Vàng' => 'Hạng thành viên dành cho người dùng tích cực',
        'Thành viên Bạch Kim' => 'Hạng thành viên dành cho người dùng cao cấp',
        'Thành viên Kim Cương' => 'Hạng thành viên cao nhất dành cho người dùng xuất sắc',
    ];

    public function index()
    {
        $ranks = HangThanhVien::with('user')->get();
        return view('backend.membership_ranks.index', compact('ranks'));
    }

    public function create()
    {
        $maxMahang = HangThanhVien::max('Mahang') ?? 0;
        $suggestedMahang = $maxMahang + 1;

        $users = User::all();
        $rankTypes = array_keys($this->rankDescriptions);
        return view('backend.membership_ranks.create', compact('suggestedMahang', 'users', 'rankTypes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'Mahang' => 'required|integer|unique:HANGTHANHVIEN,Mahang',
            'Tenhang' => 'required|string|in:' . implode(',', array_keys($this->rankDescriptions)),
            'Manguoidung' => 'required|exists:USER,Manguoidung|unique:HANGTHANHVIEN,Manguoidung',
        ]);

        HangThanhVien::create([
            'Mahang' => $request->Mahang,
            'Tenhang' => $request->Tenhang,
            'Mota' => $this->rankDescriptions[$request->Tenhang],
            'Manguoidung' => $request->Manguoidung,
        ]);

        return redirect()->route('admin.membership_ranks.index')->with('success', 'Thêm hạng thành viên thành công!');
    }

    public function show($id)
    {
        $rank = HangThanhVien::with('user')->findOrFail($id);
        return view('backend.membership_ranks.show', compact('rank'));
    }

    public function edit($id)
    {
        $rank = HangThanhVien::findOrFail($id);
        $users = User::whereDoesntHave('membershipRank')->orWhere('Manguoidung', $rank->Manguoidung)->get();
        $rankTypes = array_keys($this->rankDescriptions);
        return view('backend.membership_ranks.edit', compact('rank', 'users', 'rankTypes'));
    }

    public function update(Request $request, $id)
    {
        $rank = HangThanhVien::findOrFail($id);

        $request->validate([
            'Tenhang' => 'required|string|in:' . implode(',', array_keys($this->rankDescriptions)),
            'Manguoidung' => 'required|exists:USER,Manguoidung|unique:HANGTHANHVIEN,Manguoidung,' . $id . ',Mahang',
        ]);

        $rank->update([
            'Tenhang' => $request->Tenhang,
            'Mota' => $this->rankDescriptions[$request->Tenhang],
            'Manguoidung' => $request->Manguoidung,
        ]);

        return redirect()->route('admin.membership_ranks.index')->with('success', 'Cập nhật hạng thành viên thành công!');
    }

    public function confirmDestroy($id)
    {
        $rank = HangThanhVien::findOrFail($id);
        return view('backend.membership_ranks.destroy', compact('rank'));
    }

    public function destroy($id)
    {
        $rank = HangThanhVien::findOrFail($id);
        $rank->delete();

        return redirect()->route('admin.membership_ranks.index')->with('success', 'Xóa hạng thành viên thành công!');
    }
}