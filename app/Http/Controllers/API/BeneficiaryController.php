<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Casee;
use App\Models\Beneficiary;
use App\Models\ReferralBeneficiary;
use App\Models\ServiceType;
Use Exception;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


class BeneficiaryController extends Controller
{

    public function getMonthlyReferralBeneficiaries(Request $request)
    {
        $referralBeneficiaries = ReferralBeneficiary::join('beneficiaries', 'referrals_beneficiaries.beneficiary_id', 'beneficiaries.id')
        ->join('casees', 'casees.id', 'beneficiaries.casee_id')
        ->select('referrals_beneficiaries.id', 'casees.file_number', 'beneficiaries.file_individual_number', 'beneficiaries.name', 'referrals_beneficiaries.is_direct');

        if($request->start_date !='' && $request->end_date !=''){
            $referralBeneficiaries->with('providedServices', function($q) use($request){
                $q->where('provision_date', '>=', $request->start_date);
                $q->where('provision_date', '<=', $request->end_date);
                return $q->with('ServiceType');
            });

        
            $referralBeneficiaries->whereHas('providedServices', function($q) use($request){
                $q->where('provision_date', '>=', $request->start_date);
                $q->where('provision_date', '<=', $request->end_date);
                return $q;
            });
        }else
        {
            $referralBeneficiaries->with('providedServices', function($q) use($request){
                $q->where('provision_date', '>=', Carbon::now()->startOfMonth()->format('Y-m-d'));
                $q->where('provision_date', '<=', Carbon::now()->format('Y-m-d'));
                return $q->with('ServiceType');
            });

        
            $referralBeneficiaries->whereHas('providedServices', function($q) use($request){
                $q->where('provision_date', '>=', Carbon::now()->startOfMonth()->format('Y-m-d'));
                $q->where('provision_date', '<=', Carbon::now()->format('Y-m-d'));
                return $q;
            });
        }

        if($request->is_direct != ''){
            $referralBeneficiaries->where('is_direct', $request->is_direct);
        }

        // $query = Referral::join('casees', 'referrals.casee_id', 'casees.id');
        // if($request->user_id == 'current_user'){
        //     $query->where('current_assigned_psw_id', Auth::id());
        // }
        // elseif($request->user_id != ''){
        //     $query->where('current_assigned_psw_id', $request->user_id);
        // }
        // New at Certain month
        // $query->whereMonth('referral_date', '=', date('m'));

        // $ReferralsQuery = clone $query;

        // if($request->start_date !='' && $request->end_date !=''){
        //     $ReferralsQuery->whereHas('activities', function($q) use($request){
        //         $q->where('activity_date', '>=', $request->start_date);
        //         $q->where('activity_date', '<=', $request->end_date);
        //         return $q;
        //     });
        // }

        
        // $ReferralsQuery->with(
        //     //'referral.casee',
        //     // 'referral.beneficiaries',
        //     // 'referral.emergencies',
        //     // 'activities.providedServices.serviceType',
        //     'referralSource',
        //     'current_assigned_psw',
        //     'directReferralBeneficiaries',
        //     'inDirectReferralBeneficiaries',
        // );
        



        $data = [
            'data' => $referralBeneficiaries->get(),
            // 'statusesCount' => $statusesCountResult,
        ];

        return response($data, 200);
    }



    public function liveSearch(Request $request)
    {
        $data = Beneficiary::where('name', 'LIKE','%'.$request->keyword.'%')->get();

        $file_number = $request->keyword;

        $beneficiaries = Beneficiary::query();

        $data = $beneficiaries->with('casee')->whereHas('casee', function($q) use ($file_number){
            return $q->where('number', 'like', "%$file_number%");
        })->get();


        return response()->json([
            'data' => $data,
        ]);
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


        $query = Beneficiary::select('nationality_id', 
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

        $referralBeneficiaries = DB::table("referrals_beneficiaries")
        ->join('beneficiaries', 'referrals_beneficiaries.beneficiary_id', '=', 'beneficiaries.id');
        if($request->filled('year')){
            $referralBeneficiaries->whereYear('provision_date', '=', $request->year);
        }

        $beneficiaries = Beneficiary::query();

        $beneficiaries->with('providedServices', function($q) use($request){
            if($request->filled('year')){
                $q->whereYear('provision_date', '=', $request->year);
            }
            if($request->filled('month')){
                $q->whereMonth('provision_date', '=', $request->month);
            }
            return $q->with('ServiceType');
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

        $beneficiaries->with(
            'casee',
            'relationship',
            'gender',
            'nationality',
            // 'providedServices.serviceType',
            // 'emergencies',
        );

        $data = [
            'data' => $beneficiaries->get(),
            'years' => $years,
            'months' =>$months,
            'monthsWithKeys' => $monthsWithKeys,
            'referralBeneficiaries' => $referralBeneficiaries->get(),
        ];

        return response($data, 200);
    }

    public function index2(Request $request)
    {
        $beneficiaries = ReferralBeneficiary::query();



        if($request->casee_id != ""){
            $beneficiaries->where('casee_id', $request->casee_id);
        }

        if($request->referral_id != ""){
            $beneficiaries->whereHas('referrals', function($q) use($request){
                $q->where('referral_id', $request->referral_id);
                return $q;
            });
        }

        $beneficiaries->with(
            'casee',
            'relationship',
            'gender',
            'nationality',
            // 'providedServices.serviceType',
            // 'emergencies',
        );

        $data = [
            'data' => $beneficiaries->get(),
        ];

        return response($data, 200);
    }

    public function index(Request $request)
    {

        $beneficiaries = Beneficiary::join('casees', 'casees.id', 'beneficiaries.casee_id')
        ->select('beneficiaries.*', 'casees.file_number');

        if($request->casee_id != ""){
            $beneficiaries->where('casee_id', $request->casee_id);
        }

        if($request->is_active != ""){
            $beneficiaries->where('is_active', $request->is_active);
        }

        if($request->referral_id != ""){
            $beneficiaries->whereHas('referrals', function($q) use($request){
                $q->where('referral_id', $request->referral_id);
                return $q;
            });
        }

        $beneficiaries->with(
            'casee',
            'relationship',
            'gender',
            'nationality',
            // 'providedServices.serviceType',
            // 'emergencies',
        );

        $data = [
            'data' => $beneficiaries->get(),
        ];

        return response($data, 200);
    }
    

    public function show($id)
    {
        $beneficiary = Beneficiary::with('casee', 'casee.beneficiaries', 'relationship', 'gender', 'nationality')->findOrFail($id);

        if($beneficiary){
            return ['data' => $beneficiary];
        }

        return ['message' => 'Does not exist'];
    }


    public function store(Request $request)
    {
        $this->validate($request, [
            'casee_id' => 'required',
            'name' => 'required',
            'passport_number' => 'required|string|max:255',
            'age' => 'required|string|max:255',
            'gender_id' => 'required', 
            'nationality_id' => 'required',
            'phone_number' => 'required', 
            'is_active' => 'required', 

            'is_registered' => '', 
            // 'file_individual_number' => 'required|string|max:255|unique:beneficiaries',
            'file_individual_number' => '', 
            'relationship_id' => '', 
        ]);

        $beneficiary = Beneficiary::create([
            'casee_id' => $request->casee_id,
            'name' => $request->name,
            'passport_number' => $request->passport_number,
            'age' => $request->age,
            'gender_id' => $request->gender_id,
            'nationality_id' => $request->nationality_id,
            'phone_number' => $request->phone_number,
            'is_active' => $request->is_active,

            'is_registered' => $request->is_registered,
            'file_individual_number' => $request->file_individual_number,
            'relationship_id' => $request->relationship_id,
        ]);

        $data = [
            'beneficiary' => $beneficiary,
            'message' => 'created successfully'
        ];

        return response($data, 201);
    }

    public function deactivateBeneficiary(Request $request, $id)
    {
        try 
        {
            $beneficiary = Beneficiary::findOrFail($id);


            $beneficiary->update([
                'is_active' => '0',
            ]);
            
            $data = [
                'beneficiary' => $beneficiary,
                'message' => 'Updated successfully'
            ];
    
            return response($data, 201);  
        } 
        catch(Exception $e)
        {
            dd($e->getMessage());
        }

    }

    public function activateBeneficiary(Request $request, $id)
    {
        try 
        {
            $beneficiary = Beneficiary::findOrFail($id);


            $beneficiary->update([
                'is_active' => '1',
            ]);
            
            $data = [
                'beneficiary' => $beneficiary,
                'message' => 'Updated successfully'
            ];
    
            return response($data, 201);  
        } 
        catch(Exception $e)
        {
            dd($e->getMessage());
        }

    }

    public function update(Request $request, $id)
    {
        try 
        {
            $beneficiary = Beneficiary::findOrFail($id);

            $this->validate($request, [
                'casee_id' => 'required',
                'name' => 'required',
                'passport_number' => 'required|string|max:255',
                'age' => 'required|string|max:255',
                'gender_id' => 'required', 
                'nationality_id' => 'required',
                'phone_number' => 'required', 
                // 'is_active' => 'required', 
    
                'is_registered' => '', 
                'file_individual_number' => 'string|max:255|unique:beneficiaries,file_individual_number,'.$beneficiary->id,
                'relationship_id' => '', 
            ]);

            $beneficiary->update([
                'casee_id' => $request->casee_id,
                'name' => $request->name,
                'passport_number' => $request->passport_number,
                'age' => $request->age,
                'gender_id' => $request->gender_id,
                'nationality_id' => $request->nationality_id,
                'phone_number' => $request->phone_number,
                // 'is_active' => $request->is_active,
    
                'is_registered' => $request->is_registered,
                'file_individual_number' => $request->file_individual_number,
                'relationship_id' => $request->relationship_id,
            ]);
            
            $data = [
                'beneficiary' => $beneficiary,
                'message' => 'Updated successfully'
            ];
    
            return response($data, 201);  
        } 
        catch(Exception $e)
        {
            dd($e->getMessage());
        }

    }

    public function unlinkCasee($beneficiary_id){
        
        
        $beneficiary = Beneficiary::findOrFail($beneficiary_id);
        if($beneficiary){
            $beneficiary->casee()->dissociate();
            $beneficiary->save();
            return response()->json([
                'data' => $beneficiary,
                'message' => 'case is unlinked'
            ]);
        }
    }



    public function destroy($id)
    {
        $beneficiary = Beneficiary::findOrFail($id);

        // if it exists
        if($beneficiary){
            // then delete
            $beneficiary->delete();
        }

        return ['message' => 'beneficiary deleted'];
    }

    public function getOtherCaseebeneficiaries($beneficiary_id)
    {
        $beneficiary = Beneficiary::findOrFail($beneficiary_id);
        $casee = $beneficiary->casee;

        // if it exists
        if($beneficiary){
            // return ['data' => $casee->beneficiaries()->with('relationship', 'gender', 'nationality')->get()];

            // $users = User::all()->except($currentUser->id);
            return ['data' => $casee->beneficiaries()->with('relationship', 'gender', 'nationality')->get()->except($beneficiary_id)];
        }

        return ['data' => ''];




        // $file_number = $request->keyword;

        // $beneficiaries = Beneficiary::query();

        // $data = $beneficiaries->with('casee')->whereHas('casee', function($q) use ($file_number){
        //     return $q->where('number', '=', "%$file_number%");
        // })->get();


        // return response()->json($data); 
    }

}



