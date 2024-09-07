<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoginControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_user_can_authenticate(): void
    {
        // Create a fake user.
        $user = User::factory()->create();

        // Make a request to the api endpoint and assert the result.
        $this->postJson('api/login', [
            'email' => $user->email,
            'password' => 'password',
        ])->assertSuccessful()
            ->assertSee('token');
    }

    public function test_a_user_can_not_authenticate_with_invalid_password(): void
    {
        // Create a fake user.
        $user = User::factory()->create();

        // Make a request to the api endpoint and assert the result.
        $this->postJson('api/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ])->assertUnauthorized()
            ->assertDontSee('token');
    }
}
