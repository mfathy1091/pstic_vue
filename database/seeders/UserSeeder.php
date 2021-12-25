<?php

namespace Database\Seeders;

use App\Http\Controllers\JobTitle;
use App\Models\Department;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        //User::factory()->times(10)->create();
        
        $users = [
            [
                'first_name' => 'Ahmed',
                'last_name' => 'Alrajeh',
                'email' => 'ahmedalrajeh@pstic-egypt.org',
                'password' => Hash::make('pstic12345'),
            ],

        ];

        foreach($users as $n){
            User::create($n);
        }

        $ahmedAlrajeh = User::where('name', 'Ahmed Alrajeh')->first();






        
    }
}