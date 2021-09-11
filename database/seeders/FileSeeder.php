<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\File;
use App\Models\Relationship;
use App\Models\Individual;

class FileSeeder extends Seeder
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
        
        
        $files = [
            [
                'number' => '555-11C01264',
                'created_user_id' => '1',
            ],
            [
                'number' => '914-16C02867',
                'created_user_id' => '1',
            ],
            [
                'number' => '555-20C01750',
                'created_user_id' => '1',
            ],
            [
                'number' => '555-19C01310',
                'created_user_id' => '1',
            ],
            [
                'number' => '555-18C00400',
                'created_user_id' => '1',
            ],
        ];

        foreach ($files as $n) {
            File::create($n);
        }



        $individuals = [
            [
                'passport_number' => '010018765',
                'file_id' => '1',
                'individual_id' => '555-00112677',
                'name' => 'Mariam KONE',
                'age' => '42',
                'gender' => '2',
                'nationality_id' => '3',
                'relationship_id' => '1',
                'current_phone_number' => '01006785305',
            ],
            [
                'passport_number' => '025025765',
                'file_id' => '1',
                'individual_id' => '555-00112682',
                'name' => 'Zaharaa DIABY',
                'age' => '20',
                'gender' => '2',
                'nationality_id' => '3',
                'relationship_id' => '5',
                'current_phone_number' => '01006785305',
            ],
            [
                'passport_number' => '030029864',
                'file_id' => '1',
                'individual_id' => '555-00112683',
                'name' => 'Kadidja DIABY',
                'age' => '19',
                'gender' => '2',
                'nationality_id' => '3',
                'relationship_id' => '5',
                'current_phone_number' => '01006785305',
            ],




            [
                'passport_number' => '548635369',
                'file_id' => '2',
                'individual_id' => '914-00127057',
                'name' => 'Hussein Asaad YASIN',
                'age' => '26',
                'gender' => '1',
                'nationality_id' => '2',
                'relationship_id' => '1',
                'current_phone_number' => '01112067527',
            ],
            [
                'passport_number' => '278945254',
                'file_id' => '2',
                'individual_id' => '914-00127062',
                'name' => 'Reem Mahmoud DAMAMA ALLOULOU',
                'age' => '23',
                'gender' => '2',
                'nationality_id' => '2',
                'relationship_id' => '2',
                'current_phone_number' => '01112067527',
            ],            
            [
                'passport_number' => '958390473',
                'file_id' => '2',
                'individual_id' => '555-00112683',
                'name' => 'Mariam Hussien YASIN',
                'age' => '4',
                'gender' => '2',
                'nationality_id' => '2',
                'relationship_id' => '5',
                'current_phone_number' => '01112067527',
            ],



            [
                'passport_number' => '290347636',
                'file_id' => '3',
                'individual_id' => '555-00270922',
                'name' => 'Anas Musa ABDI',
                'age' => '15',
                'gender' => '1',
                'nationality_id' => '7',
                'relationship_id' => '1',
                'current_phone_number' => '01154212163',
            ],



            [
                'passport_number' => '850303586',
                'file_id' => '4',
                'individual_id' => '555-00244422',
                'name' => 'Suleiman Mussa Yusuf Mohamed',
                'age' => '30',
                'gender' => '1',
                'nationality_id' => '4',
                'relationship_id' => '1',
                'current_phone_number' => '01008817734',
            ],

            [
                'passport_number' => '568455878',
                'file_id' => '5',
                'individual_id' => '555-00219736',
                'name' => 'Asmaa Ahmed ALI',
                'age' => '62',
                'gender' => '2',
                'nationality_id' => '4',
                'relationship_id' => '1',
                'current_phone_number' => '01027594793',
            ],

        ];

        foreach ($individuals as $n) {
            Individual::create($n);
        } 
    }
}
