<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

use App\Models\User;

class UserController extends Controller
{

    public function index(Request $request)
    {
        $users = User::query();
        
        if($request->name != ""){
            $users->where('first_name', 'LIKE','%'.$request->name.'%')
            ->orWhere('last_name', 'LIKE','%'.$request->name.'%');
        }
        

        if($request->role_id != ""){
            $users->whereHas('roles', function($q) use($request){
                $q->where('role_id', $request->role_id);
                return $q;
            });
        }


        if($request->is_active != ""){
            $users->where('is_active', $request->is_active);
        }

        if($request->budget_id != ""){
            $users->where('budget_id', $request->budget_id);
        }
        
        $users->with(
            'roles',
            'budget',
        );

        $data = [
            'data' => $users->paginate(10),
        ];

        return response($data, 200);
    }


    public function currentUser(){
        return Auth::user();
    }


    public function store(Request $request)
    {
        $this->validate($request, [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'budget_id' => 'required',
        ]);

        $user = User::create([
            'first_name' => $request['first_name'],
            'last_name' => $request['last_name'],
            'email' => $request['email'],
            // 'photo' => $request['photo'],
            'password' => Hash::make($request['password']),
            'budget_id' => $request['budget_id'],
            'is_active' => $request['is_active'],
        ]);

        $rolesIds = collect($request->input('roles'))->pluck('id');
        $user->roles()->sync($rolesIds);

        return $user;
    }


    public function show($id)
    {
        //
    }


    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        if($user){
            
            $this->validate($request, [
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                // if the email doesn't ahcanged, an error will occur because it is already in the database
                // and the unique rule will reject the same email because it aleady there
                // it thnks that we are duplicating
                // so you have to execulde the current user email from the unique rule
                // do not forget the comma after "email, "
                'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
                // sometimes is like unrequired
                'password' => 'sometimes|string|min:8',
                'budget_id' => 'required',
            ]);

            $user->update([
                'first_name' => $request['first_name'],
                'last_name' => $request['last_name'],
                'email' => $request['email'],
                // 'photo' => $request['photo'],
                
                // 'password' => Hash::make($request['password']),
                'budget_id' => $request['budget_id'],
                'is_active' => $request['is_active'],
            ]);

            $rolesIds = collect($request->input('roles'))->pluck('id');
            $user->roles()->sync($rolesIds);
    
            return ['message' => 'User updated'];
        }

    }


    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // if it exists
        if($user){
            // detach first
            $user->roles()->detach();
            // then delete
            $user->delete();
        }

        return ['message' => 'User deleted'];
    }
}
