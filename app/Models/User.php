<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'USER';
    protected $primaryKey = 'Manguoidung';
    public $incrementing = false;
    protected $fillable = ['Manguoidung', 'MaTK', 'Hoten', 'SDT', 'DiaChi', 'Email', 'Ngaysinh', 'Gioitinh'];
    public $timestamps = false;

    public function account()
    {
        return $this->belongsTo(Account::class, 'MaTK', 'MaTK');
    }

    public function datLich()
    {
        return $this->hasMany(DatLich::class, 'Manguoidung', 'Manguoidung');
    }

    public function danhGia()
    {
        return $this->hasMany(DanhGia::class, 'Manguoidung', 'Manguoidung');
    }

    public function hoaDon()
    {
        return $this->hasMany(HoaDonVaThanhToan::class, 'Manguoidung', 'Manguoidung');
    }

    public function hangThanhVien()
    {
        return $this->hasMany(HangThanhVien::class, 'Manguoidung', 'Manguoidung');
    }

    public function phieuHoTro()
    {
        return $this->hasMany(PhieuHoTro::class, 'Manguoidung', 'Manguoidung');
    }

    public function quangCao()
    {
        return $this->hasMany(QuangCao::class, 'Manguoidung', 'Manguoidung');
    }

    public function lsDiemThuong()
    {
        return $this->hasMany(LSDiemThuong::class, 'Manguoidung', 'Manguoidung');
    }
}