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

    <table>
        <tr>
            <th>Nama Mahasiswa</th>
            <td>{{ $mahasiswa->name }}</td>
        </tr>
        <tr>
            <th>NIM</th>
            <td>{{ $mahasiswa->nim_nip ?? '-' }}</td>
        </tr>
        <tr>
            <th>Jumlah Laporan</th>
            <td>{{ $laporans->count() }} laporan</td>
        </tr>
        <tr>
            <th>Tanggal Cetak</th>
            <td>{{ $tanggalCetak }}</td>
        </tr>
    </table>

    @foreach ($laporans as $index => $laporan)
        <div class="laporan">
<h3>Laporan {{ $index + 1 }}</h3>

<table>
            <tr>
                <th>Tanggal</th>
                <td>{{ $formatTanggal($laporan->tanggal ?? $laporan->created_at) }}</td>
            </tr>
            <tr>
                <th>Kegiatan</th>
                <td>{!! $teks($laporan->kegiatan ?: '-') !!}</td>
            </tr>
            <tr>
                <th>Deskripsi</th>
                <td>{!! $teks($laporan->keterangan ?: '-') !!}</td>
            </tr>
            <tr>
                <th>Status</th>
                <td>{{ $laporan->status ?? '-' }}</td>
            </tr>
            <tr>
                <th>Catatan Pembimbing</th>
                <td>{!! $teks($laporan->catatan_pembimbing ?: '-') !!}</td>
            </tr>
            <tr>
                <th>Foto Dokumentasi</th>
                <td>
                    @if (!empty($laporan->foto_data_uri))
                        <img class="foto" src="{{ $laporan->foto_data_uri }}" alt="Foto dokumentasi">
                    @else
                        Tidak ada foto.
                    @endif
                </td>
            </tr>
        </table>
        </div>
    @endforeach

</body>
</html>