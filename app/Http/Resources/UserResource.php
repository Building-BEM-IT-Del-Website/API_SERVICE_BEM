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

        // Ambil semua permission dari role
        $rolePermissions = $this->getAllPermissions()
            ->pluck('name')
            ->unique()
            ->values();

        // Ambil struktur organisasi aktif beserta relasi ormawa dan jabatan
        $strukturAktif = $this->strukturOrganisasiAktif()
            ->with(['ormawa', 'jabatan'])
            ->get();

        // Inisialisasi collection kosong untuk permission organisasi
        $organisasiPermissions = collect();

        // Jika user punya role "mahasiswa" dan punya struktur aktif
        if ($roles->contains('mahasiswa') && $strukturAktif->isNotEmpty()) {
            foreach ($strukturAktif as $struktur) {
                $names = DB::table('ormawa_jabatan_permissions')
                    ->join('permissions', 'ormawa_jabatan_permissions.permission_id', '=', 'permissions.id')
                    ->where('ormawa_jabatan_permissions.ormawa_id', $struktur->ormawa_id)
                    ->where('ormawa_jabatan_permissions.jabatan_id', $struktur->jabatan_id)
                    ->pluck('permissions.name');

                // Gabungkan dengan collection sebelumnya
                $organisasiPermissions = $organisasiPermissions->merge($names);
            }

            // Hilangkan duplikat dan reset index
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
                    'ormawa' => $s->ormawa->nama ?? null,
                    'jabatan' => $s->jabatan->nama ?? null,
                    'periode' => $s->periode,
                    'status' => $s->status,
                ];
            }),

            'status' => $this->status,
            'avatar' => $this->avatar ? asset('storage/' . $this->avatar) : null,
            'created_at' => optional($this->created_at)->format('Y-m-d H:i:s'),
            'updated_at' => optional($this->updated_at)->format('Y-m-d H:i:s'),
        ];
    }
}
