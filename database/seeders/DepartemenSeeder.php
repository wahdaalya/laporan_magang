<?php

namespace Database\Seeders;

use App\Models\Departemen;
use Illuminate\Database\Seeder;

class DepartemenSeeder extends Seeder
{
    public function run(): void
    {
        $departemens = [
            'Executive General Manager / Office of the GM',
            'Finance / Keuangan',
            'Human Capital',
            'Operation & Services',
            'Aviation Security (Avsec)',
            'Engineering / Teknik',
            'Procurement / Pengadaan',
        ];

        foreach ($departemens as $nama) {
            Departemen::firstOrCreate(['nama_departemen' => $nama]);
        }
    }
}