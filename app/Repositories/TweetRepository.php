<?php

namespace App\Repositories;

use App\Models\Tweet;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

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

    public function getTweetsByUsers(array $followingUsers, int $paginationLength): LengthAwarePaginator
    {
        return Tweet::query()->whereIn('posted_by', $followingUsers)->paginate($paginationLength);
    }
}
