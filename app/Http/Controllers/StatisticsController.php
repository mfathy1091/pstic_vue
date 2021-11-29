<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Emergency;

class StatisticsController extends Controller
{

    
    public function getEmergencies()
    {
        $emergencies =  Emergency::all();

        $data = [
            'data' => $emergencies,
        ];

        return response($data, 200);
    }


}



