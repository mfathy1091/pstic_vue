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
                'type' => 'PS Intake',
            ],
            [
                'name' => 'Inactive',
                'type' => 'PS Intake',
            ],
            [
                'name' => 'Closed',
                'type' => 'PS Intake',
            ],

            [
                'name' => 'Accepted',
                'type' => 'Housing Intake',
            ],
            [
                'name' => 'Rejected',
                'type' => 'Housing Intake',
            ],
            [
                'name' => 'Pending',
                'type' => 'Housing Intake',
            ],
            [
                'name' => 'Active',
                'type' => 'Beneficiary',
            ],
            [
                'name' => 'Inactive',
                'type' => 'Beneficiary',
            ],
        ];

        foreach ($statuses as $n) {
            Status::create($n);
        }
    }
}
