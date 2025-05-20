<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuangCao extends Model
{
    protected $table = 'QUANGCAO';
    protected $primaryKey = 'MaQC';
    public $incrementing = false;
    protected $fillable = ['MaQC', 'Tieude', 'Noidung', 'Image', 'Loaiquangcao', 'Ngaybatdau', 'Ngayketthuc', 'MaTTQC', 'Manguoidung', 'MaDV'];
    public $timestamps = false;

    public function trangThaiQC()
    {
        return $this->belongsTo(TrangThaiQC::class, 'MaTTQC', 'MaTTQC');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'Manguoidung', 'Manguoidung');
    }

    public function dichVu()
    {
        return $this->belongsTo(DichVu::class, 'MaDV', 'MaDV');
    }
}