<?php

namespace App\Providers;

use App\Models\Laporan;
use App\Models\LaporanAkhir;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
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
        if (app()->environment('production')) {
            URL::forceScheme('https');
        }

        View::composer('layouts.app', function (ViewInstance $view): void {
            /** @var User|null $user */
            $user = Auth::user();

            if (! $user) {
                $view->with('profileSummary', null);

                return;
            }

            $roleLabel = match ($user->role) {
                'ADMIN' => 'Administrator',
                'MENTOR' => 'Pembimbing Lapangan',
                'DOSEN' => 'Dosen / Guru Pembimbing',
                'MAHASISWA' => 'Peserta Magang / PKL',
                default => 'Pengguna',
            };

            $notifikasiAdmin = null;
            if (in_array($user->role, ['ADMIN', 'SUPERADMIN'], true)) {
                $pendingLaporan = Laporan::where('status', 'Belum Diperiksa')->count();
                $pendingLaporanAkhir = LaporanAkhir::where('status', 'Menunggu Review')->count();
                $totalPending = $pendingLaporan + $pendingLaporanAkhir;

                $laporanBaru = Laporan::with('mahasiswa:id,name')
                    ->latest('created_at')
                    ->limit(4)
                    ->get()
                    ->map(fn ($lap) => [
                        'judul' => 'Laporan Harian: '.($lap->mahasiswa?->name ?? 'Peserta'),
                        'keterangan' => Str::limit($lap->kegiatan, 40),
                        'waktu' => $lap->created_at?->diffForHumans() ?? 'Baru saja',
                        'status' => $lap->status,
                        'ikon' => 'fas fa-clipboard-list text-amber-500',
                    ]);

                $laporanAkhirBaru = LaporanAkhir::with('mahasiswa:id,name')
                    ->latest('created_at')
                    ->limit(3)
                    ->get()
                    ->map(fn ($la) => [
                        'judul' => 'Laporan Akhir: '.($la->mahasiswa?->name ?? 'Peserta'),
                        'keterangan' => Str::limit($la->judul_laporan, 40),
                        'waktu' => $la->created_at?->diffForHumans() ?? 'Baru saja',
                        'status' => $la->status,
                        'ikon' => 'fas fa-file-signature text-rose-500',
                    ]);

                $daftar = $laporanBaru->concat($laporanAkhirBaru)->take(5)->values();

                $notifikasiAdmin = [
                    'totalPending' => $totalPending,
                    'pendingLaporan' => $pendingLaporan,
                    'pendingLaporanAkhir' => $pendingLaporanAkhir,
                    'daftar' => $daftar,
                ];
            }

            $view->with('notifikasiAdmin', $notifikasiAdmin);

            $view->with('profileSummary', [
                'name' => $user->display_name,
                'email' => $user->email,
                'photo' => $user->foto_url,
                'initial' => strtoupper(substr($user->display_name ?: 'U', 0, 1)),
                'role' => $roleLabel,
            ]);
        });
    }
}
