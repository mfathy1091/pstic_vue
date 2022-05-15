<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use App\Models\Month;
use App\Models\PsIntake;
use App\Models\Record;
use App\Models\PsIntakeBeneficiary;
use App\Models\PsIntakeStatus;
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
    $query = PsIntake::select(
            DB::raw("DATE_FORMAT(ps_intake_statuses.month, '%M %Y') as month"),
            'ps_intakes.referral_date',
            'ps_intake_statuses.status_id',
            'statuses.name as status',
            'ps_intakes.id',
            'ps_intakes.referral_source_id',
            'ps_intakes.current_assigned_psw_id',

            // 'referral_sources.name as "referral_source"'
            // DB::raw("count(statuses.name) as count")
        )
        ->join('ps_intake_statuses', 'ps_intakes.id', 'ps_intake_statuses.ps_intake_id')
        ->join('statuses', 'ps_intake_statuses.status_id', 'statuses.id');
        // ->join('referral_sources', 'ps_intakes.referral_source_id', 'referral_sources.id');

        if($request->month != ''){
            $query->where('ps_intake_statuses.month', '=', $request->month);
        }
        
        // if($request->status_id != ''){
        //     if($request->status_id == 'New + Ongoing'){
        //         $query->where('statuses.name', 'New')->OrWhere('statuses.name', 'Ongoing');
        //     }
        //     else
        //     {
        //         $query->where('statuses.name', $request->status);
        //     }
        // }

        $query->with(
            //'referral.casee',
            // 'referral.beneficiaries',
            // 'referral.emergencies',
            // 'activities.providedServices.serviceType',
            'referralSource',
            'current_assigned_psw',
            'psIntakeBeneficiaries',
            'beneficiaries'
            // 'directReferralBeneficiaries',
            // 'inDirectReferralBeneficiaries',
            // 'records', 
            // 'records.month', 
            // 'records.status',
            // 'currentRecord.status' 
        );

        $data = [
            'data' => $query->get(),
        ];

        return response($data, 200);
    }

    public function getCurrentCountsByStatuses(Request $request)
    {
        $queryNew = PsIntake::join('statuses', 'ps_intakes.current_status_id', 'statuses.id')
        ->select('ps_intakes.*')
        ->where('current_status_id', '1')
        ->count();

        $queryOngoing = PsIntake::join('statuses', 'ps_intakes.current_status_id', 'statuses.id')
        ->select('ps_intakes.*')
        ->where('current_status_id', '2')
        ->count();

        $queryClosed = PsIntake::join('statuses', 'ps_intakes.current_status_id', 'statuses.id')
        ->select('ps_intakes.*')
        ->where('current_status_id', '3')
        ->count();

        $data = [
            'new' => $queryNew,
            'ongoing' => $queryOngoing,
            'closed' => $queryClosed,
        ];

        return response($data, 200);
    }
    public function getMonthlyCountsByStatuses(Request $request)
    {
        $query = DB::table('ps_intakes')
            ->select(
                DB::raw("DATE_FORMAT(ps_intake_statuses.month, '%M %Y') as month"),
                'statuses.name as status',
                DB::raw("count(statuses.name) as count")
            )
        ->join('ps_intake_statuses', 'ps_intakes.id', 'ps_intake_statuses.ps_intake_id')
        ->join('statuses', 'ps_intake_statuses.status_id', 'statuses.id')
        ->where('ps_intake_statuses.month', '>=', '2022-01-01')
        ->where('ps_intake_statuses.month', '<=', '2022-12-31')
        ->groupBy('ps_intake_statuses.month', 'statuses.name');        

        $result = $query->get();

        $monthlyCounts = $result->mapToGroups(function($item, $key){
            return [$item->status => [$item->month => $item->count]];
            
        });

        $newData = [
            'name' => 'New',
            'data' => $monthlyCounts['New']->collapse()
        ];

        $ongoingData = [
            'name' => 'Ongoing',
            'data' => $monthlyCounts['Ongoing']->collapse()
        ];



        $data = [
            $newData,
            $ongoingData,
        ];

        return response($data, 200);
    }

    public function show($id)
    {
        $psIntake = PsIntake::with(
        'beneficiaries',
        // 'directReferralBeneficiaries',
        // 'indirectReferralBeneficiaries',
        // 'referralBeneficiaries.beneficiary',
        'referralSource', 
        'beneficiaries',
        // 'casee',
        // 'casee.beneficiaries',
        // 'records.emergencies.user',
        // 'reasons', 
        // 'emergencies.record.month',
        'emergencies.user',
        'emergencies.emergencyTypes',
        'emergencies.beneficiary',
        'activities.services.serviceType',
        'activities.serviceTypes',
        'activities.emergencyTypes',
        'activities.record.month',
        'activities.user',
        'activities.beneficiary',
        // 'records', 
        // 'records.month', 
        // 'records.status', 
        // 'records.recordBeneficiaries',
        // 'records.recordBeneficiaries.individual',
        // 'currentRecord.status' 
        )->findOrFail($id);

        if($psIntake){
            return ['data' => $psIntake];
        }

        return ['message' => 'beneficiary does not exist'];
    }

    public function store(Request $request)
    {

        /* validate if referradate is in future (reject it - it must be today or older) */
        $this->validate($request, [
            'referral_source_id' => 'required',
            'referral_date' => 'required|date',
            'referring_person_name' => 'required',
            'referring_person_email' => 'required|email',
            'referral_narrative_reason' => 'required',
            'current_status_id' => '',
            'current_assigned_psw_id' => '',
            'ps_intake_beneficiaries' => 'required|array|min:1',
        ],
        [
            'ps_intake_beneficiaries.required' => 'Please add at least 1 beneficiary'
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

        // Sync beneficiaries
        $ps_intake_beneficiaries = collect($request->ps_intake_beneficiaries);
        
        $ps_intake_beneficiaries = $ps_intake_beneficiaries->mapWithKeys(function($item, $key){
            if($item["is_direct"]){
                return [$item["beneficiary_id"] => ['is_direct' => 1]];
            }else{
                return [$item["beneficiary_id"] => ['is_direct' => 0]];
            }    
        });

        $psIntake->beneficiaries()->sync($ps_intake_beneficiaries);

        // Add PsIntakeStatuses
        $referralMonth = date("Y-m", strtotime($request->referral_date));

        $period = \Carbon\CarbonPeriod::create($referralMonth, '1 month', date("Y-m"));

        $months = collect($period)->map(function($dt){
            return $dt->format("Y-m-d");
        });

        $i = 0;
        foreach($months as $month)
        {
            $i++;
            if($i == 1){
                $status_id = 1;
            }else{
                $status_id = 2;
            }
            PsIntakeStatus::create([
                'month' => $month,
                'ps_intake_id' => $psIntake->id,
                'status_id' => '2',
            ]);
        }


        $data = [
            'psIntake' => $psIntake,
        ];

        return response($data, 201);

    }



}
