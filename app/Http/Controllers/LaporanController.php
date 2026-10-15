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
            $data['foto'] = $request->file('foto')->store('laporan-foto', 'local');
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
                Storage::disk('local')->delete($laporan->foto);
            }
            $data['foto'] = $request->file('foto')->store('laporan-foto', 'local');
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
        if ($laporan->status !== 'Belum Diperiksa') {
            return redirect()->route('mahasiswa.dashboard')->with('error', $laporan->isTerkunci()
                ? 'Laporan yang sudah di-ACC pembimbing terkunci dan tidak bisa dihapus.'
                : 'Kegiatan yang sudah diperiksa tidak bisa dihapus.');
        }

        if ($laporan->foto) {
            Storage::disk('local')->delete($laporan->foto);
        }

        $laporan->delete();

        return redirect()->route('mahasiswa.dashboard')
            ->with('status', 'Laporan kegiatan Magang / PKL berhasil dihapus.');
    }
    
public function downloadPdf()
{
    $mahasiswa = auth()->user();

    // Ambil laporan milik mahasiswa yang sedang login.
    $laporans = \App\Models\Laporan::where('user_id', $mahasiswa->id)
        ->orderBy('tanggal')
->orderBy('id')
        ->get();

    if ($laporans->isEmpty()) {
        return redirect()
            ->route('mahasiswa.laporan.index')
            ->with('error', 'Belum ada laporan untuk diunduh.');
    }

    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView(
        'mahasiswa.laporan-arsip-pdf',
        [
            'mahasiswa' => $mahasiswa,
            'laporans' => $laporans,
            'tanggalCetak' => now()->format('d/m/Y H:i'),
        ]
    );

    return $pdf->download('arsip-laporan-magang.pdf');
}

}
