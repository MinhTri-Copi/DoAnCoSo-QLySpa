<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HoaDonVaThanhToan extends Model
{
    protected $table = 'HOADON_VA_THANHTOAN';
    protected $primaryKey = 'MaHD';
    public $incrementing = false;
    protected $fillable = ['MaHD', 'Ngaythanhtoan', 'Tongtien', 'MaDL', 'Manguoidung', 'Maphong', 'MaPT', 'Matrangthai'];
    public $timestamps = false; // Thêm dòng này để tắt timestamps

    public function datLich()
    {
        return $this->belongsTo(DatLich::class, 'MaDL', 'MaDL');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'Manguoidung', 'Manguoidung');
    }

    public function phong()
    {
        return $this->belongsTo(Phong::class, 'Maphong', 'Maphong');
    }

    public function phuongThuc()
    {
        return $this->belongsTo(PhuongThuc::class, 'MaPT', 'MaPT');
    }

    public function trangThai()
    {
        return $this->belongsTo(TrangThai::class, 'Matrangthai', 'Matrangthai');
    }

    public function danhGia()
    {
        return $this->hasMany(DanhGia::class, 'MaHD', 'MaHD');
    }

    public function lsDiemThuong()
    {
        return $this->hasMany(LSDiemThuong::class, 'MaHD', 'MaHD');
    }
}