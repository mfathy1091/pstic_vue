<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Status;

class StatusController extends Controller
{
    public function index(Request $request)
    {
        $statuses =  Status::where('type', 'Psychosocial')->withCount(['records' => function($q){
            $q->withFilters();
        }])
        ->get();

        $data = [
            'data' => $statuses,
        ];

        return response($data, 200);
    }

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


