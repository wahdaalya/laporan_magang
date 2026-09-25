<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $dosen = User::create([
            'name' => 'Dr. Siti Rahayu',
            'email' => 'dosen@kualanamu.id',
            'password' => Hash::make('dosen123'),
            'role' => 'DOSEN',
            'nim_nip' => '198005112005',
            'instansi' => 'Universitas Sumatera Utara',
        ]);

        $mentor = User::create([
            'name' => 'Budi Santoso',
            'email' => 'mentor@kualanamu.id',
            'password' => Hash::make('mentor123'),
            'role' => 'MENTOR',
            'nim_nip' => 'PT-AP2-0091',
            'instansi' => 'PT Angkasa Pura Aviasi',
            'unit' => 'IT & Digitalization Section',
        ]);

        User::create([
            'name' => 'Ahmad Fauzi',
            'email' => 'mahasiswa@kualanamu.id',
            'password' => Hash::make('mahasiswa123'),
            'role' => 'MAHASISWA',
            'nim_nip' => '2103001',
            'instansi' => 'Universitas Sumatera Utara',
            'unit' => 'IT & Digitalization Section',
            'dosen_id' => $dosen->id,
            'mentor_id' => $mentor->id,
        ]);

        User::create([
            'name' => 'Admin Kualanamu',
            'email' => 'admin@kualanamu.id',
            'password' => Hash::make('admin123'),
            'role' => 'ADMIN',
            'instansi' => 'PT Angkasa Pura Aviasi',
        ]);
    }
}