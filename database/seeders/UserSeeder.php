<?php

namespace Database\Seeders;

use App\Models\Departemen;
use App\Models\Dosen;
use App\Models\Kampus;
use App\Models\Kelompok;
use App\Models\Pembimbing;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // ============================================================
        // 1. SUPERADMIN (STATIS) — Kredensial tetap, identitas resmi
        // ============================================================
        User::updateOrCreate(
            ['email' => 'superadmin@kualanamu.id'],
            [
                'name' => 'Super Administrator',
                'password' => Hash::make('SuperAdm!n#2025Kualanamu'),
                'role' => 'SUPERADMIN',
                'instansi' => 'PT Angkasa Pura Aviasi',
            ],
        );

        // ============================================================
        // 2. ADMIN SISTEM (PT Angkasa Pura Aviasi)
        // ============================================================
        User::updateOrCreate(
            ['email' => 'admin@kualanamu.id'],
            [
                'name' => 'Admin Kualanamu',
                'password' => Hash::make('admin123'),
                'role' => 'ADMIN',
                'instansi' => 'PT Angkasa Pura Aviasi',
            ],
        );

        // ============================================================
        // 3. AUDITOR (Read-Only)
        // ============================================================
        User::updateOrCreate(
            ['email' => 'auditor@kualanamu.id'],
            [
                'name' => 'Auditor Internal',
                'password' => Hash::make('auditor123'),
                'role' => 'AUDITOR',
                'instansi' => 'PT Angkasa Pura Aviasi',
            ],
        );

        // ============================================================
        // 4. DOSEN PEMBIMBING SEKOLAH / KAMPUS
        // ============================================================
        $dosenUser = User::updateOrCreate(
            ['email' => 'dosen@kualanamu.id'],
            [
                'name' => 'Dr. Siti Rahayu, M.Kom',
                'password' => Hash::make('dosen123'),
                'role' => 'DOSEN',
                'nim_nip' => '198005112005012001',
                'hp_wa' => '081234567891',
                'instansi' => 'Universitas Sumatera Utara',
                'kampus' => 'Universitas Sumatera Utara',
                'fakultas' => 'Fakultas Ilmu Komputer & TI',
                'prodi' => 'Teknologi Informasi',
            ],
        );

        $dosenModel = Dosen::updateOrCreate(
            ['email' => 'dosen@kualanamu.id'],
            [
                'user_id' => $dosenUser->id,
                'nama' => 'Dr. Siti Rahayu, M.Kom',
                'nidn' => '198005112005012001',
                'kampus' => 'Universitas Sumatera Utara',
                'hp_wa' => '081234567891',
                'fakultas' => 'Fakultas Ilmu Komputer & TI',
                'prodi' => 'Teknologi Informasi',
            ],
        );

        // Master Departemen Penempatan
        $departemen = Departemen::firstOrCreate(
            ['nama_departemen' => 'Engineering / Teknik'],
            ['nama_pimpinan' => 'Ir. Hendra Gunawan, M.T.']
        );

        // ============================================================
        // 5. PEMBIMBING LAPANGAN (MENTOR)
        // ============================================================
        $mentorUser = User::updateOrCreate(
            ['email' => 'mentor@kualanamu.id'],
            [
                'name' => 'Budi Santoso, S.T.',
                'password' => Hash::make('mentor123'),
                'role' => 'MENTOR',
                'nim_nip' => 'PT-AP2-0091',
                'hp_wa' => '081234567892',
                'instansi' => 'PT Angkasa Pura Aviasi',
                'unit' => 'Engineering & IT Bandara Kualanamu',
            ],
        );

        $pembimbingModel = Pembimbing::updateOrCreate(
            ['email' => 'mentor@kualanamu.id'],
            [
                'user_id' => $mentorUser->id,
                'nama' => 'Budi Santoso, S.T.',
                'nip' => 'PT-AP2-0091',
                'hp_wa' => '081234567892',
                'departemen_id' => $departemen->id,
            ],
        );

        // Master Kampus Dinamis
        $kampusModel = Kampus::firstOrCreate(
            ['nama_universitas' => 'Universitas Sumatera Utara'],
            [
                'fakultas' => 'Fakultas Ilmu Komputer & TI',
                'prodi' => 'Teknologi Informasi',
                'alamat' => 'Jl. Dr. Mansyur No. 9, Medan',
            ]
        );

        // Master Kelompok Dinamis
        $kelompok = Kelompok::firstOrCreate(
            ['nama_kelompok' => 'Kelompok IT & Sistem Informasi 2026'],
            [
                'kampus_id' => $kampusModel->id,
                'departemen_id' => $departemen->id,
                'dosen_id' => $dosenModel->id,
                'pembimbing_id' => $pembimbingModel->id,
                'tanggal_mulai' => now()->startOfYear()->toDateString(),
                'tanggal_selesai' => now()->endOfYear()->toDateString(),
            ]
        );

        // ============================================================
        // 6. MAHASISWA PESERTA MAGANG
        // ============================================================
        User::updateOrCreate(
            ['email' => 'mahasiswa@kualanamu.id'],
            [
                'name' => 'Ahmad Fauzi',
                'password' => Hash::make('mahasiswa123'),
                'role' => 'MAHASISWA',
                'nim_nip' => '2103001',
                'hp_wa' => '081234567893',
                'instansi' => 'Universitas Sumatera Utara',
                'kampus' => 'Universitas Sumatera Utara',
                'fakultas' => 'Fakultas Ilmu Komputer & TI',
                'prodi' => 'Teknologi Informasi',
                'unit' => 'Engineering & IT Bandara Kualanamu',
                'dosen_id' => $dosenUser->id,
                'mentor_id' => $mentorUser->id,
                'kelompok_id' => $kelompok->id,
            ],
        );
    }
}
