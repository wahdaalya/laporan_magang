<?php

namespace App\Providers;

use App\Models\Dosen;
use App\Models\Pembimbing;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\View as ViewInstance;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('layouts.app', function (ViewInstance $view): void {
            /** @var User|null $user */
            $user = Auth::user();

            if (! $user) {
                $view->with('profileSummary', null);

                return;
            }

            $details = [];
            $roleLabel = 'Pengguna';

            if ($user->role === 'ADMIN') {
                $roleLabel = 'Administrator';
                $details = [
                    ['label' => 'Status Role', 'value' => 'Administrator'],
                    ['label' => 'Akses Sistem', 'value' => 'Administrasi seluruh data'],
                ];
            } elseif ($user->role === 'MENTOR') {
                $roleLabel = 'Pembimbing Lapangan';
                $pembimbing = Pembimbing::query()->with('departemen')->where('user_id', $user->id)->first();
                $details = [
                    ['label' => 'Departemen / Unit Kerja', 'value' => $pembimbing?->departemen?->nama_departemen ?? $user->unit ?? '-'],
                    ['label' => 'Instansi', 'value' => 'PT Angkasa Pura Aviasi'],
                ];
            } elseif ($user->role === 'DOSEN') {
                $roleLabel = 'Dosen / Guru Pembimbing';
                $dosen = Dosen::query()->where('user_id', $user->id)->first();
                $details = [
                    ['label' => 'Asal Kampus / Sekolah', 'value' => $dosen?->kampus ?? $user->kampus ?? $user->instansi ?? '-'],
                    ['label' => 'NIDN / NIP', 'value' => $dosen?->nidn ?? $user->nim_nip ?? '-'],
                    ['label' => 'Jabatan Akademik', 'value' => $user->unit ?? 'Dosen / Guru Pembimbing'],
                ];
            } elseif ($user->role === 'MAHASISWA') {
                $roleLabel = 'Peserta Magang / PKL';
                $user->loadMissing('kelompok.kampus');
                $details = [
                    ['label' => 'NIM / NISN', 'value' => $user->nim_nip ?? '-'],
                    ['label' => 'Sekolah / Kampus', 'value' => $user->kampus ?? $user->kelompok?->kampus?->nama_universitas ?? $user->instansi ?? '-'],
                ];
            }

            $view->with('profileSummary', [
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->hp_wa ?: '-',
                'photo' => $user->foto_url,
                'initial' => strtoupper(substr($user->name ?? 'U', 0, 1)),
                'role' => $roleLabel,
                'details' => $details,
            ]);
        });
    }
}
