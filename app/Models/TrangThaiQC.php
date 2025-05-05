<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrangThaiQC extends Model
{
    protected $table = 'TRANGTHAIQC';
    protected $primaryKey = 'MaTTQC';
    public $incrementing = false;
    protected $fillable = ['MaTTQC', 'TenTT'];
    public $timestamps = false;

    
    public function quangCao()
    {
        return $this->hasMany(QuangCao::class, 'MaTTQC', 'MaTTQC');
    }
}