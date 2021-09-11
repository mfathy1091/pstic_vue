<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Nationality;

class NationalityController extends Controller
{

    
    public function index()
    {
        $nationalities =  Nationality::all();
        return ['data' => $nationalities];
    }


    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
        ]);

        $nationality = Nationality::create([
            'name' => $request['name'],
        ]);
        


        return $nationality;
    }

    public function show($id)
    {
        //
    }



    public function update(Request $request, $id)
    {
        $nationality = Nationality::findOrFail($id);

        if($nationality){
            
            $this->validate($request, [
                'name' => 'required|string|max:255',
            ]);

            if($nationality){
                $nationality->update([
                    'name' => $request['name'],
                ]);
            }
            
            return ['message' => 'Nationality updated'];
        }

    }


    public function destroy($id)
    {
        $nationality = Nationality::findOrFail($id);

        // if it exists
        if($nationality){
            // then delete
            $nationality->delete();
        }

        return ['message' => 'Nationality deleted'];
    }
}



