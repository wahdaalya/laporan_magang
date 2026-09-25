<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartemenController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\KampusController;
use App\Http\Controllers\KelompokController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\PembimbinganController;
use App\Http\Controllers\PembimbingController;
use App\Http\Controllers\ProfileController;
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
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/admin', [AdminController::class, 'index'])
        ->middleware('role:ADMIN')
        ->name('admin.index');

    Route::get('/pembimbingan', [PembimbinganController::class, 'index'])
        ->middleware('role:DOSEN,MENTOR')
        ->name('pembimbingan.index');

    Route::middleware('role:MAHASISWA')->group(function () {
        Route::get('/mahasiswa/dashboard', [MahasiswaController::class, 'dashboard'])->name('mahasiswa.dashboard');
    });

    Route::middleware('role:ADMIN')->group(function () {
        Route::get('/kampus', [KampusController::class, 'index'])->name('kampus.index');
        Route::resource('dosen', DosenController::class)->parameters(['dosen' => 'dosen'])->except('show');
        Route::resource('pembimbing', PembimbingController::class)->parameters(['pembimbing' => 'pembimbing'])->only(['index', 'store', 'update', 'destroy']);
        Route::resource('departemen', DepartemenController::class)->parameters(['departemen' => 'departemen'])->except('show');
                Route::get('/kelompok', [KelompokController::class, 'index'])->name('kelompok.index');
        Route::post('/kelompok', [KelompokController::class, 'store'])->name('kelompok.store');
        Route::put('/kelompok/{kelompok}', [KelompokController::class, 'update'])->name('kelompok.update');
        Route::delete('/kelompok/{kelompok}', [KelompokController::class, 'destroy'])->name('kelompok.destroy');
                        Route::get('/kelompok/cari-mahasiswa', [KelompokController::class, 'searchMahasiswa'])->name('kelompok.mahasiswa.search');
        Route::get('/mahasiswa', [MahasiswaController::class, 'index'])->name('mahasiswa.index');
                Route::post('/mahasiswa', [MahasiswaController::class, 'store'])->name('mahasiswa.store');
        Route::put('/mahasiswa/{mahasiswa}', [MahasiswaController::class, 'update'])->name('mahasiswa.update');
        Route::delete('/mahasiswa/{mahasiswa}', [MahasiswaController::class, 'destroy'])->name('mahasiswa.destroy');
    });
});

require __DIR__.'/auth.php';
