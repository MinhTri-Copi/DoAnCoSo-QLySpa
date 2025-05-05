<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PendingAccount extends Model
{
    protected $table = 'pending_accounts';
    protected $fillable = [
        'Tendangnhap', 'Matkhau', 'RoleID', 'Hoten', 'SDT', 'DiaChi',
        'Email', 'Ngaysinh', 'Gioitinh', 'token'
    ];
}