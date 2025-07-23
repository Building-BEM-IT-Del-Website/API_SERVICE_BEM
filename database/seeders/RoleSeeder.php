<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
           $roles = [
            'admin-bem',
            'mahasiswa',
            'ketua-hima',
            'ketua-ukm',
            'ketua-departamen'
        ];

             foreach ($roles as $role) {
            Role::firstOrCreate(
                ['name' => $role, 'guard_name' => 'web'] // ⬅️ tambahkan guard_name
            );

    }
}
}
