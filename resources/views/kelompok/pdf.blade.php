@php
    $bulan = [1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'];
    $tgl = fn ($d) => $d->day.' '.$bulan[$d->month].' '.$d->year;
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Data Kelompok {{ $kelompok->nama_kelompok }}</title>
    <style>
        @page { size: A4 portrait; margin: 16mm 14mm; }
        body { margin: 0; color: #1f2937; font-family: "DejaVu Sans", Arial, sans-serif; font-size: 10px; line-height: 1.45; }
        .kop { width: 100%; border-bottom: 2px solid #1f4e8c; padding-bottom: 6px; margin-bottom: 14px; }
        .kop td { vertical-align: middle; }
        .kop .logo { width: 56px; }
        .kop img { width: 48px; height: auto; }
        .kop .instansi { font-size: 13px; font-weight: bold; color: #1b2e4f; }
        .kop .alamat { font-size: 9px; color: #5f7390; }
        h1 { margin: 0 0 2px; font-size: 15px; text-align: center; color: #1b2e4f; letter-spacing: .5px; }
        .sub { margin: 0 0 14px; text-align: center; color: #5f7390; font-size: 10px; }
        table.info { width: 100%; border-collapse: collapse; margin-bottom: 14px; }
        table.info td { padding: 4px 6px; border-bottom: 1px solid #e5e7eb; vertical-align: top; }
        table.info td.label { width: 32%; color: #5f7390; font-weight: bold; }
        h2 { margin: 0 0 6px; font-size: 11px; color: #1b2e4f; text-transform: uppercase; letter-spacing: .5px; }
        table.anggota { width: 100%; border-collapse: collapse; }
        table.anggota th { background: #e9eff7; color: #1b2e4f; border: 1px solid #b9cce4; padding: 5px 6px; font-size: 9px; text-align: center; white-space: nowrap; }
        table.anggota td { border: 1px solid #cbd5e1; padding: 5px 6px; vertical-align: top; }
        table.anggota td.c { text-align: center; }
        table.anggota tr { page-break-inside: avoid; }
        .kosong { padding: 10px; text-align: center; color: #6b7280; border: 1px dashed #cbd5e1; }
        .ttd { width: 100%; margin-top: 28px; page-break-inside: avoid; }
        .ttd td { width: 50%; text-align: center; vertical-align: top; }
        .ttd .space { height: 52px; }
        .ttd .nama { font-weight: bold; text-decoration: underline; }
        .foot { margin-top: 18px; padding-top: 6px; border-top: 1px solid #e5e7eb; font-size: 8px; color: #6b7280; }
    </style>
</head>
<body>
    <table class="kop">
        <tr>
            @if (!empty($logoDataUri))
                <td class="logo"><img src="{{ $logoDataUri }}" alt="Logo"></td>
            @elseif (!empty($logoPath))
                <td class="logo"><img src="{{ $logoPath }}" alt="Logo"></td>
            @endif
            <td>
                <div class="instansi">PT Angkasa Pura Aviasi</div>
                <div class="alamat">Bandara Internasional Kualanamu</div>
            </td>
        </tr>
    </table>

    <h1>DATA KELOMPOK MAGANG / PKL</h1>
    <p class="sub">{{ $kelompok->nama_kelompok }}</p>

    <table class="info">
        <tr><td class="label">Kode Kelompok</td><td>{{ $kelompok->nama_kelompok }}</td></tr>
        <tr><td class="label">Periode Magang</td><td>{{ $tgl($kelompok->tanggal_mulai) }} s.d. {{ $tgl($kelompok->tanggal_selesai) }}</td></tr>
        <tr><td class="label">Instansi Pendidikan</td><td>{{ $kelompok->kampus?->nama_universitas ?? '-' }}</td></tr>
        <tr><td class="label">Departemen</td><td>{{ $kelompok->departemen?->nama_departemen ?? '-' }}</td></tr>
        <tr><td class="label">Pembimbing Sekolah/Kampus</td><td>{{ $kelompok->dosen?->nama ?? '-' }}</td></tr>
        <tr><td class="label">Pembimbing Lapangan</td><td>{{ $kelompok->pembimbing?->nama ?? '-' }}</td></tr>
        <tr><td class="label">Jumlah Anggota</td><td>{{ $kelompok->mahasiswas->count() }} orang</td></tr>
    </table>

    <h2>Daftar Anggota</h2>
    @if ($kelompok->mahasiswas->isEmpty())
        <div class="kosong">Belum ada anggota di kelompok ini.</div>
    @else
        <table class="anggota">
            <thead>
                <tr>
                    <th style="width: 6%;">No</th>
                    <th>Nama</th>
                    <th style="width: 18%;">NIM / NIP</th>
                    <th>Fakultas / Prodi</th>
                    <th style="width: 18%;">HP / WA</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($kelompok->mahasiswas as $anggota)
                    <tr>
                        <td class="c">{{ $loop->iteration }}</td>
                        <td>{{ $anggota->name }}</td>
                        <td class="c">{{ $anggota->nim_nip ?? '-' }}</td>
                        <td>{{ collect([$anggota->fakultas, $anggota->prodi])->filter()->implode(' / ') ?: '-' }}</td>
                        <td class="c">{{ $anggota->hp_wa ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <table class="ttd">
        <tr>
            <td>
                <div>Pembimbing Lapangan</div>
                <div class="space"></div>
                <div class="nama">{{ $kelompok->pembimbing?->nama ?? '........................' }}</div>
            </td>
            <td>
                <div>Pembimbing Sekolah/Kampus</div>
                <div class="space"></div>
                <div class="nama">{{ $kelompok->dosen?->nama ?? '........................' }}</div>
            </td>
        </tr>
    </table>

    <div class="foot">Dicetak pada {{ $tgl($dicetakPada) }}, {{ $dicetakPada->format('H:i') }} WIB</div>
</body>
</html>