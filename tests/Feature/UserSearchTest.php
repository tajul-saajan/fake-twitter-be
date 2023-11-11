<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserSearchTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_should_search_by_user_name()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)
            ->withHeaders([
                'Accept' => 'application/json',
            ])
            ->get("api/users?search=$user->user_name");

        $response->assertOk();
        $response->assertJson($user->toArray());
    }

    public function test_user_should_search_by_email()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)
            ->withHeaders([
                'Accept' => 'application/json',
            ])
            ->get("api/users?search=$user->email");

        $response->assertOk();
        $response->assertJson($user->toArray());
    }

    public function test_user_search_should_return_empty_json_when_email_does_not_exist()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)
            ->withHeaders([
                'Accept' => 'application/json',
            ])
            ->get("api/users?search=$user->email.'m'");

        $response->assertOk();
        $response->assertJson([]);

    }

    public function test_user_search_should_return_empty_json_when_user_name_does_not_exist()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)
            ->withHeaders([
                'Accept' => 'application/json',
            ])
            ->get("api/users?search=$user->user_name.'pp'");

        $response->assertOk();
        $response->assertJson([]);

    }
}
