<?php

namespace App\Http\Controllers;

use App\Models\HangThanhVien;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\MembershipRankService;

class MembershipRankController extends Controller
{
    protected $rankDescriptions = [
        'Thành viên Bạc' => 'Hạng thành viên dành cho người mới',
        'Thành viên Vàng' => 'Hạng thành viên dành cho người dùng tích cực',
        'Thành viên Bạch Kim' => 'Hạng thành viên dành cho người dùng cao cấp',
        'Thành viên Kim Cương' => 'Hạng thành viên cao nhất dành cho người dùng xuất sắc',
    ];

    public function index(Request $request)
    {
        // Lấy các tham số tìm kiếm và lọc từ request
        $search = $request->input('search');
        $rankType = $request->input('rank_type');
        
        // Query cơ bản
        $query = HangThanhVien::with('user');
        
        // Áp dụng tìm kiếm nếu có
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('Mahang', 'like', "%{$search}%")
                  ->orWhere('Tenhang', 'like', "%{$search}%")
                  ->orWhere('Mota', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('Hoten', 'like', "%{$search}%")
                                ->orWhere('Email', 'like', "%{$search}%");
                  });
            });
        }
        
        // Áp dụng bộ lọc theo loại hạng nếu có
        if ($rankType) {
            $query->where('Tenhang', $rankType);
        }
        
        // Lấy dữ liệu và phân trang
        $ranks = $query->paginate(10);
        
        // Lấy thống kê
        $totalRanks = HangThanhVien::count();
        $diamondCount = HangThanhVien::where('Tenhang', 'Thành viên Kim Cương')->count();
        $silverCount = HangThanhVien::where('Tenhang', 'Thành viên Bạc')->count();
        $goldCount = HangThanhVien::where('Tenhang', 'Thành viên Vàng')->count();
        $platinumCount = HangThanhVien::where('Tenhang', 'Thành viên Bạch Kim')->count();
        
        return view('backend.membership_ranks.index', compact('ranks', 'totalRanks', 'diamondCount', 'silverCount', 'goldCount', 'platinumCount', 'search', 'rankType'));
    }

    public function create()
    {
        $maxMahang = HangThanhVien::max('Mahang') ?? 0;
        $suggestedMahang = $maxMahang + 1;

        // Only get users who don't already have a membership rank
        $users = User::whereDoesntHave('hangThanhVien')->get();
        $rankTypes = array_keys($this->rankDescriptions);
        
        return view('backend.membership_ranks.create', compact('suggestedMahang', 'users', 'rankTypes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'Mahang' => 'required|integer|unique:HANGTHANHVIEN,Mahang',
            'Tenhang' => 'required|string|in:' . implode(',', array_keys($this->rankDescriptions)),
            'Manguoidung' => 'required|exists:USER,Manguoidung|unique:HANGTHANHVIEN,Manguoidung',
        ], [
            'Mahang.required' => 'Mã hạng là bắt buộc',
            'Mahang.integer' => 'Mã hạng phải là số nguyên',
            'Mahang.unique' => 'Mã hạng đã tồn tại',
            'Tenhang.required' => 'Tên hạng là bắt buộc',
            'Tenhang.in' => 'Tên hạng không hợp lệ',
            'Manguoidung.required' => 'Người dùng là bắt buộc',
            'Manguoidung.exists' => 'Người dùng không tồn tại',
            'Manguoidung.unique' => 'Người dùng này đã có hạng thành viên',
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
        
        // Get only users without a membership rank or the current user
        $users = User::where('Manguoidung', $rank->Manguoidung)
                    ->orWhereDoesntHave('hangThanhVien')
                    ->get();
                    
        $rankTypes = array_keys($this->rankDescriptions);
        return view('backend.membership_ranks.edit', compact('rank', 'users', 'rankTypes'));
    }

    public function update(Request $request, $id)
    {
        $rank = HangThanhVien::findOrFail($id);

        $request->validate([
            'Tenhang' => 'required|string|in:' . implode(',', array_keys($this->rankDescriptions)),
            'Manguoidung' => 'required|exists:USER,Manguoidung|unique:HANGTHANHVIEN,Manguoidung,' . $id . ',Mahang',
        ], [
            'Tenhang.required' => 'Tên hạng là bắt buộc',
            'Tenhang.in' => 'Tên hạng không hợp lệ',
            'Manguoidung.required' => 'Người dùng là bắt buộc',
            'Manguoidung.exists' => 'Người dùng không tồn tại',
            'Manguoidung.unique' => 'Người dùng này đã có hạng thành viên khác',
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
    
    /**
     * Update all user membership ranks based on their reward points
     */
    public function updateAllRanks()
    {
        try {
            $membershipService = app(MembershipRankService::class);
            $stats = $membershipService->updateAllMembershipRanks();
            
            $message = "Cập nhật thành công hạng thành viên cho {$stats['total']} người dùng!";
            return redirect()->route('admin.membership_ranks.index')->with('success', $message);
        } catch (\Exception $e) {
            return redirect()->route('admin.membership_ranks.index')->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }
    
    // Thêm các phương thức API cho Ajax
    public function apiSearch(Request $request)
    {
        $search = $request->input('search');
        $rankType = $request->input('rank_type');
        
        $query = HangThanhVien::with('user');
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('Mahang', 'like', "%{$search}%")
                  ->orWhere('Tenhang', 'like', "%{$search}%")
                  ->orWhere('Mota', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('Hoten', 'like', "%{$search}%")
                                ->orWhere('Email', 'like', "%{$search}%");
                  });
            });
        }
        
        if ($rankType) {
            $query->where('Tenhang', $rankType);
        }
        
        $ranks = $query->get();
        
        return response()->json($ranks);
    }
    
    /**
     * Clean up duplicate membership ranks
     */
    public function cleanupDuplicates()
    {
        try {
            // Get all users with more than one membership rank
            $users = DB::table('HANGTHANHVIEN')
                ->select('Manguoidung', DB::raw('count(*) as total'))
                ->groupBy('Manguoidung')
                ->having('total', '>', 1)
                ->get();
                
            if ($users->count() === 0) {
                return redirect()->route('admin.membership_ranks.index')
                    ->with('success', 'Không tìm thấy hạng thành viên trùng lặp.');
            }
            
            $total = 0;
            $affectedUsers = [];
            
            foreach ($users as $user) {
                // Get all ranks for this user, ordered by Mahang (assuming higher is newer)
                $ranks = DB::table('HANGTHANHVIEN')
                    ->where('Manguoidung', $user->Manguoidung)
                    ->orderBy('Mahang', 'desc')
                    ->get();
                    
                // Keep the first one (latest) and delete the rest
                $keepRankId = $ranks->first()->Mahang;
                
                $deleted = DB::table('HANGTHANHVIEN')
                    ->where('Manguoidung', $user->Manguoidung)
                    ->where('Mahang', '!=', $keepRankId)
                    ->delete();
                    
                $total += $deleted;
                $userData = User::find($user->Manguoidung);
                $affectedUsers[] = $userData ? $userData->Hoten : "User #{$user->Manguoidung}";
            }
            
            $message = "Đã xóa {$total} hạng thành viên trùng lặp của " . count($affectedUsers) . " người dùng.";
            if (count($affectedUsers) <= 5) {
                $message .= " Người dùng ảnh hưởng: " . implode(', ', $affectedUsers);
            }
            
            return redirect()->route('admin.membership_ranks.index')
                ->with('success', $message);
        } catch (\Exception $e) {
            return redirect()->route('admin.membership_ranks.index')
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }
    
    public function apiGetStatistics()
    {
        $totalRanks = HangThanhVien::count();
        $rankCounts = HangThanhVien::select('Tenhang', DB::raw('count(*) as count'))
                                  ->groupBy('Tenhang')
                                  ->get()
                                  ->pluck('count', 'Tenhang')
                                  ->toArray();
        
        // Đảm bảo có số lượng cho mỗi loại hạng thành viên
        $silverCount = HangThanhVien::where('Tenhang', 'Thành viên Bạc')->count();
        $goldCount = HangThanhVien::where('Tenhang', 'Thành viên Vàng')->count();
        $platinumCount = HangThanhVien::where('Tenhang', 'Thành viên Bạch Kim')->count();
        $diamondCount = HangThanhVien::where('Tenhang', 'Thành viên Kim Cương')->count();
        
        return response()->json([
            'totalRanks' => $totalRanks,
            'rankCounts' => $rankCounts,
            'silverCount' => $silverCount,
            'goldCount' => $goldCount,
            'platinumCount' => $platinumCount,
            'diamondCount' => $diamondCount
        ]);
    }
}