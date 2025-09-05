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

    // Ambil struktur organisasi user dengan join ke ormawa + jenis_ormawa
    $strukturOrganisasi = DB::table('struktur_organisasis')
        ->join('jabatan', 'struktur_organisasis.jabatan_id', '=', 'jabatan.id')
        ->join('ormawas', 'struktur_organisasis.ormawa_id', '=', 'ormawas.id')
        ->join('jenis_ormawas', 'ormawas.jenis_ormawa_id', '=', 'jenis_ormawas.id')
        ->where('struktur_organisasis.user_id', $user->id)
        ->select(
            'struktur_organisasis.periode',
            'struktur_organisasis.status',
            'ormawas.id as ormawa_id',
            'ormawas.nama as ormawa_nama',
            'ormawas.logo as ormawa_logo',
            'ormawas.jenis_ormawa_id',
            'jenis_ormawas.nama as jenis_ormawa_nama',
            'jabatan.id as jabatan_id',
            'jabatan.nama as jabatan_nama'
        )
        ->get()
        ->map(function ($item) {
            return [
                'ormawa' => [
                    'id' => $item->ormawa_id,
                    'nama' => $item->ormawa_nama,
                    'logo' => $item->ormawa_logo,
                    'jenis_ormawa_id' => $item->jenis_ormawa_id,
                    'jenis_ormawa_nama' => $item->jenis_ormawa_nama,
                ],
                'jabatan' => [
                    'id' => $item->jabatan_id,
                    'nama' => $item->jabatan_nama,
                ],
                'periode' => $item->periode,
                'status' => $item->status,
            ];
        });

    // Tambahkan ke user
    $user->struktur_organisasi = $strukturOrganisasi;

    return response()->json([
        'success' => true,
        'message' => 'Login success',
        'data' => [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
            'user' => [
                'id' => $user->id,
                'username' => $user->username,
                'email' => $user->email,
                'nama_lengkap' => $user->nama_lengkap,
                'roles' => $user->roles,
                'permissions' => $user->permissions,
                'struktur_organisasi' => $user->struktur_organisasi,
                'status' => $user->status,
                'avatar' => $user->avatar,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
            ],
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
