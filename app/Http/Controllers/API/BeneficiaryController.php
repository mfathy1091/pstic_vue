<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Casee;
use App\Models\Beneficiary;
Use Exception;

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
            'providedServices.serviceType',
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



