<?php

namespace App\Http\Controllers;

use App\Models\DatLich;
use App\Models\User;
use App\Models\DichVu;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DatLichController extends Controller
{
    public function index(Request $request)
    {
        $query = DatLich::with('user', 'dichVu');
        
        // Tìm kiếm theo người dùng
        if ($request->has('user_id') && $request->user_id) {
            $query->where('Manguoidung', $request->user_id);
        }
        
        // Tìm kiếm theo dịch vụ
        if ($request->has('service_id') && $request->service_id) {
            $query->where('MaDV', $request->service_id);
        }
        
        // Tìm kiếm theo trạng thái
        if ($request->has('status') && $request->status) {
            $query->where('Trangthai_', $request->status);
        }
        
        // Tìm kiếm theo ngày
        if ($request->has('date') && $request->date) {
            $date = Carbon::parse($request->date)->format('Y-m-d');
            $query->whereDate('Thoigiandatlich', $date);
        }
        
        // Tìm kiếm theo khoảng thời gian
        if ($request->has('date_from') && $request->date_from) {
            $dateFrom = Carbon::parse($request->date_from)->startOfDay();
            $query->where('Thoigiandatlich', '>=', $dateFrom);
        }
        
        if ($request->has('date_to') && $request->date_to) {
            $dateTo = Carbon::parse($request->date_to)->endOfDay();
            $query->where('Thoigiandatlich', '<=', $dateTo);
        }
        
        // Sắp xếp
        $sortField = $request->get('sort', 'Thoigiandatlich');
        $sortDirection = $request->get('direction', 'desc');
        $query->orderBy($sortField, $sortDirection);
        
        $datLichs = $query->paginate(10);
        
        // Lấy danh sách người dùng và dịch vụ cho bộ lọc
        $users = User::all();
        $dichVus = DichVu::all();
        
        // Lấy danh sách trạng thái
        $statuses = DatLich::select('Trangthai_')->distinct()->pluck('Trangthai_');
        
        return view('backend.datlich.index', compact('datLichs', 'users', 'dichVus', 'statuses'));
    }

    public function create()
    {
        // Lấy giá trị MaDL lớn nhất
        $maxMaDL = DatLich::max('MaDL') ?? 0;
        $suggestedMaDL = $maxMaDL + 1;

        $users = User::all();
        $dichVus = DichVu::all();
        return view('backend.datlich.create', compact('suggestedMaDL', 'users', 'dichVus'));
    }

    public function store(Request $request)
    {
        // Log the received data for debugging
        \Log::info('Received booking data:', $request->all());
        
        // Xử lý trường hợp nhận bookingDate và bookingTime thay vì Thoigiandatlich
        if ($request->has('bookingDate') && $request->has('bookingTime') && !$request->filled('Thoigiandatlich')) {
            $request->merge([
                'Thoigiandatlich' => $request->bookingDate . ' ' . $request->bookingTime . ':00'
            ]);
            \Log::info('Created Thoigiandatlich from date and time:', ['Thoigiandatlich' => $request->Thoigiandatlich]);
        }
        
        $request->validate([
            'Manguoidung' => 'required|exists:USER,Manguoidung',
            'Thoigiandatlich' => 'required|date',
            'Trangthai_' => 'required|string|max:50',
            'MaDV' => 'required|exists:DICHVU,MaDV',
        ], [
            'Manguoidung.required' => 'Vui lòng chọn người dùng.',
            'Manguoidung.exists' => 'Người dùng không hợp lệ.',
            'Thoigiandatlich.required' => 'Thời gian đặt lịch không được để trống.',
            'Thoigiandatlich.date' => 'Thời gian đặt lịch không hợp lệ.',
            'Trangthai_.required' => 'Trạng thái không được để trống.',
            'Trangthai_.max' => 'Trạng thái không được vượt quá 50 ký tự.',
            'MaDV.required' => 'Vui lòng chọn dịch vụ.',
            'MaDV.exists' => 'Dịch vụ không hợp lệ.',
        ]);

        // Lấy ngày từ thời gian đặt lịch
        $bookingDate = Carbon::parse($request->Thoigiandatlich)->format('Y-m-d');
        
        // Kiểm tra số lượng lịch đặt trong ngày không vượt quá 30
        $bookingsCountInDay = DatLich::whereDate('Thoigiandatlich', $bookingDate)->count();
        if ($bookingsCountInDay >= 30) {
            return redirect()->back()->withInput()->with('error', 'Đã đạt giới hạn 30 lịch đặt trong ngày này. Vui lòng chọn ngày khác.');
        }
        
        // Lấy thông tin dịch vụ
        $dichVu = DichVu::findOrFail($request->MaDV);
        
        // Lấy thời gian dịch vụ (phút)
        $serviceTime = $dichVu->Thoigian;
        
        // Thời gian đặt lịch
        $bookingTime = Carbon::parse($request->Thoigiandatlich);
        
        // Tính thời gian kết thúc dịch vụ
        $endTime = (clone $bookingTime)->addMinutes($serviceTime);
        
        // Kiểm tra số lượng lịch đặt cho dịch vụ này trong cùng khung giờ
        $overlappingBookings = DatLich::where('MaDV', $request->MaDV)
            ->where(function($query) use ($bookingTime, $endTime) {
                // Lịch đặt bắt đầu trong khoảng thời gian dịch vụ
                $query->whereBetween('Thoigiandatlich', [$bookingTime, $endTime])
                    // Hoặc lịch đặt kết thúc trong khoảng thời gian dịch vụ
                    ->orWhere(function($q) use ($bookingTime, $endTime) {
                        $q->where('Thoigiandatlich', '<=', $bookingTime)
                          ->whereRaw("DATE_ADD(Thoigiandatlich, INTERVAL (SELECT Thoigian FROM DICHVU WHERE MaDV = DATLICH.MaDV) MINUTE) >= ?", [$bookingTime]);
                    });
            })
            ->count();
        
        // Nếu dịch vụ có thời gian 30 phút, giới hạn 2 lịch đặt cùng lúc
        $maxConcurrentBookings = 2;
        if ($overlappingBookings >= $maxConcurrentBookings) {
            return redirect()->back()->withInput()->with('error', 'Đã đạt giới hạn số lượng lịch đặt cho dịch vụ này trong khung giờ đã chọn. Vui lòng chọn thời gian khác.');
        }
        
        // Lấy giá trị MaDL lớn nhất
        $maxMaDL = DatLich::max('MaDL');
        if ($maxMaDL) {
            $number = (int) substr($maxMaDL, 2);
            $suggestedMaDL = 'DL' . ($number + 1);
        } else {
            $suggestedMaDL = 'DL1';
        }

        try {
            DB::beginTransaction();
            
            DatLich::create([
                'MaDL' => $suggestedMaDL,
                'Manguoidung' => $request->Manguoidung,
                'Thoigiandatlich' => $request->Thoigiandatlich,
                'Trangthai_' => $request->Trangthai_,
                'MaDV' => $request->MaDV,
            ]);
            
            DB::commit();
            return redirect()->route('admin.datlich.index')->with('success', 'Thêm đặt lịch thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Đã xảy ra lỗi: ' . $e->getMessage())->withInput();
        }
    }

    public function show($id)
    {
        $datLich = DatLich::with('user', 'dichVu')->findOrFail($id);
        return view('backend.datlich.show', compact('datLich'));
    }

    public function edit($id)
    {
        $datLich = DatLich::findOrFail($id);
        $users = User::all();
        $dichVus = DichVu::all();
        return view('backend.datlich.edit', compact('datLich', 'users', 'dichVus'));
    }

    public function update(Request $request, $id)
    {
        // Log the received data for debugging
        \Log::info('Received update booking data:', $request->all());
        
        // Xử lý trường hợp nhận bookingDate và bookingTime thay vì Thoigiandatlich
        if ($request->has('bookingDate') && $request->has('bookingTime') && !$request->filled('Thoigiandatlich')) {
            $request->merge([
                'Thoigiandatlich' => $request->bookingDate . ' ' . $request->bookingTime . ':00'
            ]);
            \Log::info('Created Thoigiandatlich from date and time for update:', ['Thoigiandatlich' => $request->Thoigiandatlich]);
        }
        
        $datLich = DatLich::findOrFail($id);

        $request->validate([
            'Manguoidung' => 'required|exists:USER,Manguoidung',
            'Thoigiandatlich' => 'required|date',
            'Trangthai_' => 'required|string|max:50',
            'MaDV' => 'required|exists:DICHVU,MaDV',
        ], [
            'Manguoidung.required' => 'Vui lòng chọn người dùng.',
            'Manguoidung.exists' => 'Người dùng không hợp lệ.',
            'Thoigiandatlich.required' => 'Thời gian đặt lịch không được để trống.',
            'Thoigiandatlich.date' => 'Thời gian đặt lịch không hợp lệ.',
            'Trangthai_.required' => 'Trạng thái không được để trống.',
            'Trangthai_.max' => 'Trạng thái không được vượt quá 50 ký tự.',
            'MaDV.required' => 'Vui lòng chọn dịch vụ.',
            'MaDV.exists' => 'Dịch vụ không hợp lệ.',
        ]);

        // Nếu thay đổi ngày hoặc dịch vụ, cần kiểm tra lại các ràng buộc
        if ($datLich->Thoigiandatlich != $request->Thoigiandatlich || $datLich->MaDV != $request->MaDV) {
            // Lấy ngày từ thời gian đặt lịch
            $bookingDate = Carbon::parse($request->Thoigiandatlich)->format('Y-m-d');
            
            // Kiểm tra số lượng lịch đặt trong ngày không vượt quá 30 (không tính lịch hiện tại)
            $bookingsCountInDay = DatLich::whereDate('Thoigiandatlich', $bookingDate)
                ->where('MaDL', '!=', $id)
                ->count();
                
            if ($bookingsCountInDay >= 30) {
                return redirect()->back()->withInput()->with('error', 'Đã đạt giới hạn 30 lịch đặt trong ngày này. Vui lòng chọn ngày khác.');
            }
            
            // Lấy thông tin dịch vụ
            $dichVu = DichVu::findOrFail($request->MaDV);
            
            // Lấy thời gian dịch vụ (phút)
            $serviceTime = $dichVu->Thoigian;
            
            // Thời gian đặt lịch
            $bookingTime = Carbon::parse($request->Thoigiandatlich);
            
            // Tính thời gian kết thúc dịch vụ
            $endTime = (clone $bookingTime)->addMinutes($serviceTime);
            
            // Kiểm tra số lượng lịch đặt cho dịch vụ này trong cùng khung giờ (không tính lịch hiện tại)
            $overlappingBookings = DatLich::where('MaDV', $request->MaDV)
                ->where('MaDL', '!=', $id)
                ->where(function($query) use ($bookingTime, $endTime) {
                    // Lịch đặt bắt đầu trong khoảng thời gian dịch vụ
                    $query->whereBetween('Thoigiandatlich', [$bookingTime, $endTime])
                        // Hoặc lịch đặt kết thúc trong khoảng thời gian dịch vụ
                        ->orWhere(function($q) use ($bookingTime, $endTime) {
                            $q->where('Thoigiandatlich', '<=', $bookingTime)
                              ->whereRaw("DATE_ADD(Thoigiandatlich, INTERVAL (SELECT Thoigian FROM DICHVU WHERE MaDV = DATLICH.MaDV) MINUTE) >= ?", [$bookingTime]);
                        });
                })
                ->count();
            
            // Nếu dịch vụ có thời gian 30 phút, giới hạn 2 lịch đặt cùng lúc
            $maxConcurrentBookings = 2;
            if ($overlappingBookings >= $maxConcurrentBookings) {
                return redirect()->back()->withInput()->with('error', 'Đã đạt giới hạn số lượng lịch đặt cho dịch vụ này trong khung giờ đã chọn. Vui lòng chọn thời gian khác.');
            }
        }

        try {
            DB::beginTransaction();
            
            $datLich->update([
                'Manguoidung' => $request->Manguoidung,
                'Thoigiandatlich' => $request->Thoigiandatlich,
                'Trangthai_' => $request->Trangthai_,
                'MaDV' => $request->MaDV,
            ]);
            
            DB::commit();
            return redirect()->route('admin.datlich.index')->with('success', 'Cập nhật đặt lịch thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Đã xảy ra lỗi: ' . $e->getMessage())->withInput();
        }
    }

    public function confirmDestroy($id)
    {
        $datLich = DatLich::with('user', 'dichVu')->findOrFail($id);
        return view('backend.datlich.destroy', compact('datLich'));
    }

    public function destroy($id)
    {
        $datLich = DatLich::findOrFail($id);

        try {
            DB::beginTransaction();
            $datLich->delete();
            DB::commit();
            return redirect()->route('admin.datlich.index')->with('success', 'Xóa đặt lịch thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.datlich.index')->with('error', 'Không thể xóa đặt lịch vì có dữ liệu liên quan!');
        }
    }
    
    // Thêm phương thức thống kê
    public function statistics(Request $request)
    {
        // Thống kê theo khoảng thời gian
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->format('Y-m-d'));
        
        // Chuyển đổi thành đối tượng Carbon
        $startDate = Carbon::parse($startDate)->startOfDay();
        $endDate = Carbon::parse($endDate)->endOfDay();
        
        // Tổng số lịch đặt trong khoảng thời gian
        $totalBookings = DatLich::whereBetween('Thoigiandatlich', [$startDate, $endDate])->count();
        
        // Thống kê theo trạng thái
        $bookingsByStatus = DatLich::whereBetween('Thoigiandatlich', [$startDate, $endDate])
            ->select('Trangthai_', DB::raw('count(*) as count'))
            ->groupBy('Trangthai_')
            ->get();
        
        // Thống kê theo dịch vụ
        $bookingsByService = DatLich::whereBetween('Thoigiandatlich', [$startDate, $endDate])
            ->select('MaDV', DB::raw('count(*) as count'))
            ->groupBy('MaDV')
            ->with('dichVu')
            ->get();
        
        // Thống kê theo ngày
        $bookingsByDate = DatLich::whereBetween('Thoigiandatlich', [$startDate, $endDate])
            ->select(DB::raw('DATE(Thoigiandatlich) as date'), DB::raw('count(*) as count'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();
        
        // Thống kê theo giờ
        $bookingsByHour = DatLich::whereBetween('Thoigiandatlich', [$startDate, $endDate])
            ->select(DB::raw('HOUR(Thoigiandatlich) as hour'), DB::raw('count(*) as count'))
            ->groupBy('hour')
            ->orderBy('hour')
            ->get();
        
        // Thống kê theo người dùng (top 10)
        $bookingsByUser = DatLich::whereBetween('Thoigiandatlich', [$startDate, $endDate])
            ->select('Manguoidung', DB::raw('count(*) as count'))
            ->groupBy('Manguoidung')
            ->with('user')
            ->orderBy('count', 'desc')
            ->limit(10)
            ->get();
        
        return view('backend.datlich.statistics', compact(
            'totalBookings', 
            'bookingsByStatus', 
            'bookingsByService', 
            'bookingsByDate', 
            'bookingsByHour', 
            'bookingsByUser',
            'startDate',
            'endDate'
        ));
    }
    
    // API để kiểm tra tính khả dụng của thời gian đặt lịch
    public function checkAvailability(Request $request)
    {
        $date = $request->date;
        $serviceId = $request->service_id;
        
        // Kiểm tra số lượng lịch đặt trong ngày
        $bookingsCountInDay = DatLich::whereDate('Thoigiandatlich', $date)->count();
        if ($bookingsCountInDay >= 30) {
            return response()->json([
                'available' => false,
                'message' => 'Đã đạt giới hạn 30 lịch đặt trong ngày này.'
            ]);
        }
        
        // Lấy các khung giờ đã đặt cho dịch vụ này trong ngày
        $bookedTimeSlots = DatLich::where('MaDV', $serviceId)
            ->whereDate('Thoigiandatlich', $date)
            ->pluck('Thoigiandatlich')
            ->map(function($time) {
                return Carbon::parse($time)->format('H:i');
            })
            ->toArray();
        
        // Lấy thông tin dịch vụ
        $dichVu = DichVu::findOrFail($serviceId);
        
        // Lấy thời gian dịch vụ (phút)
        $serviceTime = $dichVu->Thoigian;
        
        // Tạo danh sách các khung giờ có sẵn (ví dụ: từ 8:00 đến 18:00, mỗi 30 phút)
        $availableTimeSlots = [];
        $startHour = 8;
        $endHour = 18;
        $interval = 30; // phút
        
        $currentTime = Carbon::parse($date)->setHour($startHour)->setMinute(0)->setSecond(0);
        $endTime = Carbon::parse($date)->setHour($endHour)->setMinute(0)->setSecond(0);
        
        while ($currentTime < $endTime) {
            $timeSlot = $currentTime->format('H:i');
            
            // Kiểm tra xem khung giờ này đã đạt giới hạn chưa
            $overlappingBookings = 0;
            foreach ($bookedTimeSlots as $bookedTime) {
                $bookedTimeObj = Carbon::parse($date . ' ' . $bookedTime);
                $bookedEndTime = (clone $bookedTimeObj)->addMinutes($serviceTime);
                
                if (
                    ($currentTime >= $bookedTimeObj && $currentTime < $bookedEndTime) ||
                    ((clone $currentTime)->addMinutes($serviceTime) > $bookedTimeObj && (clone $currentTime)->addMinutes($serviceTime) <= $bookedEndTime)
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
            'timeSlots' => $availableTimeSlots
        ]);
    }
}