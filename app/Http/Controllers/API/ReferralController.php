<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Month;
use App\Models\Referral;
use App\Models\Record;
use App\Models\Beneficiary;

class ReferralController extends Controller
{

    public function show($id)
    {
        $referral = Referral::with('originalDirectIndividual', 'referralSource')->findOrFail($id);

        if($referral){
            return ['data' => $referral];
        }

        return ['message' => 'Individual does not exist'];
    }

    public function store(Request $request)
    {

        /* validate if referradate is in future (reject it - it must be today or older) */
        $this->validate($request, [
            'original_direct_individual_id' => 'required',
            'referral_source_id' => 'required',
            'referral_date' => 'required',
            'referring_person_name' => 'required',
            'referring_person_email' => 'required',
            'current_status_id' => '',
            'current_assigned_psw_id' => '',
        ]);

        // Create Referral
        $referral = Referral::create([
            'original_direct_individual_id' => $request->original_direct_individual_id,
            'referral_source_id' => $request->referral_source_id,
            'referral_date' => $request->referral_date,
            'referring_person_name' => $request->referring_person_name,
            'referring_person_email' => $request->referring_person_email,
            'current_status_id' => $request->current_status_id,
            'current_assigned_psw_id' => $request->current_assigned_psw_id,
        ]);

        

        // Referral Month
        $referralDate = $request->referral_date;
        $ConvertedReferralDate = strtotime($referralDate);
        $referralMonth = date("Y-m", $ConvertedReferralDate);
        $currentMonth = date("Y-m");
        
        // Get Months List
        $period = \Carbon\CarbonPeriod::create($referralMonth, '1 month', $currentMonth);

        $i = 0;
        foreach ($period as $dt)
        {
            $monthsCodes[$i] = $dt->format("Y-m");
            $i++;
        }

        // dd($monthsCodes);


    

        // insert referral reasons
/*         $reasonsIds = $request->reasons_ids;
        foreach($reasonsIds as $reasonId)
        {
            $reason = Reason::find($reasonId);
            $referral->reasons()->attach($reason);
        } */

        /////* Insert Records *///////
        //$this->insertDefaultRecords($pssCase->id, $referralMonth);
        $i = 0;
        foreach($monthsCodes as $monthCode)
        {
            $i++;
            // insert Records
            $month = Month::where('code', $monthCode)->firstOrFail();
            if($i == 1){
                $is_new = 1;
            }else{
                $is_new = 0;
            }
            $record = Record::create([
                'month_id' => $month->id,
                'referral_id' => $referral->id,
                'status_id' => '2',
                'is_new' => $is_new,
                'is_emergency' => '0',
            ]);
            
            // Insert Direct Individual In Each Record
            $directIndividualId = $request->original_direct_individual_id;
            Beneficiary::create([
                'individual_id' => $directIndividualId,
                'record_id' => $record->id,
                'is_direct' => '1',
            ]);


            // Insert Indirect Individuals In Each Record
            $indirectIndividualsIds = $request->indirect_individuals_ids;
            if(!empty($indirectIndividualsIds)){
                foreach($indirectIndividualsIds as $indirectIndividualId)
                {
                    Beneficiary::create([
                        'individual_id' => $indirectIndividualId,
                        'record_id' => $record->id,
                        'is_direct' => '0',
                    ]);
                }
            }
        }

        return $referral;
    }

    public function getIndividualReferrals(Request $request)
    {
        $referrals =  Referral::where('individual_id', $request->individual_id)->get();
        return ['data' => $referrals];
    }

}
