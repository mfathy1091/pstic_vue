<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Status;

class StatusController extends Controller
{
    public function getHousingGrantStatuses(Request $request)
    {
        $housingGrantStatuses =  Status::where('type', 'Housing Grant')
        ->get();

        $data = [
            'data' => $housingGrantStatuses,
        ];

        return response($data, 200);
    }

    public function getBeneficiaryStatuses(Request $request)
    {
        $beneficiaryStatuses =  Status::where('type', 'Beneficiary')
        ->get();

        $data = [
            'data' => $beneficiaryStatuses,
        ];

        return response($data, 200);
    }

}


