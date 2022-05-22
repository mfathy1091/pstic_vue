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
            'ps_intake_statuses.is_new',
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

        if($request->user_id != 'All'){
            $query->where('ps_intakes.current_assigned_psw_id', '=', $request->user_id);
        }

        if($request->month != ''){
            $query->where('ps_intake_statuses.month', '=', $request->month);
        }

        $statusesCounts = [
            'countActive' => $query->clone()->where('statuses.name', '=', 'Active')->count(),
            'countClosed' => $query->clone()->where('statuses.name', '=', 'Closed')->count(),
            'countInactive' => $query->clone()->where('statuses.name', '=', 'Inactive')->count(),
        ];
        
        if($request->status != 'All'){
            $query->where('statuses.name', $request->status);
        }

        if($request->is_new != 'All'){
            $query->where('is_new', $request->is_new);
        }

        $query->with(
            //'referral.casee',
            'referralSource',
            'current_assigned_psw',
            'psIntakeBeneficiaries',
            'beneficiaries'
            // 'directReferralBeneficiaries',
            // 'inDirectReferralBeneficiaries',
        );

        $data = [
            'psIntakes' => $query->get(),
            'statusesCounts' => $statusesCounts,
        ];

        return response($data, 200);
    }

    public function commulative(Request $request){
        $startMonth = '2022-01-01';
        $endMonth = '2022-12-01';

        $period = \Carbon\CarbonPeriod::create($startMonth, '1 month', $endMonth);

        $months = collect($period)->map(function($dt){
            return $dt->format("Y-m-d");
        })->toArray();

        $months2 = collect($period)->map(function($dt){
            return $dt->format("F Y");
        })->toArray();

        // dd($months2);

        $secondMonth = $months[1];

        $query = DB::table('ps_intakes')
        ->select(
            DB::raw("DATE_FORMAT(ps_intake_statuses.month, '%M %Y') as month"),
            'statuses.name as status',
            // 'ps_intake_statuses.is_new',
            DB::raw("count(statuses.name) as count")
        )
        ->join('ps_intake_statuses', 'ps_intakes.id', 'ps_intake_statuses.ps_intake_id')
        ->join('statuses', 'ps_intake_statuses.status_id', 'statuses.id');

        $firstMonthResult = $query->clone()
            ->where('ps_intake_statuses.month', '=', $months[0])
            ->groupBy('ps_intake_statuses.month', 'statuses.name')
            ->get();

        // dd($firstMonthResult);

        $restMonthsResult = $query->where('ps_intake_statuses.month', '>=', $secondMonth)
            ->where('ps_intake_statuses.month', '<=', $months[array_key_last($months)])
            ->where('ps_intake_statuses.is_new', '=', 1)
            ->groupBy('ps_intake_statuses.month', 'statuses.name')
            ->get();

        $result = $firstMonthResult->merge($restMonthsResult);
        $countArray2 = [];
        
        $i = 0;
        foreach($months2 as $month)
        {
            if($result->where('month', $month)->first()){
                $foundItem = $result->where('month', $month)->first();
                $countArray2[$i]['month'] = $foundItem->month;
                $countArray2[$i]['countNew'] = $foundItem->count;
            }
            else{
                $countArray2[$i]['month'] = $month;
                $countArray2[$i]['countNew'] = 0;
            }
            $i++;
            // if(!empty($result[$i]['month'])){

            // }else{
                // $countsArray[$month] = 0;
            // }
        }

        
        dd($countArray2);

        $result2 = $result->mapWithKeys(function($item, $key){
            return [$item->month => $item->count];
        });
        
        // dd($result2['March 2022']);

        $countsArray = [];

        foreach($months2 as $month){
            if(!empty($result2[$month])){
                $countsArray[$month] = $result2[$month];
            }else{
                $countsArray[$month] = 0;
            }
        }

        $commulativeArray = [];
        for($i=0; $i <= count($countsArray); $i++){
            $commulativeArray[$i] = $countsArray[$i];
        }

        dd($commulativeArray);

    $data = [
        'result2' => $countsArray,
    ];

    return response($data, 200);
}

    public function getMonthlyCountsByStatuses(Request $request)
    {
        $startMonth = '2022-01-01';
        $endMonth = '2022-05-01';

        $period = \Carbon\CarbonPeriod::create($startMonth, '1 month', $endMonth);


        $months = collect($period)->map(function($dt){
            return $dt->format("Y-m-d");
        })->toArray();

        $query = DB::table('ps_intakes')
            ->select(
                // DB::raw("DATE_FORMAT(ps_intake_statuses.month, '%M %Y') as month"),
                DB::raw("DATE_FORMAT(ps_intake_statuses.month, '%Y-%m') as month"),
                'statuses.name as status',
                'ps_intake_statuses.is_new',
                DB::raw("count(statuses.name) as count")
            )
        ->join('ps_intake_statuses', 'ps_intakes.id', 'ps_intake_statuses.ps_intake_id')
        ->join('statuses', 'ps_intake_statuses.status_id', 'statuses.id')
        ->where('ps_intake_statuses.month', '>=', $months[0])
        ->where('ps_intake_statuses.month', '<=', $months[array_key_last($months)])
        ->groupBy('ps_intake_statuses.month', 'ps_intake_statuses.is_new', 'statuses.name')
        ->orderBy('ps_intake_statuses.month');      

        $result = $query->get();

        $monthlyCounts = $result->mapToGroups(function($item, $key){
            return [$item->is_new => [$item->month => $item->count]];
            
        });

        $new = $monthlyCounts['1']->collapse();

        // $new2 = $months->mapWithKeys(function($item, $key)  use($months){
        //     if(in_array($item, $months)){
        //         return true;
        //     }else{
        //         return false;
        //     }
        // });


        $newData = [
            'name' => 'New',
            'data' => $new,
        ];

        $ongoingData = [
            'name' => 'Ongoing',
            'data' => $monthlyCounts['0']->collapse()
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
                $is_new = 1;
            }else{
                $is_new = 0;
            }
            PsIntakeStatus::create([
                'month' => $month,
                'ps_intake_id' => $psIntake->id,
                'status_id' => '1',
                'is_new' => $is_new,
            ]);
        }


        $data = [
            'psIntake' => $psIntake,
        ];

        return response($data, 201);

    }



}
