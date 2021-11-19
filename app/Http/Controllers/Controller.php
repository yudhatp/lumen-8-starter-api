<?php

namespace App\Http\Controllers;

use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Facades\JWTFactory;
use Tymon\JWTAuth\Facades\JWTAuth\Token;
use Tymon\JWTAuth\Payload;
// or
//use JWTAuth;
use Illuminate\Support\Facades\Auth;
use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{

    /*protected function jwt($user) {
        //$credentials = request(['email', 'password']);

        //$tokenWithCustom = auth('api')->attempt($credentials);

        //$customClaims = ['foo' => 'bar', 'baz' => 'bob'];

        $customClaims = [
            'iss' => "rspr-jwt", // Issuer of the token
            'iat' => time(), // Time when JWT was issued. 
            'exp' => time() + 60*60, // Expiration time
            'nbf' => time(), //timestamp
            'sub' => $user->cnomr,
            'jti' => uniqid()
        ];

        $payload = JWTFactory::make($customClaims);
        var_dump($payload);
        /*$factory = JWTFactory::addClaims([
            'sub' => $user->cnomr,
        ]);
        $payload = $factory->make();*/
        //$token = JWTAuth::encode($payload);
        //$payload = JWTFactory::make($credentials + ['sub' => json_encode($credentials)]);

        // As you can see we are passing `JWT_SECRET` as the second parameter that will 
        // be used to decode the token in the future.
        //$token = JWTAuth::encode($payload);
        //$tokenString = (string) $token;

        //$token = JWTAuth::fromUser($user);

        //return $token;
    //}*/

    protected function respondWithToken($token, $user)
    {
        return response()->json([
            'token' => $token,
            'name'  => $user->name,
            'email' => $user->email,
            'token_type' => 'bearer',
            'expires_in' => Auth::factory()->getTTL() * 60
        ], 200);
    }
}
