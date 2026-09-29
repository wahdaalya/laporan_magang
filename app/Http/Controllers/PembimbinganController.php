<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\Laporan;
use App\Models\Pembimbing;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PembimbinganController extends Controller
{
    public function index(Request $request): View
    {
        $pembimbing = $request->user();
        $today = today();
        $pesertas = $this->pesertaQuery($pembimbing)
            ->with([
                'kelompok.kampus',
                'kelompok.departemen',
                'laporans' => fn (Builder $query) => $query->whereDate('tanggal', $today),
            ])
            ->orderBy('name')
            ->get();

        $pesertas->each(function (User $peserta) use ($today): void {
            $kelompok = $peserta->kelompok;
            $laporanHariIni = $peserta->laporans->isNotEmpty();
            $progressDurasi = 0;

            if ($kelompok) {
                $durasiTotal = max($kelompok->tanggal_mulai->diffInDays($kelompok->tanggal_selesai) + 1, 1);
                $durasiTerpakai = $kelompok->tanggal_mulai->gt($today)
                    ? 0
                    : min($kelompok->tanggal_mulai->diffInDays($today) + 1, $durasiTotal);
                $progressDurasi = (int) round(($durasiTerpakai / $durasiTotal) * 100);
            }

            $peserta->setAttribute('status_laporan_hari_ini', $laporanHariIni ? 'Sudah Mengisi' : 'Belum Mengisi');
            $peserta->setAttribute('progress_durasi', $progressDurasi);
            $peserta->setAttribute('instansi_tampil', $peserta->kampus
                ?? $kelompok?->kampus?->nama_universitas
                ?? $peserta->instansi
                ?? '-');
            $peserta->setAttribute('jurusan_tampil', trim(($peserta->fakultas ?? '').' / '.($peserta->prodi ?? ''), ' /') ?: '-');
        });

        $belumLaporHariIni = $pesertas->filter(fn (User $peserta): bool => $peserta->status_laporan_hari_ini === 'Belum Mengisi')->values();
        $totalLaporanPending = $this->laporanQuery($pembimbing)
            ->where('status', 'Belum Diperiksa')
            ->count();

        return view('pembimbingan.index', [
            'pesertas' => $pesertas,
            'belumLaporHariIni' => $belumLaporHariIni,
            'totalPeserta' => $pesertas->count(),
            'totalLaporanPending' => $totalLaporanPending,
            'totalBelumLapor' => $belumLaporHariIni->count(),
        ]);
    }

    public function laporan(Request $request): View
    {
        $filters = $request->validate([
            'tanggal' => ['nullable', 'date'],
            'status' => ['nullable', Rule::in(['Belum Diperiksa', 'Disetujui', 'Revisi', 'Ditolak'])],
            'nama' => ['nullable', 'string', 'max:255'],
            'peserta' => ['nullable', 'integer'],
        ]);

        $laporans = $this->laporanQuery($request->user())
            ->with('mahasiswa')
            ->when($filters['tanggal'] ?? null, fn (Builder $query, string $tanggal) => $query->whereDate('tanggal', $tanggal))
            ->when($filters['status'] ?? null, fn (Builder $query, string $status) => $query->where('status', $status))
            ->when($filters['nama'] ?? null, fn (Builder $query, string $nama) => $query->whereHas('mahasiswa', fn (Builder $query) => $query->where('name', 'like', '%'.$nama.'%')))
            ->when($filters['peserta'] ?? null, fn (Builder $query, string $pesertaId) => $query->where('user_id', (int) $pesertaId))
            ->latest('tanggal')
            ->latest('id')
            ->get();

        return view('pembimbingan.laporan', [
            'laporans' => $laporans,
            'filters' => $filters,
            'jumlahPendingHariIni' => $this->laporanQuery($request->user())
                ->whereDate('tanggal', today())
                ->where('status', 'Belum Diperiksa')
                ->count(),
        ]);
    }

    public function setujui(Request $request, Laporan $laporan): RedirectResponse
    {
        $this->authorizeLaporan($request->user(), $laporan);
        $data = $request->validate([
            'catatan_pembimbing' => ['nullable', 'string', 'max:5000'],
        ]);

        $laporan->update([
            'status' => 'Disetujui',
            'catatan_pembimbing' => $data['catatan_pembimbing'] ?? null,
            'diperiksa_oleh' => $request->user()->name,
        ]);

        return back()->with('status', 'Laporan peserta berhasil disetujui.');
    }

    public function revisi(Request $request, Laporan $laporan): RedirectResponse
    {
        $this->authorizeLaporan($request->user(), $laporan);
        $data = $request->validate([
            'catatan_pembimbing' => ['required', 'string', 'max:5000'],
        ]);

        $laporan->update([
            'status' => 'Revisi',
            'catatan_pembimbing' => $data['catatan_pembimbing'],
            'diperiksa_oleh' => $request->user()->name,
        ]);

        return back()->with('status', 'Catatan revisi berhasil disimpan.');
    }

    public function setujuiHariIni(Request $request): RedirectResponse
    {
        $updated = $this->laporanQuery($request->user())
            ->whereDate('tanggal', today())
            ->where('status', 'Belum Diperiksa')
            ->update([
                'status' => 'Disetujui',
                'diperiksa_oleh' => $request->user()->name,
                'updated_at' => now(),
            ]);

        return back()->with('status', $updated.' laporan hari ini berhasil disetujui.');
    }

    public function peserta(Request $request): View
    {
        $pesertas = $this->pesertaQuery($request->user())
            ->with(['kelompok.kampus', 'penilaians' => fn (Builder $query) => $query->latest()->limit(1)])
            ->orderBy('name')
            ->get();

        return view('pembimbingan.peserta', ['pesertas' => $pesertas]);
    }

    private function pesertaQuery(User $pembimbing): Builder
    {
        $isDosen = $pembimbing->role === 'DOSEN';
        $profileId = $isDosen
            ? Dosen::query()->where('user_id', $pembimbing->id)->value('id')
            : Pembimbing::query()->where('user_id', $pembimbing->id)->value('id');
        $groupForeignKey = $isDosen ? 'dosen_id' : 'pembimbing_id';
        $directForeignKey = $isDosen ? 'dosen_id' : 'mentor_id';

        return User::query()
            ->where('role', 'MAHASISWA')
            ->where(function (Builder $query) use ($profileId, $groupForeignKey, $directForeignKey, $pembimbing): void {
                $query->when($profileId, fn (Builder $query) => $query->whereHas('kelompok', fn (Builder $kelompokQuery) => $kelompokQuery->where($groupForeignKey, $profileId)))
                    ->orWhere($directForeignKey, $pembimbing->id);
            });
    }

    private function laporanQuery(User $pembimbing): Builder
    {
        return Laporan::query()->whereIn('user_id', $this->pesertaQuery($pembimbing)->select('users.id'));
    }

    private function authorizeLaporan(User $pembimbing, Laporan $laporan): void
    {
        abort_unless($this->pesertaQuery($pembimbing)->whereKey($laporan->user_id)->exists(), 404);
    }
}
