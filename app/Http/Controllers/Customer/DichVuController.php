<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\DichVu;
use App\Models\DanhGia;
use App\Models\DatLich;
use App\Models\HoaDonVaThanhToan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DichVuController extends Controller
{
    /**
     * Display a listing of the services.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = DichVu::query();
        
        // Thêm phương thức local scope cho model
        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('Tendichvu', 'like', '%' . $searchTerm . '%')
                  ->orWhere('MoTa', 'like', '%' . $searchTerm . '%');
            });
        }
        
        // Apply price range filter if provided
        if ($request->has('min_price') && $request->min_price != '') {
            $minPrice = (float) $request->min_price;
            $query->where('Gia', '>=', $minPrice);
        }
        
        if ($request->has('max_price') && $request->max_price != '') {
            $maxPrice = (float) $request->max_price;
            $query->where('Gia', '<=', $maxPrice);
        }
        
        // Lọc theo nổi bật nếu có
        if ($request->has('featured') && $request->featured == '1') {
            $query->where('featured', true);
        }
        
        // Apply sorting
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
                case 'newest':
                    $query->orderBy('MaDV', 'desc');
                    break;
                case 'popular':
                    $query->withCount('datLich')
                          ->orderBy('dat_lich_count', 'desc');
                    break;
                default:
                    $query->orderBy('MaDV', 'desc');
                    break;
            }
        } else {
            // Default sorting
            $query->orderBy('MaDV', 'desc');
        }
        
        $services = $query->paginate(12)->withQueryString();
        
        // Get min and max prices for filter
        $minServicePrice = DichVu::min('Gia');
        $maxServicePrice = DichVu::max('Gia');
        
        // Pass booking route to view for "Đặt lịch" buttons
        $bookingRoute = route('customer.datlich.create');
        
        return view('customer.dichvu.index', compact(
            'services', 
            'minServicePrice', 
            'maxServicePrice', 
            'bookingRoute'
        ));
    }

    /**
     * Display the specified service.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Lấy thông tin dịch vụ
        $service = DichVu::findOrFail($id);
        
        // Lấy danh sách đánh giá cho dịch vụ thông qua chuỗi quan hệ
        $reviews = DB::table('DANHGIA')
            ->join('HOADON_VA_THANHTOAN', 'DANHGIA.MaHD', '=', 'HOADON_VA_THANHTOAN.MaHD')
            ->join('DATLICH', 'HOADON_VA_THANHTOAN.MaDL', '=', 'DATLICH.MaDL')
            ->join('USER', 'DANHGIA.Manguoidung', '=', 'USER.Manguoidung')
            ->where('DATLICH.MaDV', $id)
            ->select('DANHGIA.*', 'USER.Hoten', 'USER.Manguoidung')
            ->get();
        
        // Get related services (similar price range)
        $relatedServices = DichVu::where('MaDV', '!=', $id)
            ->whereBetween('Gia', [$service->Gia * 0.8, $service->Gia * 1.2])
            ->limit(4)
            ->get();
            
        // Generate booking URL for this service
        $bookingUrl = route('customer.datlich.create', ['service_id' => $service->MaDV]);
        
        return view('customer.dichvu.show', compact('service', 'relatedServices', 'bookingUrl', 'reviews'));
    }
    
    /**
     * Lấy danh sách dịch vụ nổi bật
     * 
     * @return \Illuminate\Http\Response
     */
    public function getFeatured()
    {
        $featuredServices = DichVu::where('featured', true)
                                 ->take(6)
                                 ->get();
                                 
        return response()->json($featuredServices);
    }
    
    /**
     * Tìm kiếm dịch vụ với AJAX
     * 
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $searchTerm = $request->input('q');
        $services = DichVu::where('Tendichvu', 'like', '%' . $searchTerm . '%')
                         ->take(5)
                         ->get(['MaDV', 'Tendichvu', 'Gia', 'Image as Hinhanh']);
        
        return response()->json($services);
    }
    
    /**
     * Kiểm tra thời gian có sẵn của dịch vụ (cho booking calendar)
     * 
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function checkAvailability(Request $request)
    {
        $serviceId = $request->input('service_id');
        $date = $request->input('date');
        
        // Xử lý logic kiểm tra lịch trống ở đây
        
        // Trả về kết quả JSON
        return response()->json([
            'available_slots' => ['08:00', '09:00', '10:00', '14:00', '15:00', '16:00'],
            'booked_slots' => ['11:00', '13:00']
        ]);
    }
}