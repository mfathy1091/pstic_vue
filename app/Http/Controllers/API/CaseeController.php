<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Casee;

class CaseeController extends Controller
{

    public function search(Request $request){
        // if($request->filled('search_text'))
        // dd($request);
        $casees = Casee::with('individuals', 'referrals', 'individuals.nationality', 'individuals.gender','individuals.relationship')
                ->where('file_number', $request->fileNumber)->get();

        $response = [
            'number' => $request,
            'data' => $casees,
        ];

        return response($response, 200);
    }

    public function index(){
        $casees = Casee::with('individuals', 'referrals', 'individuals.nationality', 'individuals.gender','individuals.relationship')->get();
        
        // return the created user and token
        $response = [
            'data' => $casees,
        ];

        return response($response, 200);
    }


    public function show($id)
    {
        $casee = Casee::with('individuals', 'referrals', 'individuals.nationality', 'individuals.gender','individuals.relationship')->findOrFail($id);

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
            // $linkedIndividualsIds = collect($request->input('linked_individuals'))->pluck('id');
            // $casee->linkedIndividuals()->sync($linkedIndividualsIds);
            
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

    public function getIndividuals($id)
    {
        $casee = Casee::findOrFail($id);

        // if it exists
        if($casee){
            return ['data' => $casee->individuals()->with('relationship', 'gender', 'nationality')->get()];
        }

        return ['data' => ''];

        // $number = $request->keyword;

        // $individuals = Individual::query();

        // $data = $individuals->with('casee')->whereHas('casee', function($q) use ($number){
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



