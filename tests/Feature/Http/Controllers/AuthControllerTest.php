<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Should be able to user logout as expected
     *
     * @return void
     */
    public function test_should_be_able_to_user_logout_as_expected()
    {
        $this->actingAs($this->user, 'web');

        $response = $this->post('api/auth/logout');

        $response->assertStatus(200);
    }

    /**
     * Should be not able to user logout when not authenticated
     *
     * @return void
     */
    public function test_should_be_not_able_to_user_logout_when_not_authenticated()
    {
        $response = $this->post('api/auth/logout');

        $response->assertStatus(401);
    }

}
