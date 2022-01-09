<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Casee;
use App\Models\Beneficiary;
use App\Models\ServiceType;
Use Exception;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


class BeneficiaryController extends Controller
{
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


        $stats = Beneficiary::select('nationality_id', 
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
        // function($query){
        //     $query->where('age', '>=', 0)
        //     ->where('age', '<=', 5);
        //     return $query;
        // })
        )->groupBy('nationality_id');
        
        $data = [
            'stats' => $stats->get(),
        ];

        return response($data, 200);
    }
    // $stats = Beneficiary::select('nationality_id', 
    // DB::raw('count(*) as total'), 
    // DB::raw("sum (case when beneficiary.age > 0 and beneficiary.age <= 5 and beneficiary.gender = 'male' then 1 else 0 end) as age_0_5_m") 
    // )->groupBy('nationality_id');
    

    // DB::table("caselog_beneficiary as beneficiary")
    // ->select("beneficiary.id", 
    //         "beneficiary.nationality", 
    //         "count (beneficiary.id) as total", 
    //         "sum (case when beneficiary.age > 0 and beneficiary.age <= 5 and beneficiary.gender = 'male' then 1 else 0 end) as age_0_5_m", 
    //         "sum (case when beneficiary.age > 0 and beneficiary.age <= 5 and beneficiary.gender = 'female' then 1 else 0 end) as age_0_5_f", 
    //         "sum (case when beneficiary.age >= 6 and beneficiary.age <= 9 and beneficiary.gender = 'male' then 1 else 0 end) as age_6_9_m", 
    //         "sum (case when beneficiary.age >= 6 and beneficiary.age <= 9 and beneficiary.gender = 'female' then 1 else 0 end) as age_6_9_f", 
    //         "sum (case when beneficiary.age >= 10 and beneficiary.age <= 14 and beneficiary.gender = 'male' then 1 else 0 end) as age_10_14_m", 
    //         "sum (case when beneficiary.age >= 10 and beneficiary.age <= 14 and beneficiary.gender = 'female' then 1 else 0 end) as age_10_14_f", 
    //         "sum (case when beneficiary.age >= 15 and beneficiary.age <= 17 and beneficiary.gender = 'male' then 1 else 0 end) as age_15_17_m", 
    //         "sum (case when beneficiary.age >= 15 and beneficiary.age <= 17 and beneficiary.gender = 'female' then 1 else 0 end) as age_15_17_f", 
    //         "sum (case when beneficiary.age >= 18 and beneficiary.age <= 24 and beneficiary.gender = 'male' then 1 else 0 end) as age_18_24_m", 
    //         "sum (case when beneficiary.age >= 18 and beneficiary.age <= 24 and beneficiary.gender = 'female' then 1 else 0 end) as age_18_24_f", 
    //         "sum (case when beneficiary.age >= 25 and beneficiary.age <= 49 and beneficiary.gender = 'male' then 1 else 0 end) as age_25_49_m", 
    //         "sum (case when beneficiary.age >= 25 and beneficiary.age <= 49 and beneficiary.gender = 'female' then 1 else 0 end) as age_25_49_f", 
    //         "sum (case when beneficiary.age >= 50 and beneficiary.age <= 59 and beneficiary.gender = 'male' then 1 else 0 end) as age_50_59_m", 
    //         "sum (case when beneficiary.age >= 50 and beneficiary.age <= 59 and beneficiary.gender = 'female' then 1 else 0 end) as age_50_59_f", 
    //         "sum (case when beneficiary.age >= 60 and beneficiary.gender = 'male' then 1 else 0 end) as age_gt60_m", 
    //         "sum (case when beneficiary.age >= 60 and beneficiary.gender = 'female' then 1 else 0 end) as age_gt60_f")
    // ->get();

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
        ];

        return response($data, 200);
    }

    public function index(Request $request)
    {
        $beneficiaries = Beneficiary::query();



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



