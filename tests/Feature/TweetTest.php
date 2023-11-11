<?php

namespace Tests\Feature;

use App\Models\Tweet;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TweetTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    private int $twitterMaxAllowedChars;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->twitterMaxAllowedChars = 280;
    }

    public function test_tweet_content_can_not_be_empty_for_creation(): void
    {
        $response = $this->actingAs($this->user)
            ->withHeaders([
                'Accept' => 'application/json',
            ])
            ->post('api/tweets', [
                'content' => '',
            ]);

        $response->assertUnprocessable();
    }

    public function test_tweet_content_length_can_not_be_more_than_twitter_allowed_length_for_creation(): void
    {
        $content = str_repeat('A', $this->twitterMaxAllowedChars + 1);
        $response = $this->actingAs($this->user)
            ->withHeaders([
                'Accept' => 'application/json',
            ])
            ->post('api/tweets', [
                'content' => $content,
            ]);

        $response->assertUnprocessable();
    }

    public function test_tweet_content_length_can_be_equal_to_twitter_allowed_length_for_creation(): void
    {
        $content = str_repeat('A', $this->twitterMaxAllowedChars);
        $response = $this->actingAs($this->user)
            ->withHeaders([
                'Accept' => 'application/json',
            ])
            ->post('api/tweets', [
                'content' => $content,
            ]);

        $response->assertCreated();
    }

    public function test_user_will_react_to_tweet_when_he_did_not_previously()
    {
        $tweet = Tweet::factory()->create(['posted_by' => $this->user->id]);
        $beforeReactCount = $tweet->reactions()->count();

        $response = $this->actingAs($this->user)
            ->withHeaders([
                'Accept' => 'application/json',
            ])
            ->post("api/tweets/$tweet->id/react");

        $response->assertAccepted();

        $afterReactCount = $tweet->reactions()->count();
        $this->assertEquals($afterReactCount, $beforeReactCount + 1);
    }

    public function test_user_react_will_be_removed_to_tweet_when_he_did_previously()
    {
        $tweet = Tweet::factory()->create(['posted_by' => $this->user->id]);
        $tweet->reactions()->sync([$this->user->id]);

        $beforeReactCount = $tweet->reactions()->count();

        $response = $this->actingAs($this->user)
            ->withHeaders([
                'Accept' => 'application/json',
            ])
            ->post("api/tweets/$tweet->id/react");

        $response->assertAccepted();

        $afterReactCount = $tweet->reactions()->count();
        $this->assertEquals($afterReactCount, $beforeReactCount - 1);
    }
}
