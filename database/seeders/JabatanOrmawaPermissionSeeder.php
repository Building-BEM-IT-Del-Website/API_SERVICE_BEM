<?php

namespace Database\Seeders;

use App\Models\Jabatan;
use App\Models\Ormawa;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;

class JabatanOrmawaPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
              $ketua = Jabatan::where('nama', 'Ketua')->first();
        $anggota = Jabatan::where('nama', 'Anggota')->first();

        $hima = Ormawa::where('nama', 'HIMATIF')->first();
        $ukm = Ormawa::where('nama', 'UKM Musik')->first();

        $kelolaAnggota = Permission::where('name', 'kelola_anggota')->first();
        $lihatDashboard = Permission::where('name', 'lihat_dashboard')->first();

        DB::table('ormawa_jabatan_permissions')->insertOrIgnore([
            [
                'ormawa_id' => $hima->id,
                'jabatan_id' => $ketua->id,
                'permission_id' => $kelolaAnggota->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'ormawa_id' => $ukm->id,
                'jabatan_id' => $anggota->id,
                'permission_id' => $lihatDashboard->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
