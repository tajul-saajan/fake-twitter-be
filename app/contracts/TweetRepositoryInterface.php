<?php

namespace App\contracts;

use App\Models\Tweet;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;


interface TweetRepositoryInterface
{
    public function create(string $content, int $postedBy): Tweet;

    public function react(Tweet $tweet): void;

    public function getTweetsByUsers(array $followingUsers, int $paginationLength): LengthAwarePaginator;
}
