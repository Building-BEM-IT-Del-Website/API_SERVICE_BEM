<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PengumumanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
     public function run(): void
    {
        DB::table('pengumuman')->insert([
            [
                'nama_pengumuman' => 'Rapat Koordinasi BEM',
                'deskripsi' => 'Rapat koordinasi rutin antar pengurus BEM Institut Teknologi Del.',
                'kategoris_id' => 1, // pastikan kategori dengan id=1 sudah ada
                'tipe_pengumuman' => 'Penting',
                'tanggal_mulai' => Carbon::now()->addDays(2),
                'tanggal_berakhir' => Carbon::now()->addDays(3),
                'ormawa_id' => 1, // id ormawa contoh
                'file_paths' => json_encode(['/storage/pengumuman/rapat.pdf']),
                'create_by' => 2, // id user contoh
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_pengumuman' => 'Pendaftaran Lomba Debat',
                'deskripsi' => 'Mahasiswa diundang untuk ikut lomba debat antar universitas.',
                'kategoris_id' => 2, // pastikan kategori id=2 ada
                'tipe_pengumuman' => 'Umum',
                'tanggal_mulai' => Carbon::now()->addWeek(),
                'tanggal_berakhir' => Carbon::now()->addWeeks(2),
                'ormawa_id' => 2,
                'file_paths' => null,
                'create_by' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_pengumuman' => 'Pemadaman Listrik Darurat',
                'deskripsi' => 'Pemadaman listrik di area kampus pada malam hari.',
                'kategoris_id' => 3,
                'tipe_pengumuman' => 'Darurat',
                'tanggal_mulai' => Carbon::now(),
                'tanggal_berakhir' => Carbon::now()->addDay(),
                'ormawa_id' => null,
                'file_paths' => null,
                'create_by' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
