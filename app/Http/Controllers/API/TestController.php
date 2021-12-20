<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Record;

class TestController extends Controller
{

    
    public function index()
    {
        $records =  Record::all();

        $data = [
            'data' => $records,
        ];

        return response($data, 201);
    }



}



