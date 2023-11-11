<?php

namespace App\Http\Controllers;

use App\contracts\TweetRepositoryInterface;
use App\contracts\UserRepositoryInterface;
use Illuminate\Http\JsonResponse;

class TimelineController extends Controller
{
    const PAGINATION_LENGTH = 15;

    public function __construct(private readonly TweetRepositoryInterface $tweetRepository, private readonly UserRepositoryInterface $userRepository)
    {
    }

    public function index(): JsonResponse
    {
        $followingUsersIds = $this->userRepository->getFollowingUsersIds(auth()->user()->id);
        $tweets = $this->tweetRepository->getTweetsByUsers($followingUsersIds, self::PAGINATION_LENGTH);

        return response()->json($tweets);
    }
}
