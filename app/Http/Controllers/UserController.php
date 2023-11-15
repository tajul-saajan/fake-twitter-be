<?php

namespace App\Http\Controllers;

use App\contracts\UserRepositoryInterface;
use App\Exceptions\UserFollowException;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
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
            return response()->json();
        }

        $user = Cache::remember($searchKey, now()->addDays(5), function () use ($searchKey) {
            // @todo add cache invalidation when username or email update functionality is added
            // @todo add remember when user create functionality is added

            $isEmail = filter_var($searchKey, FILTER_VALIDATE_EMAIL);
            if ($isEmail) {
                return $this->userRepository->getByEmail($searchKey);
            }

            return $this->userRepository->getOneByUserName($searchKey);
        });

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
