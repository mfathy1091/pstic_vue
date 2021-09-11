<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        $user = User::create([
            'name' => 'Admin', 
            'email' => 'admin@gmail.com',
            'password' => Hash::make('pstic12345'),
        ]);
    
        // $role = Role::create(['name' => 'Admin']);
    
        // $permissions = Permission::pluck('id','id')->all();
    
        // $role->syncPermissions($permissions);
    
        // $user->assignRole([$role->id]);
    }
}