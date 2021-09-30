<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public const PERMISSIONS = [
        'user_list',
        'user_create',
        'user_edit',
        'user_delete',

        'role_list',
        'role_create',
        'role_edit',
        'role_delete',
        'pss-menu',
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    
}
