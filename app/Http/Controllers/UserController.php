<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use  App\User;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function changePassword(Request $request)
    {
        try{
            $this->validate($request, [
                'email'                 => 'required|string',
                'current_password'      => 'required|string',
                'new_password'          => 'required|string',
                'new_confirm_password'  => 'required|string',
            ]);

            $user = User::where('email', $request->email)
                ->first(['email','password']);

            if($user) {
                //check password
                if (!Hash::check($request->current_password, $user->password)) {
                    return response()->json(['message' => 'Wrong password'], 200);
                }else{
                    if($request->new_password != $request->new_confirm_password){
                        return response()->json(['message' => 'Confirmation password not match'], 200);
                    }else{
                        User::where('email', $request->email)->update(['password'=> Hash::make($request->new_password)]);
                        return response()->json(['message' => 'success'], 200);
                    }
                }
            }else{
                return response()->json(['message' => 'Unauthorized'], 401);
            }
        } catch (\Exception $e) {

            return response()->json(['message' => 'Change password failed'], 200);
        }
    }

}
