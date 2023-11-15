<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserFollowerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_user_can_see_his_followers(): void
    {
        $users = User::factory()->count(3)->create();
        $users->each(function (User $u) {
            $u->following()->sync([$this->user->id]);
        });

        $response = $this->actingAs($this->user)->get("/api/users/{$this->user->user_name}/followers");
        $response->assertStatus(200);

        $data = json_decode($response->content(), true);
        $this->assertCount(3, $data);

    }

    public function test_user_can_see_his_following_users(): void
    {
        $users = User::factory()->count(3)->create();

        $users->each(function (User $u) {
            $this->user->following()->syncWithoutDetaching([$u->id]);
        });

        $response = $this->actingAs($this->user)->get("/api/users/{$this->user->user_name}/following");
        $response->assertStatus(200);

        $data = json_decode($response->content(), true);
        $this->assertCount(3, $data);

    }
}
