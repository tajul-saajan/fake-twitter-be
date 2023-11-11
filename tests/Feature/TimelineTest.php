<?php

namespace Tests\Feature;

use App\Models\Tweet;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TimelineTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_user_should_see_all_of_his_following_users_tweets()
    {
        $user2 = User::factory()->create();

        Tweet::factory()->count(3)->create([
            'posted_by' => $user2->id,
        ]);

        $this->user->following()->sync([$user2->id]);

        $response = $this->actingAs($this->user)
            ->withHeaders([
                'Accept' => 'application/json',
            ])
            ->get('api/timeline');

        $content = json_decode($response->content(), true);
        $response->assertOk();

        $tweets = $content['data'];
        foreach ($tweets as $tweet) {
            $this->assertEquals($tweet['posted_by'], $user2->id);
        }

        $this->assertCount(3, $tweets);
    }

    public function test_user_should_see_all_of_his_following_users_tweets_in_chronological_descending_order()
    {
        $user2 = User::factory()->create();

        Tweet::factory()->count(3)->create([
            'posted_by' => $user2->id,
        ]);

        $this->user->following()->sync([$user2->id]);

        $response = $this->actingAs($this->user)
            ->withHeaders([
                'Accept' => 'application/json',
            ])
            ->get('api/timeline');

        $content = json_decode($response->content(), true);
        $response->assertOk();

        $tweets = $content['data'];

        $previous = null;

        foreach ($tweets as $tweet) {
            $created_at = $tweet['created_at'];
            if (! is_null($previous)) {
                $p = new \DateTime($previous);
                $n = new \DateTime($created_at);

                $this->assertGreaterThanOrEqual($p, $n);
            }
            $previous = $created_at;

        }

        $this->assertCount(3, $tweets);
    }
}
