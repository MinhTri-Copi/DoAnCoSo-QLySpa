<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Account extends Authenticatable
{
    use Notifiable;

    protected $table = 'ACCOUNT';
    protected $primaryKey = 'MaTK';
    public $incrementing = false;
    protected $fillable = ['MaTK', 'RoleID', 'Tendangnhap', 'Matkhau'];
    
    public $timestamps = false; // Thêm dòng này để tắt timestamps

    public function role()
    {
        return $this->belongsTo(Role::class, 'RoleID', 'RoleID');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'MaTK', 'MaTK');
    }

    public function getAuthPassword()
    {
        return $this->Matkhau;
    }
}