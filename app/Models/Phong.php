<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Phong extends Model
{
    protected $table = 'PHONG';
    protected $primaryKey = 'Maphong';
    public $incrementing = false;
    protected $fillable = ['Maphong', 'Tenphong', 'Loaiphong', 'MatrangthaiP'];
    public $timestamps = false;

    public function trangThaiPhong()
    {
        return $this->belongsTo(TrangThaiPhong::class, 'MatrangthaiP', 'MatrangthaiP');
    }

    public function hoaDon()
    {
        return $this->hasMany(HoaDonVaThanhToan::class, 'Maphong', 'Maphong');
    }
}