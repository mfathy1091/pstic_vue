<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Disability;

class DisabilityController extends Controller
{

    
    public function index()
    {
        $disabilities =  Disability::all();
        return ['data' => $disabilities];
    }


    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
        ]);

        $disability = Disability::create([
            'name' => $request['name'],
        ]);
        


        return $disability;
    }

    public function show($id)
    {
        //
    }



    public function update(Request $request, $id)
    {
        $disability = Disability::findOrFail($id);

        if($disability){
            
            $this->validate($request, [
                'name' => 'required|string|max:255',
            ]);

            if($disability){
                $disability->update([
                    'name' => $request['name'],
                ]);
            }
            
            return ['message' => 'Disabiity updated'];
        }

    }


    public function destroy($id)
    {
        $disability = Disability::findOrFail($id);

        // if it exists
        if($disability){
            // then delete
            $disability->delete();
        }

        return ['message' => 'Disabiity deleted'];
    }
}



