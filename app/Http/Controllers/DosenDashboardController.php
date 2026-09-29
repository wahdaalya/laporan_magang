<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\Laporan;
use App\Models\LaporanAkhir;
use App\Models\Penilaian;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DosenDashboardController extends Controller
{
    public function index(Request $request): View
    {
        $dosen = $request->user();
        $today = today();
        $pesertas = $this->pesertaQuery($dosen)
            ->with(['kelompok.kampus', 'kelompok.departemen', 'kelompok.pembimbing'])
            ->get();
        $mahasiswaAktif = 0;

        $pesertas->each(function (User $peserta) use ($today, &$mahasiswaAktif): void {
            $kelompok = $peserta->kelompok;
            $status = 'Belum Terjadwal';
            $progress = 0;

            if ($kelompok) {
                $mulai = $kelompok->tanggal_mulai;
                $selesai = $kelompok->tanggal_selesai;
                $totalHari = max($mulai->diffInDays($selesai) + 1, 1);
                $hariBerjalan = $mulai->gt($today) ? 0 : min($mulai->diffInDays($today) + 1, $totalHari);
                $progress = (int) round(($hariBerjalan / $totalHari) * 100);
                $status = $today->lt($mulai) ? 'Akan Dimulai' : ($today->gt($selesai) ? 'Selesai' : 'Aktif');

                if ($status === 'Aktif') {
                    $mahasiswaAktif++;
                }
            }

            $peserta->setAttribute('progress_durasi', $progress);
            $peserta->setAttribute('status_magang', $status);
            $peserta->setAttribute('perusahaan_tampil', $peserta->instansi
                ?? $kelompok?->departemen?->nama_departemen
                ?? $kelompok?->kampus?->nama_universitas
                ?? '-');
            $peserta->setAttribute('mentor_tampil', $kelompok?->pembimbing?->nama ?? '-');
        });

        $jumlahReview = LaporanAkhir::query()
            ->whereIn('mahasiswa_id', $this->pesertaQuery($dosen)->select('users.id'))
            ->where('status', 'Menunggu Review')
            ->count();

        return view('dosen.dashboard', [
            'pesertas' => $pesertas,
            'totalPeserta' => $pesertas->count(),
            'jumlahReview' => $jumlahReview,
            'mahasiswaAktif' => $mahasiswaAktif,
        ]);
    }

    public function logbook(Request $request): View
    {
        $filters = $request->validate([
            'nama' => ['nullable', 'string', 'max:255'],
            'bulan' => ['nullable', 'date_format:Y-m'],
        ]);
        $pesertaQuery = $this->pesertaQuery($request->user());
        $logbooksQuery = Laporan::query()
            ->whereIn('user_id', $pesertaQuery->select('users.id'))
            ->where('status', 'Disetujui')
            ->when($filters['nama'] ?? null, fn (Builder $query, string $nama) => $query->whereHas('mahasiswa', fn (Builder $peserta) => $peserta->where('name', 'like', '%'.$nama.'%')));

        if ($bulan = $filters['bulan'] ?? null) {
            $awal = Carbon::createFromFormat('Y-m', $bulan)->startOfMonth();
            $logbooksQuery->whereBetween('tanggal', [$awal->toDateString(), $awal->copy()->endOfMonth()->toDateString()]);
        }

        return view('dosen.logbook', [
            'logbooks' => $logbooksQuery->with('mahasiswa')->latest('tanggal')->get(),
            'pesertas' => $this->pesertaQuery($request->user())->orderBy('name')->get(['id', 'name']),
            'filters' => $filters,
        ]);
    }

    public function laporanAkhir(Request $request): View
    {
        $laporanAkhirs = LaporanAkhir::query()
            ->whereIn('mahasiswa_id', $this->pesertaQuery($request->user())->select('users.id'))
            ->with('mahasiswa')
            ->latest()
            ->get();

        return view('dosen.laporan-akhir', ['laporanAkhirs' => $laporanAkhirs]);
    }

    public function updateLaporanAkhir(Request $request, LaporanAkhir $laporanAkhir): RedirectResponse
    {
        abort_unless($this->pesertaQuery($request->user())->whereKey($laporanAkhir->mahasiswa_id)->exists(), 404);

        $data = $request->validate([
            'keputusan' => ['required', Rule::in(['revisi', 'setujui'])],
            'catatan_revisi' => ['nullable', 'required_if:keputusan,revisi', 'string', 'max:5000'],
        ]);

        $laporanAkhir->update([
            'status' => $data['keputusan'] === 'setujui' ? 'Disetujui' : 'Revisi',
            'catatan_revisi' => $data['catatan_revisi'] ?? null,
            'dosen_user_id' => $request->user()->id,
        ]);

        return back()->with('status', $data['keputusan'] === 'setujui'
            ? 'Laporan akhir berhasil disetujui.'
            : 'Catatan revisi berhasil disimpan.');
    }

    public function downloadLaporanAkhir(Request $request, LaporanAkhir $laporanAkhir): StreamedResponse
    {
        abort_unless($this->pesertaQuery($request->user())->whereKey($laporanAkhir->mahasiswa_id)->exists(), 404);
        /** @var FilesystemAdapter $disk */
        $disk = Storage::disk('public');
        abort_unless($disk->exists($laporanAkhir->file_path), 404);

        return $disk->download($laporanAkhir->file_path, basename($laporanAkhir->file_path), [
            'Content-Type' => 'application/pdf',
        ]);
    }

    public function penilaianAkhir(Request $request): View
    {
        $pesertas = $this->pesertaQuery($request->user())
            ->with(['kelompok.kampus', 'nilaiAkademik'])
            ->orderBy('name')
            ->get();
        $nilaiMentor = Penilaian::query()
            ->whereIn('mahasiswa_id', $pesertas->modelKeys())
            ->latest()
            ->get()
            ->unique('mahasiswa_id')
            ->keyBy('mahasiswa_id');

        return view('dosen.penilaian-akhir', [
            'pesertas' => $pesertas,
            'nilaiMentor' => $nilaiMentor,
        ]);
    }

    public function simpanNilaiAkhir(Request $request, User $peserta): RedirectResponse
    {
        abort_unless($this->pesertaQuery($request->user())->whereKey($peserta->id)->exists(), 404);

        $data = $request->validate([
            'nilai_bimbingan' => ['required', 'numeric', 'min:0', 'max:100'],
            'nilai_laporan_akhir' => ['required', 'numeric', 'min:0', 'max:100'],
            'nilai_presentasi' => ['required', 'numeric', 'min:0', 'max:100'],
        ]);

        $peserta->nilaiAkademik()->updateOrCreate([], [
            ...$data,
            'dosen_user_id' => $request->user()->id,
        ]);

        return back()->with('status', 'Nilai akhir berhasil disimpan untuk '.$peserta->name.'.');
    }

    private function pesertaQuery(User $dosen): Builder
    {
        $profilDosenId = Dosen::query()->where('user_id', $dosen->id)->value('id');

        return User::query()
            ->where('role', 'MAHASISWA')
            ->where(function (Builder $query) use ($dosen, $profilDosenId): void {
                $query->when($profilDosenId, fn (Builder $query) => $query->whereHas('kelompok', fn (Builder $kelompok) => $kelompok->where('dosen_id', $profilDosenId)))
                    ->orWhere('dosen_id', $dosen->id);
            });
    }
}
