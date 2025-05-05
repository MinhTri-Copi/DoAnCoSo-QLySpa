<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'ROLE';
    protected $primaryKey = 'RoleID';
    public $incrementing = false;
    protected $fillable = ['RoleID', 'Tenrole'];
    public $timestamps = false;
    public function accounts()
    {
        return $this->hasMany(Account::class, 'RoleID', 'RoleID');
    }
}