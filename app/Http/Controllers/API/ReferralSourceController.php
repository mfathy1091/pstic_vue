<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\ReferralSource;

class ReferralSourceController extends Controller
{

    
    public function index()
    {
        $referralSources =  ReferralSource::all();
        return ['data' => $referralSources];
    }


    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
        ]);

        $referralSource = ReferralSource::create([
            'name' => $request['name'],
        ]);
        


        return $referralSource;
    }

    public function show($id)
    {
        //
    }



    public function update(Request $request, $id)
    {
        $referralSource = ReferralSource::findOrFail($id);

        if($referralSource){
            
            $this->validate($request, [
                'name' => 'required|string|max:255',
            ]);

            if($referralSource){
                $referralSource->update([
                    'name' => $request['name'],
                ]);
            }
            
            return ['message' => 'Referral Source updated'];
        }

    }


    public function destroy($id)
    {
        $referralSource = ReferralSource::findOrFail($id);

        // if it exists
        if($referralSource){
            // then delete
            $referralSource->delete();
        }

        return ['message' => 'Referral Source deleted'];
    }
}



