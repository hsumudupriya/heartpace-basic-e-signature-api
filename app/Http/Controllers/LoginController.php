<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Handle an authentication attempt.
     */
    public function login(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            return new JsonResponse(
                data: ['token' => Auth::user()->createToken('auth_token')->plainTextToken],
                status: 200
            );
        }

        return new JsonResponse(
            data: ['message' => 'The provided credentials do not match our records.'],
            status: 401
        );
    }
}
