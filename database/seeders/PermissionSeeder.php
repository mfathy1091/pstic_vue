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
        ];

        foreach($permissions as $n){
            Permission::create(['name' => $n]);
        }


        $adminRole = Role::create(['name' => 'admin']);
        $userRole = Role::create(['name' => 'user']);


        $permissions = Permission::all();

        $adminRole->permissions()->sync($permissions);
        $adminUser = User::find(1);

        $adminUser->roles()->sync([$adminRole->id, $userRole->id]);

        
    }
}