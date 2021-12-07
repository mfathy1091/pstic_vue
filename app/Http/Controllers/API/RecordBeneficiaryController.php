<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Month;
use App\Models\Referral;
use App\Models\Record;
use App\Models\RecordBeneficiary;

class RecordBeneficiaryController extends Controller
{

    public function index(Request $request)
    {
        $beneficiaries =  RecordBeneficiary::with('beneficiary')->where('record_id', $request->record_id)->get();
        
        return response()->json([
            'data' => $beneficiaries,
        ]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'beneficiary_id' => 'required',
            'record_id' => 'required',
            'status' => 'required',
        ]);

        $beneficiary = RecordBeneficiary::create([
            'beneficiary_id' => $request['beneficiary_id'],
            'record_id' => $request['record_id'],
            'status' => $request['status'],
        ]);

        $servicesIds = collect($request->input('services'))->pluck('id');
        $beneficiary->services()->sync($servicesIds);
        
        $disabilitiesIds = collect($request->input('disabilities'))->pluck('id');
        $beneficiary->disabilities()->sync($disabilitiesIds);

        return $beneficiary;
        

    }

    public function update(Request $request, $id)
    {
        $beneficiary = RecordBeneficiary::findOrFail($id);

        if($beneficiary){
            
            $this->validate($request, [
                'beneficiary_id' => 'required',
                'record_id' => 'required',
                'status' => 'required',
            ]);

            // if it exists
            if($beneficiary){
                // update first
                $beneficiary->update([
                    'beneficiary_id' => $request['beneficiary_id'],
                    'record_id' => $request['record_id'],
                    'status' => $request['status'],
                ]);

                // then sync
                // $servicesIds = collect($request->input('services'))->pluck('id');
                // $beneficiary->services()->sync($servicesIds);

                // $disabilitiesIds = collect($request->input('disabilities'))->pluck('id');
                // $beneficiary->disabilities()->sync($disabilitiesIds);
            }

            // $record = Record::findOrFail($request->record_id);
            // $recordBeneficiariesCount = $record->beneficiaries->count();

            // $recordBeneficiariesHasServicesCount = RecordBeneficiary::where('record_id', $record->id)->has('services')->count();

            // // make record active, if it has at least one beneficiary and if all beneficiaries has services
            // if($recordBeneficiariesCount > 0 && ($recordBeneficiariesHasServicesCount === $recordBeneficiariesCount)){
            //     $record->status_id = 1;
            //     $record->save();
            // }else{
            //     $record->status_id = 2;
            //     $record->save();
            // }

            
            return ['message' => 'Beneficiary updated'];
        }
    }

    public function destroy($id)
    {
        $beneficiary = RecordBeneficiary::findOrFail($id);

        // if it exists
        if($beneficiary){
            // detach first
            $beneficiary->services()->detach();
            $beneficiary->disabilities()->detach();
            // then delete
            $beneficiary->delete();
        }

        //$record = Record::findOrFail($request->record_id);
        $record = $beneficiary->record;
        $recordBeneficiariesCount = $record->beneficiaries->count();

        $recordBeneficiariesHasServicesCount = RecordBeneficiary::where('record_id', $record->id)->has('services')->count();

        // make record active, if it has at least one beneficiary and if all beneficiaries has services
        if($recordBeneficiariesCount > 0 && ($recordBeneficiariesHasServicesCount === $recordBeneficiariesCount)){
            $record->status_id = 1;
            $record->save();
        }else{
            $record->status_id = 2;
            $record->save();
        }

        return ['message' => 'Beneficiary deleted'];
    }

}
