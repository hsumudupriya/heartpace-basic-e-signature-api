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
    public function register(Request $request, CreateNewUser $creator): UserResource
    {
        if (config('fortify.lowercase_usernames')) {
            $request->merge([
                Fortify::username() => Str::lower($request->{Fortify::username()}),
            ]);
        }

        $user = $creator->create($request->all());

        return new UserResource($user);
    }
}
