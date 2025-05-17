<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\DichVu;
use Illuminate\Http\Request;

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
        
        // Apply search filter if provided
        if ($request->has('search') && $request->search != '') {
            $query->search($request->search);
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
                default:
                    $query->orderBy('MaDV', 'desc');
                    break;
            }
        } else {
            // Default sorting
            $query->orderBy('MaDV', 'desc');
        }
        
        $services = $query->paginate(12);
        
        // Get min and max prices for filter
        $minServicePrice = DichVu::min('Gia');
        $maxServicePrice = DichVu::max('Gia');
        
        // Pass booking route to view for "Đặt lịch" buttons
        $bookingRoute = route('customer.datlich.create');
        
        return view('customer.dichvu.index', compact('services', 'minServicePrice', 'maxServicePrice', 'bookingRoute'));
    }

    /**
     * Display the specified service.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $service = DichVu::with(['danhGia.user'])->findOrFail($id);
        
        // Get related services (same price range or similar services)
        $relatedServices = DichVu::where('MaDV', '!=', $id)
            ->whereBetween('Gia', [$service->Gia * 0.8, $service->Gia * 1.2])
            ->limit(4)
            ->get();
            
        // Generate booking URL for this service
        $bookingUrl = route('customer.datlich.create', ['service_id' => $service->MaDV]);
        
        return view('customer.dichvu.show', compact('service', 'relatedServices', 'bookingUrl'));
    }
    
    // Other methods remain the same...
}