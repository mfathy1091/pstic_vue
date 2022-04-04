<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\PsStatus;

class PsStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // DB::table('statuses')->delete();
        $statuses = [
            [
                'name' => 'New',
            ],
            [
                'name' => 'Ongoing',
            ],
            [
                'name' => 'Closed',
            ],
            [
                'name' => 'Inactive',
            ],
 
        ];

        foreach ($statuses as $n) {
            PsStatus::create($n);
        }
    }
}
