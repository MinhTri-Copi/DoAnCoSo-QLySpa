<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\DatLich;
use App\Models\HoaDonVaThanhToan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

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
        $query = DatLich::where('Manguoidung', $user->id)
            ->with(['dichVu', 'trangThai', 'hoaDon']);
            
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
            '1' => 'Chờ xác nhận',
            '2' => 'Đã xác nhận',
            '3' => 'Đang thực hiện',
            '4' => 'Đã hủy',
            '5' => 'Hoàn thành'
        ];
        
        return view('customer.lichsudatlich.index', compact('bookings', 'statuses'));
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
        $booking = DatLich::with(['dichVu', 'trangThai', 'hoaDon.phuongThuc'])
            ->where('Manguoidung', $user->id)
            ->where('MaDL', $id)
            ->firstOrFail();
            
        // Check if the booking has a review
        $hasReview = false;
        if ($booking->hoaDon) {
            $hasReview = $booking->hoaDon->danhGia()->exists();
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
        
        return view('customer.lichsudatlich.show', compact('booking', 'hasReview', 'timeLeftData'));
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
        $booking = DatLich::where('Manguoidung', $user->id)
            ->where('MaDL', $id)
            ->firstOrFail();
            
        // Check if booking can be cancelled
        if (in_array($booking->Trangthai_, [4, 5])) {
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
        $booking->Trangthai_ = 4; // Cancelled status
        $booking->save();
        
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
        $booking = DatLich::where('Manguoidung', $user->id)
            ->where('MaDL', $id)
            ->firstOrFail();
            
        // Check if booking can be rescheduled
        if (!in_array($booking->Trangthai_, [1, 2])) {
            return redirect()->route('customer.lichsudatlich.show', $id)
                ->with('error', 'Lịch đặt này không thể đổi lịch (đã hoàn thành, đang thực hiện hoặc đã hủy).');
        }
        
        // Check if the new time is available
        $newDateTime = $request->new_date . ' ' . $request->new_time;
        $existingBooking = DatLich::where('MaDV', $booking->MaDV)
            ->where('Thoigiandatlich', $newDateTime)
            ->where('MaDL', '!=', $id)
            ->where('Trangthai_', '!=', 4) // Not cancelled
            ->exists();
            
        if ($existingBooking) {
            return back()->withInput()->withErrors([
                'new_time' => 'Khung giờ này đã được đặt. Vui lòng chọn khung giờ khác.'
            ]);
        }
        
        // Store old booking time for notification
        $oldDateTime = $booking->Thoigiandatlich;
        
        // Update booking time
        $booking->Thoigiandatlich = $newDateTime;
        $booking->save();
        
        // Could send notification to admin about reschedule here
        
        return redirect()->route('customer.lichsudatlich.show', $id)
            ->with('success', 'Yêu cầu đổi lịch đã được gửi thành công.');
    }
} 