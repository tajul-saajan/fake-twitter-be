<?php

namespace App\Repositories;

use App\contracts\TweetRepositoryInterface;
use App\Models\Tweet;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class TweetRepository implements TweetRepositoryInterface
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
        return Tweet::query()
            ->whereIn('posted_by', $followingUsers)
            ->orderBy('created_at', 'desc')
            ->paginate($paginationLength);
    }
}
