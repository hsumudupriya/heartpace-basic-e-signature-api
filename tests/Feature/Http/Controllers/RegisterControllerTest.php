<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegisterControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_a_user_can_register(): void
    {
        // Make a request to the api endpoint and assert the result.
        $name = $this->faker()->name;
        $email = $this->faker()->email;

        $this->postJson('api/register', [
            'name' => $name,
            'email' => $email,
            'password' => 'password',
            'password_confirmation' => 'password',
        ])->assertSuccessful()
            ->assertExactJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'email',
                ],
            ]);

        // Assert the database has a user record.
        $this->assertDatabaseHas('users', [
            'name' => $name,
            'email' => $email,
        ]);
    }

    public function test_a_user_cannot_register_with_empty_values(): void
    {
        // Make a request to the api endpoint and assert the result.
        $this->postJson('api/register', [
            'name' => '',
            'email' => '',
            'password' => '',
            'password_confirmation' => '',
        ])->assertUnprocessable()
            ->assertExactJsonStructure([
                'message',
                'errors' => [
                    'name',
                    'email',
                    'password'
                ],
            ]);

        // Assert the database does not has a user record.
        $this->assertDatabaseEmpty('users');
    }
}
