<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\DatLich;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LinkGuestBookingsController extends Controller
{
    /**
     * Hiển thị danh sách các lịch đặt của khách vãng lai có cùng số điện thoại
     */
    public function index()
    {
        // Lấy thông tin người dùng hiện tại
        $user = User::where('MaTK', Auth::id())->first();
        
        if (!$user) {
            return redirect()->route('customer.home')
                ->with('error', 'Không tìm thấy thông tin người dùng.');
        }
        
        // Lấy các lịch đặt của khách vãng lai có cùng số điện thoại
        $guestBookings = DatLich::whereNull('Manguoidung')
            ->where('SDT_khach', $user->SDT)
            ->with('dichVu') // Eager load dịch vụ để hiển thị thông tin
            ->get();
        
        // Nếu không tìm thấy lịch đặt nào, chuyển hướng về trang chủ
        if ($guestBookings->isEmpty()) {
            return redirect()->route('customer.home')
                ->with('info', 'Không tìm thấy lịch đặt nào của bạn trước đây.');
        }
        
        return view('customer.datlich.link-guest-bookings', [
            'guestBookings' => $guestBookings,
            'user' => $user
        ]);
    }
    
    /**
     * Xử lý việc liên kết các lịch đặt được chọn với tài khoản người dùng
     */
    public function store(Request $request)
    {
        // Validate dữ liệu đầu vào
        $request->validate([
            'booking_ids' => 'required|array',
            'booking_ids.*' => 'exists:DATLICH,MaDL'
        ], [
            'booking_ids.required' => 'Vui lòng chọn ít nhất một lịch đặt để liên kết.',
            'booking_ids.*.exists' => 'Lịch đặt không tồn tại.'
        ]);
        
        // Lấy thông tin người dùng hiện tại
        $user = User::where('MaTK', Auth::id())->first();
        
        if (!$user) {
            return redirect()->back()
                ->with('error', 'Không tìm thấy thông tin người dùng.');
        }
        
        try {
            DB::beginTransaction();
            
            // Cập nhật Manguoidung cho các lịch đặt được chọn
            $updatedCount = DatLich::whereIn('MaDL', $request->booking_ids)
                ->whereNull('Manguoidung')
                ->update(['Manguoidung' => $user->Manguoidung]);
            
            DB::commit();
            
            return redirect()->route('customer.lichsudatlich.index')
                ->with('success', "Đã liên kết thành công {$updatedCount} lịch đặt vào tài khoản của bạn.");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Đã xảy ra lỗi khi liên kết lịch đặt: ' . $e->getMessage());
        }
    }
    
    /**
     * Bỏ qua việc liên kết lịch đặt
     */
    public function skip()
    {
        // Xóa thông tin session về việc tìm thấy lịch đặt cũ
        session()->forget(['found_guest_bookings', 'guest_bookings_count']);
        
        return redirect()->route('customer.home')
            ->with('info', 'Bạn đã bỏ qua việc liên kết các lịch đặt cũ.');
    }
} 