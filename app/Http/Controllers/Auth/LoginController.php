<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequests;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function postLogin(LoginRequests $request)
    {
        $credentials = $request->only(['email', 'password']);
        if ($credentials) {
            if (Auth::check() && Auth::user()->last_activity < Carbon::now()->subMinutes(1)) {
                Auth::logout();
                return response()->json(['error' => 'Session expired. Please log in again.'], 401);
            }
            if (Auth::attempt($credentials)) {
                $user = Auth::user();
                $user->last_activity = Carbon::now();
                $user = $request->user();
                $success = $user->createToken('authToken')->accessToken;
                return response()->json(['message' => 'true', 'token' => $success, 'user' => $user]);
            } else {
                return response()->json(['message' => 'Unauthorised'], 401);
            }
        } else {
            return response()->json(['message' => 'Unauthorised'], 401);
        }
    }
}
