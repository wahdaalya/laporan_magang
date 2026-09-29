<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AbsensiController extends Controller
{
    public function index(Request $request): View
    {
        return view('mahasiswa.absensi', [
            'bulan' => (int) $request->input('bulan', now()->month),
            'tahun' => (int) $request->input('tahun', now()->year),
        ]);
    }

    public function cetak(Request $request): Response
    {
        $request->validate([
            'bulan' => ['required', 'integer', 'min:1', 'max:12'],
            'tahun' => ['required', 'integer', 'min:2000', 'max:2100'],
        ]);

        $user = $request->user()->load([
            'kelompok.departemen',
            'kelompok.kampus',
            'kelompok.dosen',
            'kelompok.pembimbing',
        ]);
        $awal = Carbon::create((int) $request->integer('tahun'), (int) $request->integer('bulan'), 1)->startOfMonth();
        $akhir = $awal->copy()->endOfMonth();

        $laporans = Laporan::where('user_id', $user->id)
            ->whereBetween('tanggal', [$awal->toDateString(), $akhir->toDateString()])
            ->get()
            ->keyBy(fn ($l) => $l->tanggal->format('Y-m-d'));

        $weeks = [];
        $week = [];
        $totalHadir = 0;
        $totalAlpa = 0;
        $totalIzinSakit = 0;
        $calendarStart = $awal->copy()->startOfWeek(Carbon::MONDAY);
        $calendarEnd = $akhir->copy()->endOfWeek(Carbon::SUNDAY);

        for ($cursor = $calendarStart->copy(); $cursor->lte($calendarEnd); $cursor->addDay()) {
            $inMonth = $cursor->month === $awal->month && $cursor->year === $awal->year;

            if (! $inMonth) {
                $week[] = ['tanggal' => null, 'status' => null, 'is_weekend' => false];
            } else {
                $laporan = $laporans->get($cursor->format('Y-m-d'));
                $isWeekend = $cursor->isWeekend();
                $hadir = ! $isWeekend && $laporan !== null;

                $week[] = [
                    'tanggal' => $cursor->copy(),
                    'status' => $isWeekend ? 'Libur' : ($hadir ? 'Hadir' : 'Alpa'),
                    'is_weekend' => $isWeekend,
                ];

                if ($hadir) {
                    $totalHadir++;
                } elseif (! $isWeekend) {
                    $totalAlpa++;
                }
            }

            if (count($week) === 7) {
                $weeks[] = $week;
                $week = [];
            }
        }

        $kelompok = $user->kelompok;
        $pembimbingLapangan = $kelompok?->pembimbing;
        $dosenPembimbing = $kelompok?->dosen;

        $namaFile = 'lembar-presensi-'.$awal->format('Y-m').'.pdf';
        $kontenPdf = Pdf::loadView('mahasiswa.absensi-pdf', [
            'user' => $user,
            'bulan' => $awal,
            'weeks' => $weeks,
            'weekDays' => ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'],
            'unitPenempatan' => $kelompok?->departemen?->nama_departemen ?? $user->unit ?? '-',
            'asalInstansi' => $user->kampus ?? $kelompok?->kampus?->nama_universitas ?? $user->instansi ?? '-',
            'jabatanPembimbing' => $pembimbingLapangan
                ? 'Pembimbing Lapangan'
                : ($dosenPembimbing ? 'Dosen / Guru Pembimbing' : 'Pembimbing'),
            'namaPembimbing' => $pembimbingLapangan?->nama ?? $dosenPembimbing?->nama ?? '-',
            'nomorPembimbing' => $pembimbingLapangan?->nip ?? $dosenPembimbing?->nidn,
            'tanggalCetak' => today(),
            'totalHadir' => $totalHadir,
            'totalAlpa' => $totalAlpa,
            'totalIzinSakit' => $totalIzinSakit,
            'logoPath' => public_path('images/logo-bintang-kualanamu.png'),
        ])
            ->setPaper('a3', 'landscape')
            ->output();

        return response($kontenPdf, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="'.$namaFile.'"',
            'Content-Length' => (string) strlen($kontenPdf),
            'X-Content-Type-Options' => 'nosniff',
            'Cache-Control' => 'private, no-store, max-age=0',
        ]);
    }
}
