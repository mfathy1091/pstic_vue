<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PsIntake;
use App\Models\PsIntakeStatus;
use App\Models\PsIntakeBeneficiary;
use App\Models\Service;
use App\Models\ReferralReason;

class PsIntakeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // PS Intakes
        $psIntakes = [
            [    
                'referral_date' => '2022-03-01',
                'referral_source_id' => '1',
                'current_assigned_psw_id' => '1',
            ],
            [    
                'referral_date' => '2022-04-01',
                'referral_source_id' => '2',
                'current_assigned_psw_id' => '2',
            ],
            [
                'referral_date' => '2022-04-02',
                'referral_source_id' => '3',
                'current_assigned_psw_id' => '3',
            ],
            [
                'referral_date' => '2022-05-01',
                'referral_source_id' => '2',
                'current_assigned_psw_id' => '3',
            ],
            [
                'referral_date' => '2022-05-02',
                'referral_source_id' => '3',
                'current_assigned_psw_id' => '3',
            ],
        ];

        foreach ($psIntakes as $n) {
            PsIntake::create($n);
        }

        // Status history
        $psIntakeStauses = [
            [
                'ps_intake_id' => '1',
                'status_id' => '1',
                'is_new' => '1',
                'month' => '2022-03-01',
            ],
            [
                'ps_intake_id' => '1',
                'status_id' => '1',
                'is_new' => '0',
                'month' => '2022-04-01',
            ],
            [
                'ps_intake_id' => '1',
                'status_id' => '1',
                'is_new' => '0',
                'month' => '2022-05-01',
            ],
            [
                'ps_intake_id' => '2',
                'status_id' => '1',
                'is_new' => '1',
                'month' => '2022-04-01',
            ],
            [
                'ps_intake_id' => '2',
                'status_id' => '1',
                'is_new' => '0',
                'month' => '2022-05-01',
            ],
            [
                'ps_intake_id' => '3',
                'status_id' => '1',
                'is_new' => '1',
                'month' => '2022-04-01',
            ],
            [
                'ps_intake_id' => '3',
                'status_id' => '1',
                'is_new' => '0',
                'month' => '2022-05-01',
            ],
            [
                'ps_intake_id' => '4',
                'status_id' => '1',
                'is_new' => '1',
                'month' => '2022-05-01',
            ],
            [
                'ps_intake_id' => '5',
                'status_id' => '1',
                'is_new' => '1',
                'month' => '2022-05-01',
            ],

        ];

        foreach($psIntakeStauses as $n){
            PsIntakeStatus::create($n);
        }

        // PS Intake Beneficiaries
        $psIntakeBeneficiaries = [
            [
                'ps_intake_id' => '1',
                'beneficiary_id' => '1',
                'is_direct' => '1',
            ],
            [
                'ps_intake_id' => '1',
                'beneficiary_id' => '2',
                'is_direct' => '0',
            ],
            [
                'ps_intake_id' => '1',
                'beneficiary_id' => '3',
                'is_direct' => '1',
            ],
            [
                'ps_intake_id' => '2',
                'beneficiary_id' => '1',
                'is_direct' => '0',
            ],
            [
                'ps_intake_id' => '2',
                'beneficiary_id' => '2',
                'is_direct' => '0',
            ],
            [
                'ps_intake_id' => '2',
                'beneficiary_id' => '3',
                'is_direct' => '1',
            ],
            [
                'ps_intake_id' => '3',
                'beneficiary_id' => '7',
                'is_direct' => '1',
            ],
            [
                'ps_intake_id' => '4',
                'beneficiary_id' => '8',
                'is_direct' => '1',
            ],
            [
                'ps_intake_id' => '5',
                'beneficiary_id' => '9',
                'is_direct' => '1',
            ],
        ];
        foreach($psIntakeBeneficiaries as $n){
            PsIntakeBeneficiary::create($n);
        }
        
                // PS Intake Beneficiaries
                $service = [
                    [
                        'beneficiary_id' => '8',
                        'ps_intake_id' => '4',
                        'service_type_id' => '1',
                        'service_date' => '2022-04-11',
                        'user_id' => '1',
                    ],
                    [
                        'beneficiary_id' => '8',
                        'ps_intake_id' => '4',
                        'service_type_id' => '4',
                        'service_date' => '2022-04-11',
                        'user_id' => '1',
                    ],
                    [
                        'beneficiary_id' => '1',
                        'ps_intake_id' => '1',
                        'service_type_id' => '1',
                        'service_date' => '2022-03-15',
                        'user_id' => '1',
                    ],
                ];
                foreach($service as $n){
                    Service::create($n);
                }

        // Reasons
        // $referralReasons = [
        //     [
        //         'ps_intake_id' => '1',
        //         'reason_id' => '1',
        //     ],
        //     [
        //         'ps_intake_id' => '1',
        //         'reason_id' => '2',
        //     ],
        //     [
        //         'ps_intake_id' => '1',
        //         'reason_id' => '3',
        //     ],

        // ];

        // foreach($referralReasons as $n){
        //     ReferralReason::create($n);
        // }
        

    }
}
