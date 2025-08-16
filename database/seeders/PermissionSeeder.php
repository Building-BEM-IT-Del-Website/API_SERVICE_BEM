<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
        'authentikasi',
        'kelola_anggota',
        'lihat_dashboard',
        'edit_kegiatan',
        'hapus_kegiatan',
        'mengelolah_user',
        'lihat_aspirasi',
        'kelola_aspirasi',
        'kelola_kalender'
    ];


        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'api',
            ]);
        }
    }
}
