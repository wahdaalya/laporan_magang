<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // ============================================================
        // SUPERADMIN STATIS — kredensial tetap, tidak boleh terhapus.
        // Gunakan updateOrCreate agar aman dijalankan berulang kali
        // (tidak membuat duplikat & tidak menimpa perubahan password).
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
        // Akun demo — dibuat hanya jika belum ada.
        // ============================================================

        $dosen = User::firstOrCreate(
            ['email' => 'dosen@kualanamu.id'],
            [
                'name' => 'Dr. Siti Rahayu',
                'password' => Hash::make('dosen123'),
                'role' => 'DOSEN',
                'nim_nip' => '198005112005',
                'instansi' => 'Universitas Sumatera Utara',
            ],
        );

        $mentor = User::firstOrCreate(
            ['email' => 'mentor@kualanamu.id'],
            [
                'name' => 'Budi Santoso',
                'password' => Hash::make('mentor123'),
                'role' => 'MENTOR',
                'nim_nip' => 'PT-AP2-0091',
                'instansi' => 'PT Angkasa Pura Aviasi',
                'unit' => 'IT & Digitalization Section',
            ],
        );

        User::firstOrCreate(
            ['email' => 'mahasiswa@kualanamu.id'],
            [
                'name' => 'Ahmad Fauzi',
                'password' => Hash::make('mahasiswa123'),
                'role' => 'MAHASISWA',
                'nim_nip' => '2103001',
                'instansi' => 'Universitas Sumatera Utara',
                'unit' => 'IT & Digitalization Section',
                'dosen_id' => $dosen->id,
                'mentor_id' => $mentor->id,
            ],
        );

        User::firstOrCreate(
            ['email' => 'admin@kualanamu.id'],
            [
                'name' => 'Admin Kualanamu',
                'password' => Hash::make('admin123'),
                'role' => 'ADMIN',
                'instansi' => 'PT Angkasa Pura Aviasi',
            ],
        );

        // ============================================================
        // AUDITOR (read-only) — akun demo untuk role auditor.
        // ============================================================
        User::firstOrCreate(
            ['email' => 'auditor@kualanamu.id'],
            [
                'name' => 'Auditor Internal',
                'password' => Hash::make('auditor123'),
                'role' => 'AUDITOR',
                'instansi' => 'PT Angkasa Pura Aviasi',
            ],
        );
    }
}
