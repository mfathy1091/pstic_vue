<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Month;
use App\Models\Referral;
use App\Models\Record;
use App\Models\RecordBeneficiary;
use App\Models\Beneficiary;
use App\Models\Reason;
use App\Models\Casee;
Use Exception;

class ReferralController extends Controller
{

    public function index(Request $request)
    {
        $referrals = Referral::query();

        if($request->user_id == 'current_user'){
            $referrals->where('current_assigned_psw_id', Auth::id());
        }
        if($request->user_id != -1 && $request->user_id != 'current_user'){
            $referrals->where('current_assigned_psw_id', $request->user_id);
        }

        $referrals->whereHas('records', function($q) use($request){
            if($request->is_new != -1){
                $q->where('is_new', $request->is_new);
            }
            if($request->month_id != -1){
                $q->where('month_id', $request->month_id);
            }
            if($request->status_id != -1){
                $q->where('status_id', $request->status_id);
            }
            return $q;
        });

        $referrals->with(
            'casee',
            'beneficiaries',
            'emergencies',
            'activities.providedServices.serviceType',
            'referralSource',
            'current_assigned_psw',
            'records', 
            'records.month', 
            'records.status');

        $data = [
            'data' => $referrals->get(),
        ];

        return response($data, 200);
    }


    public function getCaseeReferrals(Request $request, $caseeId)
    {
        $referrals =  Referral::with(
            'referralSource',
            'current_assigned_psw',
            'records', 
            'records.month', 
            'records.status')
            ->where('casee_id', $caseeId)
            ->get();

        $data = [
            'data' => $referrals,
        ];

        return response($data, 200);
    }


    public function getIndividualReferrals(Request $request)
    {
        $referrals =  Referral::with('referralSource')->where('beneficiary_id', $request->beneficiary_id)->get();
        
        $data = [
            'referrals' => $referrals,
        ];

        return response($data, 200);
    }

    public function show($id)
    {
        $referral = Referral::with(
        'beneficiaries',
        'originalDirectIndividual', 
        'referralSource', 
        'casee',
        'casee.beneficiaries',
        'records.emergencies.user',
        'reasons', 
        'emergencies.record.month',
        'emergencies.user',
        'emergencies.emergencyTypes',
        'emergencies.beneficiaries',
        'activities.providedServices.serviceType',
        'activities.serviceTypes',
        'activities.record.month',
        'activities.user',
        'activities.beneficiary',
        'records', 
        'records.month', 
        'records.status', 
        'records.recordBeneficiaries',
        'records.recordBeneficiaries.individual',
        'currentRecord.status' 
        )->findOrFail($id);

        if($referral){
            return ['data' => $referral];
        }

        return ['message' => 'beneficiary does not exist'];
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
            'current_assigned_psw_id' => Auth::id(),
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


    

        // // insert referral reasons
        // $reasonsIds = $request->reasons_ids;
        // foreach($reasonsIds as $reasonId)
        // {
        //     $reason = Reason::find($reasonId);
        //     $referral->reasons()->attach($reason);
        // }

        // then sync
        $reasonsIds = collect($request->input('referral_reasons'))->pluck('id');
        $referral->reasons()->sync($reasonsIds);

        /////* Insert Records *///////
        //$this->insertDefaultRecords($pssCase->id, $referralMonth);


        $casee = Casee::find($request->casee_id);
        $beneficiariesIds = $casee->beneficiaries->pluck('id')->toArray();

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
            
            $record->beneficiaries()->sync($beneficiariesIds);
            $referral->beneficiaries()->sync($beneficiariesIds);


            //$referralbeneficiaries = $request->referral_beneficiaries;
            // $directbeneficiaries = collect($request->direct_beneficiaries)->pluck('id')->toArray();
            // if(!empty($referralbeneficiaries)){
            //     foreach($referralbeneficiaries as $beneficiary)
            //     {
            //         $status = in_array($beneficiary['id'], $directbeneficiaries) ? 1 : 0;
            //         RecordBeneficiary::create([
            //             'beneficiary_id' => $beneficiary['id'],
            //             'record_id' => $record->id,
            //             'status' => $status
            //         ]);
            //     }
            // }


            // // Insert Direct Beneficiary In Each Record
            // $directbeneficiariesIds = $request->direct_beneficiaries_ids;
            // if(!empty($directbeneficiariesIds)){
            //     foreach($directbeneficiariesIds as $directBeneficiaryId)
            //     {
            //         RecordBeneficiary::create([
            //             'beneficiary_id' => $directBeneficiaryId,
            //             'record_id' => $record->id,
            //             'status' => '1',
            //         ]);
            //     }
            // }


            // // Insert Indirect beneficiaries In Each Record
            // $indirectbeneficiariesIds = $request->indirect_beneficiaries_ids;
            // if(!empty($indirectbeneficiariesIds)){
            //     foreach($indirectbeneficiariesIds as $indirectIndividualId)
            //     {
            //         RecordBeneficiary::create([
            //             'beneficiary_id' => $indirectIndividualId,
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
    

    public function closeReferral(Request $request, $id)
    {
        $referral = Referral::findOrFail($id);

        // if it exists
        if($referral){
            $record = $referral->records->first();

            $record->status_id = 3;
            $record->save();
            return $record;
        }
    }

    public function update(Request $request, $id)
    {
        $referral = Referral::findOrFail($id);

        // if it exists
        if($referral){

            $this->validate($request, [
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
                'referral_source_id' => $request->referral_source_id,
                'referral_date' => $request->referral_date,
                'referring_person_name' => $request->referring_person_name,
                'referring_person_email' => $request->referring_person_email,
                'referral_narrative_reason' => $request->referral_narrative_reason,
                'current_status_id' => $request->current_status_id,
                'current_assigned_psw_id' => Auth::id(),
                'casee_id' => $request->casee_id,
            ]);

            // then sync
            $reasonsIds = collect($request->input('referral_reasons'))->pluck('id');
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


