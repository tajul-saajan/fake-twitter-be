<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    public function __construct(private readonly UserRepository $userRepository)
    {
    }

    public function search(Request $request): JsonResponse
    {
        $searchKey = $request->query('search');
        if (! empty($searchKey) || is_null($searchKey)) {
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
        $this->userRepository->addFollower($user, $authUserId);
        return response()->json(['message' => 'success'], Response::HTTP_ACCEPTED);
    }
}
