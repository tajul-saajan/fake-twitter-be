<?php

namespace App\Http\Controllers;

use App\Http\Requests\TweetCreateRequest;
use App\Repositories\TweetRepository;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class TweetController extends Controller
{
    public function __construct(private readonly TweetRepository $tweetRepository)
    {
    }

    public function store(TweetCreateRequest $request): JsonResponse
    {
        $content = $request->get('content');
        $postedBy = auth()->user()->id;

        $tweet = $this->tweetRepository->create($content, $postedBy);

        return response()->json($tweet, Response::HTTP_CREATED);
    }
}
