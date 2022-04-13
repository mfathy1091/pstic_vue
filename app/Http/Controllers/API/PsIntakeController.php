<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use App\Models\Month;
use App\Models\PsIntake;
use App\Models\Record;
use App\Models\PsIntakeBeneficiary;
use App\Models\Beneficiary;
use App\Models\Reason;
use App\Models\Casee;
Use Exception;
use \Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PsIntakeController extends Controller
{
    public function index(Request $request)
    {
        $psIntakes = PsIntake::latest();

        $psIntakes->with(
            //'referral.casee',
            // 'referral.beneficiaries',
            // 'referral.emergencies',
            // 'activities.providedServices.serviceType',
            'referralSource',
            'current_assigned_psw',
            // 'directReferralBeneficiaries',
            // 'inDirectReferralBeneficiaries',
            // 'records', 
            // 'records.month', 
            // 'records.status',
            // 'currentRecord.status' 
        );

        $data = [
            'data' => $psIntakes->get(),
        ];

        return response($data, 200);
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

        // Create PsIntake
        $psIntake = PsIntake::create([
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


    

        // // insert Referring reasons
        // $reasonsIds = $request->reasons_ids;
        // foreach($reasonsIds as $reasonId)
        // {
        //     $reason = Reason::find($reasonId);
        //     $psIntake->reasons()->attach($reason);
        // }

        // then sync
        // $reasonsIds = collect($request->input('referral_reasons'))->pluck('id');
        // $psIntake->reasons()->sync($reasonsIds);

        /////* Insert Records *///////
        //$this->insertDefaultRecords($pssCase->id, $referralMonth);

        


        $directBeneficiariesIds = [1000, 1001];
        $beneficiariesIds = collect($request->beneficiaries)->pluck('id');

        $beneficiariesIds = $beneficiariesIds->mapWithKeys(function($item, $key) use($directBeneficiariesIds){
            if(in_array($item, $directBeneficiariesIds) ){
                return [$item => ['is_direct' => 1]];
            }else{
                return [$item => ['is_direct' => 0]];
            }    
        });

        $psIntake->beneficiaries()->sync($beneficiariesIds);



        $data = [
            'psIntake' => $psIntake,
        ];

        return response($data, 201);

    }
}
