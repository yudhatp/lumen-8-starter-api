<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use  App\User;

class UserController extends Controller
{
     /**
     * Instantiate a new UserController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function changePassword(Request $request)
    {
        try{
            $this->validate($request, [
                'email' => 'required|string',
                'current_password' => 'required|string',
                'new_password' => 'required|string',
                'new_confirm_password' => 'required|string',
            ]);

            $user = User::where('email', $request->email)
                ->first(['email','password']);

            if($user) {
                //check passwordnya
                if (!Hash::check($request->current_password, $user->password)) {
                    return response()->json(['message' => 'Password Salah, Pastikan Input Dengan Benar'], 200);
                }else{
                    if($request->new_password != $request->new_confirm_password){
                        return response()->json(['message' => 'Konfirmasi Password Baru Salah, Pastikan Input Dengan Benar'], 200);
                    }else{
                        User::where('email', $request->email)->update(['password'=> Hash::make($request->new_password)]);
                        return response()->json(['message' => 'success'], 200);
                    }
                }
            }else{
                return response()->json(['message' => 'Unauthorized'], 401);
            }
        } catch (\Exception $e) {

            return response()->json(['message' => 'Ganti Password Gagal, Pastikan Input Dengan Benar'], 200);
        }
    }

    /**
     * Get the authenticated User.
     *
     * @return Response
     */
    public function profile()
    {
        return response()->json(['user' => Auth::user()], 200);
    }

    /**
     * Get all User.
     *
     * @return Response
     */
    public function allUsers()
    {
         return response()->json(['users' =>  User::all()], 200);
    }

    /**
     * Get one user.
     *
     * @return Response
     */
    public function singleUser($id)
    {
        try {
            $user = User::findOrFail($id);

            return response()->json(['user' => $user], 200);

        } catch (\Exception $e) {

            return response()->json(['message' => 'user not found!'], 404);
        }

    }

}
