<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HangThanhVien extends Model
{
    protected $table = 'HANGTHANHVIEN';
    protected $primaryKey = 'Mahang';
    public $incrementing = false;
    protected $fillable = ['Mahang', 'Tenhang', 'Mota', 'Manguoidung'];
    public $timestamps = false; // Thêm dòng này để tắt timestamps

    public function user()
    {
        return $this->belongsTo(User::class, 'Manguoidung', 'Manguoidung');
    }
}