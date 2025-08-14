<?php

namespace Database\Seeders;

use App\Models\Jabatan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JabatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       Jabatan::firstOrCreate(
            ['nama' => 'Ketua'],
            [
                'deskripsi' => 'Memimpin organisasi',
                'level' => 1
            ]
        );

        Jabatan::firstOrCreate(
            ['nama' => 'Sekretaris'],
            [
                'deskripsi' => 'Mencatat dan menyimpan dokumen organisasi',
                'level' => 2
            ]
        );

        Jabatan::firstOrCreate(
            ['nama' => 'Anggota'],
            [
                'deskripsi' => 'Membantu pelaksanaan program kerja',
                'level' => 3
            ]
            );
        }
}
