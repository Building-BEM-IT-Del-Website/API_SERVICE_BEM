<?php

namespace Database\Seeders;

use App\Models\Jabatan;
use App\Models\Ormawa;
use App\Models\StrukturOrganisasi;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StrukturOrganisasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       $user = User::where('email', 'mahasiswa@example.com')->first();
        $hima = Ormawa::where('nama', 'HIMATIF')->first();
        $ukm = Ormawa::where('nama', 'UKM Musik')->first();
        $ketua = Jabatan::where('nama', 'Ketua')->first();
        $anggota = Jabatan::where('nama', 'Anggota')->first();

        if (!$user || !$hima || !$ukm || !$ketua || !$anggota) {
            $this->command->error("Data belum lengkap. Pastikan UserSeeder, OrmawaSeeder, dan JabatanSeeder sudah dijalankan.");
            return;
        }

        StrukturOrganisasi::firstOrCreate(
            [
                'user_id' => $user->id,
                'ormawa_id' => $hima->id,
            ],
            [
                'jabatan_id' => $ketua->id,
                'tanggal_mulai' => now(),
                'tanggal_selesai' => null,
                'status' => 'active',

            ]
        );

        StrukturOrganisasi::firstOrCreate(
            [
                'user_id' => $user->id,
                'ormawa_id' => $ukm->id,
            ],
            [
                'jabatan_id' => $anggota->id,
                'tanggal_mulai' => now(),
                'tanggal_selesai' => null,
                'status' => 'active',

            ]
        );
    }
}
