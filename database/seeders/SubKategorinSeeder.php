<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubKategorinSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $creatorId = DB::table('users')->value('id'); // null jika belum ada user
        $now = now();

        $subs = [
            'Akademik',
            'Kemahasiswaan',
            'Keorganisasian',
            'Beasiswa',
            'Kegiatan',
            'Sarana & Prasarana',
            'Umum',
        ];

        foreach ($subs as $nama) {
            DB::table('sub_kategoris')->updateOrInsert(
                ['nama_sub_kategori' => $nama],
                [
                    'create_by'  => $creatorId,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
        }
    }
}
