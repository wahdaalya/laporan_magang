<?php

namespace App\Http\Controllers;

use App\Models\LaporanAkhir;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LaporanAkhirController extends Controller
{
    public function index(Request $request): View
    {
        return view('mahasiswa.laporan-akhir', [
            'laporanAkhir' => $request->user()->laporanAkhir,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'judul_laporan' => ['required', 'string', 'max:255'],
            'file' => ['required', 'file', 'mimes:pdf', 'mimetypes:application/pdf', 'extensions:pdf', 'max:20480'],
        ]);
        $peserta = $request->user();
        $laporanAkhir = $peserta->laporanAkhir;
        $path = $data['file']->store('laporan-akhir', 'public');

        if ($laporanAkhir) {
            Storage::disk('public')->delete($laporanAkhir->file_path);
            $laporanAkhir->update([
                'judul_laporan' => $data['judul_laporan'],
                'file_path' => $path,
                'status' => 'Menunggu Review',
                'catatan_revisi' => null,
                'dosen_user_id' => null,
            ]);
        } else {
            LaporanAkhir::create([
                'mahasiswa_id' => $peserta->id,
                'judul_laporan' => $data['judul_laporan'],
                'file_path' => $path,
                'status' => 'Menunggu Review',
            ]);
        }

        return back()->with('status', 'Draf Laporan Akhir berhasil diunggah.');
    }
}
