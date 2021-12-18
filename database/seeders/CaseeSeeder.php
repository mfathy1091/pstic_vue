<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Casee;
use App\Models\Relationship;
use App\Models\Beneficiary;

class CaseeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        $relationships = [
            'PA',
            'Wife',
            'Husband',
            'Son',
            'Daughter'
        ];
        foreach ($relationships as $n) {
            Relationship::create(['name' => $n]);
        }
        
        
        $casees = [
            [
                'file_number' => '555-11C01264',
                'created_user_id' => '1',
            ],
            [
                'file_number' => '914-16C02867',
                'created_user_id' => '1',
            ],
            [
                'file_number' => '555-20C01750',
                'created_user_id' => '1',
            ],
            [
                'file_number' => '555-19C01310',
                'created_user_id' => '1',
            ],
            [
                'file_number' => '555-18C00400',
                'created_user_id' => '1',
            ],
        ];

        foreach ($casees as $n) {
            Casee::create($n);
        }



        $beneficiaries = [
            [
                'casee_id' => '1',
                'name' => 'Mariam KONE',
                'passport_number' => '010018765',
                'age' => '42',
                'gender_id' => '2',
                'nationality_id' => '3',
                'phone_number' => '01006785305',
                'beneficiary_status_id' => '7',

                'is_registered' => '1',
                'file_individual_number' => '555-00112677',
                'relationship_id' => '1',
            ],

            [
                'casee_id' => '1',
                'passport_number' => '025025765',
                'name' => 'Zaharaa DIABY',
                'age' => '20',
                'gender_id' => '2',
                'nationality_id' => '3',
                'phone_number' => '01006785305',
                'beneficiary_status_id' => '7',
                'is_registered' => '1',

                'file_individual_number' => '555-00112682',
                'relationship_id' => '5',


            ],
            [
                'casee_id' => '1',
                'name' => 'Kadidja DIABY',
                'passport_number' => '030029864',
                'age' => '19',
                'gender_id' => '2',
                'nationality_id' => '3',
                'phone_number' => '01006785305',
                'beneficiary_status_id' => '7',

                'is_registered' => '1',
                'file_individual_number' => '555-00112683',
                'relationship_id' => '5',

            ],




            [
                'casee_id' => '2',
                'name' => 'Hussein Asaad YASIN',
                'passport_number' => '548635369',
                'age' => '26',
                'gender_id' => '1',
                'nationality_id' => '2',
                'phone_number' => '01112067527',
                'beneficiary_status_id' => '7',

                'is_registered' => '1',
                'file_individual_number' => '914-00127057',
                'relationship_id' => '1',

            ],
            [
                'casee_id' => '2',
                'name' => 'Reem Mahmoud DAMAMA ALLOULOU',
                'passport_number' => '278945254',
                'age' => '23',
                'gender_id' => '2',
                'nationality_id' => '2',
                'phone_number' => '01112067527',
                'beneficiary_status_id' => '7',

                'is_registered' => '1',
                'file_individual_number' => '914-00127062',
                'relationship_id' => '2',

            ],            
            [
                'casee_id' => '2',
                'name' => 'Mariam Hussien YASIN',
                'passport_number' => '958390473',
                'age' => '4',
                'gender_id' => '2',
                'nationality_id' => '2',
                'phone_number' => '01112067527',
                'beneficiary_status_id' => '7',

                'is_registered' => '1',
                'file_individual_number' => '555-00118684',
                'relationship_id' => '5',

            ],



            [
                'casee_id' => '3',
                'name' => 'Anas Musa ABDI',
                'passport_number' => '290347636',
                'age' => '15',
                'gender_id' => '1',
                'nationality_id' => '7',
                'phone_number' => '01154212163',
                'beneficiary_status_id' => '7',

                'is_registered' => '1',
                'file_individual_number' => '555-00270922',
                'relationship_id' => '1',

            ],



            [
                'casee_id' => '4',
                'name' => 'Suleiman Mussa Yusuf Mohamed',
                'passport_number' => '850303586',
                'age' => '30',
                'gender_id' => '1',
                'nationality_id' => '4',
                'phone_number' => '01008817734',
                'beneficiary_status_id' => '7',

                'is_registered' => '1',
                'file_individual_number' => '555-00244422',
                'relationship_id' => '1',

            ],

            [
                'casee_id' => '5',
                'name' => 'Asmaa Ahmed ALI',
                'passport_number' => '568455878',
                'age' => '62',
                'gender_id' => '2',
                'nationality_id' => '4',
                'phone_number' => '01027594793',
                'beneficiary_status_id' => '7',

                'is_registered' => '1',
                'file_individual_number' => '555-00219736',
                'relationship_id' => '1',

            ],

        ];

        foreach ($beneficiaries as $n) {
            Beneficiary::create($n);
        } 
    }
}
