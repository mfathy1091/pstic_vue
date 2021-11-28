<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Activity;

class ActivityController extends Controller
{

    
    public function index()
    {
        $activities =  Activity::all();

        $data = [
            'data' => $activities,
        ];

        return response($data, 200);
    }


}



