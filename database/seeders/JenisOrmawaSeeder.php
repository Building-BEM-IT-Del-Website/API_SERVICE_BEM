<?php

namespace Database\Seeders;

use App\Models\JenisOrmawa;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JenisOrmawaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        JenisOrmawa::firstOrCreate(['nama' => 'Himpunan']);
        JenisOrmawa::firstOrCreate(['nama' => 'UKM']);
        JenisOrmawa::firstOrCreate(['nama' => 'BEM']);
    }
}
