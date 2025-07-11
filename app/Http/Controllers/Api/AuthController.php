<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $user = Auth::user();
        $token = $user->createToken('SSO Token')->accessToken;

        return response()->json([
            'token' => $token,
            'user' => $user,
        ]);
    }

    public function user(Request $request)
    {
        return $request->user();
    }

    public function logout(Request $request)
    {
        if ($request->user()?->token()) {
            $request->user()->token()->revoke();

            // $token = session('sso_access_token');
            // if ($token) {
            //     Http::withToken($token)->post('http://website-app.test/api/logout');
            // }

            return response()->json(['message' => 'Token revoked']);
        }
        return response()->json(['message' => 'No token found'], 400);
    }
}
