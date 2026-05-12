<?php

namespace App\Actions\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;

class RegisterUser
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
    }

    public function execute(array $data)
    {
        $user =  User::create(
            [
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]
        );

        event(new Registered($user));

        return $user;
    }
}
