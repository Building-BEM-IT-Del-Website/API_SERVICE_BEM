<?php

namespace Database\Seeders;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            'admin-bem' => [
                ['username' => 'adminbem', 'nama_lengkap' => 'Admin BEM', 'email' => 'adminbem@example.com', 'status' => 'aktif'],
            ],
            'mahasiswa' => [
                ['username' => 'mahasiswa1', 'nama_lengkap' => 'Mahasiswa 1', 'email' => 'mahasiswa1@example.com', 'status' => 'aktif'],
                ['username' => 'mahasiswa2', 'nama_lengkap' => 'Mahasiswa 2', 'email' => 'mahasiswa2@example.com', 'status' => 'aktif'],
                // Tambahan dummy mahasiswa
            ],
            'ketua-hima' => [
                ['username' => 'ketuahima', 'nama_lengkap' => 'Ketua HIMA', 'email' => 'ketuahima@example.com', 'status' => 'aktif'],
            ],
            'ketua-ukm' => [
                ['username' => 'ketuaukm', 'nama_lengkap' => 'Ketua UKM', 'email' => 'ketuaukm@example.com', 'status' => 'aktif'],
            ],
            'ketua-departamen' => [
                ['username' => 'ketuadept', 'nama_lengkap' => 'Ketua Departemen', 'email' => 'ketuadept@example.com', 'status' => 'aktif'],
            ]
        ];

        // Tambah 50 mahasiswa dummy
        for ($i = 3; $i <= 52; $i++) {
            $roles['mahasiswa'][] = [
                'username' => 'mahasiswa' . $i,
                'nama_lengkap' => 'Mahasiswa ' . $i,
                'email' => 'mahasiswa' . $i . '@example.com',
                'status' => 'aktif',
            ];
        }

        foreach ($roles as $roleName => $users) {
            $role = Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);

            foreach ($users as $userData) {
                $user = User::updateOrCreate(
                    ['username' => $userData['username']],
                    [
                        'nama_lengkap' => $userData['nama_lengkap'],
                        'email' => $userData['email'],
                        'status' => $userData['status'],
                        'password' => Hash::make('password123')
                    ]
                );

                $user->assignRole($roleName);
            }
        }
    }
}
