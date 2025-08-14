<?php

namespace Database\Seeders;

use App\Models\JenisOrmawa;
use App\Models\Ormawa;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrmawaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Ormawa::firstOrCreate(
            ['nama' => 'HIMATIF'],
            [
                'jenis_ormawa_id' => 1,
                'deskripsi' => 'Himpunan Mahasiswa Teknik Informatika',
                'logo' => 'himatif.jpg',
                'visi' => 'Menjadi himpunan terdepan dalam inovasi teknologi',
                'misi' => "1. Meningkatkan kualitas akademik mahasiswa\n2. Mendorong kolaborasi antar himpunan",
                'status' => 'active',
            ]
        );

        Ormawa::firstOrCreate(
            ['nama' => 'UKM Musik'],
            [
                'jenis_ormawa_id' => 2,
                'deskripsi' => 'Unit Kegiatan Mahasiswa bidang musik',
                'logo' => 'ukm-musik.jpg',
                'visi' => 'Mengembangkan bakat musik mahasiswa',
                'misi' => "1. Menyelenggarakan pelatihan rutin\n2. Mengadakan konser kampus",
                'status' => 'active',
            ]
        );
    }
}
