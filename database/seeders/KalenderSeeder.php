<?php

namespace Database\Seeders;

use App\Models\Kalender;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon; // Kita akan menggunakan Carbon untuk memanipulasi tanggal

class KalenderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Dapatkan satu user untuk dijadikan sebagai 'create_by'
        // Ini untuk memastikan seeder tidak error jika tabel user masih kosong.
        $user = User::find(2);
        if (!$user) {
            $this->command->info('Tidak ada user di database. Silakan jalankan UserSeeder terlebih dahulu. KalenderSeeder dilewati.');
            return;
        }

        // 2. Hapus data lama agar tidak terjadi duplikasi saat seeding ulang
        Kalender::truncate();

        // 3. Siapkan data dummy dalam bentuk array
        $kalenders = [
            [
                'judul' => 'Pekan Olahraga Mahasiswa (POM)',
                'deskripsi' => 'Kompetisi olahraga antar jurusan untuk mempererat tali persaudaraan.',
                'tanggal_mulai' => Carbon::now()->addDays(10),
                'tanggal_berakhir' => Carbon::now()->addDays(15),
                'sumber' => json_encode(['BEM', 'Kemahasiswaan']), // Simpan sebagai JSON string
                'create_by' => $user->id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'judul' => 'Seminar Nasional Technopreneurship 2024',
                'deskripsi' => 'Seminar tentang bagaimana membangun startup di era digital bersama para ahli.',
                'tanggal_mulai' => Carbon::now()->addDays(25)->setTime(9, 0, 0),
                'tanggal_berakhir' => null, // Acara satu hari
                'sumber' => json_encode(['HME']),
                'create_by' => $user->id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'judul' => 'Upacara Penutupan Matrikulasi',
                'deskripsi' => 'Acara penutupan resmi untuk seluruh kegiatan matrikulasi mahasiswa baru.',
                'tanggal_mulai' => Carbon::now()->subDays(5)->setTime(14, 0, 0),
                'tanggal_berakhir' => Carbon::now()->subDays(5)->setTime(16, 0, 0), // Acara beberapa jam
                'sumber' => json_encode(['BEM', 'Keasramaan']),
                'create_by' => $user->id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'judul' => 'Workshop Desain Grafis (Acara Lalu)',
                'deskripsi' => 'Workshop dasar menggunakan Figma untuk pemula.',
                'tanggal_mulai' => Carbon::now()->subMonth(),
                'tanggal_berakhir' => null,
                'sumber' => json_encode(['UKM Desain']),
                'create_by' => $user->id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        // 4. Masukkan data ke dalam database
        Kalender::insert($kalenders);

    }
}