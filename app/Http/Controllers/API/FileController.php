<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\File;

class FileController extends Controller
{

    public function exists($n)
    {
        $file = File::where('number', $n)->first();

        // if it exists
        if($file){
            return ['data' => true];
        }

        return ['data' => false];
    }

    public function get($n)
    {
        $file = File::where('number', $n)->first();

        // if it exists
        if($file){
            return ['data' => $file ];
        }

        return ['data' => ''];
    }

    public function getIndividuals($id)
    {
        $file = File::findOrFail($id);

        // if it exists
        if($file){
            return ['data' => $file->individuals];
        }

        return ['data' => ''];
    }
    

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
        ]);

        $file = File::create([
            'name' => $request['name'],
        ]);
        


        return $file;
    }
}



