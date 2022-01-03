<?php

namespace App\Http\Controllers\API;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Activity;
use App\Models\ProvidedService;

class ActivityController extends Controller
{

    public function index(Request $request)
    {
        $activities = Activity::query();
        // year_id: '2021',
        // month_id: '',
        // user_id: '',
        // direct_only: '',

        if($request->user_id != ''){
            $activities->where('user_id', $request->user_id);
        }

        $activities->whereHas('record', function($q) use($request){
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

        $activities->with(
            'casee',
            'beneficiary',
            'providedServices',
            'user',
            'record', 
            'record.month', 
            'record.status');

        $data = [
            'data' => $activities->get(),
        ];

        return response($data, 200);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'record_id' => 'required',
            'referral_id' => 'required',
            'casee_id' => 'required',
            'activity_date' => 'required',
            'comment' => 'required',
            'beneficiary_id' => 'required',
        ]);

        $activity = Activity::create([
            'record_id' => $request['record_id'],
            'referral_id' => $request['referral_id'],
            'casee_id' => $request['casee_id'],
            'activity_date' => $request['activity_date'],
            'comment' => $request['comment'],
            'beneficiary_id' => $request['beneficiary_id'],
            'user_id' => Auth::id(),
        ]);

        $serviceTypesIds = collect($request->input('service_types'))->pluck('id');
        $activity->serviceTypes()->syncWithPivotValues($serviceTypesIds, ['beneficiary_id' => $request->beneficiary_id, 'provision_date' => $request->activity_date, 'user_id' => Auth::id()]);

        $data = [
            'data' => $activity,
        ];

        return response($data, 201);

    }

    public function update(Request $request, $id)
    {
        $activity = Activity::findOrFail($id);

        if($activity){
            
            $this->validate($request, [
                'record_id' => 'required',
                'referral_id' => 'required',
                'casee_id' => 'required',
                'activity_date' => 'required',
                'comment' => 'required',
                'beneficiary_id' => 'required',
            ]);

            $activity->update([
                'record_id' => $request['record_id'],
                'referral_id' => $request['referral_id'],
                'casee_id' => $request['casee_id'],
                'activity_date' => $request['activity_date'],
                'comment' => $request['comment'],
                'beneficiary_id' => $request['beneficiary_id'],
                'user_id' => Auth::id(),
            ]);
            
            $serviceTypesIds = collect($request->input('service_types'))->pluck('id');
            $activity->serviceTypes()->syncWithPivotValues($serviceTypesIds, ['beneficiary_id' => $request->beneficiary_id, 'provision_date' => $request->activity_date, 'user_id' => Auth::id()]);

            $data = [
                'data' => $activity,
            ];
            
            return response($data, 200);
        }

    }

    public function destroy($id)
    {
        $activity = Activity::findOrFail($id);

        if($activity){
            $activity->delete();
        }

        return ['message' => 'Service deleted'];
    }

    
}
