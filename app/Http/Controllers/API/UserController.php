<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use App\Models\User;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    
    
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return User::latest()->with('roles')->paginate(10);
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
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'type' => 'required',
        ]);

        return User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'type' => $request['type'],
            'bio' => $request['bio'],
            // 'photo' => $request['photo'],
            'password' => Hash::make($request['password']),
        ]);
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
        $user = User::findOrFail($id);

        if($user){
            
            $this->validate($request, [
                'name' => 'required|string|max:255',
                // if the email doesn't ahcanged, an error will occur because it is already in the database
                // and the unique rule will reject the same email because it aleady there
                // it thnks that we are duplicating
                // so you have to execulde the current user email from the unique rule
                // do not forget the comma after "email, "
                'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
                // sometimes is like unrequired
                'password' => 'sometimes|string|min:8',
                'type' => 'required',
            ]);

            $user->update([
                'name' => $request['name'],
                'email' => $request['email'],
                'type' => $request['type'],
                'bio' => $request['bio'],
                // 'photo' => $request['photo'],
                
                'password' => Hash::make($request['password']),
            ]);
            
            return ['message' => 'User updated'];
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
        $user = User::findOrFail($id);

        // delete the user
        if($user){
            $user->delete();
        }

        return ['message' => 'User deleted'];
    }
}
