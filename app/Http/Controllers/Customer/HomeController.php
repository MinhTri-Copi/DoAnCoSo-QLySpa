<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\DichVu;
use App\Models\DatLich;
use App\Models\DanhGia;
use App\Models\HangThanhVien;
use App\Http\Controllers\Customer\QuangCaoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Display the customer dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get authenticated user
        $user = Auth::user();
        
        // Get featured services
        $featuredServices = DichVu::where('featured', true)
            ->orderBy('MaDV', 'desc')
            ->limit(4)
            ->get();
            
        // Get upcoming bookings for the user
        $upcomingBookings = DatLich::where('Manguoidung', $user->id)
            ->where('Thoigiandatlich', '>=', Carbon::now())
            ->where('Trangthai_', '!=', 4) // Not cancelled
            ->with('dichVu')
            ->orderBy('Thoigiandatlich', 'asc')
            ->limit(3)
            ->get();
            
        // Get user's membership information
        $membershipRank = null;
        if (isset($user->Diemtichluy)) {
            $membershipRank = HangThanhVien::where('Diemtoithieu', '<=', $user->Diemtichluy)
                ->orderBy('Diemtoithieu', 'desc')
                ->first();
        }
        
        // Simply get featured services instead of attempting complex ratings
        $topRatedServices = DichVu::where('featured', true)
            ->orderBy('MaDV', 'desc')
            ->limit(3)
            ->get();
            
        // Get latest reviews - without relationships to avoid errors
        $latestReviews = DanhGia::orderBy('Ngaydanhgia', 'desc')
            ->limit(3)
            ->get();
            
        // Calculate user points needed for next rank
        $nextRank = null;
        $pointsNeeded = 0;
        
        if ($membershipRank) {
            $nextRank = HangThanhVien::where('Diemtoithieu', '>', $membershipRank->Diemtoithieu)
                ->orderBy('Diemtoithieu', 'asc')
                ->first();
                
            if ($nextRank) {
                $pointsNeeded = $nextRank->Diemtoithieu - $user->Diemtichluy;
            }
        }
        
        // Get advertisement data from QuangCaoController
        try {
            $quangCaoController = new QuangCaoController();
            $featuredAds = $quangCaoController->getFeaturedAds(3);
            $promotionAds = $quangCaoController->getPromotionAds(2);
            $eventAds = $quangCaoController->getEventAds(1);
        } catch (\Exception $e) {
            // If advertisements can't be loaded, use empty collections
            $featuredAds = collect();
            $promotionAds = collect();
            $eventAds = collect();
        }

        return view('customer.home', compact(
            'user',
            'featuredServices',
            'upcomingBookings',
            'membershipRank',
            'topRatedServices',
            'latestReviews',
            'nextRank',
            'pointsNeeded',
            'featuredAds',
            'promotionAds',
            'eventAds'
        ));
    }
    
    /**
     * Display the customer's profile settings page.
     *
     * @return \Illuminate\Http\Response
     */
    public function profile()
    {
        $user = Auth::user();
        
        return view('customer.profile', compact('user'));
    }
    
    /**
     * Update the customer's profile information.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'Hoten' => 'required|string|max:255',
            'Email' => 'required|string|email|max:255|unique:users,Email,'.$user->id.',id',
            'Sodienthoai' => 'required|string|max:15',
            'Diachi' => 'nullable|string|max:255',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        $user->Hoten = $request->Hoten;
        $user->Email = $request->Email;
        $user->Sodienthoai = $request->Sodienthoai;
        $user->Diachi = $request->Diachi;
        
        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            $avatar = $request->file('avatar');
            $filename = 'avatar-' . time() . '-' . $user->id . '.' . $avatar->getClientOriginalExtension();
            $avatar->move(public_path('images/avatars'), $filename);
            $user->avatar = 'images/avatars/' . $filename;
        }
        
        $user->save();
        
        return redirect()->route('customer.profile')
            ->with('success', 'Thông tin cá nhân đã được cập nhật thành công.');
    }
    
    /**
     * Update the customer's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|current_password',
            'password' => 'required|string|min:8|confirmed',
        ]);
        
        $user = Auth::user();
        $user->password = bcrypt($request->password);
        $user->save();
        
        return redirect()->route('customer.profile')
            ->with('success', 'Mật khẩu đã được cập nhật thành công.');
    }
} 