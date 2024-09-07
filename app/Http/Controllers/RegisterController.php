<?php

namespace App\Http\Controllers;

use App\Actions\Fortify\CreateNewUser;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Laravel\Fortify\Fortify;

class RegisterController extends Controller
{
    /**
     * Handle an authentication attempt.
     */
    public function __invoke(Request $request, CreateNewUser $creator): UserResource
    {
        // Check the config and replace the username in the request inputs in lowercase.
        if (config('fortify.lowercase_usernames')) {
            $request->merge([
                Fortify::username() => Str::lower($request->{Fortify::username()}),
            ]);
        }

        // Create the user.
        $user = $creator->create($request->all());

        // Return the new user.
        return new UserResource($user);
    }
}
