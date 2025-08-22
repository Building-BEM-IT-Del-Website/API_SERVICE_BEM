<?php

namespace Database\Seeders;

use App\Models\Aspirasi;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AspirasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 3; $i++) {
            Aspirasi::firstOrCreate([
                'judul'   => 'Aspirasi ' . $i,
                'deskripsi'     => 'Ini adalah isi aspirasi ke-' . $i . ' yang dibuat untuk testing fitur.',
                'status'  => collect(['Pending', 'Approved', 'Rejected', 'Completed', 'In Progress'])->random(),
            ]);
        }
    }
}
