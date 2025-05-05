<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrangThaiPhong extends Model
{
    protected $table = 'TRANGTHAIPHONG';
    protected $primaryKey = 'MatrangthaiP';
    public $incrementing = false;
    protected $fillable = ['MatrangthaiP', 'Tentrangthai'];
    public $timestamps = false;
    public function phong()
    {
        return $this->hasMany(Phong::class, 'MatrangthaiP', 'MatrangthaiP');
    }
}