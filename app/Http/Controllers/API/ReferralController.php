<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Month;
use App\Models\Referral;
use App\Models\Record;
use App\Models\RecordBeneficiary;
use App\Models\Individual;
use App\Models\Reason;
use App\Models\Casee;

class ReferralController extends Controller
{
    public function index()
    {
        $referrals =  Referral::with('referralSource', 'records', 'records.month', 'records.status')->get();

        $data = [
            'data' => $referrals,
        ];

        return response($data, 200);
    }

    public function getCaseeReferrals(Request $request)
    {
        $referrals =  Referral::with('referralSource', 'records', 'records.month', 'records.status')->where('casee_id', $request->casee_id)->get();

        $data = [
            'referrals' => $referrals,
        ];

        return response($data, 200);

        

    }

    public function getIndividualReferrals(Request $request)
    {
        $referrals =  Referral::with('referralSource')->where('individual_id', $request->individual_id)->get();
        
        $data = [
            'referrals' => $referrals,
        ];

        return response($data, 200);
    }

    public function show($id)
    {
        $referral = Referral::with(
        'originalDirectIndividual', 
        'referralSource', 
        'casee',
        'casee.individuals',
        'reasons', 
        'records', 
        'records.month', 
        'records.status', 
        'records.recordBeneficiaries',
        'records.recordBeneficiaries.individual',
        //'records.recordBeneficiaries.services' 
        )->findOrFail($id);

        if($referral){
            return ['data' => $referral];
        }

        return ['message' => 'Individual does not exist'];
    }

    public function store(Request $request)
    {

        /* validate if referradate is in future (reject it - it must be today or older) */
        $this->validate($request, [
            'referral_source_id' => 'required',
            'referral_date' => 'required',
            'referring_person_name' => 'required',
            'referring_person_email' => 'required|email',
            'referral_narrative_reason' => 'required',
            'current_status_id' => '',
            'current_assigned_psw_id' => '',
        ]);

        // Create Referral
        $referral = Referral::create([
            'referral_source_id' => $request->referral_source_id,
            'referral_date' => $request->referral_date,
            'referring_person_name' => $request->referring_person_name,
            'referring_person_email' => $request->referring_person_email,
            'referral_narrative_reason' => $request->referral_narrative_reason,
            'current_status_id' => $request->current_status_id,
            'current_assigned_psw_id' => $request->current_assigned_psw_id,
            'casee_id' => $request->casee_id,
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
        $reasonsIds = $request->reasons_ids;
        foreach($reasonsIds as $reasonId)
        {
            $reason = Reason::find($reasonId);
            $referral->reasons()->attach($reason);
        }

        /////* Insert Records *///////
        //$this->insertDefaultRecords($pssCase->id, $referralMonth);


        $casee = Casee::find($request->casee_id);
        $individualsIds = $casee->individuals->pluck('id')->toArray();

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
            ]);
            
            $record->individuals()->sync($individualsIds);


            //$referralIndividuals = $request->referral_beneficiaries;
            // $directIndividuals = collect($request->direct_beneficiaries)->pluck('id')->toArray();
            // if(!empty($referralIndividuals)){
            //     foreach($referralIndividuals as $individual)
            //     {
            //         $status = in_array($individual['id'], $directIndividuals) ? 1 : 0;
            //         RecordBeneficiary::create([
            //             'individual_id' => $individual['id'],
            //             'record_id' => $record->id,
            //             'status' => $status
            //         ]);
            //     }
            // }


            // // Insert Direct Individual In Each Record
            // $directIndividualsIds = $request->direct_beneficiaries_ids;
            // if(!empty($directIndividualsIds)){
            //     foreach($directIndividualsIds as $directIndividualId)
            //     {
            //         RecordBeneficiary::create([
            //             'individual_id' => $directIndividualId,
            //             'record_id' => $record->id,
            //             'status' => '1',
            //         ]);
            //     }
            // }


            // // Insert Indirect Individuals In Each Record
            // $indirectIndividualsIds = $request->indirect_beneficiaries_ids;
            // if(!empty($indirectIndividualsIds)){
            //     foreach($indirectIndividualsIds as $indirectIndividualId)
            //     {
            //         RecordBeneficiary::create([
            //             'individual_id' => $indirectIndividualId,
            //             'record_id' => $record->id,
            //             'status' => '0',
            //         ]);
            //     }
            // }
        }

        $data = [
            'referral' => $referral,
            'message' => 'created successfully'
        ];

        return response($data, 201);

    }


    public function update(Request $request, $id)
    {
        $referral = Referral::findOrFail($id);

        // if it exists
        if($referral){
            
            $this->validate($request, [
                'original_direct_individual_id' => 'required',
                'referral_source_id' => 'required',
                'referral_date' => 'required',
                'referring_person_name' => 'required',
                'referring_person_email' => 'required|email',
                'referral_narrative_reason' => 'required',
                'current_status_id' => '',
                'current_assigned_psw_id' => '',
            ]);
            
            // update first
            $referral->update([
                'original_direct_individual_id' => $request->original_direct_individual_id,
                'referral_source_id' => $request->referral_source_id,
                'referral_date' => $request->referral_date,
                'referring_person_name' => $request->referring_person_name,
                'referring_person_email' => $request->referring_person_email,
                'referral_narrative_reason' => $request->referral_narrative_reason,
                'current_status_id' => $request->current_status_id,
                'current_assigned_psw_id' => $request->current_assigned_psw_id,
                'casee_id' => $request->casee_id,
            ]);

            // then sync
            $reasonsIds = collect($request->input('reasons'))->pluck('id');
            $referral->reasons()->sync($reasonsIds);
            
            
            return ['message' => 'Referral updated'];
        }

    }


    public function destroy($id)
    {
        $referral = Referral::findOrFail($id);

        // if it exists
        if($referral){
            // detach first
            $referral->permissions()->detach();
            // then delete
            $referral->delete();
        }

        return ['message' => 'Referral deleted'];
    }

}


