<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\ServiceType;

class ServiceTypeController extends Controller
{

    
    public function index()
    {
        $serviceTypes =  ServiceType::all();

        return response()->json([
            'data' => $serviceTypes,
        ]);
    }


    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
        ]);

        $servicetType = ServiceType::create([
            'name' => $request['name'],
        ]);
        


        return $servicetType;
    }

    public function show($id)
    {
        //
    }



    public function update(Request $request, $id)
    {
        $servicetType = ServiceType::findOrFail($id);

        if($servicetType){
            
            $this->validate($request, [
                'name' => 'required|string|max:255',
            ]);

            if($servicetType){
                $servicetType->update([
                    'name' => $request['name'],
                ]);
            }
            
            return ['message' => 'Nationality updated'];
        }

    }


    public function destroy($id)
    {
        $servicetType = ServiceType::findOrFail($id);

        // if it exists
        if($servicetType){
            // then delete
            $servicetType->delete();
        }

        return ['message' => 'Service deleted'];
    }
}



