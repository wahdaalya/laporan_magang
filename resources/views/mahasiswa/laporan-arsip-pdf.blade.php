@php
    $bulan = [1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    $formatTanggal = function ($tanggal) use ($bulan) {
        $tanggal = $tanggal ? \Illuminate\Support\Carbon::parse($tanggal) : null;

        return $tanggal ? $tanggal->day.' '.$bulan[$tanggal->month].' '.$tanggal->year : '-';
    };
    // DomPDF tidak bisa memotong kata yang sangat panjang, jadi kita sisipkan spasi tiap 40 karakter.
    $teks = fn ($nilai) => nl2br(e(preg_replace('/(\S{40})(?=\S)/u', '$1 ', trim((string) $nilai))));
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Arsip Laporan Magang</title>
    <style>
        @page { margin: 30px 30px 45px 30px; }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            line-height: 1.4;
            color: #263238;
        }

        h1 {
            text-align: center;
            font-size: 16px;
            margin: 0 0 16px;
        }

        h3 {
    margin: 0 0 6px;
    font-size: 12px;
    color: #1d4ed8;
    page-break-after: avoid;
}

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        th, td {
            border: 1px solid #d1d5db;
            padding: 8px 10px;
            text-align: left;
            vertical-align: top;
        }

        th {
            width: 28%;
            background-color: #eaf1fb;
        }

        .laporan { page-break-inside: avoid; }

        .foto {
            max-width: 230px;
            max-height: 170px;
        }

        .footer {
            position: fixed;
            bottom: -28px;
            left: 0;
            right: 0;
            font-size: 8.5px;
            color: #6b7280;
        }

        .pagenum:before { content: counter(page); }
    </style>
</head>
<body>

    <div class="footer">
        Dokumen ini dibuat melalui sistem Intern Track &middot; {{ $tanggalCetak }} &middot; Halaman <span class="pagenum"></span>
    </div>

    <h1>ARSIP LAPORAN KEGIATAN MAGANG / PKL</h1>

    @php
        $kelompok = $mahasiswa->kelompok;
        $dosen = $kelompok?->dosen;
        $pembimbing = $kelompok?->pembimbing;
        $departemen = $kelompok?->departemen;
        $kampus = $mahasiswa->kampus ?: ($kelompok?->kampus?->nama_universitas ?: '-');
        $prodi = $mahasiswa->prodi ?: ($kelompok?->kampus?->prodi ?: '-');
        $laporanAkhir = $mahasiswa->laporanAkhir;
    @endphp

    <table>
        <tr>
            <th>Nama Mahasiswa</th>
            <td><strong>{{ $mahasiswa->name }}</strong></td>
        </tr>
        <tr>
            <th>NIM / NISN</th>
            <td>{{ $mahasiswa->nim_nip ?? '-' }}</td>
        </tr>
        <tr>
            <th>Instansi / Kampus</th>
            <td>{{ $kampus }}</td>
        </tr>
        <tr>
            <th>Program Studi</th>
            <td>{{ $prodi }}</td>
        </tr>
        <tr>
            <th>Departemen Penempatan</th>
            <td>{{ $departemen?->nama_departemen ?: '-' }}</td>
        </tr>
        <tr>
            <th>Periode Magang</th>
            <td>
                {{ $kelompok?->tanggal_mulai?->format('d/m/Y') ?: '-' }} s.d. {{ $kelompok?->tanggal_selesai?->format('d/m/Y') ?: '-' }}
            </td>
        </tr>
        <tr>
            <th>Jumlah Laporan</th>
            <td>{{ $laporans->count() }} kegiatan tercatat</td>
        </tr>
        <tr>
            <th>Tanggal Cetak Dokumen</th>
            <td>{{ $tanggalCetak }}</td>
        </tr>
    </table>

    <div style="margin-top: 24px;"></div>

    @foreach ($laporans as $index => $laporan)
        <div class="laporan" style="margin-bottom: 14px;">
            <h3>Laporan {{ $index + 1 }} &mdash; {{ $formatTanggal($laporan->tanggal ?? $laporan->created_at) }}</h3>

            <table>
                <tr>
                    <th>Tanggal Pelaksanaan</th>
                    <td>{{ $formatTanggal($laporan->tanggal ?? $laporan->created_at) }}</td>
                </tr>
                <tr>
                    <th>Kegiatan Utama</th>
                    <td><strong>{!! $teks($laporan->kegiatan ?: '-') !!}</strong></td>
                </tr>
                <tr>
                    <th>Deskripsi Rinci</th>
                    <td>{!! $teks($laporan->keterangan ?: '-') !!}</td>
                </tr>
                <tr>
                    <th>Status Pemeriksaan</th>
                    <td>
                        <strong>{{ $laporan->status ?? '-' }}</strong>
                        @if ($laporan->status_mengetahui)
                            &middot; <span style="color: #059669;">[ACC Mengetahui: {{ $laporan->mengetahui_oleh }}]</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>Catatan Pembimbing</th>
                    <td>{!! $teks($laporan->catatan_pembimbing ?: '-') !!}</td>
                </tr>
                <tr>
                    <th>Lampiran Dokumentasi</th>
                    <td>
                        @if (!empty($laporan->foto_data_uri))
                            <img class="foto" src="{{ $laporan->foto_data_uri }}" alt="Foto dokumentasi">
                        @else
                            <span style="color: #888;">Tidak ada foto/lampiran dokumentasi.</span>
                        @endif
                    </td>
                </tr>
            </table>
        </div>
    @endforeach

    {{-- Lembar Pengesahan & Validasi 4 Pihak --}}
    <div style="page-break-inside: avoid; margin-top: 30px; border-top: 2px solid #3b82f6; padding-top: 16px;">
        <h3 style="text-align: center; font-size: 13px; color: #1e3a8a; margin-bottom: 4px;">LEMBAR PENGESAHAN LAPORAN (4 PIHAK)</h3>
        <p style="text-align: center; font-size: 8.5px; color: #666; margin-bottom: 16px;">Dokumen arsip ini telah divalidasi dan disahkan secara digital oleh pihak terkait melalui sistem Intern Track.</p>

        <table style="width: 100%; border: none;">
            <tr>
                <td style="width: 50%; border: 1px solid #d1d5db; padding: 10px; text-align: center; vertical-align: top;">
                    <p style="margin: 0 0 6px; font-weight: bold; color: #1e40af;">Peserta Magang / PKL</p>
                    <div style="height: 50px; padding-top: 8px;">
                        <span style="border: 1px dashed #2563eb; background: #eff6ff; color: #1e40af; padding: 3px 8px; font-size: 7.5px; border-radius: 3px;">
                            &#10003; TERVERIFIKASI SISTEM
                        </span>
                    </div>
                    <p style="margin: 4px 0 0;"><strong><u>{{ $mahasiswa->name }}</u></strong></p>
                    <p style="margin: 2px 0 0; font-size: 8.5px; color: #555;">NIM: {{ $mahasiswa->nim_nip ?: '-' }}</p>
                </td>
                <td style="width: 50%; border: 1px solid #d1d5db; padding: 10px; text-align: center; vertical-align: top;">
                    <p style="margin: 0 0 6px; font-weight: bold; color: #047857;">Pembimbing Lapangan</p>
                    <div style="height: 50px; padding-top: 8px;">
                        @if ($laporanAkhir?->status_mengetahui || $laporans->where('status', 'Disetujui')->isNotEmpty())
                            <span style="border: 1px solid #059669; background: #ecfdf5; color: #065f46; padding: 3px 8px; font-size: 7.5px; border-radius: 3px;">
                                &#10003; ACC / MENGETAHUI
                            </span>
                        @endif
                    </div>
                    <p style="margin: 4px 0 0;"><strong><u>{{ $pembimbing?->nama ?: '-' }}</u></strong></p>
                    <p style="margin: 2px 0 0; font-size: 8.5px; color: #555;">NIP: {{ $pembimbing?->nip ?: '-' }}</p>
                </td>
            </tr>
            <tr>
                <td style="width: 50%; border: 1px solid #d1d5db; padding: 10px; text-align: center; vertical-align: top;">
                    <p style="margin: 0 0 6px; font-weight: bold; color: #4338ca;">Dosen Pembimbing</p>
                    <div style="height: 50px; padding-top: 8px;">
                        @if ($laporanAkhir?->status === 'Disetujui' || $laporanAkhir?->status_setuju)
                            <span style="border: 1px solid #4f46e5; background: #eef2ff; color: #3730a3; padding: 3px 8px; font-size: 7.5px; border-radius: 3px;">
                                &#10003; DISETUJUI DIGITAL
                            </span>
                        @endif
                    </div>
                    <p style="margin: 4px 0 0;"><strong><u>{{ $dosen?->nama ?: '-' }}</u></strong></p>
                    <p style="margin: 2px 0 0; font-size: 8.5px; color: #555;">NIDN / NIP: {{ $dosen?->nidn ?: '-' }}</p>
                </td>
                <td style="width: 50%; border: 1px solid #d1d5db; padding: 10px; text-align: center; vertical-align: top;">
                    <p style="margin: 0 0 6px; font-weight: bold; color: #b45309;">Pimpinan / Kepala Departemen</p>
                    <div style="height: 50px; padding-top: 8px;">
                        @if ($laporanAkhir?->status === 'Disetujui' || $laporanAkhir?->status_setuju)
                            <span style="border: 1px solid #d97706; background: #fffbeb; color: #92400e; padding: 3px 8px; font-size: 7.5px; border-radius: 3px;">
                                &#10003; PENGESAHAN RESMI
                            </span>
                        @endif
                    </div>
                    <p style="margin: 4px 0 0;"><strong><u>{{ $departemen?->nama_pimpinan ?: 'Pimpinan Instansi / Mitra' }}</u></strong></p>
                    <p style="margin: 2px 0 0; font-size: 8.5px; color: #555;">{{ $departemen?->nama_departemen ?: 'Instansi / Perusahaan' }}</p>
                </td>
            </tr>
        </table>
    </div>

</body>
</html>