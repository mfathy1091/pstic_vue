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
            'activity_id' => 'required',
        ]);

        $service = Service::create([
            'activity_id' => $request['activity_id'],
        ]);
        

        $data = [
            'data' => $service,
        ];

        return response($data, 201);

    }

    

    public function show($id)
    {
        //
    }



    public function update(Request $request, $id)
    {
        $servicetype = Servicetype::findOrFail($id);

        if($servicetype){
            
            $this->validate($request, [
                'name' => 'required|string|max:255',
            ]);

            if($servicetype){
                $servicetype->update([
                    'name' => $request['name'],
                ]);
            }
            
            return ['message' => 'Nationality updated'];
        }

    }


    public function destroy($id)
    {
        $servicetype = Servicetype::findOrFail($id);

        // if it exists
        if($servicetype){
            // then delete
            $servicetype->delete();
        }

        return ['message' => 'Service deleted'];
    }
}



