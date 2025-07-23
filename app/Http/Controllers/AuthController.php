<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Services\AuthService;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    private AuthService $authService;
    private UserRepository $userRepo;

    public function __construct(AuthService $authService, UserRepository $userRepo)
    {
        $this->authService = $authService;
        $this->userRepo    = $userRepo;
    }

    /** POST /api/auth/login */
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $token = $this->authService->login($request->only('username', 'password'));

        if (!$token) {
            return response()->json([
                'success' => false,
                'message' => 'Incorrect username or password.',
                'data'    => null,
            ], 401);
        }

        return response()->json([
            'success' => true,
            'message' => 'Login success',
            'data'    => [
                'access_token' => $token,
                'token_type'   => 'bearer',
                'expires_in'   => auth('api')->factory()->getTTL() * 60,
                'user'         => new UserResource(auth('api')->user()),
            ],
        ]);
    }

    /** GET /api/users */
    public function allUsers(): JsonResponse
    {
        $users = $this->userRepo->getAll();

        return response()->json([
            'success' => true,
            'message' => 'List of users',
            'data'    => UserResource::collection($users),
        ]);
    }
  public function logout(): JsonResponse
{
    $this->authService->logout();

    return response()->json([
        'success' => true,
        'message' => 'Successfully logged out',
        'data'    => null,
    ]);
}
public function me(): JsonResponse
{
    return response()->json([
        'success' => true,
        'message' => 'Authenticated user data',
        'data'    => new UserResource(auth('api')->user()),
    ]);
}
}
