<?php

namespace App\Http\Controllers;

use App\contracts\UserRepositoryInterface;
use App\Exceptions\UserFollowException;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    public function __construct(private readonly UserRepositoryInterface $userRepository)
    {
    }

    public function search(Request $request): JsonResponse
    {
        $searchKey = $request->query('search');
        if (empty($searchKey)) {
            return response()->json([]);
        }

        $isEmail = filter_var($searchKey, FILTER_VALIDATE_EMAIL);
        // @todo scope for cache implementation
        if ($isEmail) {
            $user = $this->userRepository->getByEmail($searchKey);
        } else {
            $user = $this->userRepository->getOneByUserName($searchKey);
        }

        return response()->json($user);
    }

    public function follow(User $user): JsonResponse
    {
        $authUserId = auth()->user()->id;

        if ($user->id === $authUserId) {
            throw new UserFollowException('user can not follow themselves');
        }

        $this->userRepository->addFollower($user, $authUserId);

        return response()->json(['message' => 'success'], Response::HTTP_ACCEPTED);
    }

    public function unfollow(User $user): JsonResponse
    {
        $authUserId = auth()->user()->id;

        if ($user->id === $authUserId) {
            throw new UserFollowException('user can not unfollow themselves');
        }

        $this->userRepository->removeFollower($user, $authUserId);

        return response()->json(['message' => 'success'], Response::HTTP_ACCEPTED);
    }

    public function profile(User $user): JsonResponse
    {
        $user->load('tweets');
        return response()->json($user);
    }
}
