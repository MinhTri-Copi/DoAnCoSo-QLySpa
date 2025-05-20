<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\QuangCao;
use App\Models\AdStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class QuangCaoController extends Controller
{
    /**
     * Display a listing of the advertisements for customers.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Only get active advertisements with a valid date range
        $query = QuangCao::where('MaTTQC', 1) // Assuming 1 is active status
            ->whereDate('Ngaybatdau', '<=', Carbon::now())
            ->whereDate('Ngayketthuc', '>=', Carbon::now());
            
        // Filter by ad type if provided
        if ($request->has('type') && $request->type != '') {
            $query->where('Loaiquangcao', $request->type);
        }
        
        // Sort advertisements
        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'date_asc':
                    $query->orderBy('Ngaybatdau', 'asc');
                    break;
                case 'date_desc':
                    $query->orderBy('Ngaybatdau', 'desc');
                    break;
                case 'priority':
                    $query->orderBy('Douutien', 'desc');
                    break;
                default:
                    $query->orderBy('Douutien', 'desc')->orderBy('Ngaybatdau', 'desc');
                    break;
            }
        } else {
            // Default sort by priority and recent first
            $query->orderBy('Douutien', 'desc')->orderBy('Ngaybatdau', 'desc');
        }
        
        $advertisements = $query->paginate(9);
        
        // Get ad types for filter
        $adTypes = [
            'Promotion' => 'Khuyến mãi',
            'New Service' => 'Dịch vụ mới',
            'Event' => 'Sự kiện',
            'General' => 'Thông tin chung',
        ];
        
        return view('customer.quangcao.index', compact('advertisements', 'adTypes'));
    }
    
    /**
     * Display the specified advertisement.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Get the advertisement with active status and valid date
        $advertisement = QuangCao::where('MaQC', $id)
            ->where('MaTTQC', 1)
            ->whereDate('Ngaybatdau', '<=', Carbon::now())
            ->whereDate('Ngayketthuc', '>=', Carbon::now())
            ->firstOrFail();
            
        // Get related ads (same type)
        $relatedAds = QuangCao::where('MaQC', '!=', $id)
            ->where('Loaiquangcao', $advertisement->Loaiquangcao)
            ->where('MaTTQC', 1)
            ->whereDate('Ngaybatdau', '<=', Carbon::now())
            ->whereDate('Ngayketthuc', '>=', Carbon::now())
            ->orderBy('Douutien', 'desc')
            ->limit(3)
            ->get();
            
        // Increment view count
        $advertisement->increment('Luotxem');
        
        return view('customer.quangcao.show', compact('advertisement', 'relatedAds'));
    }
    
    /**
     * Get featured advertisements for homepage.
     *
     * @param  int  $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getFeaturedAds($limit = 3)
    {
        // Cache this query for 30 minutes to improve performance
        return Cache::remember('featured_ads_' . $limit, 30, function () use ($limit) {
            return QuangCao::where('MaTTQC', 1)
                ->where('Douutien', '>=', 3) // High priority ads (assuming priority scale 1-5)
                ->whereDate('Ngaybatdau', '<=', Carbon::now())
                ->whereDate('Ngayketthuc', '>=', Carbon::now())
                ->orderBy('Douutien', 'desc')
                ->orderBy('Ngaybatdau', 'desc')
                ->limit($limit)
                ->get();
        });
    }
    
    /**
     * Get promotional advertisements.
     *
     * @param  int  $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getPromotionAds($limit = 4)
    {
        // Cache this query for 30 minutes to improve performance
        return Cache::remember('promotion_ads_' . $limit, 30, function () use ($limit) {
            return QuangCao::where('MaTTQC', 1)
                ->where('Loaiquangcao', 'Promotion')
                ->whereDate('Ngaybatdau', '<=', Carbon::now())
                ->whereDate('Ngayketthuc', '>=', Carbon::now())
                ->orderBy('Douutien', 'desc')
                ->orderBy('Ngaybatdau', 'desc')
                ->limit($limit)
                ->get();
        });
    }
    
    /**
     * Get new service advertisements.
     *
     * @param  int  $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getNewServiceAds($limit = 2)
    {
        // Cache this query for 30 minutes to improve performance
        return Cache::remember('new_service_ads_' . $limit, 30, function () use ($limit) {
            return QuangCao::where('MaTTQC', 1)
                ->where('Loaiquangcao', 'New Service')
                ->whereDate('Ngaybatdau', '<=', Carbon::now())
                ->whereDate('Ngayketthuc', '>=', Carbon::now())
                ->orderBy('Douutien', 'desc')
                ->orderBy('Ngaybatdau', 'desc')
                ->limit($limit)
                ->get();
        });
    }
    
    /**
     * Get event advertisements.
     *
     * @param  int  $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getEventAds($limit = 2)
    {
        // Cache this query for 30 minutes to improve performance
        return Cache::remember('event_ads_' . $limit, 30, function () use ($limit) {
            return QuangCao::where('MaTTQC', 1)
                ->where('Loaiquangcao', 'Event')
                ->whereDate('Ngaybatdau', '<=', Carbon::now())
                ->whereDate('Ngayketthuc', '>=', Carbon::now())
                ->orderBy('Douutien', 'desc')
                ->orderBy('Ngaybatdau', 'desc')
                ->limit($limit)
                ->get();
        });
    }
}