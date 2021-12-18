<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HousingReferral;
use Illuminate\Support\Facades\Auth;
Use Exception;

class HousingReferralController extends Controller
{
    public function getCaseeHousingReferrals(Request $request)
    {
        $housingReferrals =  HousingReferral::with(
            'referralSource',
            'assignedHousingAdvocate',
            'grantStatus')
            ->where('casee_id', $request->casee_id)->get();

        $data = [
            'data' => $housingReferrals,
        ];

        return response($data, 200);
    }

    public function getCurrentHousingAdvocateHousingReferrals(Request $request)
    {
        $housingReferrals =  HousingReferral::with(
            'referralSource',
            'assignedHousingAdvocate',
            'grantStatus')
            ->where('casee_id', $request->casee_id)
            ->where('current_assigned_psw', Auth::id())
            ->get();

        $data = [
            'data' => $housingReferrals,
        ];

        return response($data, 200);
    }


    public function store(Request $request)
    {
        $this->validate($request, [
            'referral_source_id' => 'required',
            'referral_date' => 'required',
            'referring_person_name' => 'required',
            'referring_person_email' => 'required|email',
            'referral_narrative_reason' => 'required',
            'grant_status_id' => 'required',
            'grant_amount' => 'required',
            'assigned_housing_advocate_id' => '',

        ]);

        // Create Referral
        $housingReferral = HousingReferral::create([
            'casee_id' => $request->casee_id,
            'referral_source_id' => $request->referral_source_id,
            'referral_date' => $request->referral_date,
            'referring_person_name' => $request->referring_person_name,
            'referring_person_email' => $request->referring_person_email,
            'referral_narrative_reason' => $request->referral_narrative_reason,
            'grant_status_id' => $request->grant_status_id,
            'grant_amount' => $request->grant_amount,
            'assigned_housing_advocate_id' => Auth::id(),
        ]);

        $data = [
            'referral' => $housingReferral,
            'message' => 'created successfully'
        ];

        return response($data, 201);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'referral_source_id' => 'required',
            'referral_date' => 'required',
            'referring_person_name' => 'required',
            'referring_person_email' => 'required|email',
            'referral_narrative_reason' => 'required',
            'grant_status_id' => 'required',
            'grant_amount' => 'required',
            'assigned_housing_advocate_id' => '',

        ]);

        try
        {
            $housingReferral = HousingReferral::findOrFail($id);

            $housingReferral->update([
                'casee_id' => $request->casee_id,
                'referral_source_id' => $request->referral_source_id,
                'referral_date' => $request->referral_date,
                'referring_person_name' => $request->referring_person_name,
                'referring_person_email' => $request->referring_person_email,
                'referral_narrative_reason' => $request->referral_narrative_reason,
                'grant_status_id' => $request->grant_status_id,
                'grant_amount' => $request->grant_amount,
                'assigned_housing_advocate_id' => Auth::id(),
            ]);


            $data = [
                'referral' => $housingReferral,
                'message' => 'created successfully'
            ];

            return response($data, 200);
        }
        catch(Exception $e)
        {
            dd($e->getMessage());
        }


    }
}
