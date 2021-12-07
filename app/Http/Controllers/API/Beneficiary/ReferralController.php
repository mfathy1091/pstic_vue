<?php

namespace App\Http\Controllers\API\Individual;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Month;
use App\Models\Referral;
use App\Models\Record;
use App\Models\Beneficiary;

class ReferralController extends Controller
{

    public function index(Request $request)
    {
        $referrals =  Referral::with('referralSource')->where('original_direct_beneficiary_id', $request->beneficiary_id)->get();
        // $referrals =  Referral::all();
        
        return response()->json([
            'data' => $referrals,
        ]);
    }

}
