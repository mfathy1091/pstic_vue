<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Servicetype;

class ServicetypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'name' => 'PSS',
                'category' => 'Psychosocial',
            ],
            [
                'name' => 'Info Sharing',
                'category' => 'Counseling',
            ],
            [
                'name' => 'Basic Needs',
                'category' => 'Psychosocial',
            ],
            [
                'name' => 'Community Support',
                'category' => 'Psychosocial',
            ],
            [
                'name' => 'Housing Advocacy',
                'category' => 'Psychosocial',
            ],

        ];

        foreach ($data as $n){
            Servicetype::create($n);
        }
    }
}
