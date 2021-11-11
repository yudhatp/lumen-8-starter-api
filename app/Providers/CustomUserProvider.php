<?php

namespace App\Providers;

use Illuminate\Auth\EloquentUserProvider as UserProvider;
use Illuminate\Contracts\Auth\Authenticatable as UserContract;
use App\User;

class CustomUserProvider extends UserProvider {

    /**
     * Overrides the framework defaults validate credentials method 
     *
     * @param UserContract $user
     * @param array $credentials
     * @return bool
     */
    public function validateCredentials(UserContract $user, array $credentials) {
        $plain = $credentials['password'];
        /*var_dump($plain);
        var_dump($user->password);
        var_dump($plain == $user->dtglahir);
        */
        return true; //($plain == $user->getAuthPassword());
    }

}