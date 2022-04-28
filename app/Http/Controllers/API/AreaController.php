<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Area;

class AreaController extends Controller
{

    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $areas = Area::get();

        $content = [
            'data' => $areas
        ];
        return response($content, 200);
        
    }

        /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
        ]);

        $area = Area::create([
            'name' => $request['name'],
        ]);
        
        $permissionsIds = collect($request->input('permissions'))->pluck('id');
        $area->permissions()->sync($permissionsIds);

        return $area;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $area = Area::findOrFail($id);

        if($area){
            
            $this->validate($request, [
                'name' => 'required|string|max:255',
            ]);

            // if it exists
            if($area){
                // update first
                $area->update([
                    'name' => $request['name'],
                ]);

                // then sync
                $permissionsIds = collect($request->input('permissions'))->pluck('id');
                $area->permissions()->sync($permissionsIds);
            }
            
            return ['message' => 'Role updated'];
        }

    }


    public function destroy($id)
    {
        $area = Area::findOrFail($id);

        // if it exists
        if($area){
            // detach first
            $area->permissions()->detach();
            // then delete
            $area->delete();
        }

        return ['message' => 'Role deleted'];
    }
}
