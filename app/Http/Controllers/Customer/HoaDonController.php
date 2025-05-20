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
        
        $query = HoaDonVaThanhToan::whereHas('datLich', function ($query) use ($user) {
            $query->where('Manguoidung', $user->id);
        })->with(['datLich.dichVu', 'phuongThuc']);
        
        // Filter by payment status
        if ($request->has('payment_status') && $request->payment_status != '') {
            $query->where('TrangthaiTT', $request->payment_status);
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
        
        // Calculate statistics
        $totalSpent = HoaDonVaThanhToan::whereHas('datLich', function ($query) use ($user) {
                $query->where('Manguoidung', $user->id);
            })
            ->where('TrangthaiTT', 'completed') // Assuming 'completed' is the status for completed payments
            ->sum('Tongtien');
            
        $totalInvoices = HoaDonVaThanhToan::whereHas('datLich', function ($query) use ($user) {
                $query->where('Manguoidung', $user->id);
            })->count();
            
        $unpaidInvoices = HoaDonVaThanhToan::whereHas('datLich', function ($query) use ($user) {
                $query->where('Manguoidung', $user->id);
            })
            ->where('TrangthaiTT', 'pending') // Assuming 'pending' is the status for pending payments
            ->count();
        
        return view('customer.hoadon.index', compact(
            'invoices',
            'paymentMethods',
            'totalSpent',
            'totalInvoices',
            'unpaidInvoices'
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
        
        $invoice = HoaDonVaThanhToan::whereHas('datLich', function ($query) use ($user) {
                $query->where('Manguoidung', $user->id);
            })
            ->with(['datLich.dichVu', 'phuongThuc', 'datLich.user'])
            ->where('MaHD', $id)
            ->firstOrFail();
        
        // Check if the invoice has been reviewed
        $hasReview = $invoice->danhGia()->exists();
        
        return view('customer.hoadon.show', compact('invoice', 'hasReview'));
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
        
        $invoice = HoaDonVaThanhToan::whereHas('datLich', function ($query) use ($user) {
                $query->where('Manguoidung', $user->id);
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
        
        $invoice = HoaDonVaThanhToan::whereHas('datLich', function ($query) use ($user) {
                $query->where('Manguoidung', $user->id);
            })
            ->with(['datLich.dichVu'])
            ->where('MaHD', $id)
            ->where('TrangthaiTT', 'pending') // Only allow payment for pending invoices
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
        
        $invoice = HoaDonVaThanhToan::whereHas('datLich', function ($query) use ($user) {
                $query->where('Manguoidung', $user->id);
            })
            ->where('MaHD', $id)
            ->where('TrangthaiTT', 'pending')
            ->firstOrFail();
        
        // Update invoice with payment details
        $invoice->update([
            'MaPT' => $request->payment_method,
            'Ngaythanhtoan' => Carbon::now(),
            'TrangthaiTT' => 'completed',
        ]);
        
        // Update booking status if needed
        if ($invoice->datLich && $invoice->datLich->Trangthai_ == 2) { // Assuming 2 is "confirmed" status
            $invoice->datLich->update([
                'Trangthai_' => 3 // Assuming 3 is "in progress" status
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
} 