<?php

namespace App\Http\Controllers;

use App\Models\DichVu;
use App\Models\DatLich;
use App\Models\DanhGia;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;

class DichVuController extends Controller
{
    /**
     * Display a listing of services with optional filters.
     */
    public function index(Request $request)
    {
        $query = DichVu::with(['datLich', 'datLich.user']);

        // Apply filters if provided
        if ($request->has('search')) {
            $query->where('Tendichvu', 'like', '%' . $request->search . '%')
                ->orWhere('MoTa', 'like', '%' . $request->search . '%');
        }

        if ($request->has('price_min') && is_numeric($request->price_min)) {
            $query->where('Gia', '>=', $request->price_min);
        }

        if ($request->has('price_max') && is_numeric($request->price_max)) {
            $query->where('Gia', '<=', $request->price_max);
        }

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
                    $query->orderBy('MaDV', 'asc');
            }
        } else {
            $query->orderBy('MaDV', 'asc');
        }

        $dichVus = $query->get();
        
        // Get price range for filters
        $priceRange = [
            'min' => DichVu::min('Gia') ?? 0,
            'max' => DichVu::max('Gia') ?? 1000000
        ];

        // Get service statistics
        $statistics = [
            'total' => DichVu::count(),
            'active' => DichVu::where('featured', true)->count(),
            'most_booked' => DichVu::withCount('datLich')
                ->orderBy('dat_lich_count', 'desc')
                ->first(),
            'avg_price' => DichVu::avg('Gia') ?? 0
        ];

        return view('backend.dichvu.index', compact(
            'dichVus', 
            'priceRange', 
            'statistics',
            'request'
        ));
    }

    /**
     * Show the form for creating a new service.
     */
    public function create()
    {
        // Get the next available service ID
        $maxId = DichVu::max('MaDV') ?? 0;
        $nextId = $maxId + 1;
        
        return view('backend.dichvu.create', compact('nextId'));
    }

    /**
     * Store a newly created service in database with image handling.
     */
    public function store(Request $request)
    {
        $request->validate([
            'MaDV' => 'required|integer|unique:DICHVU,MaDV',
            'Tendichvu' => 'required|string|max:255',
            'Gia' => 'required|numeric|min:0',
            'MoTa' => 'nullable|string|max:1000',
            'image_upload' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'Thoigian' => 'required|date_format:H:i',
            'available_days' => 'nullable|array',
            'featured' => 'nullable|boolean',
        ], [
            'MaDV.required' => 'Mã dịch vụ không được để trống.',
            'MaDV.integer' => 'Mã dịch vụ phải là số nguyên.',
            'MaDV.unique' => 'Mã dịch vụ đã tồn tại.',
            'Tendichvu.required' => 'Tên dịch vụ không được để trống.',
            'Tendichvu.max' => 'Tên dịch vụ không được vượt quá 255 ký tự.',
            'Gia.required' => 'Giá không được để trống.',
            'Gia.numeric' => 'Giá phải là số.',
            'Gia.min' => 'Giá không được nhỏ hơn 0.',
            'MoTa.max' => 'Mô tả không được vượt quá 1000 ký tự.',
            'image_upload.image' => 'File phải là hình ảnh.',
            'image_upload.mimes' => 'Định dạng hình ảnh phải là: jpeg, png, jpg, gif.',
            'image_upload.max' => 'Kích thước hình ảnh không được vượt quá 2MB.',
            'Thoigian.required' => 'Thời gian không được để trống.',
            'Thoigian.date_format' => 'Thời gian không đúng định dạng (HH:mm).',
        ]);

        // Handle image upload
        $imagePath = null;
        if ($request->hasFile('image_upload')) {
            $image = $request->file('image_upload');
            $filename = 'dichvu-' . time() . '-' . Str::slug($request->Tendichvu) . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/dichvu'), $filename);
            $imagePath = 'images/dichvu/' . $filename;
        }

        // Chuyển đổi thời gian thành datetime
        $thoigian = Carbon::createFromFormat('H:i', $request->Thoigian);
        $thoigian->setDate(Carbon::now()->year, Carbon::now()->month, Carbon::now()->day);

        // Create the service
        DichVu::create([
            'MaDV' => $request->MaDV,
            'Tendichvu' => $request->Tendichvu,
            'Gia' => $request->Gia,
            'MoTa' => $request->MoTa,
            'Image' => $imagePath ?? $request->Image,
            'Thoigian' => $thoigian,
            'available_days' => $request->has('available_days') ? json_encode($request->available_days) : null,
            'featured' => $request->featured ?? false,
        ]);

        return redirect()->route('admin.dichvu.index')->with('success', 'Thêm dịch vụ thành công!');
    }

    /**
     * Display detailed service information with stats.
     */
    public function show($id)
    {
        $dichVu = DichVu::with(['datLich', 'datLich.user'])->findOrFail($id);
        
        // Get booking statistics
        $bookingStats = DatLich::where('MaDV', $id)
            ->select(
                DB::raw('COUNT(*) as total_bookings'),
                DB::raw('COUNT(CASE WHEN Trangthai_ = "Đã xác nhận" THEN 1 END) as confirmed_bookings'),
                DB::raw('COUNT(CASE WHEN Trangthai_ = "Đã hủy" THEN 1 END) as canceled_bookings')
            )
            ->first();
            
        // Get recent bookings
        $recentBookings = DatLich::with('user')
            ->where('MaDV', $id)
            ->orderBy('Thoigiandatlich', 'desc')
            ->limit(5)
            ->get();
            
        // Calculate popular times
        $popularTimes = DatLich::where('MaDV', $id)
            ->whereNotNull('Thoigiandatlich')
            ->select(DB::raw('HOUR(Thoigiandatlich) as hour'), DB::raw('COUNT(*) as count'))
            ->groupBy('hour')
            ->orderBy('count', 'desc')
            ->limit(3)
            ->get();
            
        return view('backend.dichvu.show', compact('dichVu', 'bookingStats', 'recentBookings', 'popularTimes'));
    }

    /**
     * Show the form for editing the specified service.
     */
    public function edit($id)
    {
        $dichVu = DichVu::findOrFail($id);
        return view('backend.dichvu.edit', compact('dichVu'));
    }

    /**
     * Update the specified service with image handling.
     */
    public function update(Request $request, $id)
    {
        $dichVu = DichVu::findOrFail($id);

        $request->validate([
            'MaDV' => 'required|integer|unique:DICHVU,MaDV,' . $dichVu->MaDV . ',MaDV',
            'Tendichvu' => 'required|string|max:255',
            'Gia' => 'required|numeric|min:0',
            'MoTa' => 'nullable|string|max:1000',
            'image_upload' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'Thoigian' => 'required|date_format:H:i',
            'available_days' => 'nullable|array',
            'featured' => 'nullable|boolean',
        ], [
            'MaDV.required' => 'Mã dịch vụ không được để trống.',
            'MaDV.integer' => 'Mã dịch vụ phải là số nguyên.',
            'MaDV.unique' => 'Mã dịch vụ đã tồn tại.',
            'Tendichvu.required' => 'Tên dịch vụ không được để trống.',
            'Tendichvu.max' => 'Tên dịch vụ không được vượt quá 255 ký tự.',
            'Gia.required' => 'Giá không được để trống.',
            'Gia.numeric' => 'Giá phải là số.',
            'Gia.min' => 'Giá không được nhỏ hơn 0.',
            'MoTa.max' => 'Mô tả không được vượt quá 1000 ký tự.',
            'image_upload.image' => 'File phải là hình ảnh.',
            'image_upload.mimes' => 'Định dạng hình ảnh phải là: jpeg, png, jpg, gif.',
            'image_upload.max' => 'Kích thước hình ảnh không được vượt quá 2MB.',
            'Thoigian.required' => 'Thời gian không được để trống.',
            'Thoigian.date_format' => 'Thời gian không đúng định dạng (HH:mm).',
        ]);

        // Handle image upload
        $imagePath = $dichVu->Image;
        if ($request->hasFile('image_upload')) {
            // Remove old image if it exists
            if ($dichVu->Image && file_exists(public_path($dichVu->Image))) {
                unlink(public_path($dichVu->Image));
            }
            
            $image = $request->file('image_upload');
            $filename = 'dichvu-' . time() . '-' . Str::slug($request->Tendichvu) . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/dichvu'), $filename);
            $imagePath = 'images/dichvu/' . $filename;
        } elseif ($request->Image) {
            $imagePath = $request->Image;
        }

        // Chuyển đổi thời gian thành datetime
        $thoigian = Carbon::createFromFormat('H:i', $request->Thoigian);
        $thoigian->setDate(Carbon::now()->year, Carbon::now()->month, Carbon::now()->day);

        $dichVu->update([
            'MaDV' => $request->MaDV,
            'Tendichvu' => $request->Tendichvu,
            'Gia' => $request->Gia,
            'MoTa' => $request->MoTa,
            'Image' => $imagePath,
            'Thoigian' => $thoigian,
            'available_days' => $request->has('available_days') ? json_encode($request->available_days) : $dichVu->available_days,
            'featured' => $request->has('featured') ? $request->featured : $dichVu->featured,
        ]);

        return redirect()->route('admin.dichvu.index')->with('success', 'Cập nhật dịch vụ thành công!');
    }

    /**
     * Show confirmation page before deleting a service.
     */
    public function confirmDestroy($id)
    {
        $dichVu = DichVu::findOrFail($id);
        $usageCount = DatLich::where('MaDV', $id)->count();
        return view('backend.dichvu.destroy', compact('dichVu', 'usageCount'));
    }

    /**
     * Remove the specified service with checks.
     */
    public function destroy($id)
    {
        $dichVu = DichVu::findOrFail($id);

        // Check if the service is in use
        $usageCount = DatLich::where('MaDV', $id)->count();
        if ($usageCount > 0) {
            return redirect()->route('admin.dichvu.index')
                ->with('error', 'Không thể xóa dịch vụ vì có ' . $usageCount . ' lịch đặt liên quan!');
        }

        try {
            // Delete image file if exists
            if ($dichVu->Image && file_exists(public_path($dichVu->Image))) {
                unlink(public_path($dichVu->Image));
            }
            
            $dichVu->delete();
            return redirect()->route('admin.dichvu.index')->with('success', 'Xóa dịch vụ thành công!');
        } catch (\Exception $e) {
            return redirect()->route('admin.dichvu.index')->with('error', 'Không thể xóa dịch vụ: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified service's status.
     */
    public function updateStatus(Request $request, $id)
    {
        $dichVu = DichVu::findOrFail($id);

        $request->validate([
            'featured' => 'required|boolean',
        ], [
            'featured.required' => 'Vui lòng chọn trạng thái.',
            'featured.boolean' => 'Trạng thái không hợp lệ.',
        ]);

        $dichVu->update([
            'featured' => $request->featured,
        ]);

        return redirect()->route('admin.dichvu.index')->with('success', 'Cập nhật trạng thái dịch vụ thành công!');
    }

    /**
     * Toggle featured status of a service.
     */
    public function toggleFeatured($id)
    {
        $dichVu = DichVu::findOrFail($id);
        $dichVu->featured = !$dichVu->featured;
        $dichVu->save();
        
        return redirect()->back()->with('success', 
            $dichVu->featured ? 'Đã đánh dấu dịch vụ làm nổi bật' : 'Đã bỏ đánh dấu dịch vụ nổi bật');
    }

    /**
     * Get analytics data for services.
     */
    public function analytics()
    {
        // Service booking trends
        $bookingTrends = DatLich::select(
                DB::raw('DATE(Thoigiandatlich) as date'),
                DB::raw('COUNT(*) as bookings'),
                'MaDV'
            )
            ->whereDate('Thoigiandatlich', '>=', now()->subDays(30))
            ->groupBy('date', 'MaDV')
            ->get()
            ->groupBy('MaDV');
            
        // Popular services
        $popularServices = DichVu::withCount('datLich')
            ->orderBy('dat_lich_count', 'desc')
            ->limit(5)
            ->get();
            
        // Revenue by service
        $revenueByService = DB::table('HOADON_VA_THANHTOAN')
            ->join('DATLICH', 'HOADON_VA_THANHTOAN.MaDL', '=', 'DATLICH.MaDL')
            ->join('DICHVU', 'DATLICH.MaDV', '=', 'DICHVU.MaDV')
            ->select('DICHVU.MaDV', 'DICHVU.Tendichvu', DB::raw('SUM(HOADON_VA_THANHTOAN.Tongtien) as total_revenue'))
            ->groupBy('DICHVU.MaDV', 'DICHVU.Tendichvu')
            ->orderBy('total_revenue', 'desc')
            ->get();
            
        return view('backend.dichvu.analytics', compact('bookingTrends', 'popularServices', 'revenueByService'));
    }

    /**
     * Export services data to CSV.
     */
    public function export()
    {
        $filename = "dichvu-export-" . date('Y-m-d-H-i-s') . ".csv";
        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $dichVus = DichVu::all();
        $columns = ['MaDV', 'Tendichvu', 'Gia', 'MoTa', 'Thời gian'];

        $callback = function() use ($dichVus, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($dichVus as $dichVu) {
                fputcsv($file, [
                    $dichVu->MaDV,
                    $dichVu->Tendichvu,
                    number_format($dichVu->Gia, 0, ',', '.') . ' VNĐ',
                    $dichVu->MoTa,
                    $dichVu->Thoigian ? $dichVu->Thoigian->format('H:i') : 'N/A',
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * API endpoint for services data.
     */
    public function apiServices()
    {
        $dichVus = DichVu::where('featured', true)
            ->get()
            ->map(function ($service) {
                return [
                    'id' => $service->MaDV,
                    'name' => $service->Tendichvu,
                    'price' => $service->Gia,
                    'description' => $service->MoTa,
                    'image' => $service->Image ? url($service->Image) : null,
                    'duration' => $service->Thoigian ? $service->Thoigian->format('H:i') : null,
                    'featured' => $service->featured ?? false,
                ];
            });
            
        return response()->json([
            'success' => true,
            'data' => $dichVus,
            'total' => $dichVus->count()
        ]);
    }

    /**
     * Automatically update featured services based on booking frequency and ratings
     * Maximum 10 featured services total
     * 
     * @return \Illuminate\Http\Response
     */
    public function updateFeaturedServices()
    {
        // Get current manually featured services (we'll preserve these)
        $currentFeaturedServices = DichVu::where('featured', true)
            ->pluck('MaDV')
            ->toArray();
        
        // Get the most booked confirmed services
        $mostBookedServices = DatLich::where('Trangthai_', 2) // "Đã xác nhận"
            ->select('MaDV', DB::raw('COUNT(*) as booking_count'))
            ->groupBy('MaDV')
            ->orderBy('booking_count', 'desc')
            ->limit(20) // Get more than we need for scoring
            ->get();
        
        // Get highest rated services
        $highestRatedServices = DB::table('DANHGIA')
            ->join('HOADON_VA_THANHTOAN', 'DANHGIA.MaHD', '=', 'HOADON_VA_THANHTOAN.MaHD')
            ->join('DATLICH', 'HOADON_VA_THANHTOAN.MaDL', '=', 'DATLICH.MaDL')
            ->select('DATLICH.MaDV', DB::raw('AVG(DANHGIA.Danhgiasao) as avg_rating, COUNT(DANHGIA.MaDG) as rating_count'))
            ->groupBy('DATLICH.MaDV')
            ->having('rating_count', '>=', 3) // At least 3 ratings
            ->orderBy('avg_rating', 'desc')
            ->limit(20) // Get more than we need for scoring
            ->get();
        
        // Create a scoring system that combines booking frequency and ratings
        $serviceScores = [];
        
        // Score based on booking frequency (0-50 points)
        $maxBookings = $mostBookedServices->max('booking_count') ?: 1;
        foreach ($mostBookedServices as $service) {
            $bookingScore = ($service->booking_count / $maxBookings) * 50;
            $serviceScores[$service->MaDV] = [
                'id' => $service->MaDV,
                'booking_score' => $bookingScore,
                'rating_score' => 0,
                'total_score' => $bookingScore
            ];
        }
        
        // Score based on ratings (0-50 points)
        $maxRating = 5; // Assuming 5-star rating system
        foreach ($highestRatedServices as $service) {
            $ratingScore = ($service->avg_rating / $maxRating) * 50;
            
            if (isset($serviceScores[$service->MaDV])) {
                $serviceScores[$service->MaDV]['rating_score'] = $ratingScore;
                $serviceScores[$service->MaDV]['total_score'] += $ratingScore;
            } else {
                $serviceScores[$service->MaDV] = [
                    'id' => $service->MaDV,
                    'booking_score' => 0,
                    'rating_score' => $ratingScore,
                    'total_score' => $ratingScore
                ];
            }
        }
        
        // Sort by total score
        uasort($serviceScores, function($a, $b) {
            return $b['total_score'] <=> $a['total_score'];
        });
        
        // Get the top services up to max 10 total
        $featuredServices = [];
        $maxFeatured = 10;
        
        // First, preserve the existing featured services 
        foreach ($currentFeaturedServices as $serviceId) {
            $featuredServices[] = $serviceId;
            if (count($featuredServices) >= $maxFeatured) {
                break;
            }
        }
        
        // Then add top rated/booked services until we reach the maximum
        if (count($featuredServices) < $maxFeatured) {
            foreach ($serviceScores as $serviceId => $score) {
                if (!in_array($serviceId, $featuredServices)) {
                    $featuredServices[] = $serviceId;
                    if (count($featuredServices) >= $maxFeatured) {
                        break;
                    }
                }
            }
        }
        
        // Reset all featured status
        DichVu::query()->update(['featured' => false]);
        
        // Set featured status for top services
        if (!empty($featuredServices)) {
            DichVu::whereIn('MaDV', $featuredServices)
                ->update(['featured' => true]);
        }
        
        return redirect()->route('admin.dichvu.index')
            ->with('success', 'Cập nhật dịch vụ nổi bật thành công! Hiện có ' . count($featuredServices) . ' dịch vụ nổi bật.');
    }
}
