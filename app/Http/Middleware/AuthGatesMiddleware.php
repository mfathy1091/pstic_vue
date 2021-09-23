<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

class AuthGatesMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        //$user = auth()->guard('api')->user();
        // Auth::guard('api')->user
        $user = auth()->user();

        if($user) {
            // $roles = Role::with('permissions')->get();
            // $permissionsArray = [];
            // $allPermissions = Permission::all();

            // $userPermissions = $user->roles()->with('permissions')->get()
            // ->pluck('permissions')
            // ->flatten()
            // ->pluck('name')
            // ->toArray();

            //dd($userPermissions);

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
            //     dd($permission->name);
            //     Gate::define($permission->name, function ($permission, $userPermissions)
            //     {
                    
            //         if(in_array($permission->name, $userPermissions)){
            //             return true;
            //             dd(true);
            //         }else{
            //             return false;
            //             dd(false);
            //         }
            //     });
            // }
            
            
            // Gate::define('user_list', function($user) use($userPermissions){
            //     return true;
            //     dd(something)
            // });
            


            
        }
        return $next($request);
        
    }
}
