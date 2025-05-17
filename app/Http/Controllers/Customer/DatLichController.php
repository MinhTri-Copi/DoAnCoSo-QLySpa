<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\DatLich;
use App\Models\DichVu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DatLichController extends Controller
{
    /**
     * Show the form for creating a new booking.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        // Pre-select service if provided in the request
        $selectedServiceId = $request->service_id;
        $selectedService = null;
        
        if ($selectedServiceId) {
            $selectedService = DichVu::find($selectedServiceId);
            
            // If service doesn't exist, redirect back with error
            if (!$selectedService) {
                return redirect()->route('customer.dichvu.index')
                    ->with('error', 'Dịch vụ không tồn tại.');
            }
        }
        
        // Get all services for dropdown
        $services = DichVu::orderBy('Tendichvu')->get();
        
        // Get available time slots
        $availableTimeSlots = $this->getAvailableTimeSlots();
        
        // Get available days for the selected service
        $availableDays = [];
        if ($selectedService) {
            $days = json_decode($selectedService->available_days ?? '[]', true);
            $availableDays = $days;
            
            // Convert day names to localized versions if needed
            $dayTranslations = [
                'monday' => 'Thứ Hai',
                'tuesday' => 'Thứ Ba',
                'wednesday' => 'Thứ Tư',
                'thursday' => 'Thứ Năm',
                'friday' => 'Thứ Sáu',
                'saturday' => 'Thứ Bảy',
                'sunday' => 'Chủ Nhật',
            ];
            
            // Map day names to their localized versions
            $availableDays = array_map(function($day) use ($dayTranslations) {
                return $dayTranslations[strtolower($day)] ?? $day;
            }, $days);
        }
        
        return view('customer.datlich.create', compact(
            'services', 
            'selectedService', 
            'availableTimeSlots', 
            'availableDays'
        ));
    }
    
    /**
     * Store a newly created booking in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'MaDV' => 'required|exists:DICHVU,MaDV',
            'booking_date' => 'required|date|after_or_equal:today',
            'booking_time' => 'required',
        ]);
        
        // Combine date and time
        $bookingDateTime = $request->booking_date . ' ' . $request->booking_time;
        
        // Check if the service is available on the selected day
        $service = DichVu::findOrFail($request->MaDV);
        $dayOfWeek = strtolower(Carbon::parse($request->booking_date)->format('l'));
        
        if (!$service->isAvailableOn($dayOfWeek)) {
            return back()->withInput()->withErrors([
                'booking_date' => 'Dịch vụ này không có sẵn vào ngày đã chọn.'
            ]);
        }
        
        // Check if the time slot is available
        $existingBooking = DatLich::where('MaDV', $request->MaDV)
            ->where('Thoigiandatlich', $bookingDateTime)
            ->where('Trangthai_', '!=', 4) // Assuming 4 is the cancelled status
            ->exists();
            
        if ($existingBooking) {
            return back()->withInput()->withErrors([
                'booking_time' => 'Khung giờ này đã được đặt. Vui lòng chọn khung giờ khác.'
            ]);
        }
        
        // Generate a unique booking ID
        $lastBooking = DatLich::orderBy('MaDL', 'desc')->first();
        $newBookingId = $lastBooking ? $lastBooking->MaDL + 1 : 1;
        
        // Create new booking
        DatLich::create([
            'MaDL' => $newBookingId,
            'Manguoidung' => Auth::id(),
            'Thoigiandatlich' => $bookingDateTime,
            'Trangthai_' => 1, // Assuming 1 is the pending status
            'MaDV' => $request->MaDV,
        ]);

        return redirect()->route('customer.datlich.index')
            ->with('success', 'Đặt lịch thành công. Vui lòng chờ xác nhận.');
    }
    
    // Other methods remain the same...
}