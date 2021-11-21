<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RecordBeneficiary;

class RecordBeneficiarySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        // (3) Record Beneficiaries
        $data = [
            [
                'record_id' => '1',
                'individual_id' => '1',
                'is_direct' => '1',
                
            ],
            [
                'record_id' => '1',
                'individual_id' => '2',
                'is_direct' => '0',
            ],

            [
                'record_id' => '2',
                'individual_id' => '1',
                'is_direct' => '1',
            ],
            
        ];

        foreach ($data as $n) {
            RecordBeneficiary::create($n);
        }
    }
}
