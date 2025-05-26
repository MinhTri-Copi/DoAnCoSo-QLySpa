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
     * Hiển thị trang xác nhận liên kết lịch đặt khách vãng lai
     */
    public function index()
    {
        // Kiểm tra nếu người dùng đã đăng nhập
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Lấy thông tin user đã đăng nhập
        $account = Auth::user();
        $user = User::where('MaTK', $account->MaTK)->first();

        if (!$user) {
            return redirect()->route('customer.home')
                ->with('error', 'Không tìm thấy thông tin người dùng.');
        }

        // Lấy danh sách đặt lịch khách vãng lai có số điện thoại trùng với người dùng
        $guestBookings = DatLich::whereNull('Manguoidung')
            ->where('SDT_khach', $user->SDT)
            ->with('dichVu')
            ->get();

        if ($guestBookings->isEmpty()) {
            return redirect()->route('customer.home')
                ->with('info', 'Không tìm thấy lịch đặt nào trùng với số điện thoại của bạn.');
        }

        return view('customer.link-guest-bookings', compact('guestBookings', 'user'));
    }

    /**
     * Liên kết các đặt lịch đã chọn vào tài khoản người dùng
     */
    public function link(Request $request)
    {
        // Kiểm tra nếu người dùng đã đăng nhập
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Validate input
        $request->validate([
            'booking_ids' => 'required|array',
            'booking_ids.*' => 'exists:DATLICH,MaDL',
        ], [
            'booking_ids.required' => 'Vui lòng chọn ít nhất một lịch đặt.',
            'booking_ids.array' => 'Dữ liệu không hợp lệ.',
            'booking_ids.*.exists' => 'Lịch đặt không tồn tại.'
        ]);

        // Lấy thông tin user đã đăng nhập
        $account = Auth::user();
        $user = User::where('MaTK', $account->MaTK)->first();

        if (!$user) {
            return redirect()->route('customer.home')
                ->with('error', 'Không tìm thấy thông tin người dùng.');
        }

        try {
            DB::beginTransaction();

            // Cập nhật các lịch đặt đã chọn
            $updatedCount = DatLich::whereIn('MaDL', $request->booking_ids)
                ->whereNull('Manguoidung')
                ->where('SDT_khach', $user->SDT)
                ->update([
                    'Manguoidung' => $user->Manguoidung,
                    'updated_at' => now()
                ]);

            DB::commit();

            return redirect()->route('customer.lichsudatlich.index')
                ->with('success', "Đã liên kết $updatedCount lịch đặt vào tài khoản của bạn.");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Đã xảy ra lỗi: ' . $e->getMessage());
        }
    }
} 