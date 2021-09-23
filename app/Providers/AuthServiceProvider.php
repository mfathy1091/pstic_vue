<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\Passport;
use App\Models\Role;
use App\Models\Permission;
use App\Models\User;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Passport::routes();

        // $user = auth()->user();
        $user = Auth::guard('api')->user();



        $permissions = [
            'user_list',
            'user_create',
            'user_edit',
            'user_delete',

            'role_list',
            'role_create',
            'role_edit',
            'role_delete',
        ];

        $userPermissions = [
            'user_list',
            'user_create',
            'user_edit',
            'user_delete',

            'role_list',
            'role_create',
            'role_edit',
            'role_delete',
        ];

        // Gate::define('user_list', function($user){
        //     return true;
        // });






        if($user==null) {

            $permissionName = 'user_list';
            Gate::define($permissionName, function ($user) use ($permissionName, $userPermissions){
                return in_array($permissionName, $userPermissions);
            });

            // $roles = Role::with('permissions')->get();
            // $permissionsArray = [];
            // $allPermissions = Permission::all();

            // $userPermissions = $user->roles()->with('permissions')->get()
            // ->pluck('permissions')
            // ->flatten()
            // ->pluck('name')
            // ->toArray();

            // dd($userPermissions);

            // foreach($roles as $role){
            //     foreach($role->permissions as $permission){
            //         $permissionsArray[$permission->name][] = $role
            //     }
            // }

            // foreach($permissionsArray as $name => $roles){
            //     Gate::define($name, function(User $user) use ($user){
            //         return count(array_intersect($user->roles->pluck))
            //     });
            // }

            // foreach($allPermissions as $permission){
            //     Gate::define($permission->name, function ($permission, $userPermissions)
            //     {
                    
            //         if(in_array($permission->name, $userPermissions)){
            //             return true;
            //         }else{
            //             return false;
            //         }
            //     });
            // }


            // Gate::define('user_list', function($user) use($userPermissions){
            //     return true;
            // });

        }

    }
}
