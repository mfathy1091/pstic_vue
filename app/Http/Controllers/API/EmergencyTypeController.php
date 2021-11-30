<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\EmergencyType;

class EmergencyTypeController extends Controller
{

    
    public function index()
    {
        $emergencyTypes =  EmergencyType::all();

        return response()->json([
            'data' => $emergencyTypes,
        ]);
    }

}



