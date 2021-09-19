<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Reason;

class ReasonController extends Controller
{

    
    public function index()
    {
        $reasons =  Reason::all();

        return response()->json([
            'data' => $reasons,
        ]);
    }


    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
        ]);

        $reason = Reason::create([
            'name' => $request['name'],
        ]);
        


        return $reason;
    }

    public function show($id)
    {
        //
    }



    public function update(Request $request, $id)
    {
        $reason = Reason::findOrFail($id);

        if($reason){
            
            $this->validate($request, [
                'name' => 'required|string|max:255',
            ]);

            if($reason){
                $reason->update([
                    'name' => $request['name'],
                ]);
            }
            
            return ['message' => 'Nationality updated'];
        }

    }


    public function destroy($id)
    {
        $reason = Reason::findOrFail($id);

        // if it exists
        if($reason){
            // then delete
            $reason->delete();
        }

        return ['message' => 'Nationality deleted'];
    }
}



