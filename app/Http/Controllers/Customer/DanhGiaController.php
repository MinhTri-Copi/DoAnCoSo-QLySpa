<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\DanhGia;
use App\Models\HoaDonVaThanhToan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DanhGiaController extends Controller
{
    /**
     * Display a listing of the customer's reviews.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $accountUser = Auth::user();
        $user = \App\Models\User::where('MaTK', $accountUser->MaTK)->first();
        
        if (!$user) {
            return redirect()->route('customer.home')
                ->with('error', 'Không tìm thấy thông tin người dùng.');
        }
        
        $reviews = DanhGia::where('Manguoidung', $user->Manguoidung)
            ->with(['dichVu', 'hoaDon'])
            ->orderBy('Ngaydanhgia', 'desc')
            ->paginate(10);
            
        return view('customer.danhgia.index', compact('reviews'));
    }
    
    /**
     * Show the form for creating a new review.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, $invoice_id = null)
    {
        // Ưu tiên route param, sau đó đến query param
        $invoiceId = $invoice_id ?? $request->invoice_id;
        
        // Validate invoice ID
        if (!$invoiceId || !is_numeric($invoiceId)) {
            return redirect()->route('customer.hoadon.index')
                ->with('error', 'ID hóa đơn không hợp lệ.');
        }
        
        // Lấy thông tin tài khoản đăng nhập
        $accountUser = Auth::user();
        
        // Lấy thông tin người dùng từ bảng USER dựa trên MaTK
        $user = \App\Models\User::where('MaTK', $accountUser->MaTK)->first();
        
        if (!$user) {
            return redirect()->route('customer.hoadon.index')
                ->with('error', 'Không tìm thấy thông tin người dùng.');
        }
        
        // Try to find the invoice with more flexible query
        $hoaDon = HoaDonVaThanhToan::with(['datLich.dichVu'])
            ->where('MaHD', $invoiceId)
            ->first();
            
        if (!$hoaDon) {
            return redirect()->route('customer.hoadon.index')
                ->with('error', 'Không tìm thấy hóa đơn này. Vui lòng thử lại.');
        }
        
        // Kiểm tra xem datLich có tồn tại không
        if (!$hoaDon->datLich) {
            return redirect()->route('customer.hoadon.index')
                ->with('error', 'Không tìm thấy thông tin đặt lịch cho hóa đơn này.');
        }
        
        // Verify ownership - kiểm tra cả hai cách
        $isOwner = false;
        
        // Cách 1: Kiểm tra qua datLich
        if ($hoaDon->datLich->Manguoidung == $user->Manguoidung) {
            $isOwner = true;
        }
        
        // Cách 2: Kiểm tra qua hóa đơn
        if ($hoaDon->Manguoidung == $user->Manguoidung) {
            $isOwner = true;
        }
        
        if (!$isOwner) {
            return redirect()->route('customer.hoadon.index')
                ->with('error', 'Bạn không có quyền đánh giá hóa đơn này.');
        }
            
        // Check if user already reviewed this invoice
        $existingReview = DanhGia::where('MaHD', $hoaDon->MaHD)
            ->where('Manguoidung', $user->Manguoidung)
            ->exists();
            
        if ($existingReview) {
            return redirect()->route('customer.danhgia.index')
                ->with('error', 'Bạn đã đánh giá dịch vụ này rồi.');
        }
        
        return view('customer.danhgia.create', compact('hoaDon'));
    }
    
    /**
     * Store a newly created review in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'invoice_id' => 'required|numeric',
            'Diemdanhgia' => 'required|integer|min:1|max:5',
            'Noidungdanhgia' => 'required|string|max:500',
        ], [
            'Diemdanhgia.required' => 'Vui lòng chọn số sao đánh giá.',
            'Diemdanhgia.min' => 'Vui lòng chọn ít nhất 1 sao.',
            'Diemdanhgia.max' => 'Đánh giá tối đa 5 sao.',
            'Noidungdanhgia.required' => 'Vui lòng nhập nội dung đánh giá.',
            'Noidungdanhgia.max' => 'Nội dung đánh giá không quá 500 ký tự.',
        ]);
        
        // Lấy thông tin tài khoản đăng nhập
        $accountUser = Auth::user();
        
        // Lấy thông tin người dùng từ bảng USER dựa trên MaTK
        $user = \App\Models\User::where('MaTK', $accountUser->MaTK)->first();
        
        if (!$user) {
            return redirect()->route('customer.hoadon.index')
                ->with('error', 'Không tìm thấy thông tin người dùng.');
        }
        
        $invoiceId = $request->invoice_id;
        
        // Find the invoice
        $hoaDon = HoaDonVaThanhToan::with('datLich')
            ->where('MaHD', $invoiceId)
            ->first();
            
        if (!$hoaDon) {
            return redirect()->route('customer.hoadon.index')
                ->with('error', 'Không tìm thấy hóa đơn này.');
        }
        
        // Verify ownership - kiểm tra cả hai cách
        $isOwner = false;
        
        // Cách 1: Kiểm tra qua datLich
        if ($hoaDon->datLich && $hoaDon->datLich->Manguoidung == $user->Manguoidung) {
            $isOwner = true;
        }
        
        // Cách 2: Kiểm tra qua hóa đơn
        if ($hoaDon->Manguoidung == $user->Manguoidung) {
            $isOwner = true;
        }
        
        if (!$isOwner) {
            return redirect()->route('customer.hoadon.index')
                ->with('error', 'Bạn không có quyền đánh giá hóa đơn này.');
        }
        
        // Generate a unique review ID
        $lastReview = DanhGia::orderBy('MaDG', 'desc')->first();
        $newReviewId = $lastReview ? $lastReview->MaDG + 1 : 1;
        
        // Create the review - remove MaDV field since it doesn't exist in the database
        $danhGia = DanhGia::create([
            'MaDG' => $newReviewId,
            'Manguoidung' => $user->Manguoidung,
            'MaHD' => $hoaDon->MaHD,
            'Danhgiasao' => $request->Diemdanhgia,
            'Nhanxet' => $request->Noidungdanhgia,
            'Ngaydanhgia' => Carbon::now(),
        ]);
        
        return redirect()->route('customer.danhgia.show', $danhGia->MaDG)
            ->with('success', 'Đánh giá đã được gửi thành công.');
    }
    
    /**
     * Display the specified review.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $accountUser = Auth::user();
        $user = \App\Models\User::where('MaTK', $accountUser->MaTK)->first();
        
        if (!$user) {
            return redirect()->route('customer.home')
                ->with('error', 'Không tìm thấy thông tin người dùng.');
        }
        
        $review = DanhGia::with(['dichVu', 'hoaDon', 'user'])
            ->where('Manguoidung', $user->Manguoidung)
            ->where('MaDG', $id)
            ->firstOrFail();
            
        return view('customer.danhgia.show', compact('review'));
    }
    
    /**
     * Show the form for editing the specified review.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $accountUser = Auth::user();
        $user = \App\Models\User::where('MaTK', $accountUser->MaTK)->first();
        
        if (!$user) {
            return redirect()->route('customer.home')
                ->with('error', 'Không tìm thấy thông tin người dùng.');
        }
        
        $review = DanhGia::with(['dichVu', 'hoaDon'])
            ->where('Manguoidung', $user->Manguoidung)
            ->where('MaDG', $id)
            ->firstOrFail();
            
        // Check if review can be edited (within 48 hours)
        $reviewDate = Carbon::parse($review->Ngaydanhgia);
        if (Carbon::now()->diffInHours($reviewDate) > 48) {
            return redirect()->route('customer.danhgia.show', $id)
                ->with('error', 'Không thể chỉnh sửa đánh giá sau 48 giờ.');
        }
        
        return view('customer.danhgia.edit', compact('review'));
    }
    
    /**
     * Update the specified review in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'Diemdanhgia' => 'required|integer|min:1|max:5',
            'Noidungdanhgia' => 'required|string|max:500',
        ]);
        
        $accountUser = Auth::user();
        $user = \App\Models\User::where('MaTK', $accountUser->MaTK)->first();
        
        if (!$user) {
            return redirect()->route('customer.home')
                ->with('error', 'Không tìm thấy thông tin người dùng.');
        }
        
        $review = DanhGia::where('Manguoidung', $user->Manguoidung)
            ->where('MaDG', $id)
            ->firstOrFail();
            
        // Check if review can be edited (within 48 hours)
        $reviewDate = Carbon::parse($review->Ngaydanhgia);
        if (Carbon::now()->diffInHours($reviewDate) > 48) {
            return redirect()->route('customer.danhgia.show', $id)
                ->with('error', 'Không thể chỉnh sửa đánh giá sau 48 giờ.');
        }
        
        // Update the review
        $review->update([
            'Danhgiasao' => $request->Diemdanhgia,
            'Nhanxet' => $request->Noidungdanhgia,
        ]);
        
        return redirect()->route('customer.danhgia.show', $id)
            ->with('success', 'Cập nhật đánh giá thành công.');
    }
} 