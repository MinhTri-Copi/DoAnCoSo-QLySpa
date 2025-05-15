<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DichVu extends Model
{
    protected $table = 'DICHVU';
    protected $primaryKey = 'MaDV';
    public $incrementing = false;
    protected $fillable = ['MaDV', 'Tendichvu', 'Gia', 'MoTa'];
    public $timestamps = false;

    public function datLich()
    {
        return $this->hasMany(DatLich::class, 'MaDV', 'MaDV');
    }
}