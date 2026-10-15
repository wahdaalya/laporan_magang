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
        <tr><th>Instansi / Sekolah / Kampus</th><td>{{ $rekap['identitas']['kampus'] }}</td></tr>
        <tr><th>Fakultas</th><td>{{ $rekap['identitas']['fakultas'] }}</td></tr>
        <tr><th>Jurusan / Program Studi</th><td>{{ $rekap['identitas']['prodi'] }}</td></tr>
        <tr><th>Departemen Penempatan</th><td>{{ $rekap['identitas']['departemen'] }}</td></tr>
        <tr><th>Periode Magang</th><td>{{ $rekap['identitas']['tanggal_mulai'] }} - {{ $rekap['identitas']['tanggal_selesai'] }}</td></tr>
        <tr><th>Pembimbing Sekolah/Kampus</th><td>{{ $rekap['identitas']['dosen']['nama'] }} (NIP / NUPTK / ID Pengajar: {{ $rekap['identitas']['dosen']['nidn'] }})</td></tr>
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

    <h2 style="margin-top: 30px; page-break-before: auto; page-break-after: avoid; text-align: center;">LEMBAR PENGESAHAN LAPORAN (4 PIHAK)</h2>
    <p style="text-align: center; font-size: 10pt; color: #555; margin-bottom: 16px;">Telah diverifikasi dan disahkan oleh para pihak terkait secara digital pada sistem Intern Track.</p>

    <table class="signature" style="margin-top: 10px; width: 100%;">
        <tr>
            <td style="width: 50%; padding: 10px 14px; text-align: center; vertical-align: top;">
                <p style="margin: 0 0 6px; font-weight: bold;">Peserta Magang / PKL</p>
                <div class="signature-space" style="height: 2cm; padding-top: 10px;">
                    <div style="display: inline-block; border: 1px dashed #0284c7; background: #f0f9ff; color: #0369a1; padding: 4px 10px; font-size: 8pt; border-radius: 4px;">
                        <strong>&#10003; TERVERIFIKASI DIGITAL</strong><br>
                        <span>{{ $rekap['pengesahan']['mahasiswa']['tanggal'] }}</span>
                    </div>
                </div>
                <p style="margin: 4px 0 0;"><strong><u>{{ $rekap['pengesahan']['mahasiswa']['nama'] }}</u></strong></p>
                <p style="margin: 2px 0 0; font-size: 9.5pt; color: #444;">NIM: {{ $rekap['pengesahan']['mahasiswa']['nomor'] }}</p>
            </td>
            <td style="width: 50%; padding: 10px 14px; text-align: center; vertical-align: top;">
                <p style="margin: 0 0 6px; font-weight: bold;">Pembimbing Lapangan (Instansi)</p>
                <div class="signature-space" style="height: 2cm; padding-top: 10px;">
                    @if (str_contains($rekap['pengesahan']['pembimbing']['status'], 'Terverifikasi'))
                        <div style="display: inline-block; border: 1px solid #059669; background: #ecfdf5; color: #047857; padding: 4px 10px; font-size: 8pt; border-radius: 4px;">
                            <strong>&#10003; ACC / MENGETAHUI</strong><br>
                            <span>{{ $rekap['pengesahan']['pembimbing']['tanggal'] }}</span>
                        </div>
                    @else
                        <div style="height: 1.5cm;"></div>
                    @endif
                </div>
                <p style="margin: 4px 0 0;"><strong><u>{{ $rekap['pengesahan']['pembimbing']['nama'] }}</u></strong></p>
                <p style="margin: 2px 0 0; font-size: 9.5pt; color: #444;">NIP: {{ $rekap['pengesahan']['pembimbing']['nomor'] }}</p>
            </td>
        </tr>
        <tr>
            <td style="width: 50%; padding: 20px 14px 10px; text-align: center; vertical-align: top;">
                <p style="margin: 0 0 6px; font-weight: bold;">Dosen Pembimbing (Sekolah/Kampus)</p>
                <div class="signature-space" style="height: 2cm; padding-top: 10px;">
                    @if (str_contains($rekap['pengesahan']['dosen']['status'], 'Disetujui'))
                        <div style="display: inline-block; border: 1px solid #16a34a; background: #f0fdf4; color: #15803d; padding: 4px 10px; font-size: 8pt; border-radius: 4px;">
                            <strong>&#10003; DISETUJUI &amp; DISAHKAN</strong><br>
                            <span>{{ $rekap['pengesahan']['dosen']['tanggal'] }}</span>
                        </div>
                    @else
                        <div style="height: 1.5cm;"></div>
                    @endif
                </div>
                <p style="margin: 4px 0 0;"><strong><u>{{ $rekap['pengesahan']['dosen']['nama'] }}</u></strong></p>
                <p style="margin: 2px 0 0; font-size: 9.5pt; color: #444;">NIDN / NIP: {{ $rekap['pengesahan']['dosen']['nomor'] }}</p>
            </td>
            <td style="width: 50%; padding: 20px 14px 10px; text-align: center; vertical-align: top;">
                <p style="margin: 0 0 6px; font-weight: bold;">Pimpinan / Kepala Departemen</p>
                <div class="signature-space" style="height: 2cm; padding-top: 10px;">
                    @if (str_contains($rekap['pengesahan']['pimpinan']['status'], 'Tersahkan'))
                        <div style="display: inline-block; border: 1px solid #2563eb; background: #eff6ff; color: #1d4ed8; padding: 4px 10px; font-size: 8pt; border-radius: 4px;">
                            <strong>&#10003; PENGESAHAN RESMI</strong><br>
                            <span>{{ $rekap['pengesahan']['pimpinan']['tanggal'] }}</span>
                        </div>
                    @else
                        <div style="height: 1.5cm;"></div>
                    @endif
                </div>
                <p style="margin: 4px 0 0;"><strong><u>{{ $rekap['pengesahan']['pimpinan']['nama'] }}</u></strong></p>
                <p style="margin: 2px 0 0; font-size: 9.5pt; color: #444;">{{ $rekap['pengesahan']['pimpinan']['nomor'] }}</p>
            </td>
        </tr>
    </table>
</body>
</html>