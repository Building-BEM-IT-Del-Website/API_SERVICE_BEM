<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       $rolesPermissions = [
            'mahasiswa' => ['kelola_anggota','authentikasi'],
            'admin' => ['edit_kegiatan'],
        ];

        foreach ($rolesPermissions as $roleName => $permissions) {
            $role = Role::where('name', $roleName)->first();

            if (!$role) {
                $this->command->warn("Role '$roleName' tidak ditemukan. Lewati.");
                continue;
            }

            foreach ($permissions as $permissionName) {
                $permission = Permission::firstOrCreate([
                    'name' => $permissionName,
                    'guard_name' => 'api',
                ]);

                $role->givePermissionTo($permission);
            }
        }
    }
}
