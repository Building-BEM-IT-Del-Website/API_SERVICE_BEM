<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class CheckStrukturPermission
{
    /**
     * Handle an incoming request.
     */
  public function handle(Request $request, Closure $next, $permissionName): Response
{
    $user = Auth::user();

    if (!$user) {
        abort(403, 'Unauthorized');
    }

    // Cek struktur organisasi aktif
    $strukturs = $user->strukturOrganisasi()->where('status', 'active')->get();

    if ($strukturs->isNotEmpty()) {
        foreach ($strukturs as $struktur) {
            $hasPermission = DB::table('ormawa_jabatan_permissions')
                ->join('permissions', 'ormawa_jabatan_permissions.permission_id', '=', 'permissions.id')
                ->where('ormawa_jabatan_permissions.ormawa_id', $struktur->ormawa_id)
                ->where('ormawa_jabatan_permissions.jabatan_id', $struktur->jabatan_id)
                ->where('permissions.name', $permissionName)
                ->exists();

            if ($hasPermission) {
                return $next($request);
            }
        }
        abort(403, 'Permission dari struktur organisasi ditolak');
    }

    // Jika tidak tergabung struktur aktif, fallback ke permission Spatie biasa
    if ($user->can($permissionName)) {
        return $next($request);
    }

    abort(403, 'Permission ditolak');
}
}
