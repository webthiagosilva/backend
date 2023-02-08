<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Should be able to register a new user as expected
     *
     * @return void
     */
    public function test_should_be_able_to_register_a_new_user_as_expected()
    {
        $response = $this->post('/api/auth/register', [
            'name' => 'User Test',
            'email' => 'usertest@email.com',
            'password' => '12345678',
            'password_confirmation' => '12345678'
        ]);

        $response->assertSuccessful();
    }

    /**
     * Should not be able to register a new user with invalid data
     *
     * @return void
     */
    public function test_should_not_be_able_to_register_a_new_user_with_invalid_data()
    {
        $response = $this->post('/api/auth/register', [
            'name' => 'User Test',
            'email' => '',
            'password' => '12345678',
            'password_confirmation' => '123'
        ]);

        $response->assertStatus(422);
    }

    /**
     * Should not be able to register a new user when email already exists
     *
     * @return void
     */
    public function test_should_not_be_able_to_register_a_new_user_when_email_already_exists()
    {
        User::factory()->create([
            'name' => 'User Test',
            'email' => 'usertest@email.com',
            'password' => '12345678'
        ]);

        $response = $this->post('/api/auth/register', [
            'name' => 'User Test',
            'email' => 'usertest@email.com',
            'password' => '12345678',
            'password_confirmation' => '12345678'
        ]);

        $response->assertStatus(422);
    }
}
