<?php

namespace App\Http\Controllers;

use App\Models\Kelompok;
use App\Models\Laporan;
use App\Models\LaporanAkhir;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class AuditorController extends Controller
{
    /**
     * Dashboard utama Auditor: ringkasan statistik seluruh data.
     */
    public function index(): View
    {
        $perRole = User::query()
            ->selectRaw('role, count(*) as total')
            ->groupBy('role')
            ->pluck('total', 'role');

        return view('auditor.index', [
            'perRole' => $perRole,
            'totalUser' => $perRole->sum(),
            'totalKelompok' => Kelompok::count(),
            'totalLaporan' => Laporan::count(),
            'laporanMenunggu' => Laporan::where('status', 'Belum Diperiksa')->count(),
            'laporanDisetujui' => Laporan::where('status', 'Disetujui')->count(),
            'totalLaporanAkhir' => LaporanAkhir::count(),
            'penggunaTerbaru' => User::latest()->limit(10)->get(['id', 'name', 'email', 'role', 'created_at']),
        ]);
    }

    /**
     * Daftar seluruh mahasiswa (read-only).
     */
    public function mahasiswa(Request $request): View
    {
        $search = trim($request->input('q', ''));
        $mahasiswas = User::where('role', 'MAHASISWA')
            ->with(['kelompok.dosen', 'kelompok.pembimbing'])
            ->withCount('laporans')
            ->when($search !== '', fn ($query) => $query->where(function ($q) use ($search) {
                $pattern = '%'.$search.'%';
                $q->where('name', 'like', $pattern)
                    ->orWhere('email', 'like', $pattern)
                    ->orWhere('nim_nip', 'like', $pattern);
            }))
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('auditor.mahasiswa', [
            'mahasiswas' => $mahasiswas,
            'q' => $search,
        ]);
    }

    /**
     * Rekapitulasi seluruh laporan harian (read-only).
     */
    public function laporanRekap(Request $request): View
    {
        $laporans = Laporan::with('mahasiswa:id,name,nim_nip')
            ->latest('tanggal')
            ->paginate(20)
            ->withQueryString();

        return view('auditor.laporan-rekap', [
            'laporans' => $laporans,
        ]);
    }

    /**
     * Rekapitulasi laporan akhir semua mahasiswa (read-only).
     */
    public function laporanAkhirRekap(): View
    {
        $laporanAkhirs = LaporanAkhir::with('mahasiswa:id,name,nim_nip')
            ->latest()
            ->get();

        return view('auditor.laporan-akhir-rekap', [
            'laporanAkhirs' => $laporanAkhirs,
        ]);
    }
}
