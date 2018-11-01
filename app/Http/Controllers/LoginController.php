<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function login(Request $request)
    {

        $credentials = [
            'email' => $request->get('email'),
            'password' => $request->get('password')
        ];

        if (auth()->attempt($credentials))
        {
            $token = auth()->user()->createToken('token')->accessToken;

            return response()->json([
                'access_token' => $token,
                'id' => auth()->user()->id,
                'role' => auth()->user()->role], 200);
        }
        else
        {
            return response()->json(['error' => 'Unauthorised'], 401);
        }
    }

    public function logout()
    {
        if(auth()->check())
        {
            auth()->user()->token()->delete();
        }

        return response()->json([], 204);
    }

}