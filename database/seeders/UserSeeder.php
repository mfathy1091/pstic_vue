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
                'first_name' => 'Abdalmola',
                'last_name' => 'Shablot',
                'email' => 'Abdalmola@pstic-egypt.org',
                'password' => Hash::make('pstic12345'),
                'budget_id' => '1',
                
            ],
            [
                'first_name' => 'Ahmed',
                'last_name' => 'Alrajeh',
                'email' => 'ahmedalrajeh@pstic-egypt.org',
                'password' => Hash::make('pstic12345'),
                'budget_id' => '1',
                'direct_manager_id' => '2'
            ],
            [
                'first_name' => 'Maha',
                'last_name' => 'Osman',
                'email' => 'maha@gmail.org',
                'password' => Hash::make('pstic12345'),
                'is_active' => '0',
                'budget_id' => '1',
                'direct_manager_id' => '2'
            ],
            [
                'first_name' => 'Maher',
                'last_name' => 'Shwieky',
                'email' => 'maher@gmail.org',
                'password' => Hash::make('pstic12345'),
                'budget_id' => '1',
                'direct_manager_id' => '2'
            ],
            [
                'first_name' => 'Gihan',
                'last_name' => 'Babiker',
                'email' => 'gihan@gmail.org',
                'password' => Hash::make('pstic12345'),
                'budget_id' => '1',
            ],
            [
                'first_name' => 'Osama',
                'last_name' => 'Alhafez',
                'email' => 'alhafez@gmail.org',
                'password' => Hash::make('pstic12345'),
                'budget_id' => '1',
            ],
            [
                'first_name' => 'Yusuf',
                'last_name' => 'Danhul',
                'email' => 'yusuf@gmail.org',
                'password' => Hash::make('pstic12345'),
                'budget_id' => '1',
            ],
        ];

        foreach($users as $n){
            User::create($n);
        }

        $ahmedAlrajeh = User::where('name', 'Ahmed Alrajeh')->first();






        
    }
}