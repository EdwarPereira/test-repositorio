<?php

namespace App\Providers;

use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Auth\Authenticatable as UserContract;
use Illuminate\Support\Str;

class GlobalsatUserProvider extends EloquentUserProvider
{


    public function validateCredentials(UserContract $user, array $credentials)
    {

        $plain = $credentials['password'];


        return $plain == $user->getAuthPassword();
    }



    public function retrieveByCredentials(array $credentials)
    {
        // First we will add each credential element to the query as a where clause.
        // Then we can execute the query and, if we found a user, return it in a
        // Eloquent User "model" that will be utilized by the Guard instances.
        $query = $this->createModel()->newQuery();

        foreach ($credentials as $key => $value) {
            if (! Str::contains($key, 'password')) {
                $query->where($key, strtolower($value));
            }
        }

        return $query->first();
    }






    public function updateRememberToken(UserContract $user, $token)
    {
        //
    }




}
