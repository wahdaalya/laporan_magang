@php
    $namaBulan = [1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'];
    $logoFile = public_path('images/logo_aviasi.png');
    $logoSrc = file_exists($logoFile) ? 'data:image/png;base64,'.base64_encode(file_get_contents($logoFile)) : null;
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Lembar Presensi Magang / PKL - {{ $namaBulan[$bulan->month] }} {{ $bulan->year }}</title>
    <style>
        * { box-sizing: border-box; }
        @page { size: A4 portrait; margin: 8mm; }
        body { margin: 0; color: #111; font-family: "DejaVu Sans", Arial, sans-serif; font-size: 8px; }
        .header { position: relative; min-height: 19mm; padding: 0 38mm 2mm; text-align: center; }
        .logo { position: absolute; top: 0; right: 0; height: 45px; }
        h1 { margin: 0 0 1.5mm; font-size: 15px; font-weight: 700; }
        .subtitle { margin: 0; font-size: 9px; font-weight: 600; }
        .metadata { width: 100%; margin: 0 0 2mm; border-collapse: collapse; }
        .metadata td { padding: 0.5mm 1mm 0.5mm 0; vertical-align: top; }
        .metadata .label { width: 29mm; font-weight: 700; }
        .attendance { width: 100%; border-collapse: collapse; table-layout: fixed; }
        .attendance th, .attendance td { width: 14.285%; border: 1px solid #000; text-align: center; vertical-align: middle; }
        .attendance th { height: 7mm; padding: 1mm 0.5mm; background: #e5e7eb; font-size: 8px; font-weight: 700; }
        .attendance td { height: 20mm; padding: 1mm; font-size: 8px; }
        .attendance .day-number { margin-bottom: 2mm; font-size: 13px; font-weight: 700; }
        .attendance .day-status { font-size: 8px; }
        .attendance td.weekend, .attendance td.absent { background: #ef4444; color: #fff; }
        .attendance .present, .attendance .blank { background: #fff; }
        .summary { margin-top: 2mm; padding: 1.5mm; border: 1px solid #000; font-size: 9px; font-weight: 700; text-align: center; }
        .signature { width: 70mm; margin: 4mm 0 0 auto; text-align: center; font-size: 9px; }
        .signature p { margin: 0 0 1mm; }
        .signature .space { height: 14mm; }
        .signature .name { font-weight: 700; text-decoration: underline; }
    </style>
</head>
<body>
    <header class="header">
        @if ($logoSrc)
    <img src="{{ $logoSrc }}" alt="" class="logo">
@endif
        <h1>LEMBAR PRESENSI MAGANG / PKL</h1>
        <p class="subtitle">PT Angkasa Pura Aviasi - Bandara Kualanamu</p>
    </header>

    <table class="metadata">
        <tr><td class="label">Unit Penempatan</td><td>: {{ $unitPenempatan }}</td></tr>
        <tr><td class="label">Asal Univ / Sekolah</td><td>: {{ $asalInstansi }}</td></tr>
        <tr><td class="label">Bulan &amp; Tahun</td><td>: {{ $namaBulan[$bulan->month] }} {{ $bulan->year }}</td></tr>
    </table>

    <table class="attendance">
        <thead>
            <tr>
                @foreach ($weekDays as $index => $dayName)
                    <th>{{ $dayName }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach ($weeks as $week)
                <tr>
                    @foreach ($week as $day)
                        @if (! $day['tanggal'])
                            <td class="blank"></td>
                        @else
                            <td class="{{ $day['is_weekend'] ? 'weekend' : ($day['status'] === 'Alpa' ? 'absent' : 'present') }}">
                                <div class="day-number">{{ $day['tanggal']->day }}</div>
                                <div class="day-status">{{ $day['status'] }}</div>
                            </td>
                        @endif
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="summary">
        Total Kehadiran: {{ $totalHadir }} Hari | Alpa: {{ $totalAlpa }} Hari | Izin/Sakit: {{ $totalIzinSakit }} Hari
    </div>

    <footer class="signature">
        <p>Deli Serdang, {{ $tanggalCetak->day }} {{ $namaBulan[$tanggalCetak->month] }} {{ $tanggalCetak->year }}</p>
        <p>Mengetahui,</p>
        <p>{{ $jabatanPembimbing }}</p>
        <div class="space"></div>
        <p class="name">{{ $namaPembimbing }}</p>
        @if ($nomorPembimbing)
            <p>ID Pembimbing. {{ $nomorPembimbing }}</p>
        @endif
    </footer>
</body>
</html>