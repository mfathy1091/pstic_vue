<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Status;

class StatusSeeder extends Seeder
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
                'name' => 'Active',
                'type' => 'Psychosocial',
            ],
            [
                'name' => 'Inactive',
                'type' => 'Psychosocial',
            ],
            [
                'name' => 'Closed',
                'type' => 'Psychosocial',
            ],

            [
                'name' => 'accepted',
                'type' => 'Housing Grant',
            ],
            [
                'name' => 'Rejected',
                'type' => 'Housing Grant',
            ],
            [
                'name' => 'Pending',
                'type' => 'Housing Grant',
            ],
        ];

        foreach ($statuses as $n) {
            Status::create($n);
        }
    }
}
