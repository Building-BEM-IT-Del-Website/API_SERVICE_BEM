<?php


namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        // Ambil semua nama role yang dimiliki user
       $roles = $this->getRoleNames();

        $rolePermissions = $this->getAllPermissions()
            ->pluck('name')
            ->unique()
            ->values();

        $strukturAktif = $this->strukturOrganisasiAktif()
            ->with(['ormawa', 'jabatan'])
            ->get();

        $organisasiPermissions = collect();

        if ($roles->contains('mahasiswa') && $strukturAktif->isNotEmpty()) {
            foreach ($strukturAktif as $struktur) {
                $names = DB::table('ormawa_jabatan_permissions')
                    ->join('permissions', 'ormawa_jabatan_permissions.permission_id', '=', 'permissions.id')
                    ->where('ormawa_jabatan_permissions.ormawa_id', $struktur->ormawa_id)
                    ->where('ormawa_jabatan_permissions.jabatan_id', $struktur->jabatan_id)
                    ->pluck('permissions.name');

                $organisasiPermissions = $organisasiPermissions->merge($names);
            }

            $organisasiPermissions = $organisasiPermissions->unique()->values();
        }

        return [
            'id' => $this->id,
            'username' => $this->username,
            'email' => $this->email,
            'nama_lengkap' => $this->nama_lengkap,
            'roles' => $roles,
            'permissions' => [
                'role_permissions' => $rolePermissions,
                'organisasi_permissions' => $organisasiPermissions,
            ],

            'struktur_organisasi' => $strukturAktif->map(function ($s) {
                return [
                    'ormawa' => [
                        'id'   => $s->ormawa->id ?? null,
                        'nama' => $s->ormawa->nama ?? null,
                        'logo' => $s->ormawa && $s->ormawa->logo
                            ? asset('storage/' . $s->ormawa->logo)
                            : null,
                    ],
                    'jabatan' => [
                        'id'   => $s->jabatan->id ?? null,
                        'nama' => $s->jabatan->nama ?? null,
                    ],
                    'periode' => $s->periode,
                    'status'  => $s->status,
                ];
            }),

            'status' => $this->status,
            'avatar' => $this->avatar ? asset('storage/' . $this->avatar) : null,
            'created_at' => optional($this->created_at)->format('Y-m-d H:i:s'),
            'updated_at' => optional($this->updated_at)->format('Y-m-d H:i:s'),
        ];
    }
}
