<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\DatLich;
use App\Models\User;
use App\Models\HoaDonVaThanhToan;
use App\Models\LSDiemThuong;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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
            
        // Lấy số lượng hóa đơn liên quan
        $guestInvoicesCount = 0;
        if ($guestBookings->count() > 0) {
            $guestBookingIds = $guestBookings->pluck('MaDL')->toArray();
            $guestInvoicesCount = HoaDonVaThanhToan::whereIn('MaDL', $guestBookingIds)
                ->whereNull('Manguoidung')
                ->count();
        }
        
        // Lưu số lượng hóa đơn vào session để hiển thị trên view
        session(['guest_invoices_count' => $guestInvoicesCount]);
        
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
            $updatedBookingsCount = DatLich::whereIn('MaDL', $request->booking_ids)
                ->whereNull('Manguoidung')
                ->update(['Manguoidung' => $user->Manguoidung]);
            
            // Lấy tất cả các hóa đơn liên quan đến các lịch đặt được chọn
            $relatedInvoices = HoaDonVaThanhToan::whereIn('MaDL', $request->booking_ids)
                ->whereNull('Manguoidung')
                ->get();
                
            $relatedInvoicesCount = $relatedInvoices->count();
            
            // Cập nhật Manguoidung cho các hóa đơn
            if ($relatedInvoicesCount > 0) {
                HoaDonVaThanhToan::whereIn('MaDL', $request->booking_ids)
                    ->whereNull('Manguoidung')
                    ->update(['Manguoidung' => $user->Manguoidung]);
                
                // Xử lý điểm thưởng cho từng hóa đơn
                $totalRewardPoints = 0;
                foreach ($relatedInvoices as $invoice) {
                    // Kiểm tra xem hóa đơn đã có điểm thưởng chưa
                    $existingPoints = LSDiemThuong::where('MaHD', $invoice->MaHD)->exists();
                    
                    if (!$existingPoints) {
                        // Tính điểm thưởng dựa trên tổng tiền
                        $soDiem = $this->calculateRewardPoints($invoice->Tongtien);
                        
                        if ($soDiem > 0) {
                            $maxMaLSDT = LSDiemThuong::max('MaLSDT') ?? 0;
                            $newMaLSDT = $maxMaLSDT + 1;
                            
                            // Tạo bản ghi lịch sử điểm thưởng
                            LSDiemThuong::create([
                                'MaLSDT' => $newMaLSDT,
                                'Thoigian' => Carbon::now(),
                                'Sodiem' => $soDiem,
                                'Manguoidung' => $user->Manguoidung,
                                'MaHD' => $invoice->MaHD,
                            ]);
                            
                            $totalRewardPoints += $soDiem;
                        }
                    }
                }
            }
            
            DB::commit();
            
            $message = "Đã liên kết thành công {$updatedBookingsCount} lịch đặt";
            if ($relatedInvoicesCount > 0) {
                $message .= " và {$relatedInvoicesCount} hóa đơn liên quan";
            }
            $message .= " vào tài khoản của bạn.";
            
            if (isset($totalRewardPoints) && $totalRewardPoints > 0) {
                $message .= " Bạn đã được cộng {$totalRewardPoints} điểm thưởng từ các hóa đơn cũ.";
            }
            
            return redirect()->route('customer.lichsudatlich.index')
                ->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Đã xảy ra lỗi khi liên kết lịch đặt: ' . $e->getMessage());
        }
    }
    
    /**
     * Tính điểm thưởng dựa trên tổng tiền hóa đơn
     * 
     * @param float $tongTien Tổng tiền hóa đơn
     * @return int Số điểm thưởng
     */
    private function calculateRewardPoints($tongTien)
    {
        $soDiem = 0;

        if ($tongTien >= 100000 && $tongTien < 500000) {
            $soDiem = 100;
        } elseif ($tongTien >= 500000 && $tongTien < 1000000) {
            $soDiem = 300;
        } elseif ($tongTien >= 1000000) {
            $soDiem = 500;
        }
        
        return $soDiem;
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