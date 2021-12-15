<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Casee;
use App\Models\Beneficiary;


class CaseeController extends Controller
{

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


    public function index(){
        $casees = Casee::with('beneficiaries', 'referrals', 'beneficiaries.nationality', 'beneficiaries.gender','beneficiaries.relationship')->get();
        
        // return the created user and token
        $response = [
            'data' => $casees,
        ];

        return response($response, 200);
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

}



