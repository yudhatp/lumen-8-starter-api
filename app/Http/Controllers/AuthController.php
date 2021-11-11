<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\DB;
//use Tymon\JWTAuth\JWTAuth;

class AuthController extends Controller
{
    
    /*public function username(){
        return 'cnomr';
    }*/

    //Get a JWT via given credentials
    public function login(Request $request)
    {
        //validate incoming request 
        $this->validate($request, [
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        //$credentials = $request->only(['cnomr', 'dtglahir']);

        /*if (! $token = Auth::attempt($credentials)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }*/

        //var_dump($credentials);

        /*if (! $token = Auth::attempt([$this->username() => $request->cnomr, 'password' => $request->dtglahir]) ) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        
        return $this->respondWithToken($token);*/
        
        
            if (!$token = Auth::attempt(['email' => $request->email, 'password' => $request->password]) ) {
                return response()->json(['message' => 'Unauthorized'], 401);
            }else{
                //$token = $this->jwt($user);
                $user = Auth::user();
                return $this->respondWithToken($token, $user);
            }
    }

    public function register(Request $request)
    {
        //validate incoming request 
        $this->validate($request, [
            'nama' => 'required|string',
            'email' => 'required|email|unique:pengguna',
            'password' => 'required|confirmed',
        ]);

        try {

            $user = new User;
            $user->nama = $request->input('nama');
            $user->email = $request->input('email');
            $plainPassword = $request->input('password');
            $user->password = app('hash')->make($plainPassword);

            $user->save();

            //return successful response
            return response()->json(['user' => $user, 'message' => 'CREATED'], 201);

        } catch (\Exception $e) {
            //return error message
            return response()->json(['message' => 'User Registration Failed!'], 409);
        }

    }


}