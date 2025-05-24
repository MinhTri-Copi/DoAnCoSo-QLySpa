<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\HoaDonVaThanhToan;
use App\Models\DatLich;
use App\Models\PhuongThuc;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use PDF;
use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class HoaDonController extends Controller
{
    /**
     * Display a listing of the customer's invoices.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Đảm bảo tìm đúng user trong bảng USER dựa trên tài khoản đăng nhập
        $customer = User::where('MaTK', $user->MaTK)->first();
        
        if (!$customer) {
            return view('customer.hoadon.index', [
                'invoices' => collect(),
                'paymentMethods' => PhuongThuc::all(),
                'totalSpent' => 0,
                'totalInvoices' => 0,
                'unpaidInvoices' => 0,
                'loggedInUser' => $user,
                'customer' => null
            ])->with('error', 'Không tìm thấy thông tin khách hàng');
        }
        
        $query = HoaDonVaThanhToan::whereHas('datLich', function ($query) use ($customer) {
            $query->where('Manguoidung', $customer->Manguoidung);
        })->with(['datLich.dichVu', 'phuongThuc']);
        
        // Filter by payment status
        if ($request->has('payment_status') && $request->payment_status != '') {
            $query->where('Matrangthai', $request->payment_status);
        }
        
        // Filter by date range
        if ($request->has('start_date') && $request->start_date != '') {
            $query->whereDate('Ngaythanhtoan', '>=', $request->start_date);
        }
        
        if ($request->has('end_date') && $request->end_date != '') {
            $query->whereDate('Ngaythanhtoan', '<=', $request->end_date);
        }
        
        // Filter by payment method
        if ($request->has('payment_method') && $request->payment_method != '') {
            $query->where('MaPT', $request->payment_method);
        }
        
        // Sort by date or amount
        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'date_asc':
                    $query->orderBy('Ngaythanhtoan', 'asc');
                    break;
                case 'date_desc':
                    $query->orderBy('Ngaythanhtoan', 'desc');
                    break;
                case 'amount_asc':
                    $query->orderBy('Tongtien', 'asc');
                    break;
                case 'amount_desc':
                    $query->orderBy('Tongtien', 'desc');
                    break;
                default:
                    $query->orderBy('Ngaythanhtoan', 'desc');
                    break;
            }
        } else {
            // Default sort (newest first)
            $query->orderBy('Ngaythanhtoan', 'desc');
        }
        
        $invoices = $query->paginate(10);
        
        // Get payment methods for filter
        $paymentMethods = PhuongThuc::all();
        
        // Debug: Log customer info
        \Log::info('Customer info:', [
            'MaTK' => $user->MaTK,
            'Customer ID' => $customer->Manguoidung,
            'Customer Name' => $customer->Hoten
        ]);
        
        // Calculate statistics - Thử truy vấn trực tiếp
        $invoiceIds = DB::table('DATLICH')
            ->where('DATLICH.Manguoidung', $customer->Manguoidung)
            ->join('HOADON_VA_THANHTOAN', 'DATLICH.MaDL', '=', 'HOADON_VA_THANHTOAN.MaDL')
            ->pluck('HOADON_VA_THANHTOAN.MaHD');
            
        // Debug: Log invoice IDs found
        \Log::info('Invoices found for user '.$customer->Manguoidung.': ', [
            'count' => count($invoiceIds),
            'ids' => $invoiceIds
        ]);
        
        $totalSpent = DB::table('HOADON_VA_THANHTOAN')
            ->whereIn('MaHD', $invoiceIds)
            ->sum('Tongtien');
            
        // Debug: Log total spent calculated
        \Log::info('Total spent calculated: '.$totalSpent);
        
        // Calculate total invoices count
        $totalInvoices = count($invoiceIds);
        
        // Calculate unpaid invoices
        $unpaidInvoices = DB::table('HOADON_VA_THANHTOAN')
            ->whereIn('MaHD', $invoiceIds)
            ->where('Matrangthai', 2) // 2 represents 'pending' status
            ->count();
        
        // Debug: Log statistics
        \Log::info('Invoice statistics:', [
            'totalSpent' => $totalSpent,
            'totalInvoices' => $totalInvoices,
            'unpaidInvoices' => $unpaidInvoices
        ]);
        
        return view('customer.hoadon.index', compact(
            'invoices',
            'paymentMethods',
            'totalSpent',
            'totalInvoices',
            'unpaidInvoices',
            'user',
            'customer'
        ));
    }
    
    /**
     * Display the specified invoice.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = Auth::user();
        $customer = User::where('MaTK', $user->MaTK)->first();
        
        if (!$customer) {
            return redirect()->route('customer.hoadon.index')
                ->with('error', 'Không tìm thấy thông tin khách hàng');
        }
        
        $invoice = HoaDonVaThanhToan::whereHas('datLich', function ($query) use ($customer) {
                $query->where('Manguoidung', $customer->Manguoidung);
            })
            ->with(['datLich.dichVu', 'phuongThuc', 'datLich.user'])
            ->where('MaHD', $id)
            ->firstOrFail();
        
        // Check if the invoice has been reviewed
        $hasReview = $invoice->danhGia()->exists();
        
        return view('customer.hoadon.show', compact('invoice', 'hasReview', 'user', 'customer'));
    }
    
    /**
     * Generate a PDF of the specified invoice.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function downloadPdf($id)
    {
        $user = Auth::user();
        $customer = User::where('MaTK', $user->MaTK)->first();
        
        if (!$customer) {
            return redirect()->route('customer.hoadon.index')
                ->with('error', 'Không tìm thấy thông tin khách hàng');
        }
        
        $invoice = HoaDonVaThanhToan::whereHas('datLich', function ($query) use ($customer) {
                $query->where('Manguoidung', $customer->Manguoidung);
            })
            ->with(['datLich.dichVu', 'phuongThuc', 'datLich.user'])
            ->where('MaHD', $id)
            ->firstOrFail();
        
        // Generate PDF using Laravel's PDF library
        $pdf = PDF::loadView('customer.hoadon.pdf', compact('invoice'));
        
        return $pdf->download('hoa-don-' . $invoice->MaHD . '.pdf');
    }
    
    /**
     * Display the payment page for an invoice.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showPayment($id)
    {
        $user = Auth::user();
        $customer = User::where('MaTK', $user->MaTK)->first();
        
        if (!$customer) {
            return redirect()->route('customer.hoadon.index')
                ->with('error', 'Không tìm thấy thông tin khách hàng');
        }
        
        $invoice = HoaDonVaThanhToan::whereHas('datLich', function ($query) use ($customer) {
                $query->where('Manguoidung', $customer->Manguoidung);
            })
            ->with(['datLich.dichVu'])
            ->where('MaHD', $id)
            ->where('Matrangthai', 2) // 2 is 'pending' status
            ->firstOrFail();
        
        // Get available payment methods
        $paymentMethods = PhuongThuc::all();
        
        return view('customer.hoadon.payment', compact('invoice', 'paymentMethods'));
    }
    
    /**
     * Process the payment for an invoice.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function processPayment(Request $request, $id)
    {
        $request->validate([
            'payment_method' => 'required|exists:PHUONGTHUC,MaPT',
            'agree_terms' => 'required|boolean|accepted',
        ]);
        
        $user = Auth::user();
        $customer = User::where('MaTK', $user->MaTK)->first();
        
        if (!$customer) {
            return redirect()->route('customer.hoadon.index')
                ->with('error', 'Không tìm thấy thông tin khách hàng');
        }
        
        $invoice = HoaDonVaThanhToan::whereHas('datLich', function ($query) use ($customer) {
                $query->where('Manguoidung', $customer->Manguoidung);
            })
            ->where('MaHD', $id)
            ->where('Matrangthai', 2) // 2 is 'pending' status
            ->firstOrFail();
        
        // Update invoice with payment details
        $invoice->update([
            'MaPT' => $request->payment_method,
            'Ngaythanhtoan' => Carbon::now(),
            'Matrangthai' => 1, // 1 is 'completed' status
        ]);
        
        // Update booking status if needed
        if ($invoice->datLich && $invoice->datLich->Trangthai_ == 'Đã xác nhận') {
            $invoice->datLich->update([
                'Trangthai_' => 'Hoàn thành'
            ]);
        }
        
        // Award loyalty points (if applicable)
        $this->awardLoyaltyPoints($invoice);
        
        return redirect()->route('customer.hoadon.show', $id)
            ->with('success', 'Thanh toán thành công! Cảm ơn quý khách đã sử dụng dịch vụ của chúng tôi.');
    }
    
    /**
     * Award loyalty points for a completed invoice.
     *
     * @param  \App\Models\HoaDonVaThanhToan  $invoice
     * @return void
     */
    private function awardLoyaltyPoints($invoice)
    {
        // Simple calculation: 1 point for every 10,000 VND spent
        $pointsEarned = floor($invoice->Tongtien / 10000);
        
        if ($pointsEarned > 0) {
            // Get the user
            $user = $invoice->datLich->user;
            
            // Update user's points
            $currentPoints = $user->Diemtichluy ?? 0;
            $user->update([
                'Diemtichluy' => $currentPoints + $pointsEarned
            ]);
            
            // Create point history record
            PointHistory::create([
                'Manguoidung' => $user->id,
                'MaHD' => $invoice->MaHD,
                'Soluongdiem' => $pointsEarned,
                'Thoigian' => Carbon::now(),
                'Mota' => 'Điểm thưởng từ hóa đơn #' . $invoice->MaHD,
                'Trangthai' => 'active',
                'Thoigianhethan' => Carbon::now()->addYear(), // Points expire after 1 year
            ]);
        }
    }
    
    /**
     * Generate invoices for completed appointments without invoices
     */
    public function createInvoice()
    {
        $user = Auth::user();
        
        // Find completed appointments without invoices
        $completedBookings = DatLich::where('Manguoidung', $user->Manguoidung)
            ->where('Trangthai_', 'Hoàn thành') // Assuming 'Hoàn thành' is the completed status
            ->whereDoesntHave('hoaDon')
            ->with('dichVu')
            ->get();
            
        $created = 0;
        
        foreach ($completedBookings as $booking) {
            // Create an invoice for this booking
            $invoiceId = 'HD' . Str::random(8);
            
            HoaDonVaThanhToan::create([
                'MaHD' => $invoiceId,
                'MaDL' => $booking->MaDL,
                'Manguoidung' => $booking->Manguoidung,
                'Tongtien' => $booking->dichVu->Gia,
                'Ngaythanhtoan' => Carbon::now(),
                'Matrangthai' => 2, // Pending payment
            ]);
            
            $created++;
        }
        
        if ($created > 0) {
            return redirect()->route('customer.hoadon.index')
                ->with('success', "Đã tạo {$created} hóa đơn mới từ các lịch hẹn đã hoàn thành.");
        } else {
            return redirect()->route('customer.hoadon.index')
                ->with('info', 'Không có lịch hẹn hoàn thành nào cần tạo hóa đơn.');
        }
    }
    
    /**
     * Diagnostic function to check invoice rating status
     * 
     * @param int $id Invoice ID to check
     * @return \Illuminate\Http\Response
     */
    public function checkRatingStatus($id)
    {
        $user = Auth::user();
        $customer = User::where('MaTK', $user->MaTK)->first();
        
        if (!$customer) {
            return response()->json([
                'error' => 'Customer not found'
            ]);
        }
        
        $invoice = HoaDonVaThanhToan::with(['datLich.dichVu', 'trangThai', 'danhGia'])
            ->find($id);
            
        if (!$invoice) {
            return response()->json([
                'error' => 'Invoice not found'
            ]);
        }
        
        // Check if user has permission to view this invoice
        $isOwner = false;
        if ($invoice->datLich && $invoice->datLich->Manguoidung == $customer->Manguoidung) {
            $isOwner = true;
        }
        if ($invoice->Manguoidung == $customer->Manguoidung) {
            $isOwner = true;
        }
        
        if (!$isOwner) {
            return response()->json([
                'error' => 'Not authorized to view this invoice'
            ]);
        }
        
        // Direct check if ratings exist
        $ratings = \App\Models\DanhGia::where('MaHD', $invoice->MaHD)
            ->where('Manguoidung', $customer->Manguoidung)
            ->get();
            
        // Use the model method
        $hasRating = $invoice->daDanhGia($customer->Manguoidung);
        
        return response()->json([
            'invoice_id' => $invoice->MaHD,
            'status_code' => $invoice->Matrangthai,
            'status_name' => $invoice->trangThai ? $invoice->trangThai->Tentrangthai : 'Unknown',
            'ratings_direct_check' => $ratings->count() > 0,
            'ratings_direct_count' => $ratings->count(),
            'ratings_model_check' => $hasRating,
            'ratings' => $ratings->map(function($rating) {
                return [
                    'id' => $rating->MaDG,
                    'stars' => $rating->Danhgiasao,
                    'comment' => $rating->Nhanxet
                ];
            })
        ]);
    }
} 