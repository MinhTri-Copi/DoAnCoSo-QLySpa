<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\DatLich;
use App\Models\DichVu;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DatLichController extends Controller
{
    /**
     * Hiển thị trang đặt lịch với các dịch vụ được đề xuất
     */
    public function create(Request $request)
    {
        // Lấy thông tin người dùng hiện tại
        $user = Auth::user();
    
        // Xử lý step và service_id từ request
        $step = $request->input('step', 1); // Mặc định là bước 1
        $selectedServiceId = $request->input('service_id');
        $selectedService = null;
    
        if ($selectedServiceId) {
            $selectedService = DichVu::find($selectedServiceId);
            if (!$selectedService) {
                return redirect()->route('customer.datlich.create')
                    ->with('error', 'Dịch vụ không tồn tại.');
            }
        }
    
        // Lấy tất cả các dịch vụ có sẵn
        $query = DichVu::query();
    
        // Áp dụng bộ lọc nếu có
        if ($request->has('price_min') && $request->price_min != '') {
            $query->where('Gia', '>=', $request->price_min);
        }
    
        if ($request->has('price_max') && $request->price_max != '') {
            $query->where('Gia', '<=', $request->price_max);
        }
    
        if ($request->has('search') && $request->search != '') {
            $query->where(function($q) use ($request) {
                $q->where('Tendichvu', 'like', '%' . $request->search . '%')
                  ->orWhere('MoTa', 'like', '%' . $request->search . '%');
            });
        }
    
        // Lấy dịch vụ theo thời gian (nếu người dùng đã chọn ngày)
        $selectedDate = $request->date ?? Carbon::now()->format('Y-m-d');
        $dayOfWeek = Carbon::parse($selectedDate)->format('l');
        $dayOfWeekLower = strtolower($dayOfWeek);
    
        if ($request->has('date')) {
            $query->whereRaw("JSON_CONTAINS(available_days, ?, '$')", ['"' . $dayOfWeekLower . '"']);
        }
    
        // Lấy các dịch vụ đề xuất (dựa trên lượt đặt nhiều nhất)
        $recommendedServices = DichVu::withCount('datLich')
            ->orderBy('dat_lich_count', 'desc')
            ->limit(4)
            ->get();
    
        // Lấy các dịch vụ đề xuất dựa trên lịch sử đặt lịch của người dùng
        $userPreferredServices = [];
        if ($user) {
            $serviceIds = DB::table('DATLICH')
                ->where('Manguoidung', $user->Manguoidung)
                ->select('MaDV', DB::raw('COUNT(*) as booking_count'))
                ->groupBy('MaDV')
                ->orderBy('booking_count', 'desc')
                ->limit(3)
                ->pluck('MaDV');
    
            if ($serviceIds->count() > 0) {
                $userPreferredServices = DichVu::whereIn('MaDV', $serviceIds)->get();
            }
        }
    
        // Lấy các khung giờ đã đặt trong ngày được chọn
        $bookedTimeSlots = DatLich::whereDate('Thoigiandatlich', $selectedDate)
            ->where('Trangthai_', '!=', 'Đã hủy')
            ->get()
            ->map(function($booking) {
                $time = Carbon::parse($booking->Thoigiandatlich);
                $serviceTime = $booking->dichVu->Thoigian;
    
                $slots = [];
                $endTime = (clone $time)->addMinutes($serviceTime);
                $currentSlot = clone $time;
                while ($currentSlot < $endTime) {
                    $slots[] = $currentSlot->format('H:i');
                    $currentSlot->addMinutes(30);
                }
    
                return [
                    'service_id' => $booking->MaDV,
                    'slots' => $slots,
                ];
            });
    
        // Sắp xếp dịch vụ
        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'price_asc':
                    $query->orderBy('Gia', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('Gia', 'desc');
                    break;
                case 'name_asc':
                    $query->orderBy('Tendichvu', 'asc');
                    break;
                case 'name_desc':
                    $query->orderBy('Tendichvu', 'desc');
                    break;
                case 'popular':
                    $query->withCount('datLich')->orderBy('dat_lich_count', 'desc');
                    break;
                default:
                    $query->orderBy('Tendichvu', 'asc');
                    break;
            }
        } else {
            $query->orderBy('Tendichvu', 'asc');
        }
    
        $dichVus = $query->paginate(9);
    
        $minPrice = DichVu::min('Gia');
        $maxPrice = DichVu::max('Gia');
    
        $availableDates = [];
        for ($i = 0; $i < 10; $i++) {
            $date = now()->addDays($i);
            $availableDates[] = [
                'date' => $date->toDateString(),
                'day' => $date->day,
                'month' => $date->month,
                'year' => $date->year,
                'day_short' => $date->locale('vi')->dayName,
            ];
        }
    
        return view('customer.datlich.create', compact(
            'dichVus',
            'recommendedServices',
            'userPreferredServices',
            'minPrice',
            'maxPrice',
            'availableDates',
            'selectedDate',
            'bookedTimeSlots',
            'step',
            'selectedService'
        ));
    }
    
    /**
     * Lưu đặt lịch mới vào cơ sở dữ liệu
     */
    public function store(Request $request)
    {
        // Validate form input
        $request->validate([
            'service_id' => 'required|exists:DICHVU,MaDV',
            'booking_date' => 'required|date|after_or_equal:today',
            'booking_time' => 'required',
        ], [
            'service_id.required' => 'Vui lòng chọn dịch vụ',
            'service_id.exists' => 'Dịch vụ không tồn tại',
            'booking_date.required' => 'Vui lòng chọn ngày đặt lịch',
            'booking_date.date' => 'Ngày đặt lịch không hợp lệ',
            'booking_date.after_or_equal' => 'Ngày đặt lịch phải từ hôm nay trở đi',
            'booking_time.required' => 'Vui lòng chọn giờ đặt lịch',
        ]);

        // Lấy thông tin người dùng hiện tại
        $account = Auth::user();
        
        // Debug log for Auth user
        \Log::info('Auth user account:', [
            'MaTK' => $account->MaTK,
            'Tendangnhap' => $account->Tendangnhap,
            'RoleID' => $account->RoleID
        ]);
        
        // Get the User record associated with this Account
        $user = User::where('MaTK', $account->MaTK)->first();
        
        if ($user) {
            \Log::info('Found user:', [
                'Manguoidung' => $user->Manguoidung,
                'Hoten' => $user->Hoten,
                'Email' => $user->Email
            ]);
        } else {
            \Log::error('User not found for MaTK: ' . $account->MaTK);
            return back()->withInput()->withErrors(['error' => 'Không tìm thấy thông tin người dùng. Vui lòng đăng nhập lại.']);
        }
        
        // Kết hợp ngày và giờ để tạo thời gian đặt lịch
        $bookingDateTime = Carbon::parse($request->booking_date . ' ' . $request->booking_time);
        
        // Kiểm tra thời gian đặt lịch có hợp lệ không (không trong quá khứ)
        if ($bookingDateTime->isPast()) {
            return back()->withInput()->withErrors(['booking_time' => 'Không thể đặt lịch trong quá khứ']);
        }
        
        // Kiểm tra dịch vụ có hoạt động trong ngày đã chọn không
        $dayOfWeek = $bookingDateTime->format('l');
        $dichVu = DichVu::findOrFail($request->service_id);
        
        if (!$dichVu->isAvailableOn($dayOfWeek)) {
            return back()->withInput()->withErrors(['booking_date' => 'Dịch vụ này không hoạt động vào ' . $dayOfWeek]);
        }
        
        // Kiểm tra số lượng đặt lịch trong ngày có vượt quá giới hạn không
        $bookingsCountInDay = DatLich::whereDate('Thoigiandatlich', $request->booking_date)->count();
        if ($bookingsCountInDay >= 30) {
            return back()->withInput()->withErrors(['booking_date' => 'Đã đạt giới hạn 30 lịch đặt trong ngày này']);
        }
        
        // Kiểm tra thời gian đặt lịch có trùng với các lịch đặt khác không
        $bookedTimeSlots = $this->getBookedTimeSlots($request->service_id, $request->booking_date, $bookingDateTime);
        
        // Lấy thời gian dịch vụ
        $serviceTime = $dichVu->Thoigian;
        
        // Tính thời gian kết thúc dịch vụ
        $endTime = (clone $bookingDateTime)->addMinutes($serviceTime);
        
        // Kiểm tra lịch đặt trong cùng khung giờ
        $overlappingBookings = DatLich::where('MaDV', $request->service_id)
            ->where('Trangthai_', '!=', 'Đã hủy')
            ->where(function($query) use ($bookingDateTime, $endTime) {
                // Lịch đặt bắt đầu trong khoảng thời gian dịch vụ
                $query->whereBetween('Thoigiandatlich', [$bookingDateTime, $endTime])
                    // Hoặc lịch đặt kết thúc trong khoảng thời gian dịch vụ
                    ->orWhere(function($q) use ($bookingDateTime, $endTime) {
                        $q->where('Thoigiandatlich', '<=', $bookingDateTime)
                          ->whereRaw("DATE_ADD(Thoigiandatlich, INTERVAL (SELECT Thoigian FROM DICHVU WHERE MaDV = DATLICH.MaDV) MINUTE) >= ?", [$bookingDateTime]);
                    });
            })
            ->count();
        
        // Nếu đã có đủ lịch đặt cùng lúc (giả sử tối đa 2 lịch cùng dịch vụ, cùng thời điểm)
        $maxConcurrentBookings = 2;
        if ($overlappingBookings >= $maxConcurrentBookings) {
            return back()->withInput()->withErrors(['booking_time' => 'Khung giờ này đã đủ lịch đặt, vui lòng chọn khung giờ khác']);
        }
        
        try {
            DB::beginTransaction();
            
            // Tạo mã đặt lịch mới
            $maxMaDL = DatLich::max('MaDL');
            $newMaDL = 'DL1';
            
            if ($maxMaDL) {
                if (is_numeric($maxMaDL)) {
                    $newMaDL = $maxMaDL + 1;
                } else {
                    $matches = [];
                    preg_match('/DL(\d+)/', $maxMaDL, $matches);
                    if (isset($matches[1])) {
                        $number = (int)$matches[1];
                        $newMaDL = 'DL' . ($number + 1);
                    }
                }
            }
            
            // Tạo đặt lịch mới
            $datLich = new DatLich();
            $datLich->MaDL = $newMaDL;
            $datLich->Manguoidung = $user->Manguoidung;
            $datLich->MaDV = $request->service_id;
            $datLich->Thoigiandatlich = $bookingDateTime;
            $datLich->Trangthai_ = 'Chờ xác nhận';
            
            // Debug log to check if user ID is correctly set
            \Log::info('Creating booking with user ID: ' . $user->Manguoidung);
            
            $datLich->save();
            
            DB::commit();
            
            // Redirect to booking history with success message
            return redirect()->route('customer.lichsudatlich.index')
                ->with('success', 'Đặt lịch thành công! Lịch đặt của bạn đang chờ xác nhận.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Đã xảy ra lỗi: ' . $e->getMessage()]);
        }
    }
    
    /**
     * Kiểm tra tính khả dụng của thời gian đặt lịch
     */
    public function checkAvailability(Request $request)
    {
        // Validate input
        $request->validate([
            'date' => 'required|date',
            'service_id' => 'required|exists:DICHVU,MaDV',
        ]);
        
        $date = $request->date;
        $serviceId = $request->service_id;
        
        // Kiểm tra số lượng lịch đặt trong ngày
        $bookingsCountInDay = DatLich::whereDate('Thoigiandatlich', $date)
            ->where('Trangthai_', '!=', 'Đã hủy')
            ->count();
            
        if ($bookingsCountInDay >= 30) {
            return response()->json([
                'available' => false,
                'message' => 'Đã đạt giới hạn 30 lịch đặt trong ngày này.'
            ]);
        }
        
        // Lấy thông tin dịch vụ
        $dichVu = DichVu::findOrFail($serviceId);
        
        // Kiểm tra dịch vụ có hoạt động trong ngày đã chọn không
        $dayOfWeek = Carbon::parse($date)->format('l');
        if (!$dichVu->isAvailableOn($dayOfWeek)) {
            return response()->json([
                'available' => false,
                'message' => 'Dịch vụ này không hoạt động vào ' . $dayOfWeek
            ]);
        }
        
        // Lấy thời gian dịch vụ (phút)
        $serviceTime = $dichVu->Thoigian;
        
        // Lấy các khung giờ đã đặt cho dịch vụ này trong ngày
        $bookedTimeSlots = DatLich::where('MaDV', $serviceId)
            ->whereDate('Thoigiandatlich', $date)
            ->where('Trangthai_', '!=', 'Đã hủy')
            ->get()
            ->map(function($booking) use ($serviceTime) {
                $time = Carbon::parse($booking->Thoigiandatlich);
                $endTime = (clone $time)->addMinutes($serviceTime);
                return [
                    'start' => $time->format('H:i'),
                    'end' => $endTime->format('H:i'),
                ];
            });
        
        // Tạo danh sách các khung giờ có sẵn (ví dụ: từ 8:00 đến 18:00, mỗi 30 phút)
        $availableTimeSlots = [];
        $startHour = 8;
        $endHour = 18;
        $interval = 30; // phút
        
        $currentTime = Carbon::parse($date)->setHour($startHour)->setMinute(0)->setSecond(0);
        $endTime = Carbon::parse($date)->setHour($endHour)->setMinute(0)->setSecond(0);
        
        // Nếu ngày đặt lịch là hôm nay, bỏ qua các khung giờ đã qua
        $now = Carbon::now();
        if ($date == $now->format('Y-m-d')) {
            $currentTime = max($currentTime, $now->ceil('30 minutes'));
        }
        
        while ($currentTime < $endTime) {
            $timeSlot = $currentTime->format('H:i');
            
            // Kiểm tra xem khung giờ này đã đạt giới hạn chưa
            $overlappingBookings = 0;
            $currentTimeEnd = (clone $currentTime)->addMinutes($serviceTime);
            
            foreach ($bookedTimeSlots as $bookedSlot) {
                $bookedStart = Carbon::parse($date . ' ' . $bookedSlot['start']);
                $bookedEnd = Carbon::parse($date . ' ' . $bookedSlot['end']);
                
                if (
                    ($currentTime >= $bookedStart && $currentTime < $bookedEnd) ||
                    ($currentTimeEnd > $bookedStart && $currentTimeEnd <= $bookedEnd) ||
                    ($currentTime <= $bookedStart && $currentTimeEnd >= $bookedEnd)
                ) {
                    $overlappingBookings++;
                }
            }
            
            $maxConcurrentBookings = 2;
            $availableTimeSlots[] = [
                'time' => $timeSlot,
                'available' => $overlappingBookings < $maxConcurrentBookings
            ];
            
            $currentTime->addMinutes($interval);
        }
        
        return response()->json([
            'available' => true,
            'timeSlots' => $availableTimeSlots,
            'service_time' => $serviceTime,
            'service_name' => $dichVu->Tendichvu,
            'service_price' => $dichVu->getFormattedPriceAttribute()
        ]);
    }
    
    /**
     * Lấy các khung giờ đã đặt
     */
    private function getBookedTimeSlots($serviceId, $date, $bookingDateTime = null)
    {
        // Lấy thông tin dịch vụ
        $dichVu = DichVu::findOrFail($serviceId);
        $serviceTime = $dichVu->Thoigian;
        
        // Lấy các khung giờ đã đặt cho dịch vụ này trong ngày
        $query = DatLich::where('MaDV', $serviceId)
            ->whereDate('Thoigiandatlich', $date)
            ->where('Trangthai_', '!=', 'Đã hủy');
            
        // Nếu đang cập nhật đặt lịch hiện có, loại trừ lịch đang cập nhật
        if ($bookingDateTime) {
            $query->where('Thoigiandatlich', '!=', $bookingDateTime);
        }
        
        return $query->get()
            ->map(function($booking) use ($serviceTime) {
                $time = Carbon::parse($booking->Thoigiandatlich);
                $endTime = (clone $time)->addMinutes($serviceTime);
                return [
                    'start' => $time->format('H:i'),
                    'end' => $endTime->format('H:i'),
                ];
            });
    }
    
    /**
     * Tìm dịch vụ theo từ khóa (AJAX)
     */
    public function searchServices(Request $request)
    {
        // Validate input
        $request->validate([
            'keyword' => 'required|string|min:2',
        ]);
        
        $keyword = $request->keyword;
        
        // Tìm dịch vụ theo từ khóa
        $services = DichVu::where('Tendichvu', 'like', '%' . $keyword . '%')
            ->orWhere('MoTa', 'like', '%' . $keyword . '%')
            ->select('MaDV', 'Tendichvu', 'Image', 'Gia')
            ->limit(5)
            ->get()
            ->map(function($service) {
                return [
                    'id' => $service->MaDV,
                    'name' => $service->Tendichvu,
                    'image' => $service->Image,
                    'price' => $service->getFormattedPriceAttribute(),
                ];
            });
        
        return response()->json([
            'success' => true,
            'services' => $services
        ]);
    }
    
    /**
     * Hiển thị lịch đã đặt theo ngày (AJAX) - dùng cho lịch đặt trực quan
     */
    public function getCalendarBookings(Request $request)
    {
        // Validate input
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);
        
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        
        // Lấy tất cả đặt lịch trong khoảng thời gian
        $bookings = DatLich::whereBetween(DB::raw('DATE(Thoigiandatlich)'), [$startDate, $endDate])
            ->where('Manguoidung', Auth::id())
            ->with('dichVu')
            ->get()
            ->map(function($booking) {
                $bookingTime = Carbon::parse($booking->Thoigiandatlich);
                $endTime = (clone $bookingTime)->addMinutes($booking->dichVu->Thoigian ?? 60);
                
                // Màu sắc theo trạng thái
                $color = '#3788d8'; // Mặc định là xanh
                switch ($booking->Trangthai_) {
                    case 'Chờ xác nhận':
                        $color = '#ffa500'; // Cam
                        break;
                    case 'Đã xác nhận':
                        $color = '#3788d8'; // Xanh
                        break;
                    case 'Hoàn thành':
                        $color = '#28a745'; // Xanh lá
                        break;
                    case 'Đã hủy':
                        $color = '#dc3545'; // Đỏ
                        break;
                }
                
                return [
                    'id' => $booking->MaDL,
                    'title' => $booking->dichVu->Tendichvu,
                    'start' => $bookingTime->format('Y-m-d\TH:i:s'),
                    'end' => $endTime->format('Y-m-d\TH:i:s'),
                    'color' => $color,
                    'url' => route('customer.lichsudatlich.show', $booking->MaDL),
                    'extendedProps' => [
                        'status' => $booking->Trangthai_,
                        'service_id' => $booking->MaDV,
                        'service_name' => $booking->dichVu->Tendichvu,
                        'service_price' => $booking->dichVu->getFormattedPriceAttribute(),
                    ]
                ];
            });
        
        return response()->json($bookings);
    }
    
    /**
     * Đề xuất thời gian đặt lịch dựa trên lịch sử đặt lịch của người dùng
     */
    public function recommendTimes(Request $request)
    {
        // Validate input
        $request->validate([
            'service_id' => 'required|exists:DICHVU,MaDV',
        ]);
        
        $user = Auth::user();
        $serviceId = $request->service_id;
        
        // Lấy lịch sử đặt lịch của người dùng cho dịch vụ này
        $userBookingHistory = DatLich::where('Manguoidung', $user->Manguoidung)
            ->where('MaDV', $serviceId)
            ->where('Trangthai_', '!=', 'Đã hủy')
            ->orderBy('Thoigiandatlich', 'desc')
            ->limit(5)
            ->get();
        
        // Nếu người dùng đã từng đặt dịch vụ này, sử dụng thời gian tương tự
        $recommendedTimes = [];
        
        if ($userBookingHistory->count() > 0) {
            // Đề xuất thời gian dựa trên lịch sử
            foreach ($userBookingHistory as $booking) {
                $bookingTime = Carbon::parse($booking->Thoigiandatlich);
                
                // Đề xuất thời gian tương tự
                $recommendedTimes[] = [
                    'time' => $bookingTime->format('H:i'),
                    'day_of_week' => $bookingTime->format('l'),
                    'frequency' => 'Đã đặt trước đây'
                ];
            }
        } else {
            // Nếu không có lịch sử, đề xuất thời gian phổ biến
            $popularTimes = DatLich::where('MaDV', $serviceId)
                ->where('Trangthai_', '!=', 'Đã hủy')
                ->select(DB::raw('HOUR(Thoigiandatlich) as hour'), DB::raw('MINUTE(Thoigiandatlich) as minute'), DB::raw('count(*) as count'), DB::raw('DAYNAME(Thoigiandatlich) as day_name'))
                ->groupBy('hour', 'minute', 'day_name')
                ->orderBy('count', 'desc')
                ->limit(3)
                ->get();
            
            foreach ($popularTimes as $time) {
                $recommendedTimes[] = [
                    'time' => sprintf('%02d:%02d', $time->hour, $time->minute),
                    'day_of_week' => $time->day_name,
                    'frequency' => 'Được đặt ' . $time->count . ' lần'
                ];
            }
            
            // Thêm một số khung giờ phổ biến nếu không đủ đề xuất
            if (count($recommendedTimes) < 3) {
                $defaultTimes = ['10:00', '14:00', '16:00'];
                $defaultDays = ['Saturday', 'Sunday'];
                
                foreach ($defaultTimes as $time) {
                    if (count($recommendedTimes) >= 3) break;
                    
                    foreach ($defaultDays as $day) {
                        if (count($recommendedTimes) >= 3) break;
                        
                        // Kiểm tra xem đề xuất này đã tồn tại chưa
                        $exists = false;
                        foreach ($recommendedTimes as $rec) {
                            if ($rec['time'] == $time && $rec['day_of_week'] == $day) {
                                $exists = true;
                                break;
                            }
                        }
                        
                        if (!$exists) {
                            $recommendedTimes[] = [
                                'time' => $time,
                                'day_of_week' => $day,
                                'frequency' => 'Thời gian phổ biến'
                            ];
                        }
                    }
                }
            }
        }
        
        return response()->json([
            'success' => true,
            'recommended_times' => $recommendedTimes
        ]);
    }

    /**
     * Get authenticated user information
     */
    public function getUserInfo()
    {
        try {
            $account = Auth::user();
            
            if (!$account) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }
            
            // Get the user related to this account
            $user = User::where('MaTK', $account->MaTK)->first();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User profile not found'
                ], 404);
            }
            
            // Format phone number and address if they're missing
            $userData = [
                'Hoten' => $user->Hoten ?: 'Chưa cập nhật',
                'Email' => $user->Email ?: 'Chưa cập nhật',
                'SDT' => $user->SDT ?: 'Chưa cập nhật',
                'DiaChi' => $user->DiaChi ?: 'Chưa cập nhật'
            ];
            
            return response()->json([
                'success' => true,
                'user' => $userData
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching user information: ' . $e->getMessage()
            ], 500);
        }
    }
}
