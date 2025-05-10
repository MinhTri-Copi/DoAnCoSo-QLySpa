<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DichVu extends Model
{
    protected $table = 'DICHVU';
    protected $primaryKey = 'MaDV';
    public $incrementing = false;
    protected $fillable = ['MaDV', 'Tendichvu', 'Gia', 'MoTa', 'Image', 'Thoigian', 'Matrangthai'];
    public $timestamps = false;

    protected $casts = [
        'Thoigian' => 'datetime',
        'Gia' => 'decimal:4'
    ];

    public function datLich()
    {
        return $this->hasMany(DatLich::class, 'MaDV', 'MaDV');
    }
    
    public function trangThai()
    {
        return $this->belongsTo(TrangThai::class, 'Matrangthai', 'Matrangthai');
    }
}