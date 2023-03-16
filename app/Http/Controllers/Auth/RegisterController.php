<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequests;
use App\Models\User;

class RegisterController extends Controller
{

    public function register(RegisterRequests $request)
    {
        $validatedData = [
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => bcrypt($request['password']),
            'phone' => $request['phone'],
            'role' => 'ROLE_SEEKER',
        ];

        $user = User::create($validatedData);
        $accessToken = $user->createToken('authToken')->accessToken;
        return response(['user' => $user, 'message' => 'Register successfully'], 201);
    }
}
