<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Http\Response;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request){
        $fields = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|unique:users, email',
            'password' => 'required|string|confirmed',
        ]);

        $user = User::create([
            'name' => $fields['name'],
            'email' => $fields['email'],
            'password' => bcrypt($fields['password'])
        ]);

        // create token
        $token = $user->createToken('myapptoken')->plainTextToken;

        // return the created user and token
        $data = [
            'user' => $user,
            'access_token' => $token
        ];

        return response($data, 201);
    }

    public function login(Request $request){
        $fields = $request->validate([
            'email' => 'required|string',   // remove 'unique:users, email'
            'password' => 'required|string',    // remove 'confirmed'
        ]);

        // Check email
        $user = User::where('email', $fields['email'])->first();

        // Check if the user exists and if the password is correct
        if(!$user || !Hash::check($fields['password'], $user->password)){
            $response = [
                'message' => 'Not matched cretentials',
            ];
            
            return response($response, 401);
        }

        // create token
        $token = $user->createToken('myapptoken')->plainTextToken;

        // return the logged-in user and token
        $response = [
            'user' => $user,
            'access_token' => $token
        ];

        return response($response, 200);
    }


    public function logout(Request $request){
        // delete the token
        auth()->user()->tokens()->delete();

        return [
            'message' => 'Logged Out',
        ];
    }

}