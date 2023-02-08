<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LoginControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Should be able to user login as expected
     *
     * @return void
     */
    public function test_should_be_able_to_user_login_as_expected()
    {
        $user = User::factory()->create([
            'password' => Hash::make('12345678')
        ]);

        $response = $this->post('/api/auth/login', [
            'email' => $user->email,
            'password' => '12345678'
        ]);

        $response->assertSuccessful();

        $response->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'access_token',
                'token_type'
            ]
        ]);
    }

    /**
     * Should not be able to user login with invalid credentials
     *
     * @return void
     */
    public function test_should_not_be_able_to_user_login_with_invalid_credentials()
    {
        $user = User::factory()->create([
            'password' => Hash::make('12345678')
        ]);

        $response = $this->post('/api/auth/login', [
            'email' => $user->email,
            'password' => '99999999'
        ]);

        $response->assertStatus(422);
    }

    /**
     * Should not be able to user login with unregistered email
     *
     * @return void
     */
    public function test_should_not_be_able_to_user_login_with_unregistered_email()
    {
        $response = $this->post('/api/auth/login', [
            'email' => 'usertest@email.com',
            'password' => '12345678'
        ]);

        $response->assertStatus(422);
    }
}
