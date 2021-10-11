<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\File;

class FileController extends Controller
{

    public function show($id)
    {
        $file = File::with('individuals', 'referrals', 'individuals.nationality', 'individuals.gender','individuals.relationship')->findOrFail($id);

        if($file){
            return ['data' => $file];
        }

        return ['message' => 'File does not exist'];
    }

    public function update(Request $request, $id)
    {
        $file = File::findOrFail($id);

        if($file){
            
            $this->validate($request, [
                'number' => 'required|string|max:255',
            ]);
            
            // update first
            $file->update([
                'number' => $request->number,
                'created_user_id' => '1',
            ]);

            // then sync
            // $linkedIndividualsIds = collect($request->input('linked_individuals'))->pluck('id');
            // $file->linkedIndividuals()->sync($linkedIndividualsIds);
            
            return ['message' => 'File updated'];
        }

    }

    public function exists($n)
    {
        $file = File::where('number', $n)->first();

        // if it exists
        if($file){
            return ['data' => true];
        }

        return ['data' => false];
    }

    public function getIndividuals($id)
    {
        $file = File::findOrFail($id);

        // if it exists
        if($file){
            return ['data' => $file->individuals()->with('relationship', 'gender', 'nationality')->get()];
        }

        return ['data' => ''];

        // $number = $request->keyword;

        // $individuals = Individual::query();

        // $data = $individuals->with('file')->whereHas('file', function($q) use ($number){
        //     return $q->where('number', '=', "%$number%");
        // })->get();


        // return response()->json($data); 
    }
    

    public function createOrGetFile(Request $request)
    {

        // dd($request->number);

        $this->validate($request, [
            'number' => 'required|string|max:255',
        ]);
        $file = File::where('number',  $request->number)->first();

        // create it if doesn't exists
        if(!$file){
            $file = File::create([
                'number' => $request->number,
                'created_user_id' => '1',
            ]);

            return response()->json([
                'data' => $file,
                'isNewFile' => true,
            ]);
        }
        return response()->json([
            'data' => $file,
            'isNewFile' => false,
        ]);
    }

}



