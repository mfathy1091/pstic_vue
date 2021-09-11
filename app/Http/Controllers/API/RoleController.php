<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Role;

class RoleController extends Controller
{

    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Role::latest()->with('permissions')->paginate(10);
        
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

        $role = Role::create([
            'name' => $request['name'],
        ]);
        
        $permissionsIds = $request->input('permissions_ids');
        $role->permissions()->sync($permissionsIds);


        return $role;
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
        $role = Role::findOrFail($id);

        if($role){
            
            $this->validate($request, [
                'name' => 'required|string|max:255',
            ]);

            // if it exists
            if($role){
                // update first
                $role->update([
                    'name' => $request['name'],
                ]);

                // then sync
                $permissionsIds = $request->input('permissions_ids');
                $role->permissions()->sync($permissionsIds);
            }
            
            return ['message' => 'Role updated'];
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $role = Role::findOrFail($id);

        // if it exists
        if($role){
            // detach first
            $role->permissions()->detach();
            // then delete
            $role->delete();
        }

        return ['message' => 'Role deleted'];
    }
}
