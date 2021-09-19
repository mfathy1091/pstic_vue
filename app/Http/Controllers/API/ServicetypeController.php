<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Servicetype;

class ServicetypeController extends Controller
{

    
    public function index()
    {
        $servicetypes =  Servicetype::all();

        return response()->json([
            'data' => $servicetypes,
        ]);
    }


    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
        ]);

        $servicetype = Servicetype::create([
            'name' => $request['name'],
        ]);
        


        return $servicetype;
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

        return ['message' => 'Nationality deleted'];
    }
}



