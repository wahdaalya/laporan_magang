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
        $tahun = $request->filled('tahun') ? (int) $request->input('tahun') : null;
        $daftarTahun = Kelompok::daftarTahunMagang();

        $mahasiswas = User::where('role', 'MAHASISWA')
            ->with(['kelompok.dosen', 'kelompok.pembimbing', 'kelompok.departemen'])
            ->withCount('laporans')
            ->when($search !== '', fn ($query) => $query->where(function ($q) use ($search) {
                $pattern = '%'.$search.'%';
                $q->where('name', 'like', $pattern)
                    ->orWhere('email', 'like', $pattern)
                    ->orWhere('nim_nip', 'like', $pattern)
                    ->orWhere('kampus', 'like', $pattern);
            }))
            ->when($tahun, fn ($query) => $query->whereHas('kelompok', fn ($kq) => $kq->whereYear('tanggal_mulai', $tahun)))
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('auditor.mahasiswa', [
            'mahasiswas' => $mahasiswas,
            'q' => $search,
            'tahun' => $tahun,
            'daftarTahun' => $daftarTahun,
        ]);
    }

    /**
     * Rekapitulasi seluruh data peserta & laporan (read-only) untuk Auditor.
     */
    public function laporanRekap(Request $request): View
    {
        $search = trim($request->input('q', ''));
        $status = $request->input('status');
        $tahun = $request->filled('tahun') ? (int) $request->input('tahun') : null;
        $daftarTahun = Kelompok::daftarTahunMagang();

        $query = Laporan::with([
            'mahasiswa.kelompok.kampus',
            'mahasiswa.kelompok.dosen',
            'mahasiswa.kelompok.pembimbing',
            'mahasiswa.kelompok.departemen',
        ]);

        if ($search !== '') {
            $query->whereHas('mahasiswa', function ($q) use ($search) {
                $pattern = '%'.$search.'%';
                $q->where('name', 'like', $pattern)
                    ->orWhere('nim_nip', 'like', $pattern);
            });
        }

        if ($status) {
            $query->where('status', $status);
        }

        if ($tahun) {
            $query->where(function ($sub) use ($tahun) {
                $sub->whereYear('tanggal', $tahun)
                    ->orWhereHas('mahasiswa.kelompok', fn ($kq) => $kq->whereYear('tanggal_mulai', $tahun));
            });
        }

        $laporans = $query->latest('tanggal')
            ->paginate(20)
            ->withQueryString();

        return view('auditor.laporan-rekap', [
            'laporans' => $laporans,
            'q' => $search,
            'status' => $status,
            'tahun' => $tahun,
            'daftarTahun' => $daftarTahun,
        ]);
    }

    /**
     * Rekapitulasi laporan akhir semua mahasiswa (read-only) dengan filter tahun magang.
     */
    public function laporanAkhirRekap(Request $request): View
    {
        $search = trim($request->input('q', ''));
        $status = $request->input('status');
        $tahun = $request->filled('tahun') ? (int) $request->input('tahun') : null;
        $daftarTahun = Kelompok::daftarTahunMagang();

        $query = LaporanAkhir::with(['mahasiswa.kelompok.departemen', 'dosen']);

        if ($search !== '') {
            $query->whereHas('mahasiswa', function ($q) use ($search) {
                $pattern = '%'.$search.'%';
                $q->where('name', 'like', $pattern)
                    ->orWhere('nim_nip', 'like', $pattern);
            });
        }

        if ($status) {
            $query->where('status', $status);
        }

        if ($tahun) {
            $query->where(function ($sub) use ($tahun) {
                $sub->whereYear('created_at', $tahun)
                    ->orWhereHas('mahasiswa.kelompok', fn ($kq) => $kq->whereYear('tanggal_mulai', $tahun));
            });
        }

        $laporanAkhirs = $query->latest()
            ->paginate(20)
            ->withQueryString();

        return view('auditor.laporan-akhir-rekap', [
            'laporanAkhirs' => $laporanAkhirs,
            'q' => $search,
            'status' => $status,
            'tahun' => $tahun,
            'daftarTahun' => $daftarTahun,
        ]);
    }
}
