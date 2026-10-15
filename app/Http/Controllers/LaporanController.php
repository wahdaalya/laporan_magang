<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLaporanRequest;
use App\Http\Requests\UpdateLaporanRequest;
use App\Models\Laporan;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
        abort_if($laporan->user_id !== auth()->id(), 403);

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
        $mahasiswa->load(['kelompok.kampus', 'kelompok.departemen', 'kelompok.dosen', 'kelompok.pembimbing', 'laporanAkhir']);

        // Ambil laporan milik mahasiswa yang sedang login.
        $laporans = Laporan::where('user_id', $mahasiswa->id)
            ->orderBy('tanggal')
            ->orderBy('id')
            ->get();

        if ($laporans->isEmpty()) {
            return redirect()
                ->route('mahasiswa.laporan.index')
                ->with('error', 'Belum ada laporan untuk diunduh.');
        }

        $pdf = Pdf::loadView(
            'mahasiswa.laporan-arsip-pdf',
            [
                'mahasiswa' => $mahasiswa,
                'laporans' => $laporans,
                'tanggalCetak' => now()->format('d/m/Y H:i'),
            ]
        )->setPaper('a4', 'portrait');

        $fileName = 'Arsip-Laporan-'.Str::slug($mahasiswa->name).'.pdf';

        return $pdf->download($fileName);
    }

    /**
     * Download arsip laporan mahasiswa untuk Admin, Auditor, Dosen, atau Mentor.
     */
    public function downloadMahasiswaPdf(User $user)
    {
        abort_unless(in_array(auth()->user()->role, ['ADMIN', 'SUPERADMIN', 'AUDITOR', 'DOSEN', 'MENTOR'], true), 403);

        $user->load(['kelompok.kampus', 'kelompok.departemen', 'kelompok.dosen', 'kelompok.pembimbing', 'laporanAkhir']);

        $laporans = Laporan::where('user_id', $user->id)
            ->orderBy('tanggal')
            ->orderBy('id')
            ->get();

        if ($laporans->isEmpty()) {
            return back()->with('error', 'Peserta ini belum memiliki laporan untuk diunduh.');
        }

        $pdf = Pdf::loadView(
            'mahasiswa.laporan-arsip-pdf',
            [
                'mahasiswa' => $user,
                'laporans' => $laporans,
                'tanggalCetak' => now()->format('d/m/Y H:i'),
            ]
        )->setPaper('a4', 'portrait');

        $fileName = 'Arsip-Laporan-'.Str::slug($user->name).'.pdf';

        return $pdf->download($fileName);
    }
}
