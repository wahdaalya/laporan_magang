<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\Kelompok;
use App\Models\Laporan;
use App\Models\LaporanAkhir;
use App\Models\Penilaian;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DosenDashboardController extends Controller
{
    public function index(Request $request): View
    {
        $dosen = $request->user();
        $today = today();
        $pesertas = $this->pesertaQuery($dosen)
            ->with(['kelompok.kampus', 'kelompok.departemen', 'kelompok.pembimbing', 'laporanAkhir'])
            ->withCount([
                'laporans',
                'laporans as laporan_disetujui_count' => fn (Builder $query) => $query->where('status', 'Disetujui'),
            ])
            ->get();
        $profilDosenId = Dosen::query()->where('user_id', $dosen->id)->value('id');
        $kelompoksBimbingan = $profilDosenId
            ? Kelompok::query()
                ->where('dosen_id', $profilDosenId)
                ->with([
                    'departemen:id,nama_departemen',
                    'kampus:id,nama_universitas,fakultas,prodi',
                    'pembimbing:id,user_id,nama,nip,email,hp_wa',
                    'pembimbing.user:id,name,foto,hp_wa,email',
                    'mahasiswas' => fn (HasMany $query) => $query
                        ->where('role', 'MAHASISWA')
                        ->with('laporanAkhir')
                        ->withCount([
                            'laporans',
                            'laporans as laporan_disetujui_count' => fn (Builder $laporanQuery) => $laporanQuery->where('status', 'Disetujui'),
                        ]),
                ])
                ->withCount(['mahasiswas as mahasiswa_count' => fn (Builder $query) => $query->where('role', 'MAHASISWA')])
                ->orderBy('nama_kelompok')
                ->get()
            : collect();
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
            'kelompoksBimbingan' => $kelompoksBimbingan,
            'jumlahKelompokBimbingan' => $kelompoksBimbingan->count(),
            'jumlahMahasiswaKelompok' => $kelompoksBimbingan->sum('mahasiswa_count'),
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
            ->with('mahasiswa:id,name,nim_nip')
            ->latest('created_at')
            ->get();

        return view('dosen.laporan-akhir', ['laporanAkhirs' => $laporanAkhirs]);
    }

    public function updateLaporanAkhir(Request $request, LaporanAkhir $laporanAkhir): RedirectResponse
    {
        abort_unless($this->pesertaQuery($request->user())->whereKey($laporanAkhir->mahasiswa_id)->exists(), 404);
        $mahasiswa = $laporanAkhir->mahasiswa()->firstOrFail();

        $data = $request->validate([
            'keputusan' => ['required', Rule::in(['revisi', 'setuju', 'setujui', 'mengetahui'])],
            'catatan_revisi' => ['required_if:keputusan,revisi', 'nullable', 'string', 'max:5000'],
        ]);

        $updateData = [
            'dosen_user_id' => $this->dosenUserIdFor($mahasiswa),
        ];

        if ($data['keputusan'] === 'mengetahui') {
            $updateData['status_mengetahui'] = true;
            $updateData['mengetahui_oleh'] = $request->user()->name;
            $updateData['mengetahui_at'] = now();
            $pesan = 'Status "Mengetahui" berhasil dicatat untuk laporan akhir '.$mahasiswa->name.'.';
        } elseif (in_array($data['keputusan'], ['setuju', 'setujui'], true)) {
            $updateData['status'] = 'Disetujui';
            $updateData['status_setuju'] = true;
            $updateData['disetujui_oleh'] = $request->user()->name;
            $updateData['disetujui_at'] = now();
            $pesan = 'Laporan akhir berhasil disetujui (Status "Setuju") untuk '.$mahasiswa->name.'.';
        } else {
            $updateData['status'] = 'Revisi';
            $updateData['status_setuju'] = false;
            $updateData['catatan_revisi'] = $data['catatan_revisi'] ?? null;
            $pesan = 'Catatan revisi berhasil disimpan.';
        }

        LaporanAkhir::query()->whereKey($laporanAkhir->id)->update($updateData);

        return back()->with('status', $pesan);
    }

    public function downloadLaporanAkhir(Request $request, LaporanAkhir $laporanAkhir): StreamedResponse
    {
        abort_unless($this->pesertaQuery($request->user())->whereKey($laporanAkhir->mahasiswa_id)->exists(), 404);
        /** @var FilesystemAdapter $disk */
        abort_unless(strtolower(pathinfo($laporanAkhir->file_path, PATHINFO_EXTENSION)) === 'pdf', 404);
        $disk = Storage::disk('local');
        abort_unless($disk->exists($laporanAkhir->file_path)
            && $disk->mimeType($laporanAkhir->file_path) === 'application/pdf', 404);

        return $disk->download($laporanAkhir->file_path, basename($laporanAkhir->file_path), [
            'Content-Type' => 'application/pdf',
            'X-Content-Type-Options' => 'nosniff',
            'Cache-Control' => 'private, no-store, max-age=0',
        ]);
    }

    public function viewPenilaian(Request $request, Penilaian $penilaian): BinaryFileResponse
    {
        abort_unless($this->pesertaQuery($request->user())->whereKey($penilaian->mahasiswa_id)->exists(), 404);
        abort_unless(strtolower(pathinfo($penilaian->file, PATHINFO_EXTENSION)) === 'pdf', 404);

        $disk = Storage::disk('local');
        abort_unless($disk->exists($penilaian->file)
            && $disk->mimeType($penilaian->file) === 'application/pdf', 404);

        return response()->file($disk->path($penilaian->file), [
            'Content-Type' => 'application/pdf',
            'X-Content-Type-Options' => 'nosniff',
            'Cache-Control' => 'private, no-store, max-age=0',
        ]);
    }

    public function penilaianAkhir(Request $request): View
    {
        $pesertas = $this->pesertaQuery($request->user())
            ->with(['kelompok.kampus', 'nilaiAkademik', 'laporanAkhir'])
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
            'dosen_user_id' => $this->dosenUserIdFor($peserta),
        ]);

        return back()->with('status', 'Nilai akhir berhasil disimpan untuk '.$peserta->name.'.');
    }

    private function pesertaQuery(User $dosen): Builder
    {
        return $dosen->mahasiswaBimbinganDosen();
    }

    private function dosenUserIdFor(User $peserta): int
    {
        $dosenUserId = $peserta->loadMissing('kelompok.dosen')->kelompok?->dosen?->user_id;

        abort_unless($dosenUserId, 404);

        return (int) $dosenUserId;
    }
}
