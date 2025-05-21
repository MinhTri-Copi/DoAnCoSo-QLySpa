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
        $user = Auth::user();
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
        $user = Auth::user();
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
        $timeLeftData = [];
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
        $statusHistory = DB::table('LICHSU_TRANGTHAI')
            ->where('MaDL', $id)
            ->orderBy('ThoigianCapNhat', 'desc')
            ->get();
            
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
        $user = Auth::user();
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
        
        // Log status change if you have a status history table
        if (class_exists('App\Models\LichSuTrangThai')) {
            DB::table('LICHSU_TRANGTHAI')->insert([
                'MaDL' => $id,
                'TrangthaiCu' => $booking->getOriginal('Trangthai_'),
                'TrangthaiMoi' => 'Đã hủy',
                'ThoigianCapNhat' => now(),
                'NguoiCapNhat' => $user->Manguoidung,
                'GhiChu' => 'Khách hàng tự hủy lịch'
            ]);
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
        $request->validate([
            'new_date' => 'required|date|after:today',
            'new_time' => 'required',
            'reason' => 'required|string|max:255'
        ]);
        
        $user = Auth::user();
        $booking = DatLich::where('Manguoidung', $user->Manguoidung)
            ->where('MaDL', $id)
            ->firstOrFail();
            
        // Check if booking can be rescheduled
        if (!in_array($booking->Trangthai_, ['Chờ xác nhận', 'Đã xác nhận'])) {
            return redirect()->route('customer.lichsudatlich.show', $id)
                ->with('error', 'Lịch đặt này không thể đổi lịch (đã hoàn thành, đang thực hiện hoặc đã hủy).');
        }
        
        // Check if the new time is available
        $newDateTime = Carbon::parse($request->new_date . ' ' . $request->new_time);
        
        // Get service information
        $dichVu = $booking->dichVu;
        
        // Check if service is available on selected day
        $dayOfWeek = $newDateTime->format('l');
        if (!$dichVu->isAvailableOn($dayOfWeek)) {
            return back()->withInput()->withErrors([
                'new_date' => 'Dịch vụ này không hoạt động vào ' . $dayOfWeek
            ]);
        }
        
        // Check for overlapping bookings
        $serviceTime = $dichVu->Thoigian;
        $endTime = (clone $newDateTime)->addMinutes($serviceTime);
        
        $overlappingBookings = DatLich::where('MaDV', $booking->MaDV)
            ->where('MaDL', '!=', $id)
            ->where('Trangthai_', '!=', 'Đã hủy')
            ->where(function($query) use ($newDateTime, $endTime) {
                $query->whereBetween('Thoigiandatlich', [$newDateTime, $endTime])
                    ->orWhere(function($q) use ($newDateTime, $endTime) {
                        $q->where('Thoigiandatlich', '<=', $newDateTime)
                          ->whereRaw("DATE_ADD(Thoigiandatlich, INTERVAL (SELECT Thoigian FROM DICHVU WHERE MaDV = DATLICH.MaDV) MINUTE) >= ?", [$newDateTime]);
                    });
            })
            ->count();
            
        $maxConcurrentBookings = 2;
        if ($overlappingBookings >= $maxConcurrentBookings) {
            return back()->withInput()->withErrors([
                'new_time' => 'Khung giờ này đã đủ lịch đặt. Vui lòng chọn khung giờ khác.'
            ]);
        }
        
        // Store old booking time for notification
        $oldDateTime = $booking->Thoigiandatlich;
        
        // Update booking time
        $booking->Thoigiandatlich = $newDateTime;
        $booking->save();
        
        // Log reschedule request
        if (class_exists('App\Models\LichSuTrangThai')) {
            DB::table('LICHSU_TRANGTHAI')->insert([
                'MaDL' => $id,
                'TrangthaiCu' => $booking->Trangthai_,
                'TrangthaiMoi' => $booking->Trangthai_,
                'ThoigianCapNhat' => now(),
                'NguoiCapNhat' => $user->Manguoidung,
                'GhiChu' => 'Đổi lịch từ ' . $oldDateTime . ' sang ' . $newDateTime . '. Lý do: ' . $request->reason
            ]);
        }
        
        return redirect()->route('customer.lichsudatlich.show', $id)
            ->with('success', 'Yêu cầu đổi lịch đã được gửi thành công.');
    }
}
