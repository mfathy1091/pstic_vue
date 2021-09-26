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

        $service = Service::create([
            'name' => $request['name'],
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

        return ['message' => 'Service deleted'];
    }
}



