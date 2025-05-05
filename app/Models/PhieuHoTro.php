<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PhieuHoTro extends Model
{
    protected $table = 'PHIEUHOTRO';
    protected $primaryKey = 'MaphieuHT';
    public $incrementing = false;
    protected $fillable = ['MaphieuHT', 'Noidungyeucau', 'Matrangthai', 'MaPTHT', 'Manguoidung'];
    public $timestamps = false;

    public function trangThai()
    {
        return $this->belongsTo(TrangThai::class, 'Matrangthai', 'Matrangthai');
    }

    public function ptHoTro()
    {
        return $this->belongsTo(PTHoTro::class, 'MaPTHT', 'MaPTHT');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'Manguoidung', 'Manguoidung');
    }
}