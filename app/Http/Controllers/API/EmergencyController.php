<?php

namespace App\Http\Controllers\API;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Emergency;
use App\Models\Record;
class EmergencyController extends Controller
{

    
    public function index()
    {
        $emergencies =  Emergency::with('emergencyType', 'user', 'beneficiaries')->get();

        $data = [
            'data' => $emergencies,
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
            'emergency_type_id' => 'required',
        ]);

        $emergency = Emergency::create([
            'record_id' => $request['record_id'],
            'referral_id' => $request['referral_id'],
            'casee_id' => $request['casee_id'],
            'emergency_date' => $request['emergency_date'],
            'comment' => $request['comment'],
            'emergency_type_id' => $request['emergency_type_id'],
            'user_id' => Auth::id(),
        ]);

        $beneficiariesIds = collect($request->input('beneficiaries'))->pluck('id');
        $emergency->beneficiaries()->sync($beneficiariesIds);

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
                'emergency_type_id' => 'required',
            ]);

            $emergency->update([
                'record_id' => $request['record_id'],
                'referral_id' => $request['referral_id'],
                'casee_id' => $request['casee_id'],
                'emergency_date' => $request['emergency_date'],
                'comment' => $request['comment'],
                'emergency_type_id' => $request['emergency_type_id'],
                'user_id' => Auth::id(),
            ]);
            
            $beneficiariesIds = collect($request->input('beneficiaries'))->pluck('id');
            $emergency->beneficiaries()->sync($beneficiariesIds);

            $data = [
                'data' => $emergency,
            ];
            
            return response($data, 201);
        }else{
            return response('', 201);
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
