<?php

namespace App\Http\Controllers;

use App\contracts\TweetRepositoryInterface;
use App\Http\Requests\TweetCreateRequest;
use App\Models\Tweet;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class TweetController extends Controller
{
    public function __construct(private readonly TweetRepositoryInterface $tweetRepository)
    {
    }

    public function store(TweetCreateRequest $request): JsonResponse
    {
        $content = $request->get('content');
        $postedBy = auth()->user()->id;

        $tweet = $this->tweetRepository->create($content, $postedBy);

        return response()->json($tweet, Response::HTTP_CREATED);
    }

    public function react(Tweet $tweet)
    {
        $this->tweetRepository->react($tweet);

        return response()->json(['message' => 'success'], Response::HTTP_ACCEPTED);
    }
}
