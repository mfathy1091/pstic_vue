<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        $permissions = [
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

        foreach($permissions as $n){
            Permission::create(['name' => $n]);
        }


        $adminRole = Role::create(['name' => 'admin']);
        $userRole = Role::create(['name' => 'worker']);


        $allPermissions = Permission::all();

        $adminRole->permissions()->sync($allPermissions);
        $adminUser = User::find(1);

        $adminUser->roles()->sync([$adminRole->id, $userRole->id]);

     
    }
}