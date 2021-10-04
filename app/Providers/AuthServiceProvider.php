<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
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

        // Gate::after(function ($user, $ability, $result, $arguments) {
        //     if ($user->isAdministrator()) {
        //         return true;
        //     }
        // });

        // Gate::after(function ($user, $ability, $result, $arguments) {
        //     if ($user->isAdministrator()) {
        //         return true;
        //     }
        // });



        $allPermissions = Permission::PERMISSIONS;
        
        foreach($allPermissions as $permission){
            Gate::define($permission["slug"] , function (User $user) use ($permission){
                // return $user->id === $post->user_id;
                
                // TRY TO SOLVE - there is a problem here
                // when ever a gate is defined, it will make a new query
                $userPermissions = $user->roles()->with('permissions')->get()
                ->pluck('permissions')
                ->flatten()
                ->pluck('slug')
                ->toArray();
    
                return in_array($permission["slug"], $userPermissions);
            });
        }

    }
}
