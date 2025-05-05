<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PhuongThuc extends Model
{
    protected $table = 'PHUONGTHUC';
    protected $primaryKey = 'MaPT';
    public $incrementing = false;
    protected $fillable = ['MaPT', 'TenPT', 'Mota'];
    public $timestamps = false;

    public function hoaDon()
    {
        return $this->hasMany(HoaDonVaThanhToan::class, 'MaPT', 'MaPT');
    }
}