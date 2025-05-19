<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class DichVu extends Model
{
    protected $table = 'DICHVU';
    protected $primaryKey = 'MaDV';
    public $incrementing = false;
    protected $fillable = [
        'MaDV', 
        'Tendichvu', 
        'Gia', 
        'MoTa', 
        'Image', 
        'Thoigian', 
        'available_days',
        'featured'
    ];
    public $timestamps = false;

    protected $casts = [
        'Thoigian' => 'datetime',
        'Gia' => 'decimal:4',
        'featured' => 'boolean',
        'MaDV' => 'integer'
    ];

    /**
     * Get all bookings for this service
     */
    public function datLich()
    {
        return $this->hasMany(DatLich::class, 'MaDV', 'MaDV');
    }
    
    /**
     * Lấy đánh giá cho dịch vụ thông qua chuỗi quan hệ:
     * DichVu → DatLich → HoaDonVaThanhToan → DanhGia
     */
    public function danhGia()
    {
        // Không sử dụng quan hệ trực tiếp vì không có MaDV trong bảng DANHGIA
        // Thay vào đó lấy đánh giá thông qua mối quan hệ với đặt lịch và hóa đơn
        return DanhGia::whereIn('MaHD', function($query) {
            $query->select('MaHD')
                ->from('HOADON_VA_THANHTOAN')
                ->whereIn('MaDL', function($subQuery) {
                    $subQuery->select('MaDL')
                        ->from('DATLICH')
                        ->where('MaDV', $this->MaDV);
                });
        });
    }

    /**
     * Get bookings with their invoices and ratings
     */
    public function datLichWithRatings() 
    {
        return $this->datLich()
            ->with(['hoaDonVaThanhToan' => function($query) {
                $query->with('danhGia');
            }]);
    }

    /**
     * Get the average rating for this service
     */
    public function getAverageRatingAttribute()
    {
        $ratings = DanhGia::whereIn('MaHD', function($query) {
            $query->select('MaHD')
                ->from('HOADON_VA_THANHTOAN')
                ->whereIn('MaDL', function($subQuery) {
                    $subQuery->select('MaDL')
                        ->from('DATLICH')
                        ->where('MaDV', $this->MaDV);
                });
        })->get();

        if ($ratings->count() > 0) {
            return round($ratings->avg('Danhgiasao'), 1);
        }
        
        return 0;
    }

    /**
     * Determine if service is available on a specific day
     */
    public function isAvailableOn($day)
    {
        $days = json_decode($this->available_days ?? '[]', true);
        return in_array(strtolower($day), $days);
    }

    /**
     * Format the price with VND currency
     */
    public function getFormattedPriceAttribute()
    {
        return number_format($this->Gia, 0, ',', '.') . ' VNĐ';
    }

    /**
     * Check if this service is popular (has many bookings)
     */
    public function getIsPopularAttribute()
    {
        return $this->datLich->count() > 5;
    }

    /**
     * Scope a query to only include featured services
     */
    public function scopeFeatured($query)
    {
        return $query->where('featured', true);
    }

    /**
     * Scope a query to filter by price range
     */
    public function scopePriceRange($query, $min, $max)
    {
        if ($min) {
            $query->where('Gia', '>=', $min);
        }
        
        if ($max) {
            $query->where('Gia', '<=', $max);
        }
        
        return $query;
    }

    /**
     * Scope a query to search by name or description
     */
    public function scopeSearch($query, $search)
    {
        if ($search) {
            return $query->where('Tendichvu', 'like', "%{$search}%")
                ->orWhere('MoTa', 'like', "%{$search}%");
        }
        
        return $query;
    }
}