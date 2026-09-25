<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLaporanRequest;
use App\Http\Requests\UpdateLaporanRequest;
use App\Models\Laporan;
use Illuminate\Http\RedirectResponse;

class LaporanController extends Controller
{
    /**
     * Mahasiswa menambah kegiatan harian miliknya sendiri.
     */
    public function store(StoreLaporanRequest $request): RedirectResponse
    {
        $request->user()->laporans()->create([
            ...$request->validated(),
            'status' => 'Belum Diperiksa',
        ]);

        return redirect()->route('mahasiswa.dashboard')
            ->with('status', 'Kegiatan berhasil ditambahkan.');
    }

    /**
     * Mahasiswa mengubah kegiatan miliknya (selama belum diperiksa).
     */
    public function update(UpdateLaporanRequest $request, Laporan $laporan): RedirectResponse
    {
        $laporan->update($request->validated());

        return redirect()->route('mahasiswa.dashboard')
            ->with('status', 'Kegiatan berhasil diperbarui.');
    }

    /**
     * Mahasiswa menghapus kegiatan miliknya (selama belum diperiksa).
     */
    public function destroy(Laporan $laporan): RedirectResponse
    {
        abort_if($laporan->user_id !== auth()->id(), 403);
        abort_if($laporan->status !== 'Belum Diperiksa', 403, 'Kegiatan yang sudah diperiksa tidak bisa dihapus.');

        $laporan->delete();

        return redirect()->route('mahasiswa.dashboard')
            ->with('status', 'Kegiatan berhasil dihapus.');
    }
}