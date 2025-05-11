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
     * Get ratings for this service
     */
    public function danhGia()
    {
        return $this->hasMany(DanhGia::class, 'MaDV', 'MaDV');
    }

    /**
     * Get the average rating for this service
     */
    public function getAverageRatingAttribute()
    {
        if ($this->danhGia && $this->danhGia->count() > 0) {
            return round($this->danhGia->avg('rating'), 1);
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