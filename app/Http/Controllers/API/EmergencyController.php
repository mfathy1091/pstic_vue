<?php

namespace App\Http\Controllers\API;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Emergency;

class EmergencyController extends Controller
{

    
    public function index()
    {
        $emergencies =  Emergency::with('emergencyType', 'user')->get();

        $data = [
            'data' => $emergencies,
        ];

        return response($data, 200);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'record_id' => 'required',
            'emergency_date' => 'required',
            'comment' => 'required',
            'emergency_type_id' => 'required',
        ]);

        $emergency = Emergency::create([
            'record_id' => $request['record_id'],
            'emergency_date' => $request['emergency_date'],
            'comment' => $request['comment'],
            'emergency_type_id' => $request['emergency_type_id'],
            'user_id' => Auth::id(),
        ]);
        
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
                'emergency_date' => 'required',
                'comment' => 'required',
                'emergency_type_id' => 'required',
            ]);

            $emergency->update([
                'record_id' => $request['record_id'],
                'emergency_date' => $request['emergency_date'],
                'comment' => $request['comment'],
                'emergency_type_id' => $request['emergency_type_id'],
                'user_id' => Auth::id(),
            ]);
            
            $data = [
                'data' => $emergency,
            ];
            
            return response($data, 201);
        }else{
            return response('', 201);
        }


        

    }

    
}
