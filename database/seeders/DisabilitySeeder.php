<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Disability;

class DisabilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('disabilities')->delete();
        $disabilities = ['Physical', 'Mental', 'Vision', 'Hearing'];

        foreach ($disabilities as $n) {
            Disability::create(['name' => $n]);
        }

    }
}
