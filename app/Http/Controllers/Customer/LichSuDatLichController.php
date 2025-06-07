<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\DatLich;
use App\Models\HoaDonVaThanhToan;
use App\Models\DichVu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LichSuDatLichController extends Controller
{
    /**
     * Display a listing of the customer's booking history.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Get authenticated account
        $account = Auth::user();
        
        // Get the User record associated with this Account
        $user = \App\Models\User::where('MaTK', $account->MaTK)->first();
        
        if (!$user) {
            return redirect()->back()->with('error', 'Không tìm thấy thông tin người dùng. Vui lòng đăng nhập lại.');
        }
        
        $query = DatLich::where('Manguoidung', $user->Manguoidung)
            ->with(['dichVu', 'hoaDon']);
            
        // Filter by status if provided
        if ($request->has('status') && $request->status != '') {
            $query->where('Trangthai_', $request->status);
        }
        
        // Filter by date range if provided
        if ($request->has('start_date') && $request->start_date != '') {
            $query->whereDate('Thoigiandatlich', '>=', $request->start_date);
        }
        
        if ($request->has('end_date') && $request->end_date != '') {
            $query->whereDate('Thoigiandatlich', '<=', $request->end_date);
        }
        
        // Filter by service if provided
        if ($request->has('service') && $request->service != '') {
            $query->where('MaDV', $request->service);
        }
        
        // Sort bookings
        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'date_asc':
                    $query->orderBy('Thoigiandatlich', 'asc');
                    break;
                case 'date_desc':
                    $query->orderBy('Thoigiandatlich', 'desc');
                    break;
                default:
                    $query->orderBy('Thoigiandatlich', 'desc');
                    break;
            }
        } else {
            // Default sort by booking date (newest first)
            $query->orderBy('Thoigiandatlich', 'desc');
        }
        
        $bookings = $query->paginate(10);
        
        // Get available statuses for filter
        $statuses = [
            'Chờ xác nhận' => 'Chờ xác nhận',
            'Đã xác nhận' => 'Đã xác nhận',
            'Đang thực hiện' => 'Đang thực hiện',
            'Đã hủy' => 'Đã hủy',
            'Hoàn thành' => 'Hoàn thành'
        ];
        
        // Get all services for filter
        $services = DichVu::pluck('Tendichvu', 'MaDV');
        
        return view('customer.lichsudatlich.index', compact('bookings', 'statuses', 'services'));
    }
    
    /**
     * Display the specified booking details.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Get authenticated account
        $account = Auth::user();
        
        // Get the User record associated with this Account
        $user = \App\Models\User::where('MaTK', $account->MaTK)->first();
        
        if (!$user) {
            return redirect()->back()->with('error', 'Không tìm thấy thông tin người dùng. Vui lòng đăng nhập lại.');
        }
        
        $booking = DatLich::with(['dichVu', 'hoaDon.phuongThuc', 'user'])
            ->where('Manguoidung', $user->Manguoidung)
            ->where('MaDL', $id)
            ->firstOrFail();
            
        // Check if the booking has a review
        $hasReview = false;
        if ($booking->hoaDon) {
            foreach ($booking->hoaDon as $hoaDon) {
                if ($hoaDon->danhGia()->exists()) {
                    $hasReview = true;
                    break;
                }
            }
        }
        
        // Calculate time left until booking
        $timeLeftData = [
            'days' => 0,
            'hours' => 0,
            'minutes' => 0
        ];
        
        if ($booking->Thoigiandatlich) {
            $now = Carbon::now();
            $bookingTime = Carbon::parse($booking->Thoigiandatlich);
            
            if ($bookingTime > $now) {
                $diff = $now->diff($bookingTime);
                $timeLeftData = [
                    'days' => $diff->days,
                    'hours' => $diff->h,
                    'minutes' => $diff->i
                ];
            }
        }
        
        // Generate QR code data for booking
        $qrCodeData = route('customer.lichsudatlich.show', $id);
        
        // Get booking status history (if available)
        $statusHistory = collect([]);
        
        // Check if the table exists before querying it
        try {
            // First check if we can get a single row to verify table exists
            if (DB::select("SHOW TABLES LIKE 'LICHSU_TRANGTHAI'")) {
                $statusHistory = DB::table('LICHSU_TRANGTHAI')
                    ->where('MaDL', $id)
                    ->orderBy('ThoigianCapNhat', 'desc')
                    ->get();
            }
        } catch (\Exception $e) {
            // Table doesn't exist or other DB error, just continue without status history
        }
        
        return view('customer.lichsudatlich.show', compact(
            'booking', 
            'hasReview', 
            'timeLeftData', 
            'qrCodeData',
            'statusHistory'
        ));
    }
    
    /**
     * Cancel the specified booking.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function cancel($id)
    {
        // Get authenticated account
        $account = Auth::user();
        
        // Get the User record associated with this Account
        $user = \App\Models\User::where('MaTK', $account->MaTK)->first();
        
        if (!$user) {
            return redirect()->back()->with('error', 'Không tìm thấy thông tin người dùng. Vui lòng đăng nhập lại.');
        }
        
        $booking = DatLich::where('Manguoidung', $user->Manguoidung)
            ->where('MaDL', $id)
            ->firstOrFail();
            
        // Check if booking can be cancelled
        if (in_array($booking->Trangthai_, ['Đã hủy', 'Hoàn thành'])) {
            return redirect()->route('customer.lichsudatlich.show', $id)
                ->with('error', 'Lịch đặt này không thể hủy (đã hoàn thành hoặc đã hủy trước đó).');
        }
        
        // Check if booking is within 24 hours
        $bookingTime = Carbon::parse($booking->Thoigiandatlich);
        $now = Carbon::now();
        
        if ($bookingTime->diffInHours($now) < 24) {
            return redirect()->route('customer.lichsudatlich.show', $id)
                ->with('error', 'Không thể hủy lịch đặt trong vòng 24 giờ trước thời gian đặt.');
        }
        
        // Cancel the booking
        $booking->Trangthai_ = 'Đã hủy';
        $booking->save();
        
        // Log status change if the table exists
        try {
            if (DB::select("SHOW TABLES LIKE 'LICHSU_TRANGTHAI'")) {
                DB::table('LICHSU_TRANGTHAI')->insert([
                    'MaDL' => $id,
                    'TrangthaiCu' => $booking->getOriginal('Trangthai_'),
                    'TrangthaiMoi' => 'Đã hủy',
                    'ThoigianCapNhat' => now(),
                    'NguoiCapNhat' => $user->Manguoidung,
                    'GhiChu' => 'Khách hàng tự hủy lịch'
                ]);
            }
        } catch (\Exception $e) {
            // Table doesn't exist or other DB error, just continue without logging
        }
        
        return redirect()->route('customer.lichsudatlich.index')
            ->with('success', 'Lịch đặt đã được hủy thành công.');
    }
    
    /**
     * Request rescheduling for the specified booking.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function reschedule(Request $request, $id)
    {
        // Validate input
        $validator = \Validator::make($request->all(), [
            'new_date' => 'required|date|after:today',
            'new_time' => 'required|string',
            'reason' => 'required|string|max:255'
        ], [
            'new_date.required' => 'Vui lòng chọn ngày mới.',
            'new_date.date' => 'Ngày không hợp lệ.',
            'new_date.after' => 'Ngày mới phải sau ngày hôm nay.',
            'new_time.required' => 'Vui lòng chọn giờ mới.',
            'reason.required' => 'Vui lòng nhập lý do đổi lịch.',
            'reason.max' => 'Lý do không được vượt quá 255 ký tự.'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        // Get authenticated account
        $account = Auth::user();
        
        // Get the User record associated with this Account
        $user = \App\Models\User::where('MaTK', $account->MaTK)->first();
        
        if (!$user) {
            return redirect()->back()->with('error', 'Không tìm thấy thông tin người dùng. Vui lòng đăng nhập lại.');
        }
        
        $booking = DatLich::where('Manguoidung', $user->Manguoidung)
            ->where('MaDL', $id)
            ->firstOrFail();
            
        // Check if booking can be rescheduled (only allow rescheduling if status is "Chờ xác nhận")
        if ($booking->Trangthai_ !== 'Chờ xác nhận') {
            return redirect()->route('customer.lichsudatlich.show', $id)
                ->with('error', 'Chỉ có thể đổi lịch khi trạng thái là "Chờ xác nhận".');
        }
        
        // Check if the booking time has passed
        $now = Carbon::now();
        $bookingTime = Carbon::parse($booking->Thoigiandatlich);
        
        if ($bookingTime->isPast()) {
            return redirect()->route('customer.lichsudatlich.show', $id)
                ->with('error', 'Không thể đổi lịch đã qua thời gian đặt.');
        }
        
        // Format the new date time from inputs
        try {
            $newDateTime = Carbon::createFromFormat('Y-m-d H:i', $request->new_date . ' ' . $request->new_time);
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Định dạng thời gian không hợp lệ. Vui lòng thử lại.');
        }
        
        // Get service information
        $dichVu = $booking->dichVu;
        if (!$dichVu) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Không tìm thấy thông tin dịch vụ.');
        }
        
        // Check if the service is available on the selected day
        $dayOfWeek = strtolower($newDateTime->format('l')); // Get day of week in lowercase
        $availableDays = $dichVu->available_days ? json_decode($dichVu->available_days) : [];
        
        if (!empty($availableDays) && !in_array($dayOfWeek, $availableDays)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Dịch vụ này không có sẵn vào ngày ' . $newDateTime->format('l') . '.');
        }
        
        // Kiểm tra giờ đặt lịch có phù hợp với giờ làm việc của spa không
        $bookingHour = (int)$newDateTime->format('H');
        if ($bookingHour < 8 || $bookingHour >= 18) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Giờ đặt lịch phải nằm trong khoảng từ 8:00 đến 18:00.');
        }
        
        // Check if the date is not too far in the future (max 30 days)
        $maxFutureDate = Carbon::now()->addDays(30);
        if ($newDateTime > $maxFutureDate) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Ngày đặt lịch không được quá 30 ngày từ hôm nay.');
        }
        
        // Check if the total bookings for the day does not exceed 30
        $bookingsCountInDay = DatLich::whereDate('Thoigiandatlich', $newDateTime->format('Y-m-d'))
            ->where('MaDL', '!=', $id)
            ->where('Trangthai_', '!=', 'Đã hủy')
            ->count();
            
        if ($bookingsCountInDay >= 30) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Đã đạt giới hạn 30 lịch đặt trong ngày này. Vui lòng chọn ngày khác.');
        }
        
        // Calculate service time and end time
        $serviceTime = $dichVu->Thoigian ?? 60; // Default to 60 minutes if not specified
        $endTime = (clone $newDateTime)->addMinutes($serviceTime);
        
        // Check for overlapping bookings (max 2 concurrent bookings for the same service)
        $overlappingBookings = DatLich::where('MaDV', $booking->MaDV)
            ->where('MaDL', '!=', $id)
            ->where('Trangthai_', '!=', 'Đã hủy')
            ->where(function($query) use ($newDateTime, $endTime) {
                // Booking starts during our service time
                $query->whereBetween('Thoigiandatlich', [$newDateTime, $endTime])
                    // Or booking ends during our service time
                    ->orWhere(function($q) use ($newDateTime, $endTime) {
                        $q->where('Thoigiandatlich', '<=', $newDateTime)
                          ->whereRaw("DATE_ADD(Thoigiandatlich, INTERVAL (SELECT COALESCE(Thoigian, 60) FROM DICHVU WHERE MaDV = DATLICH.MaDV) MINUTE) >= ?", [$newDateTime]);
                    });
            })
            ->count();
            
        $maxConcurrentBookings = 2;
        if ($overlappingBookings >= $maxConcurrentBookings) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Đã có đủ ' . $maxConcurrentBookings . ' lịch đặt dịch vụ "' . $dichVu->Tendichvu . '" vào khung giờ ' . $newDateTime->format('H:i') . ' ngày ' . $newDateTime->format('d/m/Y') . '. Vui lòng chọn khung giờ khác.');
        }
        
        // Store old booking time for notification
        $oldDateTime = $booking->Thoigiandatlich;
        
        try {
            // Begin transaction
            DB::beginTransaction();
            
            // Update booking time
            $booking->Thoigiandatlich = $newDateTime;
            $booking->save();
            
            // Log reschedule request if the table exists
            try {
                if (DB::select("SHOW TABLES LIKE 'LICHSU_TRANGTHAI'")) {
                    DB::table('LICHSU_TRANGTHAI')->insert([
                        'MaDL' => $id,
                        'TrangthaiCu' => $booking->Trangthai_,
                        'TrangthaiMoi' => $booking->Trangthai_,
                        'ThoigianCapNhat' => now(),
                        'NguoiCapNhat' => $user->Manguoidung,
                        'GhiChu' => 'Đổi lịch từ ' . Carbon::parse($oldDateTime)->format('d/m/Y H:i') . ' sang ' . $newDateTime->format('d/m/Y H:i') . '. Lý do: ' . $request->reason
                    ]);
                }
            } catch (\Exception $e) {
                // Table doesn't exist or other DB error, just continue without logging
                \Log::error('Error logging booking status change: ' . $e->getMessage());
            }
            
            // Commit transaction
            DB::commit();
            
            return redirect()->route('customer.lichsudatlich.show', $id)
                ->with('success', 'Đổi lịch thành công. Vui lòng đợi xác nhận từ phía spa.');
                
        } catch (\Exception $e) {
            // Rollback transaction
            DB::rollBack();
            \Log::error('Error rescheduling booking: ' . $e->getMessage());
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Đã xảy ra lỗi khi đổi lịch: ' . $e->getMessage());
        }
    }
}
