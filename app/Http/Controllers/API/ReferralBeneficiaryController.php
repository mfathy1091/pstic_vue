<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Beneficiary;
use App\Models\ReferralBeneficiary;
use Illuminate\Support\Facades\DB;
Use Exception;
use Carbon\Carbon;

class ReferralBeneficiaryController extends Controller
{
    public function index(Request $request)
    {
        $referralBeneficiaries = DB::table('beneficiaries')
        ->join('referrals_beneficiaries', 'beneficiaries.id', '=', 'referrals_beneficiaries.beneficiary_id');
        
        if($request->filled('is_active')){
            $referralBeneficiaries->where('is_active', '=', 1);
        }

        if($request->filled('referral_id')){
            $referralBeneficiaries->where('referral_id', '=', $request->referral_id);
        }

        $data = [
            'data' => $referralBeneficiaries->get(),
        ];

        return response($data, 200);
    }

    public function search(Request $request)
    {

        $dates = DB::table('provided_services')->select('provision_date')->distinct()->get()->pluck('provision_date')->sort();
        
        $years = $dates->map(function ($date) {
            return Carbon::parse($date)->format('Y');
        })->unique();

        $selectedYear = $request->year;
        $selectedYearDates = DB::table('provided_services')->whereYear('provision_date', $selectedYear)->select('provision_date')->distinct()->get()->pluck('provision_date')->sort();
        
        $months = $selectedYearDates->map(function ($date) {
            return Carbon::parse($date)->format('m');
        })->unique();

       //  $monthsWithKeys = $months->collect();
        $monthsWithKeys = $months->collect()->mapWithKeys(function($item, $key){
            return[
                $key => $item,
            ];
        
        });


        // if($request->filled('year')){
        //     $referralBeneficiaries->whereYear('provision_date', '=', $request->year);
        // }
        
        $referralBeneficiaries = ReferralBeneficiary::query();

        $referralBeneficiaries->withCount(['providedPss' => function ($query) use($request)
        {
            if($request->filled('year')){
                $query->whereYear('provision_date', '=', $request->year);
            }
            if($request->filled('month')){
                $query->whereMonth('provision_date', '=', $request->month);
            }
        }]);

        $referralBeneficiaries->withCount(['providedInfoSharing' => function ($query) use($request)
        {
            if($request->filled('year')){
                $query->whereYear('provision_date', '=', $request->year);
            }
            if($request->filled('month')){
                $query->whereMonth('provision_date', '=', $request->month);
            }
        }]);

        $referralBeneficiaries->withCount(['providedBasicNeed' => function ($query) use($request)
        {
            if($request->filled('year')){
                $query->whereYear('provision_date', '=', $request->year);
            }
            if($request->filled('month')){
                $query->whereMonth('provision_date', '=', $request->month);
            }
        }]);

        $referralBeneficiaries->with('providedServices', function($q) use($request){
            // $q->withCount('providedServices.');

            // $q->select('provided_services.*',
            //     DB::raw("sum (case when provided_services.service_type_id = '1' then 1 else 0 end) as pss"),
            //     DB::raw("sum (case when provided_services.service_type_id = '5' then 1 else 0 end) as housing_advocacy"),);
            
            // $q->groupBy('service_type_id')->count();

            // if($request->filled('year')){
            //     $q->whereYear('provision_date', '=', $request->year);
            // }
            // if($request->filled('month')){
            //     $q->whereMonth('provision_date', '=', $request->month);
            // }
            
            $q->with('ServiceType');
            // $q->join('service_types', 'provided_services.id', '=', 'provided_services.referral_beneficiary_id');
            //$q->select(['service_type_id', 'name', 'id']);
            return $q;

        })

        ->whereHas('providedServices', function($q) use($request){
            if($request->filled('year')){
                $q->whereYear('provision_date', '=', $request->year);
            }
            if($request->filled('month')){
                $q->whereMonth('provision_date', '=', $request->month);
            }
            return $q;
        });

        $referralBeneficiaries->with(
            'beneficiary.casee',
            'beneficiary.relationship',
            'beneficiary.gender',
            'beneficiary.nationality',
            // 'providedServices.serviceType',
            // 'emergencies',
        );

        // $test = return $referralBeneficiaries->get()->mapToDictionary(function ($item){
        //     return [$item['product'] =>]
        // });

        $data = [
            'data' => $referralBeneficiaries->get(),
            'years' => $years,
            'months' =>$months,
            'monthsWithKeys' => $monthsWithKeys,
            'referralBeneficiaries' => $referralBeneficiaries->get(),
        ];

        return response($data, 200);
    }


    public function getStats(Request $request)
    {
        // $ranges = [ // the start of each age-range.
        //     '18-24' => 18,
        //     '25-35' => 25,
        //     '36-45' => 36,
        //     '46+' => 46
        // ];

        // $output = Beneficiary::query()
        //     ->get()
        //     ->map(function ($beneficiary) use ($ranges) {
        //         // $age = Carbon::parse($beneficiary->dob)->age;
        //         $age = $beneficiary->age;
        //         foreach($ranges as $key => $breakpoint)
        //         {
        //             if ($breakpoint >= $age)
        //             {
        //                 $beneficiary->range = $key;
        //                 break;
        //             }
        //         }

        //         return $beneficiary;
        //     })
        //     ->mapToGroups(function ($beneficiary, $key) {
        //         return [$beneficiary->range => $beneficiary];
        //     })
        //     ->map(function ($group) {
        //         return count($group);
        //     })
        //     ->sortKeys();

        // dd($output);
        
        // $query_test = DB::table('beneficiaries')
        //     ->join('nationalities', 'nationalities.id', '=', 'beneficiaries.nationality_id')
        //     ->select('nationality_id', 
        //             DB::raw('count(*) as total'), 
        //             DB::raw("sum (case when beneficiaries.age > 0 and beneficiaries.age <= 5 and beneficiaries.gender_id = '1' then 1 else 0 end) as age_0_5_m"), 
        //             DB::raw("sum (case when beneficiaries.age > 0 and beneficiaries.age <= 5 and beneficiaries.gender_id = '2' then 1 else 0 end) as age_0_5_f"),
        //             DB::raw("sum (case when beneficiaries.age >= 6 and beneficiaries.age <= 9 and beneficiaries.gender_id = '1' then 1 else 0 end) as age_6_9_m"), 
        //             DB::raw("sum (case when beneficiaries.age >= 6 and beneficiaries.age <= 9 and beneficiaries.gender_id = '2' then 1 else 0 end) as age_6_9_f"),
        //             DB::raw("sum (case when beneficiaries.age >= 10 and beneficiaries.age <= 14 and beneficiaries.gender_id = '1' then 1 else 0 end) as age_10_14_m"), 
        //             DB::raw("sum (case when beneficiaries.age >= 10 and beneficiaries.age <= 14 and beneficiaries.gender_id = '2' then 1 else 0 end) as age_10_14_f"),
        //             DB::raw("sum (case when beneficiaries.age >= 15 and beneficiaries.age <= 17 and beneficiaries.gender_id = '1' then 1 else 0 end) as age_15_17_m"), 
        //             DB::raw("sum (case when beneficiaries.age >= 15 and beneficiaries.age <= 17 and beneficiaries.gender_id = '2' then 1 else 0 end) as age_15_17_f"),
        //             DB::raw("sum (case when beneficiaries.age >= 18 and beneficiaries.age <= 24 and beneficiaries.gender_id = '1' then 1 else 0 end) as age_18_24_m"), 
        //             DB::raw("sum (case when beneficiaries.age >= 18 and beneficiaries.age <= 24 and beneficiaries.gender_id = '2' then 1 else 0 end) as age_18_24_f"),
        //             DB::raw("sum (case when beneficiaries.age >= 25 and beneficiaries.age <= 49 and beneficiaries.gender_id = '1' then 1 else 0 end) as age_25_49_m"), 
        //             DB::raw("sum (case when beneficiaries.age >= 25 and beneficiaries.age <= 49 and beneficiaries.gender_id = '2' then 1 else 0 end) as age_25_49_f"),
        //             DB::raw("sum (case when beneficiaries.age >= 50 and beneficiaries.age <= 59 and beneficiaries.gender_id = '1' then 1 else 0 end) as age_50_59_m"), 
        //             DB::raw("sum (case when beneficiaries.age >= 50 and beneficiaries.age <= 59 and beneficiaries.gender_id = '2' then 1 else 0 end) as age_50_59_f"),
        //             DB::raw("sum (case when beneficiaries.age >= 60 and beneficiaries.gender_id = '1' then 1 else 0 end) as age_gt_60_m"), 
        //             DB::raw("sum (case when beneficiaries.age >= 60 and beneficiaries.gender_id = '2' then 1 else 0 end) as age_gt_60_f"),
        // );

        // $query1test = clone $query_test;
        // $result1test = $query1test->groupBy('nationality_id')->with('nationality')->get()->collect();
        $test = Beneficiary::join('referrals_beneficiaries', 'beneficiaries.id', '=', 'referrals_beneficiaries.beneficiary_id')
            ->join('provided_services', 'referrals_beneficiaries.beneficiary_id', '=', 'provided_services.referral_beneficiary_id')
            ->select('beneficiaries.*', 'referrals_beneficiaries.status', DB::raw("sum (case when provided_services.service_type_id = 1 then 1 else 0 end) as pss"))
            ->get();

        $query = Beneficiary::join('referrals_beneficiaries', 'beneficiaries.id', '=', 'referrals_beneficiaries.beneficiary_id')
            ->join('provided_services', 'referrals_beneficiaries.beneficiary_id', '=', 'provided_services.referral_beneficiary_id')
            ->select(
                DB::raw("sum (case when provided_services.service_type_id = 1 then 1 else 0 end) as pss"),
                'nationality_id', 
                DB::raw('count(*) as total'),
                DB::raw("sum (case when beneficiaries.age > 0 and beneficiaries.age <= 5 and beneficiaries.gender_id = '1' then 1 else 0 end) as age_0_5_m"), 
                DB::raw("sum (case when beneficiaries.age > 0 and beneficiaries.age <= 5 and beneficiaries.gender_id = '2' then 1 else 0 end) as age_0_5_f"),
                DB::raw("sum (case when beneficiaries.age >= 6 and beneficiaries.age <= 9 and beneficiaries.gender_id = '1' then 1 else 0 end) as age_6_9_m"), 
                DB::raw("sum (case when beneficiaries.age >= 6 and beneficiaries.age <= 9 and beneficiaries.gender_id = '2' then 1 else 0 end) as age_6_9_f"),
                DB::raw("sum (case when beneficiaries.age >= 10 and beneficiaries.age <= 14 and beneficiaries.gender_id = '1' then 1 else 0 end) as age_10_14_m"), 
                DB::raw("sum (case when beneficiaries.age >= 10 and beneficiaries.age <= 14 and beneficiaries.gender_id = '2' then 1 else 0 end) as age_10_14_f"),
                DB::raw("sum (case when beneficiaries.age >= 15 and beneficiaries.age <= 17 and beneficiaries.gender_id = '1' then 1 else 0 end) as age_15_17_m"), 
                DB::raw("sum (case when beneficiaries.age >= 15 and beneficiaries.age <= 17 and beneficiaries.gender_id = '2' then 1 else 0 end) as age_15_17_f"),
                DB::raw("sum (case when beneficiaries.age >= 18 and beneficiaries.age <= 24 and beneficiaries.gender_id = '1' then 1 else 0 end) as age_18_24_m"), 
                DB::raw("sum (case when beneficiaries.age >= 18 and beneficiaries.age <= 24 and beneficiaries.gender_id = '2' then 1 else 0 end) as age_18_24_f"),
                DB::raw("sum (case when beneficiaries.age >= 25 and beneficiaries.age <= 49 and beneficiaries.gender_id = '1' then 1 else 0 end) as age_25_49_m"), 
                DB::raw("sum (case when beneficiaries.age >= 25 and beneficiaries.age <= 49 and beneficiaries.gender_id = '2' then 1 else 0 end) as age_25_49_f"),
                DB::raw("sum (case when beneficiaries.age >= 50 and beneficiaries.age <= 59 and beneficiaries.gender_id = '1' then 1 else 0 end) as age_50_59_m"), 
                DB::raw("sum (case when beneficiaries.age >= 50 and beneficiaries.age <= 59 and beneficiaries.gender_id = '2' then 1 else 0 end) as age_50_59_f"),
                DB::raw("sum (case when beneficiaries.age >= 60 and beneficiaries.gender_id = '1' then 1 else 0 end) as age_gt_60_m"), 
                DB::raw("sum (case when beneficiaries.age >= 60 and beneficiaries.gender_id = '2' then 1 else 0 end) as age_gt_60_f"),
        );

        // $query2 = clone $query1;
        
        $query1 = clone $query;
        $result1 = $query1->groupBy('nationality_id')->with('nationality')->get()->collect();
        
        $query2 = clone $query;
        $result2 = $query2->get()->collect();
        
        $stats = $result1->merge($result2);
        $data = [
            'stats' => $stats,
            'result1' => $result1,
            'result2' => $result2,
            // 'result1test' => $result1test,
            // 'totals' => $query2->get(),
            'test' => $test,
        ];

        return response($data, 200);
    }
    // $stats = Beneficiary::select('nationality_id', 
    // DB::raw('count(*) as total'), 
    // DB::raw("sum (case when beneficiary.age > 0 and beneficiary.age <= 5 and beneficiary.gender = 'male' then 1 else 0 end) as age_0_5_m") 
    // )->groupBy('nationality_id');
    



    // "UNION
    //     SELECT
    //         logentry.id,
    //        "TOTAL",
    //         COUNT(logentry.id) AS total,
    //         sum(case when logentry.age > 0 AND logentry.age <= 5 AND logentry.gender ='Male' Then 1 else 0 end) As age_0_5_M,
    //         sum(case when logentry.age > 0 AND logentry.age <= 5 AND logentry.gender ='Female' Then 1 else 0 end) As age_0_5_F,
    //         sum(case when logentry.age >= 6 AND logentry.age <= 9 AND logentry.gender ='Male' Then 1 else 0 end) As age_6_9_M,
    //         sum(case when logentry.age >= 6 AND logentry.age <= 9 AND logentry.gender ='Female' Then 1 else 0 end) As age_6_9_F,
    //         sum(case when logentry.age >= 10 AND logentry.age <= 14 AND logentry.gender ='Male' Then 1 else 0 end) As age_10_14_M,
    //         sum(case when logentry.age >= 10 AND logentry.age <= 14 AND logentry.gender ='Female' Then 1 else 0 end) As age_10_14_F,
    //         sum(case when logentry.age >= 15 AND logentry.age <= 17 AND logentry.gender ='Male' Then 1 else 0 end) As age_15_17_M,
    //         sum(case when logentry.age >= 15 AND logentry.age <= 17 AND logentry.gender ='Female' Then 1 else 0 end) As age_15_17_F,
    //         sum(case when logentry.age >= 18 AND logentry.age <= 24 AND logentry.gender ='Male' Then 1 else 0 end) As age_18_24_M,
    //         sum(case when logentry.age >= 18 AND logentry.age <= 24 AND logentry.gender ='Female' Then 1 else 0 end) As age_18_24_F,
    //         sum(case when logentry.age >= 25 AND logentry.age <= 49 AND logentry.gender ='Male' Then 1 else 0 end) As age_25_49_M,
    //         sum(case when logentry.age >= 25 AND logentry.age <= 49 AND logentry.gender ='Female' Then 1 else 0 end) As age_25_49_F,
    //         sum(case when logentry.age >= 50 AND logentry.age <= 59 AND logentry.gender ='Male' Then 1 else 0 end) As age_50_59_M,
    //         sum(case when logentry.age >= 50 AND logentry.age <= 59 AND logentry.gender ='Female' Then 1 else 0 end) As age_50_59_F,
    //         sum(case when logentry.age >= 60 AND logentry.gender ='Male' Then 1 else 0 end) As age_gt60_M,
    //         sum(case when logentry.age >= 60 AND logentry.gender ='Female' Then 1 else 0 end) As age_gt60_F
    //     FROM caselog_logentry AS logentry"

}
