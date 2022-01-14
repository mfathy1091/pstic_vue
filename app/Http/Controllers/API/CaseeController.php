<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Casee;
use App\Models\Beneficiary;
use App\Models\Referral;

use Illuminate\Support\Facades\Auth;

class CaseeController extends Controller
{
    public function getReferrals($id, Request $request)
    {
        $referrals = Referral::query();

        if($request->user_id == 'current_user'){
            $referrals->where('current_assigned_psw_id', Auth::id());
        }
        elseif($request->user_id != ''){
            $referrals->where('current_assigned_psw_id', $request->user_id);
        }
        
        if($request->casee_id != ''){
            $referrals->where('casee_id', $request->casee_id);
        }

        if($request->is_new != '' || $request->month_id != '' || $request->status_id != ''){
            $referrals->whereHas('records', function($q) use($request){
                if($request->is_new != ''){
                    $q->where('is_new', $request->is_new);
                }
                if($request->month_id != ''){
                    $q->where('month_id', $request->month_id);
                }
                if($request->status_id != ''){
                    $q->where('status_id', $request->status_id);
                }
                return $q;
            });
        }

        $referrals->with(
            'casee',
            'beneficiaries',
            'emergencies',
            'activities.providedServices.serviceType',
            'referralSource',
            'current_assigned_psw',
            'records', 
            'records.month', 
            'records.status',
            'currentRecord.status' 
        );


        $response = [
            'data' => $referrals->get(),
        ];

        return response($response, 200);
    }


    public function search(Request $request){
        // if($request->filled('search_text'))
        // dd($request);
        $casees = Casee::with('beneficiaries', 'referrals', 'beneficiaries.nationality', 'beneficiaries.gender','beneficiaries.relationship')
                ->where('file_number', $request->fileNumber)->get();

        $response = [
            'number' => $request,
            'data' => $casees,
        ];

        return response($response, 200);
    }


    public function index(Request $request){
        $casees = Casee::query();

        // $casees->has('referrals');

        // if($request->month_id != -1){
        //     $casees->where('month_id', $request->month_id);
        // }
        
        $casees->with(
            'beneficiaries',
            'referrals',
            'housingReferrals',
            'beneficiaries.nationality', 
            'beneficiaries.gender',
            'beneficiaries.relationship'
        )->withCount('referrals', 'housingReferrals');
        

        $response = [
            'data' => $casees->get(),
        ];

        return response($response, 200);





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
            'referralSource',
            'current_assigned_psw',
            'records', 
            'records.month', 
            'records.status');

    }


    public function show($id)
    {
        $casee = Casee::with('beneficiaries', 'referrals', 'beneficiaries.nationality', 'beneficiaries.gender','beneficiaries.relationship')->findOrFail($id);

        if($casee){
            return ['data' => $casee];
        }

        return ['message' => 'Case does not exist'];
    }

    public function update(Request $request, $id)
    {
        $casee = Casee::findOrFail($id);

        if($casee){
            
            $this->validate($request, [
                'file_number' => 'required|string|max:255',
            ]);
            
            // update first
            $casee->update([
                'file_number' => $request->number,
                'created_user_id' => '1',
            ]);

            // then sync
            // $linkedbeneficiariesIds = collect($request->input('linked_beneficiaries'))->pluck('id');
            // $casee->linkedbeneficiaries()->sync($linkedbeneficiariesIds);
            
            return ['message' => 'Case updated'];
        }

    }

    public function exists($n)
    {
        $casee = Casee::where('file_number', $n)->first();

        // if it exists
        if($casee){
            return ['data' => true];
        }

        return ['data' => false];
    }

    public function getbeneficiaries($id)
    {
        $casee = Casee::findOrFail($id);

        // if it exists
        if($casee){
            return ['data' => $casee->beneficiaries()->with('relationship', 'gender', 'nationality')->get()];
        }

        return ['data' => ''];

        // $number = $request->keyword;

        // $beneficiaries = Beneficiary::query();

        // $data = $beneficiaries->with('casee')->whereHas('casee', function($q) use ($number){
        //     return $q->where('file_number', '=', "%$number%");
        // })->get();


        // return response()->json($data); 
    }
    

    public function createOrGetCasee(Request $request)
    {
        $this->validate($request, [
            'file_number' => 'required|string|max:255',
        ]);
        $casee = Casee::where('file_number',  $request->file_number)->first();

        // create it if doesn't exists
        if(!$casee){
            $casee = Casee::create([
                'file_number' => $request->file_number,
                'created_user_id' => '1',
            ]);

            return response()->json([
                'data' => $casee,
                'isNewCasee' => true,
            ]);
        }
        return response()->json([
            'data' => $casee,
            'isNewCasee' => false,
        ]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'file_number' => 'required',
            'is_family' => 'required',
            // 'created_user_id' => 'required',
        ]);

        $casee = Casee::create([
            'file_number' => $request->file_number,
            'is_family' => $request->is_family,
            'created_user_id' => Auth::id(),
        ]);


        $data = [
            'data' => $casee,
        ];

        return response($data, 201);

    }

}



