<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserFollowTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_should_not_follow_themselves()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->withHeaders([
                'Accept' => 'application/json',
            ])
            ->post("api/users/$user->user_name/follow");
        $response->assertInternalServerError();
    }

    public function test_user_can_follow_different_user()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $response = $this->actingAs($user1)
            ->withHeaders([
                'Accept' => 'application/json',
            ])
            ->post("api/users/$user2->user_name/follow");

        $response->assertStatus(202);
    }

    public function test_user_should_not_unfollow_themselves()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->withHeaders([
                'Accept' => 'application/json',
            ])
            ->post("api/users/$user->user_name/unfollow");
        $response->assertInternalServerError();
    }

    public function test_user_can_unfollow_different_user()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $response = $this->actingAs($user1)
            ->withHeaders([
                'Accept' => 'application/json',
            ])
            ->post("api/users/$user2->user_name/unfollow");

        $response->assertStatus(202);
    }
}
