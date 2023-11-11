<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_login_get_validation_error_without_credentials(): void
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->post('api/login', []);
        $response->assertUnprocessable();
        $response->assertJsonValidationErrors([
            'email',
            'password',
        ]);
    }

    public function test_user_login_should_get_401_with_invalid_credential()
    {
        $user = User::factory()->create();

        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->post('api/login', [
            'email' => $user->email,
            'password' => 'invalid_password',
        ]);

        $response->assertUnauthorized();
    }

    public function test_user_should_login_with_valid_credential()
    {
        $user = User::factory()->create();

        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->post('api/login', [
            'email' => $user->email,
            'password' => 'password', // password is default factory password
        ]);

        $response->assertOk();
        $response->assertJson([
            'user' => true,
            'access_token' => true,
            'token_type' => true,
            'expires_in' => true,
        ]);
    }

    public function test_user_successful_login_should_return_user_with_token_data()
    {
        $user = User::factory()->create();

        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->post('api/login', [
            'email' => $user->email,
            'password' => 'password', // password is default factory password
        ]);

        $response->assertJson([
            'user' => true,
            'access_token' => true,
            'token_type' => true,
            'expires_in' => true,
        ]);
    }

    public function test_unauthenticated_user_should_not_have_access_to_protected_route()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->get('api/profile');

        $response->assertUnauthorized();
    }

    public function test_authenticated_user_should_have_access_to_protected_route()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->withHeaders([
            'Accept' => 'application/json',
        ])->get('api/profile');

        $response->assertOk();
    }

    public function test_user_should_be_able_to_refresh_token()
    {
        $user = User::factory()->create();

        $token = JWTAuth::fromUser($user);

        // Simulate a delay to ensure the token is expired
        sleep(2);

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer '.$token,
        ])->json('POST', 'api/refresh');

        $response->assertStatus(200);

        $newToken = $response->json('access_token');
        $this->assertNotEquals($token, $newToken);
    }
}
