<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DatLich extends Model
{
    protected $table = 'DATLICH';
    protected $primaryKey = 'MaDL';
    public $incrementing = false;
    protected $fillable = ['MaDL', 'Manguoidung', 'Thoigiandatlich', 'Trangthai_', 'MaDV'];
    public $timestamps = false; // Thêm dòng này để tắt timestamps

    public function user()
    {
        return $this->belongsTo(User::class, 'Manguoidung', 'Manguoidung');
    }

    public function dichVu()
    {
        return $this->belongsTo(DichVu::class, 'MaDV', 'MaDV');
    }

    public function hoaDon()
    {
        return $this->hasMany(HoaDonVaThanhToan::class, 'MaDL', 'MaDL');
    }
}