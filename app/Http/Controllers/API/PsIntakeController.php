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

    public function getMonthlyCountsByStatuses(Request $request)
    {
        // (1) Create Months
        $startMonth = '2022-01-01';
        $endMonth = '2022-12-01';

        $period = \Carbon\CarbonPeriod::create($startMonth, '1 month', $endMonth);

        $months = collect($period)->map(function($dt){
            return $dt->format("Y-m-d");
        })->toArray();

        $months2 = collect($period)->map(function($dt){
            return $dt->format("F Y");
        })->toArray();

        // (2) create query
        $query = DB::table('ps_intakes')
            ->select(
                DB::raw("DATE_FORMAT(ps_intake_statuses.month, '%M %Y') as month"),
                DB::raw("sum(case when ps_intake_statuses.is_new = 1 then 1 else 0 end) as activeNewCount"),
                DB::raw("sum(case when ps_intake_statuses.is_new = 0 then 1 else 0 end) as activeOngoingCount"),
                DB::raw("count(statuses.name) as activeCount"),
            )
            ->join('ps_intake_statuses', 'ps_intakes.id', 'ps_intake_statuses.ps_intake_id')
            ->join('statuses', 'ps_intake_statuses.status_id', 'statuses.id')
            ->where('statuses.name', '=', 'Active')
            ->where('ps_intake_statuses.month', '>=', $months[0])
            ->where('ps_intake_statuses.month', '<=', $months[array_key_last($months)])
            ->groupBy('ps_intake_statuses.month')
            ->orderBy('ps_intake_statuses.month');      

        $resultWithoutEmptyMonths = $query->get();
        // dd($resultWithoutEmptyMonths);

        // (3) Add empty months
        $result = [];
        $i = 0;
        foreach($months2 as $month)
        {
            if($resultWithoutEmptyMonths->where('month', $month)->first()){
                $foundItem = $resultWithoutEmptyMonths->where('month', $month)->first();
                $result[$i]['month'] = $foundItem->month;
                $result[$i]['activeCount'] = $foundItem->activeCount;
                $result[$i]['activeNewCount'] = $foundItem->activeNewCount;
                $result[$i]['activeOngoingCount'] = $foundItem->activeOngoingCount;
            }
            else{
                $result[$i]['month'] = $month;
                $result[$i]['activeCount'] = "0";
                $result[$i]['activeNewCount'] = "0";
                $result[$i]['activeOngoingCount'] = "0";
            }
            $i++;
        }

        // dd($result);

        // (5) calculate New
        // $activeNewCounts = $result->mapToGroups(function($item, $key){
        //     return [$item->is_new => [$item->month => $item->count]];
            
        // });

        $activeNewCounts = collect($result)->mapWithKeys(function($item, $key){
            return [$item['month'] => $item['activeNewCount']];
        });

        $activOngoingCounts = collect($result)->mapWithKeys(function($item, $key){
            return [$item['month'] => $item['activeOngoingCount']];
        });

        $commulativeCount = 0;
        $commulativeCounts = collect($result)->mapWithKeys(function($item, $key) use(&$commulativeCount, $months){        
            if(date("Y-m", strtotime($item['month'])) == date('Y-m', strtotime($months[0]))){
                $commulativeCount = $commulativeCount + $item['activeNewCount'] + $item['activeOngoingCount'];
                return [$item['month'] => $commulativeCount];
            }
            elseif(date("Y-m", strtotime($item['month'])) <= date('Y-m')){
                $commulativeCount = $commulativeCount + $item['activeNewCount'];
                return [$item['month'] => $commulativeCount];
            }
            else{
                return [$item['month'] => ''];
            }
        });


        $data = [
            'activeNewCounts' => $activeNewCounts,
            'activOngoingCounts' => $activOngoingCounts,
            'commulativeCounts' => $commulativeCounts,
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
