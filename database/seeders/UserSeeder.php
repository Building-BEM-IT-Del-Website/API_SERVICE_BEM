<?php

namespace Database\Seeders;

use App\Models\Ormawa;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class UserSeeder extends Seeder
{
    public function run(): void
    {
   $team = Ormawa::first();
    app(PermissionRegistrar::class)->setPermissionsTeamId($team->id);

    // Ambil role/jabatan yang sesuai untuk assign role_id di users
    $mahasiswaRole = Role::where('name', 'mahasiswa')->first(); // atau Jabatan::...
    $adminRole = Role::where('name', 'admin')->first();

    $user1 = User::updateOrCreate(
        ['email' => 'mahasiswa@example.com'],
        [
            'username' => 'Mahasiswa A',
            'nama_lengkap' => 'Mahasiswa A',
            'password' => bcrypt('password'),
            'role_id' => $mahasiswaRole->id,  // assign role_id di kolom user
        ]
    );
    $user1->assignRole('mahasiswa');

    $admin = User::updateOrCreate(
        ['email' => 'admin@example.com'],
        [
            'username' => 'Admin',
            'nama_lengkap' => 'Administrator',
            'password' => bcrypt('password'),
            'role_id' => $adminRole->id,
        ]
    );
    $admin->assignRole('admin');

    app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
