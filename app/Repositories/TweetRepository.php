<?php

namespace App\Repositories;

use App\Models\Tweet;

class TweetRepository
{
    public function create(string $content, int $postedBy): Tweet
    {
        return Tweet::create([
            'content' => $content,
            'posted_by' => $postedBy,
        ]);
    }

    public function react(Tweet $tweet): void
    {
        $authUserId = auth()->user()->id;
        $tweet->reactions()->toggle([$authUserId]);
    }
}
