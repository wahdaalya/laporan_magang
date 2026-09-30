<?php

namespace Tests\Feature;

use Barryvdh\DomPDF\Facade\Pdf;
use Tests\TestCase;

class LaporanAkhirPdfTemplateTest extends TestCase
{
    public function test_report_template_renders_a_pdf_from_the_recap_payload(): void
    {
        $rekap = [
            'identitas' => [
                'nama' => 'Peserta Uji',
                'nim' => 'NIM-1',
                'kampus' => 'Sekolah Uji',
                'fakultas' => '-',
                'prodi' => '-',
                'departemen' => 'Operasional',
                'tanggal_mulai' => '01/09/2026',
                'tanggal_selesai' => '30/09/2026',
                'dosen' => ['nama' => 'Dosen Uji', 'nidn' => '-'],
                'pembimbing' => ['nama' => 'Mentor Uji', 'nip' => '-'],
            ],
            'ringkasan' => [
                'total_hari' => 1,
                'disetujui' => 1,
                'revisi' => 0,
                'belum_diperiksa' => 0,
            ],
            'minggu' => [
                [
                    'nomor' => 1,
                    'laporan' => [
                        [
                            'nomor' => 1,
                            'tanggal' => '01/09/2026',
                            'kegiatan' => 'Kegiatan Uji',
                            'keterangan' => 'Keterangan Uji',
                            'foto_data_uri' => null,
                        ],
                    ],
                ],
            ],
        ];

        $pdf = Pdf::loadView('mahasiswa.laporan-akhir-pdf', ['rekap' => $rekap])
            ->setPaper('a4', 'portrait')
            ->output();

        $this->assertStringStartsWith('%PDF', $pdf);
    }
}
