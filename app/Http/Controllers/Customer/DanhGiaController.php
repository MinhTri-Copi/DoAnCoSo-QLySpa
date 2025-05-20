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
        $user = Auth::user();
        $reviews = DanhGia::where('Manguoidung', $user->id)
            ->with(['dichVu', 'hoaDon'])
            ->latest()
            ->paginate(10);
            
        return view('customer.danhgia.index', compact('reviews'));
    }
    
    /**
     * Show the form for creating a new review.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        // Validate invoice ID
        $request->validate([
            'invoice_id' => 'required|exists:HOADON_VA_THANHTOAN,MaHD'
        ]);
        
        $user = Auth::user();
        $hoaDon = HoaDonVaThanhToan::with(['datLich.dichVu'])
            ->whereHas('datLich', function($query) use ($user) {
                $query->where('Manguoidung', $user->id);
            })
            ->where('MaHD', $request->invoice_id)
            ->firstOrFail();
            
        // Check if user already reviewed this invoice
        $existingReview = DanhGia::where('MaHD', $hoaDon->MaHD)->exists();
        if ($existingReview) {
            return redirect()->route('customer.danhgia.index')
                ->with('error', 'Bạn đã đánh giá dịch vụ này rồi.');
        }
        
        // Check if service is completed
        if ($hoaDon->datLich->Trangthai_ != 5) { // Assuming 5 is completed status
            return redirect()->route('customer.lichsudatlich.index')
                ->with('error', 'Chỉ có thể đánh giá dịch vụ đã hoàn thành.');
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
            'invoice_id' => 'required|exists:HOADON_VA_THANHTOAN,MaHD',
            'Diemdanhgia' => 'required|integer|min:1|max:5',
            'Noidungdanhgia' => 'required|string|max:500',
            'photos.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ], [
            'Diemdanhgia.required' => 'Vui lòng chọn số sao đánh giá.',
            'Diemdanhgia.min' => 'Vui lòng chọn ít nhất 1 sao.',
            'Diemdanhgia.max' => 'Đánh giá tối đa 5 sao.',
            'Noidungdanhgia.required' => 'Vui lòng nhập nội dung đánh giá.',
            'Noidungdanhgia.max' => 'Nội dung đánh giá không quá 500 ký tự.',
            'photos.*.image' => 'File phải là hình ảnh.',
            'photos.*.mimes' => 'Định dạng hình ảnh phải là: jpeg, png, jpg, gif.',
            'photos.*.max' => 'Kích thước hình ảnh không quá 2MB.'
        ]);
        
        $user = Auth::user();
        $hoaDon = HoaDonVaThanhToan::with('datLich')
            ->whereHas('datLich', function($query) use ($user) {
                $query->where('Manguoidung', $user->id);
            })
            ->where('MaHD', $request->invoice_id)
            ->firstOrFail();
        
        // Generate a unique review ID
        $lastReview = DanhGia::orderBy('MaDG', 'desc')->first();
        $newReviewId = $lastReview ? $lastReview->MaDG + 1 : 1;
        
        // Handle photo uploads
        $photos = [];
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $filename = 'review-' . time() . '-' . rand(1000, 9999) . '.' . $photo->getClientOriginalExtension();
                $photo->move(public_path('images/reviews'), $filename);
                $photos[] = 'images/reviews/' . $filename;
            }
        }
        
        // Create the review
        $danhGia = DanhGia::create([
            'MaDG' => $newReviewId,
            'Manguoidung' => $user->id,
            'MaDV' => $hoaDon->datLich->MaDV,
            'MaHD' => $hoaDon->MaHD,
            'Diemdanhgia' => $request->Diemdanhgia,
            'Noidungdanhgia' => $request->Noidungdanhgia,
            'Ngaydanhgia' => Carbon::now(),
            'Hinhanh' => !empty($photos) ? json_encode($photos) : null
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
        $user = Auth::user();
        $review = DanhGia::with(['dichVu', 'hoaDon', 'user'])
            ->where('Manguoidung', $user->id)
            ->where('MaDG', $id)
            ->firstOrFail();
            
        // Parse photo JSON
        $photos = [];
        if ($review->Hinhanh) {
            $photos = json_decode($review->Hinhanh, true) ?? [];
        }
        
        return view('customer.danhgia.show', compact('review', 'photos'));
    }
    
    /**
     * Show the form for editing the specified review.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = Auth::user();
        $review = DanhGia::with(['dichVu', 'hoaDon'])
            ->where('Manguoidung', $user->id)
            ->where('MaDG', $id)
            ->firstOrFail();
            
        // Check if review can be edited (within 48 hours)
        $reviewDate = Carbon::parse($review->Ngaydanhgia);
        if (Carbon::now()->diffInHours($reviewDate) > 48) {
            return redirect()->route('customer.danhgia.show', $id)
                ->with('error', 'Không thể chỉnh sửa đánh giá sau 48 giờ.');
        }
        
        // Parse photo JSON
        $photos = [];
        if ($review->Hinhanh) {
            $photos = json_decode($review->Hinhanh, true) ?? [];
        }
        
        return view('customer.danhgia.edit', compact('review', 'photos'));
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
            'photos.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'delete_photos' => 'nullable|array',
        ]);
        
        $user = Auth::user();
        $review = DanhGia::where('Manguoidung', $user->id)
            ->where('MaDG', $id)
            ->firstOrFail();
            
        // Check if review can be edited (within 48 hours)
        $reviewDate = Carbon::parse($review->Ngaydanhgia);
        if (Carbon::now()->diffInHours($reviewDate) > 48) {
            return redirect()->route('customer.danhgia.show', $id)
                ->with('error', 'Không thể chỉnh sửa đánh giá sau 48 giờ.');
        }
        
        // Handle existing photos
        $photos = [];
        if ($review->Hinhanh) {
            $photos = json_decode($review->Hinhanh, true) ?? [];
        }
        
        // Remove selected photos
        if ($request->has('delete_photos')) {
            foreach ($request->delete_photos as $photoPath) {
                $index = array_search($photoPath, $photos);
                if ($index !== false) {
                    // Delete file if exists
                    if (file_exists(public_path($photoPath))) {
                        unlink(public_path($photoPath));
                    }
                    unset($photos[$index]);
                }
            }
            $photos = array_values($photos); // Re-index array
        }
        
        // Add new photos
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $filename = 'review-' . time() . '-' . rand(1000, 9999) . '.' . $photo->getClientOriginalExtension();
                $photo->move(public_path('images/reviews'), $filename);
                $photos[] = 'images/reviews/' . $filename;
            }
        }
        
        // Update the review
        $review->update([
            'Diemdanhgia' => $request->Diemdanhgia,
            'Noidungdanhgia' => $request->Noidungdanhgia,
            'Hinhanh' => !empty($photos) ? json_encode($photos) : null,
            'Ngaysuadanhgia' => Carbon::now()
        ]);
        
        return redirect()->route('customer.danhgia.show', $id)
            ->with('success', 'Cập nhật đánh giá thành công.');
    }
} 