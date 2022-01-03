<?php

namespace App\Http\Controllers\API;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Emergency;
use App\Models\Record;
class EmergencyController extends Controller
{

    public function index(Request $request)
    {
        $emergencies = Emergency::query();
        // year_id: '2021',
        // month_id: '',
        // user_id: '',
        // direct_only: '',

        if($request->user_id != ''){
            $emergencies->where('user_id', $request->user_id);
        }

        $emergencies->whereHas('record', function($q) use($request){
            if($request->month_id != ''){
                $q->where('month_id', $request->month_id);
            }
            // if($request->month_id != -1){
            //     $q->where('month_id', $request->month_id);
            // }
            // if($request->status_id != -1){
            //     $q->where('status_id', $request->status_id);
            // }
            return $q;
        });

        $emergencies->with(
            'casee',
            'beneficiary',
            'emergencyTypes',
            'user',
            'record', 
            'record.month', 
            'record.status');

        $stats = 

        $data = [
            'data' => $emergencies->get(),
        ];

        return response($data, 200);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'record_id' => 'required',
            'referral_id' => 'required',
            'casee_id' => 'required',
            'emergency_date' => 'required',
            'comment' => 'required',
            'beneficiary_id' => 'required',
            // 'emergency_types' => 'required',
        ]);

        $emergency = Emergency::create([
            'record_id' => $request['record_id'],
            'referral_id' => $request['referral_id'],
            'casee_id' => $request['casee_id'],
            'emergency_date' => $request['emergency_date'],
            'comment' => $request['comment'],
            'beneficiary_id' => $request['beneficiary_id'],
            'user_id' => Auth::id(),
        ]);

        $emergencyTypesIds = collect($request->input('emergency_types'))->pluck('id');
        $emergency->emergencyTypes()->sync($emergencyTypesIds);

        $data = [
            'data' => $emergency,
        ];

        return response($data, 201);

    }

    public function update(Request $request, $id)
    {
        $emergency = Emergency::findOrFail($id);

        if($emergency){
            
            $this->validate($request, [
                'record_id' => 'required',
                'referral_id' => 'required',
                'casee_id' => 'required',
                'emergency_date' => 'required',
                'comment' => 'required',
                'beneficiary_id' => 'required',
            ]);

            $emergency->update([
                'record_id' => $request['record_id'],
                'referral_id' => $request['referral_id'],
                'casee_id' => $request['casee_id'],
                'emergency_date' => $request['emergency_date'],
                'comment' => $request['comment'],
                'beneficiary_id' => $request['beneficiary_id'],
                'user_id' => Auth::id(),
            ]);
            
            $emergencyTypesIds = collect($request->input('emergency_types'))->pluck('id');
            $emergency->emergencyTypes()->sync($emergencyTypesIds);

            $data = [
                'data' => $emergency,
            ];
            
            return response($data, 200);
        }

    }

    public function destroy($id)
    {
        $emergency = emergency::findOrFail($id);

        if($emergency){
            $emergency->delete();
        }

        return ['message' => 'Service deleted'];
    }

    
}
