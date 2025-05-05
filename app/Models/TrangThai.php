<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrangThai extends Model
{
    protected $table = 'TRANGTHAI';
    protected $primaryKey = 'Matrangthai';
    public $incrementing = false;
    protected $fillable = ['Matrangthai', 'Tentrangthai'];
    public $timestamps = false;

    public function hoaDon()
    {
        return $this->hasMany(HoaDonVaThanhToan::class, 'Matrangthai', 'Matrangthai');
    }

    public function phieuHoTro()
    {
        return $this->hasMany(PhieuHoTro::class, 'Matrangthai', 'Matrangthai');
    }
}