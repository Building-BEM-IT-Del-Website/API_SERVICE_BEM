<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Services\AuthService;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;

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

    $credentials = $request->only('username', 'password');

    if (!$token = auth('api')->attempt($credentials)) {
        return response()->json([
            'success' => false,
            'message' => 'Incorrect username or password.',
            'data' => null,
        ], 401);
    }

    // Ambil user dari token
    $user = auth('api')->setToken($token)->user();

    // Ambil struktur organisasi aktif user (jika mahasiswa)
    $strukturAktif = DB::table('struktur_organisasis')
        ->join('jabatan', 'struktur_organisasis.jabatan_id', '=', 'jabatan.id')
        ->where('struktur_organisasis.user_id', $user->id)
        ->where('struktur_organisasis.status', 'active')
        ->whereDate('struktur_organisasis.tanggal_mulai', '<=', now())
        ->whereDate('struktur_organisasis.tanggal_selesai', '>=', now())
        ->select(
            'struktur_organisasis.ormawa_id',
            'struktur_organisasis.jabatan_id',
            'jabatan.nama as jabatan_nama'
        )
        ->get();

    // Tambahkan default_ormawa_id ke user
    $user->default_ormawa_id = $strukturAktif->first()->ormawa_id ?? null;
    $user->struktur_aktif = $strukturAktif;

    return response()->json([
        'success' => true,
        'message' => 'Login success',
        'data' => [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
            'user' => new UserResource($user),
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
