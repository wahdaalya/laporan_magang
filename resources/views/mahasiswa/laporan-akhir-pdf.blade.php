<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Laporan Akhir Magang / PKL</title>
    <style>
        @page { size: A4 portrait; margin: 2cm 1.8cm; }
        body { color: #111; font-family: "Times New Roman", "DejaVu Serif", serif; font-size: 12pt; line-height: 1.35; }
        h1 { margin: 0 0 18px; text-align: center; font-size: 16pt; }
        h2 { margin: 20px 0 8px; font-size: 13pt; }
        p { margin: 4px 0; }
        table { width: 100%; border-collapse: collapse; }
        .identity th, .identity td, .summary th, .summary td, .daily th, .daily td { border: 1px solid #444; padding: 6px; vertical-align: top; }
        .identity th { width: 31%; text-align: left; }
        .summary th, .daily th { background: #eee; text-align: left; }
        .week { page-break-inside: avoid; }
        .week-title { margin: 16px 0 6px; font-weight: bold; }
        .daily { font-size: 10pt; }
        .photo { max-width: 6cm; max-height: 5cm; }
        .signature { margin-top: 28px; page-break-inside: avoid; }
        .signature td { width: 50%; padding: 0 18px; text-align: center; vertical-align: top; }
        .signature-space { height: 2.5cm; }
    </style>
</head>
<body>
    <h1>Laporan Akhir Magang / PKL</h1>

    <h2>Identitas Peserta</h2>
    <table class="identity">
        <tr><th>Nama</th><td>{{ $rekap['identitas']['nama'] }}</td></tr>
        <tr><th>NIM / NISN</th><td>{{ $rekap['identitas']['nim'] }}</td></tr>
        <tr><th>Kampus / Sekolah</th><td>{{ $rekap['identitas']['kampus'] }}</td></tr>
        <tr><th>Fakultas</th><td>{{ $rekap['identitas']['fakultas'] }}</td></tr>
        <tr><th>Prodi / Jurusan</th><td>{{ $rekap['identitas']['prodi'] }}</td></tr>
        <tr><th>Departemen Penempatan</th><td>{{ $rekap['identitas']['departemen'] }}</td></tr>
        <tr><th>Periode Magang</th><td>{{ $rekap['identitas']['tanggal_mulai'] }} - {{ $rekap['identitas']['tanggal_selesai'] }}</td></tr>
        <tr><th>Dosen / Guru Pembimbing</th><td>{{ $rekap['identitas']['dosen']['nama'] }} (NIDN: {{ $rekap['identitas']['dosen']['nidn'] }})</td></tr>
        <tr><th>Pembimbing Lapangan</th><td>{{ $rekap['identitas']['pembimbing']['nama'] }} (NIP: {{ $rekap['identitas']['pembimbing']['nip'] }})</td></tr>
    </table>

    <h2>Ringkasan Laporan</h2>
    <table class="summary">
        <tr><th>Total Hari Laporan</th><th>Disetujui</th><th>Revisi</th><th>Belum Diperiksa</th></tr>
        <tr><td>{{ $rekap['ringkasan']['total_hari'] }}</td><td>{{ $rekap['ringkasan']['disetujui'] }}</td><td>{{ $rekap['ringkasan']['revisi'] }}</td><td>{{ $rekap['ringkasan']['belum_diperiksa'] }}</td></tr>
    </table>

    <h2>Rekap Kegiatan Harian</h2>
    @foreach ($rekap['minggu'] as $minggu)
        <div class="week">
            <p class="week-title">Minggu {{ $minggu['nomor'] }}</p>
            <table class="daily">
                <thead><tr><th>No</th><th>Tanggal</th><th>Kegiatan</th><th>Keterangan</th><th>Foto Dokumentasi</th></tr></thead>
                <tbody>
                    @foreach ($minggu['laporan'] as $laporan)
                        <tr>
                            <td>{{ $laporan['nomor'] }}</td>
                            <td>{{ $laporan['tanggal'] }}</td>
                            <td>{{ $laporan['kegiatan'] }}</td>
                            <td>{{ $laporan['keterangan'] }}</td>
                            <td>@if ($laporan['foto_data_uri'])<img class="photo" src="{{ $laporan['foto_data_uri'] }}" alt="Foto kegiatan">@else - @endif</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endforeach

    <table class="signature">
        <tr>
            <td>
                <p>Dosen / Guru Pembimbing</p>
                <div class="signature-space"></div>
                <p><strong><u>{{ $rekap['identitas']['dosen']['nama'] }}</u></strong></p>
            </td>
            <td>
                <p>Pembimbing Lapangan</p>
                <div class="signature-space"></div>
                <p><strong><u>{{ $rekap['identitas']['pembimbing']['nama'] }}</u></strong></p>
            </td>
        </tr>
    </table>
</body>
</html>