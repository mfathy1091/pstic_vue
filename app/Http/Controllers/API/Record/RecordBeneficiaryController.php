<?php

namespace App\Http\Controllers\API\Record;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Month;
use App\Models\Referral;
use App\Models\Record;
use App\Models\RecordBeneficiary;

class RecordBeneficiaryController extends Controller
{

    public function index(Request $request)
    {
        $beneficiaries =  RecordBeneficiary::with('individual.casee', 'services', 'disabilities')->where('record_id', $request->record_id)->get();
        
        return response()->json([
            'data' => $beneficiaries,
        ]);
    }



}
