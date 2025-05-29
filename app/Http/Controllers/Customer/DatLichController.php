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
        // Log dữ liệu đầu vào để debug
        \Log::info('Dữ liệu đặt lịch nhận được:', $request->all());
        
        // Validate dữ liệu đầu vào
        $validator = \Validator::make($request->all(), [
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

        // Nếu là khách vãng lai, validate thông tin khách
        if (!Auth::check()) {
            $validator->addRules([
                'guest_name' => 'required|string|max:255',
                'guest_phone' => [
                    'required',
                    'string', 
                    'min:10',
                    'max:15',
                ],
            ]);
            
            $validator->setCustomMessages([
                'guest_name.required' => 'Vui lòng nhập họ tên',
                'guest_phone.required' => 'Vui lòng nhập số điện thoại',
                'guest_phone.min' => 'Số điện thoại phải có ít nhất 10 kí tự',
            ]);
        }
        
        if ($validator->fails()) {
            \Log::error('Lỗi validation:', $validator->errors()->toArray());
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
            
            return redirect()
                ->route('customer.datlich.create', [
                    'step' => 3, 
                    'service_id' => $request->input('service_id'),
                    'booking_time' => $request->input('booking_time') // Truyền lại booking_time khi có lỗi
                ])
                ->withErrors($validator)
                ->withInput();
        }
        
        try {
            // Format booking datetime
            $bookingDate = $request->input('booking_date');
            $bookingTime = $request->input('booking_time');
            
            // Log thời gian đặt lịch để debug
            \Log::info("Thời gian đặt lịch: Ngày {$bookingDate}, Giờ {$bookingTime}");
            
            // Kiểm tra định dạng thời gian
            if (!preg_match('/^\d{2}:\d{2}$/', $bookingTime)) {
                \Log::error("Định dạng giờ không hợp lệ: {$bookingTime}");
                
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Định dạng giờ không hợp lệ'
                    ], 422);
                }
                
                return redirect()
                    ->route('customer.datlich.create', [
                        'step' => 3, 
                        'service_id' => $request->input('service_id'),
                        'booking_time' => $request->input('booking_time')
                    ])
                    ->with('error', 'Định dạng giờ không hợp lệ')
                    ->withInput();
            }
            
            try {
                $bookingDateTime = Carbon::createFromFormat('Y-m-d H:i', $bookingDate . ' ' . $bookingTime);
                \Log::info("Đã tạo DateTime: " . $bookingDateTime->toDateTimeString());
            } catch (\Exception $e) {
                \Log::error("Lỗi tạo DateTime: " . $e->getMessage());
                
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Không thể tạo thời gian đặt lịch: ' . $e->getMessage()
                    ], 422);
                }
                
                return redirect()
                    ->route('customer.datlich.create', [
                        'step' => 3, 
                        'service_id' => $request->input('service_id'),
                        'booking_time' => $request->input('booking_time')
                    ])
                    ->with('error', 'Không thể tạo thời gian đặt lịch: ' . $e->getMessage())
                    ->withInput();
            }

            // Kiểm tra service
            $service = DichVu::findOrFail($request->input('service_id'));
            \Log::info("Đã tìm thấy dịch vụ: {$service->Tendichvu}");
                
            // Kiểm tra trùng lịch
            $bookedSlots = $this->getBookedTimeSlots($service->MaDV, $bookingDate);
            \Log::info("Đã lấy các khung giờ đã đặt: ", $bookedSlots->toArray());

            // Kiểm tra xem khung giờ này đã đạt giới hạn chưa (tối đa 2 lịch đặt trùng giờ)
            $overlappingBookings = 0;
            $serviceEndTime = (clone $bookingDateTime)->addMinutes($service->Thoigian);
            \Log::info("Thời gian kết thúc dịch vụ: " . $serviceEndTime->toDateTimeString());
            
            foreach ($bookedSlots as $slot) {
                $slotStart = Carbon::parse($bookingDate . ' ' . $slot['start']);
                $slotEnd = Carbon::parse($bookingDate . ' ' . $slot['end']);

                \Log::info("Kiểm tra slot: {$slotStart->toDateTimeString()} - {$slotEnd->toDateTimeString()}");
                
                if (($bookingDateTime >= $slotStart && $bookingDateTime < $slotEnd) ||
                    ($serviceEndTime > $slotStart && $serviceEndTime <= $slotEnd) ||
                    ($bookingDateTime <= $slotStart && $serviceEndTime >= $slotEnd)
                ) {
                    $overlappingBookings++;
                    \Log::warning("Phát hiện trùng lịch! Số lượng: {$overlappingBookings}");
                }
            }
            
            // Giới hạn tối đa 2 lịch đặt trùng giờ
            $maxConcurrentBookings = 2;
            if ($overlappingBookings >= $maxConcurrentBookings) {
                \Log::warning("Đã đạt giới hạn lịch đặt trùng giờ: {$overlappingBookings}/{$maxConcurrentBookings}");
                
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Khung giờ này đã đạt giới hạn đặt lịch. Vui lòng chọn khung giờ khác.'
                    ], 422);
                }
                
                return redirect()
                    ->route('customer.datlich.create', [
                        'step' => 2, 
                        'service_id' => $request->input('service_id')
                    ])
                    ->with('error', 'Khung giờ này đã đạt giới hạn đặt lịch. Vui lòng chọn khung giờ khác.');
            }

            // Tạo mã đặt lịch mới
            $maxMaDL = DatLich::max('MaDL');
            $newMaDL = $maxMaDL ? (is_numeric($maxMaDL) ? $maxMaDL + 1 : 'DL1') : 'DL1';
            \Log::info("Mã đặt lịch mới: {$newMaDL}");

            // Tạo đối tượng đặt lịch
            $datLich = new DatLich();
            $datLich->MaDL = $newMaDL;
            $datLich->MaDV = $request->input('service_id');
            $datLich->Thoigiandatlich = $bookingDateTime;
            $datLich->Ghichu = $request->input('notes');
            $datLich->Trangthai_ = 'Chờ xác nhận';
            
            // Xử lý thông tin người dùng
            if (Auth::check()) {
                $account = Auth::user();
                $user = User::where('MaTK', $account->MaTK)->first();
                
                if ($user) {
                    \Log::info("Đặt lịch cho user: {$user->Manguoidung} - {$user->Hoten}");
                    $datLich->Manguoidung = $user->Manguoidung;
                } else {
                    \Log::error("Không tìm thấy thông tin người dùng với MaTK: {$account->MaTK}");
                    
                    if ($request->ajax() || $request->wantsJson()) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Không tìm thấy thông tin người dùng.'
                        ], 422);
                    }
                    
                    return redirect()
                        ->route('customer.datlich.create', [
                            'step' => 3, 
                            'service_id' => $request->input('service_id'),
                            'booking_time' => $request->input('booking_time')
                        ])
                        ->with('error', 'Không tìm thấy thông tin người dùng.')
                        ->withInput();
                }
            } else {
                // Khách vãng lai
                \Log::info("Đặt lịch cho khách vãng lai: {$request->input('guest_name')} - {$request->input('guest_phone')}");
                $datLich->Manguoidung = null;
                $datLich->Hoten_khach = $request->input('guest_name');
                $datLich->SDT_khach = $request->input('guest_phone');
            }
            
            // Log các giá trị của đối tượng đặt lịch trước khi lưu
            \Log::info("Thông tin đặt lịch trước khi lưu:", [
                'MaDL' => $datLich->MaDL,
                'MaDV' => $datLich->MaDV,
                'Thoigiandatlich' => $datLich->Thoigiandatlich,
                'Ghichu' => $datLich->Ghichu,
                'Trangthai_' => $datLich->Trangthai_,
                'Manguoidung' => $datLich->Manguoidung,
                'Hoten_khach' => $datLich->Hoten_khach ?? null,
                'SDT_khach' => $datLich->SDT_khach ?? null
            ]);
            
            try {
                // Lưu đặt lịch
                $datLich->save();
                \Log::info("Đã lưu đặt lịch thành công với ID: {$datLich->MaDL}");
                
                // Thông báo thành công
                $successMessage = 'Đặt lịch thành công! Chúng tôi sẽ liên hệ xác nhận trong thời gian sớm nhất.';
                
                // Điều hướng tương ứng sau khi đặt lịch
                if (!Auth::check()) {
                    \Log::info("Redirect khách vãng lai về trang chủ");
                    
                    if ($request->ajax() || $request->wantsJson()) {
                        return response()->json([
                            'success' => true,
                            'message' => $successMessage . ' Hãy đăng ký tài khoản để quản lý lịch đặt và nhận ưu đãi đặc biệt.',
                            'redirect' => route('welcome')
                        ]);
                    }
                    
                    return redirect()->route('welcome')
                        ->with('success', $successMessage . ' Hãy đăng ký tài khoản để quản lý lịch đặt và nhận ưu đãi đặc biệt.');
                } else {
                    \Log::info("Redirect khách đăng nhập về trang lịch sử đặt lịch");
                    
                    if ($request->ajax() || $request->wantsJson()) {
                        return response()->json([
                            'success' => true,
                            'message' => $successMessage,
                            'redirect' => route('customer.lichsudatlich.index')
                        ]);
                    }
                    
                    return redirect()->route('customer.lichsudatlich.index')
                        ->with('success', $successMessage);
                }
            } catch (\Exception $e) {
                \Log::error("Lỗi khi lưu đặt lịch: " . $e->getMessage());
                
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Lỗi khi lưu đặt lịch: ' . $e->getMessage()
                    ], 500);
                }
                
                return redirect()
                    ->route('customer.datlich.create', [
                        'step' => 3, 
                        'service_id' => $request->input('service_id'),
                        'booking_time' => $request->input('booking_time')
                    ])
                    ->with('error', 'Lỗi khi lưu đặt lịch: ' . $e->getMessage())
                    ->withInput();
            }
                
        } catch (\Exception $e) {
            \Log::error('Error in booking: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Đã xảy ra lỗi: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()
                ->route('customer.datlich.create', [
                    'step' => 3, 
                    'service_id' => $request->input('service_id'),
                    'booking_time' => $request->input('booking_time')  // Truyền lại booking_time khi có lỗi
                ])
                ->with('error', 'Đã xảy ra lỗi: ' . $e->getMessage())
                ->withInput();
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
        
        \Log::info("Kiểm tra khả dụng cho dịch vụ ID: {$serviceId}, ngày: {$date}");
        
        // Kiểm tra số lượng lịch đặt trong ngày
        $bookingsCountInDay = DatLich::whereDate('Thoigiandatlich', $date)
            ->where('Trangthai_', '!=', 'Đã hủy')
            ->count();
            
        \Log::info("Số lịch đặt trong ngày: {$bookingsCountInDay}");
        
        if ($bookingsCountInDay >= 30) {
            \Log::info("Đã đạt giới hạn lịch đặt trong ngày");
            return response()->json([
                'available' => false,
                'message' => 'Đã đạt giới hạn 30 lịch đặt trong ngày này.'
            ]);
        }
        
        // Lấy thông tin dịch vụ
        $dichVu = DichVu::findOrFail($serviceId);
        \Log::info("Dịch vụ: {$dichVu->Tendichvu}");
        
        // Kiểm tra dịch vụ có hoạt động trong ngày đã chọn không
        $dayOfWeek = Carbon::parse($date)->format('l');
        \Log::info("Ngày trong tuần: {$dayOfWeek}");
        
        if (!$dichVu->isAvailableOn($dayOfWeek)) {
            \Log::info("Dịch vụ không hoạt động vào ngày {$dayOfWeek}");
            return response()->json([
                'available' => false,
                'message' => 'Dịch vụ này không hoạt động vào ' . $dayOfWeek
            ]);
        }
        
        // Lấy thời gian dịch vụ (phút)
        $serviceTime = $dichVu->Thoigian;
        \Log::info("Thời gian dịch vụ: {$serviceTime} phút");
        
        // Lấy các khung giờ đã đặt cho dịch vụ này trong ngày
        $bookedSlots = $this->getBookedTimeSlots($serviceId, $date);
        \Log::info("Đã tìm thấy " . count($bookedSlots) . " khung giờ đã đặt");
        
        // Tạo danh sách các khung giờ có sẵn (ví dụ: từ 8:00 đến 18:00, mỗi 30 phút)
        $availableTimeSlots = [];
        $startHour = 8;
        $endHour = 18;
        $interval = 30; // phút
        
        $currentTime = Carbon::parse($date)->setHour($startHour)->setMinute(0)->setSecond(0);
        $endTime = Carbon::parse($date)->setHour($endHour)->setMinute(0)->setSecond(0);
        
        // Thiết lập múi giờ cho Việt Nam/TP.HCM
        $now = Carbon::now('Asia/Ho_Chi_Minh');
        \Log::info("Thời gian hiện tại tại TP.HCM: " . $now->format('Y-m-d H:i:s'));
        
        // Nếu ngày đặt lịch là hôm nay, bỏ qua các khung giờ đã qua
        if ($date == $now->format('Y-m-d')) {
            $currentTime = max($currentTime, $now->ceil('30 minutes'));
            \Log::info("Ngày đặt lịch là hôm nay, bắt đầu từ: " . $currentTime->format('H:i'));
        }
        
        \Log::info("Tạo khung giờ từ " . $currentTime->format('H:i') . " đến " . $endTime->format('H:i'));
        
        while ($currentTime < $endTime) {
            $timeSlot = $currentTime->format('H:i');
            
            // Kiểm tra xem khung giờ này đã đạt giới hạn chưa
            $overlappingBookings = 0;
            $currentTimeEnd = (clone $currentTime)->addMinutes($serviceTime);
            
            foreach ($bookedSlots as $bookedSlot) {
                $bookedStart = Carbon::parse($date . ' ' . $bookedSlot['start']);
                $bookedEnd = Carbon::parse($date . ' ' . $bookedSlot['end']);
                
                if (
                    ($currentTime >= $bookedStart && $currentTime < $bookedEnd) ||
                    ($currentTimeEnd > $bookedStart && $currentTimeEnd <= $bookedEnd) ||
                    ($currentTime <= $bookedStart && $currentTimeEnd >= $bookedEnd)
                ) {
                    $overlappingBookings++;
                    \Log::info("Khung giờ {$timeSlot} trùng với booking ID: {$bookedSlot['booking_id']}");
                }
            }
            
            $maxConcurrentBookings = 2;
            $isAvailable = $overlappingBookings < $maxConcurrentBookings;
            
            \Log::info("Khung giờ {$timeSlot}: " . ($isAvailable ? "Khả dụng" : "Không khả dụng") . " ({$overlappingBookings}/{$maxConcurrentBookings})");
            
            $availableTimeSlots[] = [
                'time' => $timeSlot,
                'available' => $isAvailable,
                'disabled' => !$isAvailable  // Thêm trường disabled để frontend biết nên disable slot này
            ];
            
            $currentTime->addMinutes($interval);
        }
        
        \Log::info("Trả về " . count($availableTimeSlots) . " khung giờ");
        
        return response()->json([
            'available' => true,
            'timeSlots' => $availableTimeSlots,
            'service_time' => $serviceTime,
            'service_name' => $dichVu->Tendichvu,
            'service_price' => $dichVu->getFormattedPriceAttribute(),
            'current_time' => $now->format('H:i')  // Trả về giờ hiện tại để frontend có thể so sánh
        ]);
    }
    
    /**
     * Lấy các khung giờ đã đặt
     */
    private function getBookedTimeSlots($serviceId, $date, $bookingDateTime = null)
    {
        // Log thông tin đầu vào
        \Log::info("Đang lấy các slot đã đặt cho ngày: {$date}");
        
        // Lấy thông tin dịch vụ
        $dichVu = DichVu::findOrFail($serviceId);
        $serviceTime = $dichVu->Thoigian;
        \Log::info("Dịch vụ: {$dichVu->Tendichvu}, thời gian: {$serviceTime} phút");
        
        // Lấy TẤT CẢ các khung giờ đã đặt trong ngày, không chỉ cho dịch vụ này
        // Để đảm bảo tính chính xác khi kiểm tra số lượng đặt lịch trùng giờ
        $query = DatLich::whereDate('Thoigiandatlich', $date)
            ->where('Trangthai_', '!=', 'Đã hủy');
            
        // Nếu đang cập nhật đặt lịch hiện có, loại trừ lịch đang cập nhật
        if ($bookingDateTime) {
            $query->where('Thoigiandatlich', '!=', $bookingDateTime);
        }
        
        $bookings = $query->get();
        \Log::info("Số lượng booking đã tìm thấy trong ngày: " . $bookings->count());
        
        $slots = $bookings->map(function($booking) {
            // Lấy thời gian dịch vụ từ dịch vụ của booking
            $bookingServiceTime = $booking->dichVu ? $booking->dichVu->Thoigian : 60; // Mặc định 60 phút nếu không tìm thấy dịch vụ
            
            $time = Carbon::parse($booking->Thoigiandatlich);
            $endTime = (clone $time)->addMinutes($bookingServiceTime);
            
            \Log::info("Booking ID: {$booking->MaDL}, Dịch vụ: {$booking->MaDV}, Thời gian: {$time->format('H:i')} - {$endTime->format('H:i')}");
            
            return [
                'start' => $time->format('H:i'),
                'end' => $endTime->format('H:i'),
                'booking_id' => $booking->MaDL,
                'service_id' => $booking->MaDV,
            ];
        });
        
        return $slots;
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
