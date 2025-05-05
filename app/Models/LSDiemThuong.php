<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LSDiemThuong extends Model
{
    protected $table = 'LSDIEMTHUONG';
    protected $primaryKey = 'MaLSDT';
    public $incrementing = false;
    protected $fillable = ['MaLSDT', 'Thoigian', 'Sodiem', 'Manguoidung', 'MaHD'];
    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class, 'Manguoidung', 'Manguoidung');
    }

    public function hoaDon()
    {
        return $this->belongsTo(HoaDonVaThanhToan::class, 'MaHD', 'MaHD');
    }
}