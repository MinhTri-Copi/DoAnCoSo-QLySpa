<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\QuangCao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class QuangCaoController extends Controller
{
    /**
     * Display a listing of the advertisements for customers.
     */
    public function index(Request $request)
    {
        // Get all advertisements without date and status filtering
        $query = QuangCao::query();
            
        // Filter by ad type if provided
        if ($request->has('type') && $request->type != '') {
            $query->where('Loaiquangcao', $request->type);
        }
        
        // Sort advertisements by date
        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'date_asc':
                    $query->orderBy('Ngaybatdau', 'asc');
                    break;
                case 'date_desc':
                    $query->orderBy('Ngaybatdau', 'desc');
                    break;
                default:
                    $query->orderBy('Ngaybatdau', 'desc');
                    break;
            }
        } else {
            $query->orderBy('Ngaybatdau', 'desc');
        }
        
        $advertisements = $query->paginate(9);
        
        // Get ad types for filter
        $adTypes = [
            'Khuyến mãi' => 'Khuyến mãi',
            'Sự kiện' => 'Sự kiện',
            'Thông báo' => 'Thông báo'
        ];
        
        return view('customer.quangcao.index', compact('advertisements', 'adTypes'));
    }
    
    /**
     * Display the specified advertisement.
     */
    public function show($id)
    {
        $advertisement = QuangCao::where('MaQC', $id)->firstOrFail();
            
        $relatedAds = QuangCao::where('MaQC', '!=', $id)
            ->where('Loaiquangcao', $advertisement->Loaiquangcao)
            ->orderBy('Ngaybatdau', 'desc')
            ->limit(3)
            ->get();
            
        return view('customer.quangcao.show', compact('advertisement', 'relatedAds'));
    }
    
    /**
     * Display featured advertisements page.
     */
    public function getFeaturedAds(Request $request)
    {
        $query = QuangCao::query()->orderBy('Ngaybatdau', 'desc');
            
        $advertisements = $query->paginate(12);
        
        return view('customer.quangcao.featured', compact('advertisements'));
    }
    
    /**
     * Display promotional advertisements page.
     */
    public function getPromotionAds(Request $request)
    {
        $query = QuangCao::where('Loaiquangcao', 'Khuyến mãi');
            
        // Sort options
        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'date_asc':
                    $query->orderBy('Ngaybatdau', 'asc');
                    break;
                case 'date_desc':
                    $query->orderBy('Ngaybatdau', 'desc');
                    break;
                default:
                    $query->orderBy('Ngaybatdau', 'desc');
                    break;
            }
        } else {
            $query->orderBy('Ngaybatdau', 'desc');
        }
        
        $advertisements = $query->paginate(12);
        
        return view('customer.quangcao.promotions', compact('advertisements'));
    }
    
    /**
     * Display event advertisements page.
     */
    public function getEventAds(Request $request)
    {
        $query = QuangCao::where('Loaiquangcao', 'Sự kiện');
            
        // Sort options
        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'date_asc':
                    $query->orderBy('Ngaybatdau', 'asc');
                    break;
                case 'date_desc':
                    $query->orderBy('Ngaybatdau', 'desc');
                    break;
                default:
                    $query->orderBy('Ngaybatdau', 'desc');
                    break;
            }
        } else {
            $query->orderBy('Ngaybatdau', 'desc');
        }
        
        $advertisements = $query->paginate(12);
        
        return view('customer.quangcao.events', compact('advertisements'));
    }
    
    /**
     * Display notification advertisements page.
     */
    public function getNotificationAds(Request $request)
    {
        $query = QuangCao::where('Loaiquangcao', 'Thông báo');
            
        // Sort options
        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'date_asc':
                    $query->orderBy('Ngaybatdau', 'asc');
                    break;
                case 'date_desc':
                    $query->orderBy('Ngaybatdau', 'desc');
                    break;
                default:
                    $query->orderBy('Ngaybatdau', 'desc');
                    break;
            }
        } else {
            $query->orderBy('Ngaybatdau', 'desc');
        }
        
        $advertisements = $query->paginate(12);
        
        return view('customer.quangcao.notifications', compact('advertisements'));
    }
    
    /**
     * Get featured advertisements data for API/AJAX calls.
     */
    public function getFeaturedAdsData($limit = 3)
    {
        return Cache::remember('featured_ads_' . $limit, 30, function () use ($limit) {
            return QuangCao::orderBy('Ngaybatdau', 'desc')
                ->limit($limit)
                ->get();
        });
    }
    
    /**
     * Get promotion advertisements data for API/AJAX calls.
     */
    public function getPromotionAdsData($limit = 3)
    {
        return Cache::remember('promotion_ads_' . $limit, 30, function () use ($limit) {
            return QuangCao::where('Loaiquangcao', 'Khuyến mãi')
                ->orderBy('Ngaybatdau', 'desc')
                ->limit($limit)
                ->get();
        });
    }
    
    /**
     * Get event advertisements data for API/AJAX calls.
     */
    public function getEventAdsData($limit = 3)
    {
        return Cache::remember('event_ads_' . $limit, 30, function () use ($limit) {
            return QuangCao::where('Loaiquangcao', 'Sự kiện')
                ->orderBy('Ngaybatdau', 'desc')
                ->limit($limit)
                ->get();
        });
    }
}