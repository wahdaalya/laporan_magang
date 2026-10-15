<?php

use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuditorController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartemenController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\DosenDashboardController;
use App\Http\Controllers\KampusController;
use App\Http\Controllers\KelompokController;
use App\Http\Controllers\LaporanAkhirController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\PembimbinganController;
use App\Http\Controllers\PembimbingController;
use App\Http\Controllers\PenilaianMahasiswaController;
use App\Http\Controllers\PenilaianUploadController;
use App\Http\Controllers\PrivateMediaController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SuperAdminController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }

    return redirect()->route('login');
})->name('home');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/users/{user}/photo', [PrivateMediaController::class, 'profilePhoto'])
        ->middleware('throttle:60,1')
        ->name('media.profile-photo');
    Route::get('/laporan/{laporan}/photo', [PrivateMediaController::class, 'laporanPhoto'])
        ->middleware('throttle:60,1')
        ->name('media.laporan-photo');

    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->middleware('throttle:10,1')->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->middleware('throttle:5,1')->name('profile.destroy');

    Route::get('/superadmin', [SuperAdminController::class, 'index'])
        ->middleware('role:SUPERADMIN')
        ->name('superadmin.index');

    // -------------------------------------------------------
    // Auditor: hanya akses baca (read-only), TANPA aksi tulis
    // -------------------------------------------------------
    Route::middleware('role:AUDITOR,SUPERADMIN')->prefix('auditor')->name('auditor.')->group(function () {
        Route::get('/', [AuditorController::class, 'index'])->name('index');
        Route::get('/mahasiswa', [AuditorController::class, 'mahasiswa'])->name('mahasiswa');
        Route::get('/laporan', [AuditorController::class, 'laporanRekap'])->name('laporan');
        Route::get('/laporan-akhir', [AuditorController::class, 'laporanAkhirRekap'])->name('laporan-akhir');
    });

    Route::get('/laporan/mahasiswa/{user}/unduh-pdf', [LaporanController::class, 'downloadMahasiswaPdf'])
        ->middleware('role:ADMIN,SUPERADMIN,AUDITOR,DOSEN,MENTOR')
        ->name('laporan.mahasiswa.download-pdf');

    Route::get('/laporan-akhir/{laporanAkhir}/unduh-admin', [LaporanAkhirController::class, 'downloadAdmin'])
        ->middleware('role:ADMIN,SUPERADMIN,AUDITOR,DOSEN,MENTOR')
        ->name('laporan-akhir.download-admin');

    Route::get('/admin', [AdminController::class, 'index'])
        ->middleware('role:ADMIN')
        ->name('admin.index');
    Route::post('/impersonate/leave', [AdminController::class, 'leaveImpersonate'])
        ->name('admin.impersonate.leave');

    Route::get('/pembimbingan', [PembimbinganController::class, 'index'])
        ->middleware('role:DOSEN,MENTOR')
        ->name('pembimbingan.index');

    Route::middleware('role:DOSEN,MENTOR')->group(function () {
        Route::get('/pembimbingan/laporan', [PembimbinganController::class, 'laporan'])->name('pembimbingan.laporan.index');
        Route::patch('/pembimbingan/laporan/{laporan}/mengetahui', [PembimbinganController::class, 'mengetahui'])->name('pembimbingan.laporan.mengetahui');
        Route::patch('/pembimbingan/laporan/{laporan}/setujui', [PembimbinganController::class, 'setujui'])->name('pembimbingan.laporan.setujui');
        Route::patch('/pembimbingan/laporan/{laporan}/revisi', [PembimbinganController::class, 'revisi'])->name('pembimbingan.laporan.revisi');
        Route::post('/pembimbingan/laporan/setujui-hari-ini', [PembimbinganController::class, 'setujuiHariIni'])->name('pembimbingan.laporan.setujui-hari-ini');
        Route::get('/pembimbingan/peserta', [PembimbinganController::class, 'peserta'])->name('pembimbingan.peserta');
        Route::get('/pembimbing/peserta', [PembimbinganController::class, 'peserta'])->name('pembimbing.peserta');
        Route::post('/pembimbingan/peserta/{peserta}/penilaian', [PenilaianUploadController::class, 'store'])->middleware('throttle:10,1')->name('pembimbingan.penilaian.store');
    });

    Route::middleware('role:DOSEN')->group(function () {
        Route::get('/dosen/dashboard', [DosenDashboardController::class, 'index'])->name('dosen.dashboard');
        Route::get('/dosen/logbook', [DosenDashboardController::class, 'logbook'])->name('dosen.logbook.index');
        Route::get('/dosen/bimbingan-laporan', [DosenDashboardController::class, 'laporanAkhir'])->name('dosen.laporan-akhir.index');
        Route::patch('/dosen/bimbingan-laporan/{laporanAkhir}', [DosenDashboardController::class, 'updateLaporanAkhir'])->name('dosen.laporan-akhir.update');
        Route::get('/dosen/bimbingan-laporan/{laporanAkhir}/unduh', [DosenDashboardController::class, 'downloadLaporanAkhir'])->name('dosen.laporan-akhir.download');
        Route::get('/dosen/penilaian-akhir', [DosenDashboardController::class, 'penilaianAkhir'])->name('dosen.penilaian-akhir.index');
        Route::get('/dosen/penilaian/{penilaian}/lihat', [DosenDashboardController::class, 'viewPenilaian'])->name('dosen.penilaian.view');
        Route::put('/dosen/penilaian-akhir/{peserta}', [DosenDashboardController::class, 'simpanNilaiAkhir'])->name('dosen.penilaian-akhir.update');
    });

    Route::middleware('role:MAHASISWA')->group(function () {
        Route::get('/mahasiswa/dashboard', [MahasiswaController::class, 'dashboard'])->name('mahasiswa.dashboard');
        Route::get('/mahasiswa/laporan', [MahasiswaController::class, 'laporanIndex'])->name('mahasiswa.laporan.index');

        Route::get('/mahasiswa/laporan/unduh-pdf', [LaporanController::class, 'downloadPdf'])
            ->middleware('throttle:10,1')
            ->name('mahasiswa.laporan.download-pdf');

        Route::post('/laporan', [LaporanController::class, 'store'])->middleware('throttle:10,1')->name('laporan.store');
        Route::put('/laporan/{laporan}', [LaporanController::class, 'update'])->middleware('throttle:10,1')->name('laporan.update');
        Route::delete('/laporan/{laporan}', [LaporanController::class, 'destroy'])->name('laporan.destroy');
        Route::get('/mahasiswa/absensi', [AbsensiController::class, 'index'])->name('mahasiswa.absensi.index');
        Route::get('/mahasiswa/absensi/cetak', [AbsensiController::class, 'cetak'])->name('mahasiswa.absensi.cetak');
        Route::get('/mahasiswa/penilaian', [PenilaianMahasiswaController::class, 'index'])->name('mahasiswa.penilaian.index');
        Route::get('/mahasiswa/penilaian/{penilaian}/unduh', [PenilaianMahasiswaController::class, 'download'])->name('mahasiswa.penilaian.download');
        Route::get('/mahasiswa/laporan-akhir', [LaporanAkhirController::class, 'index'])->name('mahasiswa.laporan-akhir.index');
        Route::get('/mahasiswa/laporan-akhir/cetak', [LaporanAkhirController::class, 'cetak'])->middleware('throttle:10,1')->name('mahasiswa.laporan-akhir.cetak');
        Route::post('/mahasiswa/laporan-akhir/ajukan', [LaporanAkhirController::class, 'ajukan'])->middleware('throttle:10,1')->name('mahasiswa.laporan-akhir.ajukan');
        Route::get('/mahasiswa/laporan-akhir/{laporanAkhir}/unduh', [LaporanAkhirController::class, 'download'])->name('mahasiswa.laporan-akhir.download');
        Route::get('/mahasiswa/penilaian/{penilaian}/lihat', [PenilaianMahasiswaController::class, 'view'])->name('mahasiswa.penilaian.view');
    });

    Route::get('/mahasiswa', [MahasiswaController::class, 'index'])
        ->middleware('role:ADMIN,SUPERADMIN,MAHASISWA')
        ->name('mahasiswa.index');

    Route::middleware('role:ADMIN')->group(function () {
        Route::get('/kampus', [KampusController::class, 'index'])->name('kampus.index');
        Route::post('/kampus', [KampusController::class, 'store'])->name('kampus.store');
        Route::put('/kampus/{kampus}', [KampusController::class, 'update'])->name('kampus.update');
        Route::delete('/kampus/{kampus}', [KampusController::class, 'destroy'])->name('kampus.destroy');
        Route::resource('dosen', DosenController::class)->parameters(['dosen' => 'dosen'])->except('show')->middleware('throttle:60,1');
        Route::post('/dosen/{dosen}/reset-password', [DosenController::class, 'resetPassword'])->middleware('throttle:5,1')->name('dosen.reset-password');
        Route::resource('pembimbing', PembimbingController::class)->parameters(['pembimbing' => 'pembimbing'])->only(['index', 'store', 'update', 'destroy'])->middleware('throttle:60,1');
        Route::post('/pembimbing/{pembimbing}/reset-password', [PembimbingController::class, 'resetPassword'])->middleware('throttle:5,1')->name('pembimbing.reset-password');
        Route::resource('departemen', DepartemenController::class)->parameters(['departemen' => 'departemen'])->except('show');
        Route::get('/kelompok', [KelompokController::class, 'index'])->name('kelompok.index');
        Route::post('/kelompok', [KelompokController::class, 'store'])->name('kelompok.store');
        Route::put('/kelompok/{kelompok}', [KelompokController::class, 'update'])->name('kelompok.update');
        Route::delete('/kelompok/{kelompok}', [KelompokController::class, 'destroy'])->name('kelompok.destroy');
        Route::post('/kelompok/{kelompok}/anggota', [KelompokController::class, 'addAnggota'])->name('kelompok.anggota.store');
        Route::get('/kelompok/cari-mahasiswa', [KelompokController::class, 'searchMahasiswa'])->name('kelompok.mahasiswa.search');
        Route::get('/kelompok/{kelompok}/pdf', [KelompokController::class, 'exportPdf'])->name('kelompok.pdf');
        Route::post('/mahasiswa', [MahasiswaController::class, 'store'])->middleware('throttle:10,1')->name('mahasiswa.store');
        Route::put('/mahasiswa/{mahasiswa}', [MahasiswaController::class, 'update'])->middleware('throttle:10,1')->name('mahasiswa.update');
        Route::delete('/mahasiswa/{mahasiswa}', [MahasiswaController::class, 'destroy'])->name('mahasiswa.destroy');
        Route::post('/mahasiswa/{user}/impersonate', [AdminController::class, 'impersonate'])
            ->middleware('throttle:10,1')
            ->name('mahasiswa.impersonate');
    });
});

require __DIR__.'/auth.php';
