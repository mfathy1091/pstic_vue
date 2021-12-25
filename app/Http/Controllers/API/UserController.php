<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

use App\Models\User;

class UserController extends Controller
{

    public function index()
    {
        $data = [
            'data' => User::latest()->with('roles')->paginate(10),
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
        ]);

        $user = User::create([
            'first_name' => $request['first_name'],
            'last_name' => $request['last_name'],
            'email' => $request['email'],
            // 'photo' => $request['photo'],
            'password' => Hash::make($request['password']),
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
            ]);

            $user->update([
                'first_name' => $request['first_name'],
                'last_name' => $request['last_name'],
                'email' => $request['email'],
                // 'photo' => $request['photo'],
                
                // 'password' => Hash::make($request['password']),
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
