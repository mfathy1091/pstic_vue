<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Casee;
use App\Models\Individual;

class IndividualController extends Controller
{
    public function liveSearch(Request $request)
    {
        $data = Individual::where('name', 'LIKE','%'.$request->keyword.'%')->get();

        $file_number = $request->keyword;

        $individuals = Individual::query();

        $data = $individuals->with('casee')->whereHas('casee', function($q) use ($file_number){
            return $q->where('number', 'like', "%$file_number%");
        })->get();


        return response()->json($data); 
    }


    public function show($id)
    {
        $individual = Individual::with('casee', 'casee.individuals', 'relationship', 'gender', 'nationality')->findOrFail($id);

        if($individual){
            return ['data' => $individual];
        }

        return ['message' => 'Individual does not exist'];
    }

    public function update(Request $request, $id)
    {
        $individual = Individual::findOrFail($id);

        if($individual){

            $this->validate($request, [
                'casee_id' => 'required|string|max:255',
                'passport_number' => 'required|string|max:255',
                'name' => 'required',
                'age' => 'required|string|max:255', 
                'is_registered' => '', 
                'individual_id' => 'required|string|max:255|unique:individuals,individual_id,'.$individual->id,
                'gender_id' => 'required', 
                'nationality_id' => 'required', 
                'relationship_id' => 'required', 
                'current_phone_number' => 'required', 
            ]);

            $individual->update([
                'casee_id' => $request->casee_id,
                'passport_number' => $request->passport_number,
                'name' => $request->name,
                'age' => $request->age,
                'is_registered' => $request->is_registered,
                'individual_id' => $request->individual_id,
                'gender_id' => $request->gender_id,
                'nationality_id' => $request->nationality_id,
                'relationship_id' => $request->relationship_id,
                'current_phone_number' => $request->current_phone_number,
            ]);
            
            return ['message' => 'User updated'];
        }

    }

    public function unlinkCasee($individual_id){
        
        
        $individual = Individual::findOrFail($individual_id);
        if($individual){
            $individual->casee()->dissociate();
            $individual->save();
            return response()->json([
                'data' => $individual,
                'message' => 'case is unlinked'
            ]);
        }
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'casee_id' => 'required|string|max:255',
            'passport_number' => 'required|string|max:255',
            'name' => 'required',
            'age' => 'required|string|max:255', 
            'is_registered' => '', 
            'individual_id' => 'required|string|max:255|unique:individuals',
            'gender_id' => 'required', 
            'nationality_id' => 'required', 
            'relationship_id' => 'required', 
            'current_phone_number' => 'required', 
        ]);

        $individual = Individual::create([
            'casee_id' => $request->casee_id,
            'passport_number' => $request->passport_number,
            'name' => $request->name,
            'age' => $request->age,
            'is_registered' => $request->is_registered,
            'individual_id' => $request->individual_id,
            'gender_id' => $request->gender_id,
            'nationality_id' => $request->nationality_id,
            'relationship_id' => $request->relationship_id,
            'current_phone_number' => $request->current_phone_number,
        ]);

        return response()->json([
            'message'=>'Added Successfully!!',
            'individual'=>$individual
        ]);
    }

    public function destroy($id)
    {
        $individual = Individual::findOrFail($id);

        // if it exists
        if($individual){
            // then delete
            $individual->delete();
        }

        return ['message' => 'Individual deleted'];
    }

    public function getOtherCaseeIndividuals($individual_id)
    {
        $individual = Individual::findOrFail($individual_id);
        $casee = $individual->casee;

        // if it exists
        if($individual){
            // return ['data' => $casee->individuals()->with('relationship', 'gender', 'nationality')->get()];

            // $users = User::all()->except($currentUser->id);
            return ['data' => $casee->individuals()->with('relationship', 'gender', 'nationality')->get()->except($individual_id)];
        }

        return ['data' => ''];




        // $file_number = $request->keyword;

        // $individuals = Individual::query();

        // $data = $individuals->with('casee')->whereHas('casee', function($q) use ($file_number){
        //     return $q->where('number', '=', "%$file_number%");
        // })->get();


        // return response()->json($data); 
    }

}



