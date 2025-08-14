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

    // Ambil user
    $user = auth('api')->setToken($token)->user();

    // Cari organisasi aktif di mana dia ketua
    $ormawaAktif = DB::table('struktur_organisasis')
        ->join('jabatan', 'struktur_organisasis.jabatan_id', '=', 'jabatan.id')
        ->where('struktur_organisasis.user_id', $user->id)
        ->where('jabatan.nama', 'Ketua')
        ->where('struktur_organisasis.status', 'active')
        ->orderBy('struktur_organisasis.tanggal_mulai', 'desc')
        ->select('struktur_organisasis.ormawa_id')
        ->first();

    // Tambahkan ormawa_aktif_id ke data user
    $user->ormawa_aktif_id = $ormawaAktif->ormawa_id ?? null;

    return response()->json([
        'success' => true,
        'message' => 'Login success',
        'data' => [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
            'user' => new UserResource($user), // pastikan resource ikut menampilkan ormawa_aktif_id
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
