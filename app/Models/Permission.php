<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug', 'name',
    ];

    public const PERMISSIONS = [
        [
            'slug' => 'user_list',
            'name' => 'Show Users',
        ],
        [
            'slug' => 'user_create',
            'name' => 'Create Users',
        ],
        [
            'slug' => 'user_edit',
            'name' => 'Edit Users',
        ],
        [
            'slug' => 'user_delete',
            'name' => 'Delete Users',
        ],

        [
            'slug' => 'role_list',
            'name' => 'Show Roles',
        ],
        [
            'slug' => 'role_create',
            'name' => 'Create Roles',
        ],
        [
            'slug' => 'role_edit',
            'name' => 'Edit Roles',
        ],
        [
            'slug' => 'role_delete',
            'name' => 'Delete Roles',
        ],

        [
            'slug' => 'pss-menu',
            'name' => 'Show PSS Menu',
        ],
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    
}
