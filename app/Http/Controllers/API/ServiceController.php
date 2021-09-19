<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Service;

class ServiceController extends Controller
{

    
    public function index()
    {
        $services =  Service::all();

        return response()->json([
            'data' => $services,
        ]);
    }


    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
        ]);

        $recordId = $request->input('record_id');
        $servicseIds = $request->input('services_ids');

        foreach($servicseIds as $serviceId){
            Benefit::create([
                'record_id' => $recordId,
                'beneficiary_id' => $request->input('beneficiary_id'),
                'service_id' => $serviceId
            ]);
        }

        // foreach( $beneficiariesIds as $beneficiaryId){
        //     Benefit::create([
        //         'beneficiary_id' => $beneficiaryId,
        //         'record_id' => $recordId,
        //         'service_id' => $serviceId,
        //     ]);
        // }

        // make the record inactive if it has no activities
        $record = Record::find($recordId);
        $record->status_id = 1;
        $record->save();







        $service = Service::create([
            'name' => $request['name'],
        ]);
        
        $i = 0;
        foreach($monthsCodes as $monthCode)
        {
            $i++;
            // insert Records
            $month = Month::where('code', $monthCode)->firstOrFail();
            if($i == 1){
                $is_new = 1;
            }else{
                $is_new = 0;
            }
            $record = Record::create([
                'month_id' => $month->id,
                'referral_id' => $referral->id,
                'status_id' => '2',
                'is_new' => $is_new,
                'is_emergency' => '0',
            ]);


        return $service;
    }

    public function show($id)
    {
        //
    }



    public function update(Request $request, $id)
    {
        $service = Service::findOrFail($id);

        if($service){
            
            $this->validate($request, [
                'name' => 'required|string|max:255',
            ]);

            if($service){
                $service->update([
                    'name' => $request['name'],
                ]);
            }
            
            return ['message' => 'Nationality updated'];
        }

    }


    public function destroy($id)
    {
        $service = Service::findOrFail($id);

        // if it exists
        if($service){
            // then delete
            $service->delete();
        }

        return ['message' => 'Nationality deleted'];
    }
}



