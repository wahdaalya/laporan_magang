<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use App\Models\LaporanAkhir;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Contracts\View\View;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LaporanAkhirController extends Controller
{
    public function index(Request $request): View
    {
        $mahasiswa = $request->user();

        return view('mahasiswa.laporan-akhir', [
            'rekap' => $this->buildReportData($mahasiswa),
            'laporanAkhir' => $mahasiswa->laporanAkhir,
        ]);
    }

    public function cetak(Request $request): StreamedResponse
    {
        $laporanAkhir = $request->user()->laporanAkhir;
        abort_unless($laporanAkhir?->status === 'Disetujui', 404);

        $disk = Storage::disk('local');
        abort_unless(strtolower(pathinfo($laporanAkhir->file_path, PATHINFO_EXTENSION)) === 'pdf', 404);
        abort_unless($disk->exists($laporanAkhir->file_path)
            && $disk->mimeType($laporanAkhir->file_path) === 'application/pdf', 404);

        return $disk->download($laporanAkhir->file_path, basename($laporanAkhir->file_path), [
            'Content-Type' => 'application/pdf',
            'X-Content-Type-Options' => 'nosniff',
            'Cache-Control' => 'private, no-store, max-age=0',
        ]);
    }

    public function ajukan(Request $request): RedirectResponse
    {
        $peserta = $request->user();
        $rekap = $this->buildReportData($peserta);
        $laporanAkhir = $peserta->laporanAkhir;
        abort_unless($rekap['siap'], 422, 'Laporan akhir baru dapat diajukan setelah Anda masuk kelompok dan memiliki laporan yang disetujui.');
        abort_if($laporanAkhir && $laporanAkhir->status !== 'Revisi', 409, 'Laporan akhir sedang ditinjau atau sudah disetujui.');

        $content = Pdf::loadView('mahasiswa.laporan-akhir-pdf', ['rekap' => $rekap])
            ->setPaper('a4', 'portrait')
            ->output();
        $disk = Storage::disk('local');
        $path = 'laporan-akhir/'.Str::uuid().'.pdf';
        abort_unless($disk->put($path, $content), 500, 'PDF laporan akhir gagal disimpan.');

        LaporanAkhir::updateOrCreate(
            ['mahasiswa_id' => $peserta->id],
            [
                'judul_laporan' => 'Laporan Akhir Magang/PKL - '.$peserta->name,
                'file_path' => $path,
                'status' => 'Menunggu Review',
                'catatan_revisi' => null,
                'dosen_user_id' => $rekap['identitas']['dosen_user_id'],
            ],
        );

        if ($laporanAkhir?->file_path && $laporanAkhir->file_path !== $path) {
            $disk->delete($laporanAkhir->file_path);
        }

        return redirect()->route('mahasiswa.laporan-akhir.index')->with('status', 'Rekap laporan akhir berhasil diajukan ke dosen.');
    }

    public function download(Request $request, LaporanAkhir $laporanAkhir): StreamedResponse
    {
        abort_unless($laporanAkhir->mahasiswa_id === $request->user()->id, 404);
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

    /**
     * @return array<string, mixed>
     */
    private function buildReportData(User $mahasiswa): array
    {
        $mahasiswa->load([
            'kelompok.kampus',
            'kelompok.departemen',
            'kelompok.dosen',
            'kelompok.pembimbing.departemen',
            'dosen',
            'mentor',
        ]);

        $kelompok = $mahasiswa->kelompok;
        $laporanHarian = $mahasiswa->laporans()
            ->orderBy('tanggal')
            ->orderBy('id')
            ->get();
        $laporanDisetujui = $laporanHarian
            ->where('status', 'Disetujui')
            ->values();
        $tanggalMulai = $kelompok?->tanggal_mulai ?? $laporanDisetujui->first()?->tanggal;
        $disk = Storage::disk('local');
        $minggu = [];
        $nomorLaporan = 1;

        foreach ($laporanDisetujui as $laporan) {
            $hariDariMulai = $tanggalMulai
                ? max(0, (int) $tanggalMulai->diffInDays($laporan->tanggal, false))
                : 0;
            $nomorMinggu = intdiv($hariDariMulai, 7) + 1;

            $minggu[$nomorMinggu][] = [
                'nomor' => $nomorLaporan++,
                'tanggal' => $laporan->tanggal->format('d/m/Y'),
                'kegiatan' => $laporan->kegiatan,
                'keterangan' => $laporan->keterangan ?: '-',
                'foto_url' => $laporan->foto && $disk->exists($laporan->foto)
                    ? route('media.laporan-photo', $laporan)
                    : null,
                'foto_data_uri' => $this->photoDataUri($disk, $laporan->foto),
            ];
        }

        ksort($minggu);
        $minggu = collect($minggu)
            ->map(fn (array $laporans, int|string $nomor): array => [
                'nomor' => (int) $nomor,
                'laporan' => $laporans,
            ])
            ->values()
            ->all();
        $kampus = $kelompok?->kampus;
        $dosen = $kelompok?->dosen;
        $pembimbing = $kelompok?->pembimbing;

        return [
            'siap' => $kelompok !== null && $laporanDisetujui->isNotEmpty(),
            'identitas' => [
                'nama' => $mahasiswa->name,
                'nim' => $mahasiswa->nim_nip ?: '-',
                'kampus' => $mahasiswa->kampus ?: ($kampus?->nama_universitas ?: ($mahasiswa->instansi ?: '-')),
                'fakultas' => $mahasiswa->fakultas ?: ($kampus?->fakultas ?: '-'),
                'prodi' => $mahasiswa->prodi ?: ($kampus?->prodi ?: '-'),
                'departemen' => $kelompok?->departemen?->nama_departemen ?: '-',
                'tanggal_mulai' => $kelompok?->tanggal_mulai?->format('d/m/Y') ?: '-',
                'tanggal_selesai' => $kelompok?->tanggal_selesai?->format('d/m/Y') ?: '-',
                'dosen' => [
                    'nama' => $dosen?->nama ?: ($mahasiswa->dosen?->name ?: '-'),
                    'nidn' => $dosen?->nidn ?: '-',
                ],
                'dosen_user_id' => $dosen?->user_id,
                'pembimbing' => [
                    'nama' => $pembimbing?->nama ?: ($mahasiswa->mentor?->name ?: '-'),
                    'nip' => $pembimbing?->nip ?: '-',
                ],
            ],
            'ringkasan' => [
                'total_hari' => $laporanHarian->pluck('tanggal')
                    ->map(fn ($tanggal): string => $tanggal->toDateString())
                    ->unique()
                    ->count(),
                'disetujui' => $laporanHarian->where('status', 'Disetujui')->count(),
                'revisi' => $laporanHarian->where('status', 'Revisi')->count(),
                'belum_diperiksa' => $laporanHarian->where('status', 'Belum Diperiksa')->count(),
                'belum_disetujui' => $laporanHarian->reject(fn (Laporan $laporan): bool => $laporan->status === 'Disetujui')->count(),
            ],
            'minggu' => $minggu,
        ];
    }

    private function photoDataUri(FilesystemAdapter $disk, ?string $path): ?string
    {
        if (! $path || ! $disk->exists($path)) {
            return null;
        }

        $mimeType = $disk->mimeType($path);
        if (! in_array($mimeType, ['image/jpeg', 'image/png', 'image/webp'], true)) {
            return null;
        }

        $contents = $disk->get($path);
        $dimensions = @getimagesizefromstring($contents);
        if (! $dimensions || $dimensions[0] * $dimensions[1] > 20000000) {
            return null;
        }

        $source = @imagecreatefromstring($contents);
        if (! $source) {
            return null;
        }

        $targetWidth = min(450, imagesx($source));
        $targetHeight = max(1, (int) round(imagesy($source) * ($targetWidth / imagesx($source))));
        $thumbnail = imagecreatetruecolor($targetWidth, $targetHeight);
        $white = imagecolorallocate($thumbnail, 255, 255, 255);
        imagefill($thumbnail, 0, 0, $white);
        imagecopyresampled($thumbnail, $source, 0, 0, 0, 0, $targetWidth, $targetHeight, imagesx($source), imagesy($source));

        ob_start();
        imagejpeg($thumbnail, null, 72);
        $thumbnailContents = ob_get_clean();
        imagedestroy($source);
        imagedestroy($thumbnail);

        return is_string($thumbnailContents) && $thumbnailContents !== ''
            ? 'data:image/jpeg;base64,'.base64_encode($thumbnailContents)
            : null;
    }
}
