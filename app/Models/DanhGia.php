<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DanhGia extends Model
{
    protected $table = 'DANHGIA';
    protected $primaryKey = 'MaDG';
    public $incrementing = false;
    protected $fillable = ['MaDG', 'Danhgiasao', 'Nhanxet', 'Ngaydanhgia', 'Manguoidung', 'MaHD'];
    public $timestamps = false; // Thêm dòng này để tắt timestamps

    public function user()
    {
        return $this->belongsTo(User::class, 'Manguoidung', 'Manguoidung');
    }

    public function hoaDon()
    {
        return $this->belongsTo(HoaDonVaThanhToan::class, 'MaHD', 'MaHD');
    }
}