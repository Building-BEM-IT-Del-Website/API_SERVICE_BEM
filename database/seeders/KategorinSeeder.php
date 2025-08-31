<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KategorinSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $creatorId = DB::table('users')->value('id'); // null kalau tidak ada
        $now = now();

        // Ambil peta sub_kategori: nama_sub_kategori => id
        $subMap = DB::table('sub_kategoris')->pluck('id', 'nama_sub_kategori');

        $rows = [
            [
                'nama' => 'Organisasi & Rapat',
                'sub'  => 'Keorganisasian',
                'deskripsi' => 'Pengumuman seputar rapat dan administrasi organisasi.',
            ],
            [
                'nama' => 'Kegiatan Mahasiswa',
                'sub'  => 'Kegiatan',
                'deskripsi' => 'Event, lomba, webinar, dan aktivitas mahasiswa.',
            ],
            [
                'nama' => 'Sarana & Prasarana',
                'sub'  => 'Sarana & Prasarana',
                'deskripsi' => 'Informasi fasilitas kampus dan maintenance.',
            ],
            [
                'nama' => 'Beasiswa',
                'sub'  => 'Beasiswa',
                'deskripsi' => 'Info beasiswa dan bantuan pendidikan.',
            ],
            [
                'nama' => 'Akademik',
                'sub'  => 'Akademik',
                'deskripsi' => 'Jadwal kuliah, ujian, wisuda, dan akademik lainnya.',
            ],
            [
                'nama' => 'Umum',
                'sub'  => 'Umum',
                'deskripsi' => 'Informasi umum kampus.',
            ],
        ];

        foreach ($rows as $r) {
            $subId = $subMap[$r['sub']] ?? null;

            DB::table('kategoris')->updateOrInsert(
                ['nama' => $r['nama']], // unik berdasarkan nama (sesuai validasi)
                [
                    'sub_kategoris_id' => $subId,     // FK ke sub_kategoris
                    'deskripsi'        => $r['deskripsi'],
                    'create_by'        => $creatorId,
                    'created_at'       => $now,
                    'updated_at'       => $now,
                ]
            );
        }
    }
}
