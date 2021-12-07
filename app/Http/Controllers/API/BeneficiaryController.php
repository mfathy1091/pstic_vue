<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Casee;
use App\Models\Beneficiary;

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


        return response()->json($data); 
    }


    public function show($id)
    {
        $beneficiary = Beneficiary::with('casee', 'casee.beneficiaries', 'relationship', 'gender', 'nationality')->findOrFail($id);

        if($beneficiary){
            return ['data' => $beneficiary];
        }

        return ['message' => 'Does not exist'];
    }

    public function update(Request $request, $id)
    {
        $beneficiary = Beneficiary::findOrFail($id);

        if($beneficiary){

            $this->validate($request, [
                'casee_id' => 'required|string|max:255',
                'passport_number' => 'required|string|max:255',
                'name' => 'required',
                'age' => 'required|string|max:255', 
                'is_registered' => '', 
                'beneficiary_id' => 'required|string|max:255|unique:beneficiaries,beneficiary_id,'.$beneficiary->id,
                'gender_id' => 'required', 
                'nationality_id' => 'required', 
                'relationship_id' => 'required', 
                'current_phone_number' => 'required', 
            ]);

            $beneficiary->update([
                'casee_id' => $request->casee_id,
                'passport_number' => $request->passport_number,
                'name' => $request->name,
                'age' => $request->age,
                'is_registered' => $request->is_registered,
                'beneficiary_id' => $request->beneficiary_id,
                'gender_id' => $request->gender_id,
                'nationality_id' => $request->nationality_id,
                'relationship_id' => $request->relationship_id,
                'current_phone_number' => $request->current_phone_number,
            ]);
            
            return ['message' => 'User updated'];
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

    public function store(Request $request)
    {
        $this->validate($request, [
            'casee_id' => 'required|string|max:255',
            'passport_number' => 'required|string|max:255',
            'name' => 'required',
            'age' => 'required|string|max:255', 
            'is_registered' => '', 
            'beneficiary_id' => 'required|string|max:255|unique:beneficiaries',
            'gender_id' => 'required', 
            'nationality_id' => 'required', 
            'relationship_id' => 'required', 
            'current_phone_number' => 'required', 
        ]);

        $beneficiary = Beneficiary::create([
            'casee_id' => $request->casee_id,
            'passport_number' => $request->passport_number,
            'name' => $request->name,
            'age' => $request->age,
            'is_registered' => $request->is_registered,
            'beneficiary_id' => $request->beneficiary_id,
            'gender_id' => $request->gender_id,
            'nationality_id' => $request->nationality_id,
            'relationship_id' => $request->relationship_id,
            'current_phone_number' => $request->current_phone_number,
        ]);

        return response()->json([
            'message'=>'Added Successfully!!',
            'beneficiary'=>$beneficiary
        ]);
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



