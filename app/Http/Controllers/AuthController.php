<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Repositories\UserRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function __construct(private readonly UserRepository $userRepository)
    {
        $this->middleware('auth:api', ['except' => ['profile']]);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $loginData = $request->only('email', 'password');
        $token = Auth::attempt($loginData);
        if (!$token) {
            return response()->json(['message' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED);
        }

        $user = $this->userRepository->getByEmail($request->get('email'));
        $tokenData = $this->getTokenData($token);

        return response()->json([
            ...$tokenData,
            'user' => $user,
        ]);
    }

    public function profile(): JsonResponse
    {
        return response()->json(auth()->user());
    }

    public function refresh(): JsonResponse
    {
        $tokenData = $this->getTokenData(auth()->refresh());

        return response()->json($tokenData);
    }

    private function getTokenData(string $token): array
    {
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::factory()->getTTL(),
        ];
    }
}
