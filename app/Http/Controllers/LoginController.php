<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Login
     *
     * @group Authentication
     * @unauthenticated
     * @response 201 {"token": "5|L5iNL6oVTXdBfEiIo7n8mE5yKQVngBlYD3eTSZ4xc9dd8b3b"}
     * @response 401 {"message": "The provided credentials do not match our records."}
     * @response 422 {"message": "The password field is required.", "errors" : { "password" : []}}
     */
    public function __invoke(Request $request): JsonResponse
    {
        // Validate the request or throw 422 error response.
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Attempt to authenticate the user with the given credentials.
        if (Auth::attempt($credentials)) {
            // Return an access token upon successful authentication.
            return new JsonResponse(
                data: ['token' => Auth::user()->createToken('access_token')->plainTextToken],
                status: 200
            );
        }

        // Return an authentication error if the user cannot be authenticated using the given credentials.
        return new JsonResponse(
            data: ['message' => 'The provided credentials do not match our records.'],
            status: 401
        );
    }
}
