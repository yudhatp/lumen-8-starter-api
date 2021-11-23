<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{

    //Get a JWT via given credentials
    public function login(Request $request)
    {
        //validate incoming request 
        $this->validate($request, [
            'email' => 'required|string',
            'password' => 'required|string',
        ]);
        
        if (!$token = Auth::attempt(['email' => $request->email, 'password' => $request->password]) ) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }else{
            $user = Auth::user();
            return $this->respondWithToken($token, $user);
        }
    }

}