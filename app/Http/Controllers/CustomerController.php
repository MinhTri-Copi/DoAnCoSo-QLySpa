<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Account;
use App\Models\HangThanhVien;
use App\Models\LSDiemThuong;
use App\Models\DatLich;
use App\Models\DichVu;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class CustomerController extends Controller
{
    /**
     * Display a listing of the customers with search and filter capabilities
     */
    public function index(Request $request)
    {
        try {
            // Base query
            $query = User::query();
            
            // Search functionality
            if ($search = $request->input('search')) {
                $query->where(function($q) use ($search) {
                    $q->where('Hoten', 'like', "%{$search}%")
                      ->orWhere('Email', 'like', "%{$search}%")
                      ->orWhere('SDT', 'like', "%{$search}%");
                });
            }
            
            // Filtering
            if ($gender = $request->input('gender')) {
                $query->where('Gioitinh', $gender);
            }
            
            if ($membership = $request->input('membership')) {
                $query->whereHas('hangThanhVien', function($q) use ($membership) {
                    $q->where('Tenhang', $membership);
                });
            }
            
            // Date range filtering
            if ($request->has('date_from') && $request->has('date_to')) {
                $dateFrom = Carbon::parse($request->date_from)->startOfDay();
                $dateTo = Carbon::parse($request->date_to)->endOfDay();
                $query->whereBetween('created_at', [$dateFrom, $dateTo]);
            }
            
            // Sorting
            $sortField = $request->input('sort_by', 'Manguoidung');
            $sortDirection = $request->input('sort_direction', 'asc');
            
            // Eager loading to reduce DB queries
            $query->with(['account', 'hangThanhVien', 'hoaDon', 'datLich', 'datLich.dichVu']);
            
            // Get all customers for statistics
            $allCustomers = $query->get();
            
            // Paginate results for display
            $customers = $query->orderBy($sortField, $sortDirection)->paginate(10)->withQueryString();
            
            // Get membership levels for filter dropdown
            $membershipLevels = HangThanhVien::select('Tenhang')->distinct()->pluck('Tenhang');
            
            return view('backend.customers.index', compact('customers', 'membershipLevels', 'allCustomers'));
        } catch (\Exception $e) {
            Log::error('Error in customer index: ' . $e->getMessage());
            return back()->with('error', 'Có lỗi xảy ra khi tải danh sách khách hàng: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new customer
     */
    public function create()
    {
        try {
            $maxManguoidung = User::max('Manguoidung') ?? 0;
            $suggestedManguoidung = $maxManguoidung + 1;

            // Get available accounts that aren't already linked to a user
            $accounts = Account::all();
            
            // Log số lượng tài khoản để debug
            \Log::info('Accounts available for create customer:', ['count' => $accounts->count()]);
            
            // Get membership ranks for dropdown
            $membershipRanks = ['Thường', 'VIP', 'Platinum', 'Diamond'];
            
            return view('backend.customers.create', compact('suggestedManguoidung', 'accounts', 'membershipRanks'));
        } catch (\Exception $e) {
            Log::error('Error in customer create form: ' . $e->getMessage());
            return back()->with('error', 'Có lỗi xảy ra khi tạo form: ' . $e->getMessage());
        }
    }

    /**
     * Store a newly created customer in storage
     */
    public function store(Request $request)
    {
        // Enhanced validation
        $request->validate([
            'Manguoidung' => 'required|integer|unique:USER,Manguoidung',
            'MaTK' => 'required|exists:ACCOUNT,MaTK',
            'Hoten' => 'required|string|max:255',
            'SDT' => 'required|string|max:15|regex:/^[0-9]+$/',
            'DiaChi' => 'required|string|max:255',
            'Email' => 'required|email|unique:USER,Email',
            'Ngaysinh' => 'required|date|before:today',
            'Gioitinh' => 'required|in:Nam,Nữ',
            'membership_level' => 'nullable|string|in:Thường,VIP,Platinum,Diamond',
        ]);

        DB::beginTransaction();
        
        try {
            // Create user
            $user = User::create([
                'Manguoidung' => $request->Manguoidung,
                'MaTK' => $request->MaTK,
                'Hoten' => $request->Hoten,
                'SDT' => $request->SDT,
                'DiaChi' => $request->DiaChi,
                'Email' => $request->Email,
                'Ngaysinh' => $request->Ngaysinh,
                'Gioitinh' => $request->Gioitinh,
            ]);
            
            // Create membership level - default to "Thường" if not specified
            $membershipLevel = $request->membership_level ?? 'Thường';
            
            HangThanhVien::create([
                'Mahang' => 'TH' . $user->Manguoidung,
                'Tenhang' => $membershipLevel,
                'Mota' => 'Hạng thành viên ' . ($membershipLevel == 'Thường' ? 'mặc định' : $membershipLevel),
                'Manguoidung' => $user->Manguoidung,
            ]);
            
            // Add initial welcome points
            $initialPoints = 100;
            if ($membershipLevel == 'VIP') $initialPoints = 200;
            if ($membershipLevel == 'Platinum') $initialPoints = 500;
            if ($membershipLevel == 'Diamond') $initialPoints = 1000;
            
            LSDiemThuong::create([
                'MaLS' => 'LS' . time(),
                'Ngay' => Carbon::now(),
                'Diem' => $initialPoints,
                'Loai' => 'Cộng',
                'Manguoidung' => $user->Manguoidung,
                'Ghichu' => 'Điểm thưởng chào mừng thành viên mới' . ($membershipLevel != 'Thường' ? ' hạng ' . $membershipLevel : ''),
            ]);
            
            DB::commit();
            
            return redirect()->route('admin.customers.show', $user->Manguoidung)
                ->with('success', 'Thêm khách hàng thành công! Khách hàng đã được cấp ' . $initialPoints . ' điểm thưởng.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating customer: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified customer with all related data
     */
    public function show($id)
    {
        try {
            $customer = User::with([
                'account', 
                'account.role', 
                'hangThanhVien', 
                'datLich', 
                'datLich.dichVu',
                'hoaDon', 
                'hoaDon.trangThai', 
                'hoaDon.phong', 
                'hoaDon.phuongThuc',
                'danhGia',
                'danhGia.dichVu',
                'phieuHoTro',
                'lsDiemThuong'
            ])->findOrFail($id);
            
            // Calculate statistics for customer dashboard
            $stats = $this->calculateCustomerStats($customer);
            
            return view('backend.customers.show', compact('customer', 'stats'));
        } catch (\Exception $e) {
            Log::error('Error showing customer: ' . $e->getMessage());
            return redirect()->route('admin.customers.index')
                ->with('error', 'Không thể hiển thị thông tin khách hàng: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified customer
     */
    public function edit($id)
    {
        try {
            $customer = User::with('hangThanhVien')->findOrFail($id);
            
            // Get all available accounts plus the current customer's account
            $accounts = Account::where(function($query) use ($customer) {
                $query->whereDoesntHave('user')
                    ->orWhere('MaTK', $customer->MaTK);
            })->get();
            
            // Get membership ranks for dropdown
            $membershipRanks = ['Thường', 'VIP', 'Platinum', 'Diamond'];
            
            return view('backend.customers.edit', compact('customer', 'accounts', 'membershipRanks'));
        } catch (\Exception $e) {
            Log::error('Error editing customer: ' . $e->getMessage());
            return redirect()->route('admin.customers.index')
                ->with('error', 'Không thể chỉnh sửa thông tin khách hàng: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified customer in storage
     */
    public function update(Request $request, $id)
    {
        $customer = User::findOrFail($id);

        $request->validate([
            'MaTK' => 'required|exists:ACCOUNT,MaTK',
            'Hoten' => 'required|string|max:255',
            'SDT' => 'required|string|max:15|regex:/^[0-9]+$/',
            'DiaChi' => 'required|string|max:255',
            'Email' => 'required|email|unique:USER,Email,' . $customer->Manguoidung . ',Manguoidung',
            'Ngaysinh' => 'required|date|before:today',
            'Gioitinh' => 'required|in:Nam,Nữ',
            'membership_level' => 'nullable|string|in:Thường,VIP,Platinum,Diamond',
        ]);

        DB::beginTransaction();
        
        try {
            $customer->update([
                'MaTK' => $request->MaTK,
                'Hoten' => $request->Hoten,
                'SDT' => $request->SDT,
                'DiaChi' => $request->DiaChi,
                'Email' => $request->Email,
                'Ngaysinh' => $request->Ngaysinh,
                'Gioitinh' => $request->Gioitinh,
            ]);
            
            // Update membership if provided
            if ($request->has('membership_level')) {
                $membershipLevel = $request->membership_level;
                $hangTV = $customer->hangThanhVien->first();
                $previousLevel = $hangTV ? $hangTV->Tenhang : null;
                
                // Only update if there's an actual change
                if ($membershipLevel != $previousLevel) {
                    if ($hangTV) {
                        $hangTV->update([
                            'Tenhang' => $membershipLevel,
                            'Mota' => 'Cập nhật hạng thành viên từ ' . ($previousLevel ?? 'không có') . ' lên ' . $membershipLevel,
                        ]);
                    } else {
                        HangThanhVien::create([
                            'Mahang' => 'TH' . $customer->Manguoidung,
                            'Tenhang' => $membershipLevel,
                            'Mota' => 'Hạng thành viên mới cập nhật',
                            'Manguoidung' => $customer->Manguoidung,
                        ]);
                    }
                    
                    // Add points for membership upgrade if it's an upgrade
                    $pointsMap = [
                        'Thường' => 0,
                        'VIP' => 200,
                        'Platinum' => 500,
                        'Diamond' => 1000
                    ];
                    
                    $previousPoints = $pointsMap[$previousLevel ?? 'Thường'] ?? 0;
                    $newPoints = $pointsMap[$membershipLevel] ?? 0;
                    
                    if ($newPoints > $previousPoints) {
                        $bonusPoints = $newPoints - $previousPoints;
                        
                        LSDiemThuong::create([
                            'MaLS' => 'LS' . time(),
                            'Ngay' => Carbon::now(),
                            'Diem' => $bonusPoints,
                            'Loai' => 'Cộng',
                            'Manguoidung' => $customer->Manguoidung,
                            'Ghichu' => 'Nâng cấp hạng thành viên lên ' . $membershipLevel,
                        ]);
                    }
                }
            }
            
            DB::commit();
            
            return redirect()->route('admin.customers.show', $customer->Manguoidung)
                ->with('success', 'Cập nhật khách hàng thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating customer: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Show confirmation form before destroying a customer
     */
    public function confirmDestroy($id)
    {
        try {
            $customer = User::with([
                'hoaDon', 
                'datLich', 
                'danhGia', 
                'phieuHoTro', 
                'hangThanhVien', 
                'lsDiemThuong'
            ])->findOrFail($id);
            
            // Calculate impact of deletion
            $impact = [
                'hoaDonCount' => $customer->hoaDon->count(),
                'datLichCount' => $customer->datLich->count(),
                'danhGiaCount' => $customer->danhGia->count(),
                'phieuHoTroCount' => $customer->phieuHoTro->count(),
                'diemThuongCount' => $customer->lsDiemThuong->count(),
                'totalPoints' => $customer->lsDiemThuong->where('Loai', 'Cộng')->sum('Diem') - 
                                $customer->lsDiemThuong->where('Loai', 'Trừ')->sum('Diem')
            ];
            
            return view('backend.customers.destroy', compact('customer', 'impact'));
        } catch (\Exception $e) {
            Log::error('Error showing delete confirmation: ' . $e->getMessage());
            return redirect()->route('admin.customers.index')
                ->with('error', 'Không thể hiển thị trang xác nhận xóa: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified customer from storage
     */
    public function destroy($id)
    {
        $customer = User::findOrFail($id);
        
        DB::beginTransaction();
        
        try {
            // Option to archive important data before deletion could be added here
            
            // Delete related records to avoid foreign key constraints
            $customer->hangThanhVien()->delete();
            $customer->lsDiemThuong()->delete();
            $customer->phieuHoTro()->delete();
            $customer->danhGia()->delete();
            $customer->datLich()->delete();
            $customer->hoaDon()->delete();
            
            // Finally delete the customer
            $customer->delete();
            
            DB::commit();
            
            return redirect()->route('admin.customers.index')
                ->with('success', 'Xóa khách hàng thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting customer: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra khi xóa khách hàng: ' . $e->getMessage());
        }
    }
    
    /**
     * Add reward points to a customer
     */
    public function addPoints(Request $request, $id)
    {
        $request->validate([
            'points' => 'required|integer|min:1',
            'note' => 'required|string|max:255',
            'point_type' => 'required|in:Cộng,Trừ',
        ]);
        
        try {
            $customer = User::findOrFail($id);
            
            // Generate a unique code for the points transaction
            $pointCode = 'LS' . time() . Str::random(3);
            
            LSDiemThuong::create([
                'MaLS' => $pointCode,
                'Ngay' => Carbon::now(),
                'Diem' => $request->points,
                'Loai' => $request->point_type,
                'Manguoidung' => $customer->Manguoidung,
                'Ghichu' => $request->note,
            ]);
            
            // Check if customer should be upgraded based on total points
            $this->checkForMembershipUpgrade($customer);
            
            $message = $request->point_type == 'Cộng' 
                ? 'Thêm ' . $request->points . ' điểm thưởng thành công!'
                : 'Trừ ' . $request->points . ' điểm thưởng thành công!';
                
            return redirect()->route('admin.customers.show', $id)
                ->with('success', $message);
        } catch (\Exception $e) {
            Log::error('Error adding points: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra khi cập nhật điểm: ' . $e->getMessage());
        }
    }
    
    /**
     * Export customers to CSV/Excel
     */
    public function exportCustomers(Request $request)
    {
        // Implement export functionality
        // This is a placeholder
        try {
            // This would be implemented with a package like maatwebsite/excel
            // For now, return a message
            return back()->with('info', 'Chức năng xuất dữ liệu đang được phát triển');
        } catch (\Exception $e) {
            Log::error('Error exporting customers: ' . $e->getMessage());
            return back()->with('error', 'Lỗi khi xuất dữ liệu: ' . $e->getMessage());
        }
    }
    
    /**
     * Customer statistics dashboard
     */
    public function dashboard()
    {
        try {
            // Basic statistics
            $stats = [
                'totalCustomers' => User::count(),
                'newCustomersThisMonth' => User::whereMonth('created_at', Carbon::now()->month)->count(),
                'maleCustomers' => User::where('Gioitinh', 'Nam')->count(),
                'femaleCustomers' => User::where('Gioitinh', 'Nữ')->count(),
            ];
            
            // Calculate percentage changes
            $lastMonth = Carbon::now()->subMonth();
            $lastMonthNewCustomers = User::whereMonth('created_at', $lastMonth->month)->count();
            $stats['customerGrowth'] = $lastMonthNewCustomers > 0 
                ? (($stats['newCustomersThisMonth'] - $lastMonthNewCustomers) / $lastMonthNewCustomers) * 100 
                : 100;
            
            // Top customers by spending
            $topCustomers = User::with(['hangThanhVien'])
                ->withCount(['hoaDon as total_spent' => function($query) {
                    $query->select(DB::raw('SUM(Tongtien)'));
                }])
                ->orderBy('total_spent', 'desc')
                ->take(10)
                ->get();
            
            // Get membership distribution
            $membershipDistribution = HangThanhVien::select('Tenhang', DB::raw('count(*) as count'))
                ->groupBy('Tenhang')
                ->get()
                ->mapWithKeys(function ($item) {
                    return [$item['Tenhang'] => $item['count']];
                })
                ->toArray();
            
            return view('backend.customers.dashboard', compact(
                'stats',
                'topCustomers',
                'membershipDistribution'
            ));
        } catch (\Exception $e) {
            Log::error('Error loading customer dashboard: ' . $e->getMessage());
            return redirect()->route('admin.customers.index')
                ->with('error', 'Lỗi khi tải bảng thống kê: ' . $e->getMessage());
        }
    }
    
    /**
     * Get services that might interest a specific customer
     */
    public function getRecommendedServices($id)
    {
        try {
            $customer = User::with(['datLich', 'hoaDon', 'danhGia'])->findOrFail($id);
            
            // Get services the customer has used before
            $usedServiceIds = $customer->datLich->pluck('MaDV')->toArray();
            
            // Get highly rated services by this customer
            $highlyRatedServiceIds = $customer->danhGia->where('Sosao', '>=', 4)->pluck('MaDV')->toArray();
            
            // Find similar services that might be interesting
            $recommendedServices = DichVu::where(function($query) use ($usedServiceIds, $highlyRatedServiceIds) {
                    // Services in the same category but not used before
                    $query->whereIn('Loaidichvu', function($subquery) use ($usedServiceIds) {
                        $subquery->select('Loaidichvu')
                            ->from('DICHVU')
                            ->whereIn('MaDV', $usedServiceIds);
                    })
                    ->whereNotIn('MaDV', $usedServiceIds);
                })
                ->orWhereIn('MaDV', $highlyRatedServiceIds) // Include services they've rated highly
                ->take(5)
                ->get();
                
            return $recommendedServices;
        } catch (\Exception $e) {
            Log::error('Error getting recommended services: ' . $e->getMessage());
            return collect(); // Return empty collection on error
        }
    }
    
    /**
     * Calculate comprehensive statistics for a customer
     */
    private function calculateCustomerStats($customer)
    {
        // Basic stats
        $totalOrders = $customer->hoaDon->count();
        $totalAppointments = $customer->datLich->count();
        $totalReviews = $customer->danhGia->count();
        
        // Calculate total spending
        $totalSpent = $customer->hoaDon->sum('Tongtien');
        
        // Calculate average order value
        $averageOrderValue = $totalOrders > 0 ? $totalSpent / $totalOrders : 0;
        
        // Calculate total reward points
        $pointsEarned = $customer->lsDiemThuong->where('Loai', 'Cộng')->sum('Diem');
        $pointsSpent = $customer->lsDiemThuong->where('Loai', 'Trừ')->sum('Diem');
        $currentPoints = $pointsEarned - $pointsSpent;
        
        // Calculate average rating given
        $averageRating = $totalReviews > 0 ? $customer->danhGia->avg('Sosao') : 0;
        
        // Calculate customer lifetime value (simple calculation)
        $customerLifetime = Carbon::parse($customer->created_at)->diffInMonths(Carbon::now()) + 1;
        $customerLifetimeValue = $customerLifetime > 0 ? $totalSpent / $customerLifetime : $totalSpent;
        
        // Get pending appointments
        $pendingAppointments = $customer->datLich->whereIn('Trangthai_', ['Đã đặt', 'Chờ xác nhận', 'Đang chờ']);
        
        return [
            'totalOrders' => $totalOrders,
            'totalAppointments' => $totalAppointments,
            'totalReviews' => $totalReviews,
            'totalSpent' => $totalSpent,
            'averageOrderValue' => $averageOrderValue,
            'pointsEarned' => $pointsEarned,
            'pointsSpent' => $pointsSpent,
            'currentPoints' => $currentPoints,
            'averageRating' => $averageRating,
            'customerLifetimeValue' => $customerLifetimeValue,
            'pendingAppointments' => $pendingAppointments
        ];
    }
    
    /**
     * Check if customer should be upgraded based on points or spending
     */
    private function checkForMembershipUpgrade($customer)
    {
        // Calculate total points
        $pointsEarned = $customer->lsDiemThuong->where('Loai', 'Cộng')->sum('Diem');
        $pointsSpent = $customer->lsDiemThuong->where('Loai', 'Trừ')->sum('Diem');
        $currentPoints = $pointsEarned - $pointsSpent;
        
        // Calculate total spending
        $totalSpent = $customer->hoaDon->sum('Tongtien');
        
        // Get current membership level
        $currentMembership = $customer->hangThanhVien->first();
        
        if (!$currentMembership) return; // No membership to upgrade
        
        $currentLevel = $currentMembership->Tenhang;
        $newLevel = $currentLevel;
        
        // Define upgrade thresholds (points or spending)
        if ($currentPoints >= 5000 || $totalSpent >= 20000000) {
            $newLevel = 'Diamond';
        } elseif ($currentPoints >= 2000 || $totalSpent >= 10000000) {
            $newLevel = 'Platinum';
        } elseif ($currentPoints >= 1000 || $totalSpent >= 5000000) {
            $newLevel = 'VIP';
        }
        
        // Perform upgrade if needed
        if ($newLevel != $currentLevel) {
            // Determine rank order
            $rankOrder = [
                'Thường' => 1,
                'VIP' => 2,
                'Platinum' => 3,
                'Diamond' => 4
            ];
            
            // Only upgrade, never downgrade
            if ($rankOrder[$newLevel] > $rankOrder[$currentLevel]) {
                $currentMembership->update([
                    'Tenhang' => $newLevel,
                    'Mota' => 'Tự động nâng cấp từ ' . $currentLevel . ' lên ' . $newLevel,
                ]);
                
                // Could notify user or admin about the upgrade here
            }
        }
    }
}