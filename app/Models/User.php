<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

use Illuminate\Support\Facades\Auth;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'budget_id',
        'photo',
        'is_active'
    ];

    protected $appends = ['full_name'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

/*     public function getAllPermissionsAttribute() {
        $permissions = [];
            foreach (Permission::all() as $permission) {
                if (Auth::user()->hasPermissionTo($permission->name)) {
                    $permissions[] = $permission->name;
                }
            }
        return $permissions;
    } */

    protected $table = 'users';

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function budget()
    {
        return $this->belongsTo(Budget::class);
    }

    public function hasPermission(string $permissionName)
    {
        return null !== $this->permissions()->where('name', $permissionName)->first();
    }

    // Accessors
    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    // Mutators
    public function setFirstNameAttribute($value)
    {
        $this->attributes['first_name'] = ucfirst(strtolower($value));
        
    }

    public function setLastNameAttribute($value)
    {
        $this->attributes['last_name'] = ucfirst(strtolower($value));
        
    }

}
