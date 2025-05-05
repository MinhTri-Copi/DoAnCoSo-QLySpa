<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PTHoTro extends Model
{
    protected $table = 'PTHOTRO';
    protected $primaryKey = 'MaPTHT';
    public $incrementing = false;
    protected $fillable = ['MaPTHT', 'TenPT'];
    public $timestamps = false;

    public function phieuHoTro()
    {
        return $this->hasMany(PhieuHoTro::class, 'MaPTHT', 'MaPTHT');
    }
}