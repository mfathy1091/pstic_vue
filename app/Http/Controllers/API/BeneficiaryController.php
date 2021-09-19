<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Month;
use App\Models\Referral;
use App\Models\Record;
use App\Models\Beneficiary;

class BeneficiaryController extends Controller
{

    public function index(Request $request)
    {
        $beneficiaries =  Beneficiary::with('individual')->where('record_id', $request->record_id)->get();
        
        return response()->json([
            'data' => $beneficiaries,
        ]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'individual_id' => 'required',
            'record_id' => 'required',
            'is_direct' => 'required',
        ]);

        $beneficiary = Beneficiary::create([
            'individual_id' => $request['individual_id'],
            'record_id' => $request['record_id'],
            'is_direct' => $request['is_direct'],
        ]);

        $servicesIds = collect($request->input('services'))->pluck('id');
        $beneficiary->services()->sync($servicesIds);
        
        $disabilitiesIds = collect($request->input('disabilities'))->pluck('id');
        $beneficiary->disabilities()->sync($disabilitiesIds);

        return $beneficiary;
        

    }

    public function update(Request $request, $id)
    {
        $beneficiary = Beneficiary::findOrFail($id);

        if($beneficiary){
            
            $this->validate($request, [
                'individual_id' => 'required',
                'record_id' => 'required',
                'is_direct' => 'required',
            ]);

            // if it exists
            if($beneficiary){
                // update first
                $beneficiary->update([
                    'individual_id' => $request['individual_id'],
                    'record_id' => $request['record_id'],
                    'is_direct' => $request['is_direct'],
                ]);

                // then sync
                $servicesIds = collect($request->input('services'))->pluck('id');
                $beneficiary->services()->sync($servicesIds);

                $disabilitiesIds = collect($request->input('disabilities'))->pluck('id');
                $beneficiary->disabilities()->sync($disabilitiesIds);
            }
            
            return ['message' => 'Beneficiary updated'];
        }
    }

    public function destroy($id)
    {
        $beneficiary = Beneficiary::findOrFail($id);

        // if it exists
        if($beneficiary){
            // detach first
            $beneficiary->services()->detach();
            $beneficiary->disabilities()->detach();
            // then delete
            $beneficiary->delete();
        }

        return ['message' => 'Beneficiary deleted'];
    }

}
