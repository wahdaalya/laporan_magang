<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLaporanRequest;
use App\Http\Requests\UpdateLaporanRequest;
use App\Models\Laporan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class LaporanController extends Controller
{
    public function store(StoreLaporanRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('laporan-foto', 'public');
        }

        $request->user()->laporans()->create([
            ...$data,
            'status' => 'Belum Diperiksa',
        ]);

        return redirect()->route('mahasiswa.dashboard')
            ->with('status', 'Laporan kegiatan Magang / PKL berhasil ditambahkan.');
    }

    public function update(UpdateLaporanRequest $request, Laporan $laporan): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('foto')) {
            if ($laporan->foto) {
                Storage::disk('public')->delete($laporan->foto);
            }
            $data['foto'] = $request->file('foto')->store('laporan-foto', 'public');
        } else {
            unset($data['foto']);
        }

        $laporan->update($data);

        return redirect()->route('mahasiswa.dashboard')
            ->with('status', 'Laporan kegiatan Magang / PKL berhasil diperbarui.');
    }

    public function destroy(Laporan $laporan): RedirectResponse
    {
        abort_if($laporan->user_id !== auth()->id(), 403);
        abort_if($laporan->status !== 'Belum Diperiksa', 403, 'Kegiatan yang sudah diperiksa tidak bisa dihapus.');

        if ($laporan->foto) {
            Storage::disk('public')->delete($laporan->foto);
        }

        $laporan->delete();

        return redirect()->route('mahasiswa.dashboard')
            ->with('status', 'Laporan kegiatan Magang / PKL berhasil dihapus.');
    }
}
