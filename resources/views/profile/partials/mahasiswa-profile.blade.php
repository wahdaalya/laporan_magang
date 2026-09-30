@php
    $kelompok = $user->kelompok;
    $dosenPembimbing = $kelompok?->dosen?->nama ?: ($user->dosen?->name ?: '-');
    $dosenKampus = $kelompok?->dosen?->kampus ?: ($user->dosen?->kampus ?: '-');
    $dosenTelepon = $kelompok?->dosen?->hp_wa ?: ($user->dosen?->hp_wa ?: '');
    $dosenEmail = $kelompok?->dosen?->email ?: ($user->dosen?->email ?: '');
    $dosenWhatsappUrl = \App\Support\ContactLink::whatsappUrl($dosenTelepon);
    $dosenNidn = $kelompok?->dosen?->nidn ?: '-';

    $mentorNama = $kelompok?->pembimbing?->nama ?: ($user->mentor?->name ?: '-');
    $mentorDepartemen = $kelompok?->pembimbing?->departemen?->nama_departemen ?: ($kelompok?->departemen?->nama_departemen ?: ($user->unit ?: '-'));
    $mentorTelepon = $kelompok?->pembimbing?->hp_wa ?: ($user->mentor?->hp_wa ?: '');
    $mentorEmail = $kelompok?->pembimbing?->email ?: ($user->mentor?->email ?: '');
    $mentorWhatsappUrl = \App\Support\ContactLink::whatsappUrl($mentorTelepon);
    $mentorNip = $kelompok?->pembimbing?->nip ?: '-';
    $mentorPimpinan = $kelompok?->pembimbing?->departemen?->nama_pimpinan ?: '-';

    $laporanAkhir = $user->laporanAkhir;
    $penilaianTerbaru = $penilaian ?? null;

    $badgeStatusMagang = match ($statusMagang ?? 'Belum Terjadwal') {
        'Aktif' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
        'Selesai' => 'bg-blue-50 text-blue-700 border-blue-200',
        'Akan Dimulai' => 'bg-amber-50 text-amber-700 border-amber-200',
        default => 'bg-gray-100 text-gray-700 border-gray-200',
    };
@endphp

<div class="space-y-6">
    {{-- Grid 2 Kolom: Info Akademik & Penempatan Perusahaan --}}
    <div class="grid gap-6 lg:grid-cols-2">
        {{-- Card 1: Identitas Akademik & Mahasiswa --}}
        <section class="overflow-hidden rounded-2xl border border-gray-200/80 bg-white shadow-sm transition hover:shadow-md">
            <header class="flex items-center justify-between border-b border-gray-100 bg-gradient-to-r from-blue-50/60 to-slate-50 px-5 py-4 sm:px-6">
                <div class="flex items-center gap-3">
                    <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-blue-600 text-white shadow-sm">
                        <i class="fas fa-graduation-cap text-sm"></i>
                    </span>
                    <div>
                        <h3 class="font-bold text-gray-900">Identitas Akademik</h3>
                        <p class="text-xs text-gray-500">Data perguruan tinggi / sekolah asal peserta</p>
                    </div>
                </div>
                <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold {{ $badgeStatusMagang }}">
                    {{ $statusMagang ?? 'Belum Terjadwal' }}
                </span>
            </header>

            <div class="p-5 sm:p-6">
                <dl class="grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-2">
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wider text-gray-400">NIM / NISN</dt>
                        <dd class="mt-1 font-mono text-sm font-bold text-gray-900">{{ $user->nim_nip ?: '-' }}</dd>
                    </div>

                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wider text-gray-400">Program Studi / Jurusan</dt>
                        <dd class="mt-1 text-sm font-semibold text-gray-900">{{ $user->prodi ?: ($kelompok?->kampus?->prodi ?: '-') }}</dd>
                    </div>

                    <div class="sm:col-span-2">
                        <dt class="text-xs font-semibold uppercase tracking-wider text-gray-400">Asal Kampus / Sekolah</dt>
                        <dd class="mt-1 text-sm font-medium text-gray-900">
                            {{ $user->kampus ?: ($kelompok?->kampus?->nama_universitas ?: ($user->instansi ?: '-')) }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wider text-gray-400">Fakultas</dt>
                        <dd class="mt-1 text-sm text-gray-700">{{ $user->fakultas ?: ($kelompok?->kampus?->fakultas ?: '-') }}</dd>
                    </div>

                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wider text-gray-400">No. Kontak WhatsApp</dt>
                        <dd class="mt-1 text-sm font-medium text-gray-900">
                            @php($mahasiswaWhatsappUrl = \App\Support\ContactLink::whatsappUrl($user->hp_wa))
                            @if ($mahasiswaWhatsappUrl)
                                <a href="{{ $mahasiswaWhatsappUrl }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1.5 text-emerald-600 hover:text-emerald-700 hover:underline">
                                    <i class="fab fa-whatsapp"></i> {{ $user->hp_wa }}
                                </a>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </dd>
                    </div>
                </dl>
            </div>
        </section>

        {{-- Card 2: Penempatan Magang di PT Angkasa Pura Aviasi --}}
        <section class="overflow-hidden rounded-2xl border border-gray-200/80 bg-white shadow-sm transition hover:shadow-md">
            <header class="flex items-center justify-between border-b border-gray-100 bg-gradient-to-r from-slate-50 to-indigo-50/50 px-5 py-4 sm:px-6">
                <div class="flex items-center gap-3">
                    <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-slate-800 text-white shadow-sm">
                        <i class="fas fa-plane-departure text-sm"></i>
                    </span>
                    <div>
                        <h3 class="font-bold text-gray-900">Penempatan Magang / PKL</h3>
                        <p class="text-xs text-gray-500">PT Angkasa Pura Aviasi · Bandara Kualanamu</p>
                    </div>
                </div>
                <span class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-medium text-slate-700">
                    {{ $kelompok?->nama_kelompok ?? 'Belum ada kelompok' }}
                </span>
            </header>

            <div class="p-5 sm:p-6 space-y-4">
                <dl class="grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-2">
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wider text-gray-400">Instansi Penyelenggara</dt>
                        <dd class="mt-1 text-sm font-semibold text-gray-900">PT Angkasa Pura Aviasi</dd>
                    </div>

                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wider text-gray-400">Lokasi Penugasan</dt>
                        <dd class="mt-1 text-sm font-medium text-gray-800">Bandar Udara Internasional Kualanamu (KNO)</dd>
                    </div>

                    <div class="sm:col-span-2">
                        <dt class="text-xs font-semibold uppercase tracking-wider text-gray-400">Departemen / Divisi Unit Kerja</dt>
                        <dd class="mt-1 inline-flex items-center gap-2 rounded-lg bg-blue-50/70 px-3 py-1.5 text-sm font-semibold text-blue-900 border border-blue-100">
                            <i class="fas fa-building text-blue-600"></i>
                            {{ $kelompok?->departemen?->nama_departemen ?: '-' }}
                        </dd>
                    </div>

                    <div class="sm:col-span-2">
                        <dt class="text-xs font-semibold uppercase tracking-wider text-gray-400">Periode Magang</dt>
                        <dd class="mt-1 text-sm font-medium text-gray-800">
                            @if ($kelompok && $kelompok->tanggal_mulai && $kelompok->tanggal_selesai)
                                <span class="font-semibold text-gray-900">{{ $kelompok->tanggal_mulai->translatedFormat('d M Y') }}</span>
                                <span class="text-gray-400 mx-1.5">s/d</span>
                                <span class="font-semibold text-gray-900">{{ $kelompok->tanggal_selesai->translatedFormat('d M Y') }}</span>
                                <span class="ml-2 text-xs text-gray-500">({{ $durasiTotal ?? 0 }} hari kalender)</span>
                            @else
                                <span class="text-gray-400">Jadwal periode belum ditentukan</span>
                            @endif
                        </dd>
                    </div>
                </dl>

                {{-- Progress Bar Durasi Magang --}}
                @if ($kelompok && $kelompok->tanggal_mulai && $kelompok->tanggal_selesai)
                    <div class="rounded-xl border border-gray-100 bg-gray-50/70 p-3.5">
                        <div class="flex items-center justify-between text-xs">
                            <span class="font-medium text-gray-600">Progres Masa Magang</span>
                            <span class="font-bold text-blue-700">{{ $progressMagang ?? 0 }}% Selesai (Sisa {{ $sisaHari ?? 0 }} hari)</span>
                        </div>
                        <div class="mt-2 h-2.5 w-full overflow-hidden rounded-full bg-gray-200">
                            <div class="h-full rounded-full bg-gradient-to-r from-blue-600 to-indigo-600 transition-all duration-500" style="width: {{ min(max($progressMagang ?? 0, 0), 100) }}%"></div>
                        </div>
                    </div>
                @endif
            </div>
        </section>
    </div>

    {{-- Card 3: Pembimbing Akademik & Pembimbing Lapangan --}}
    <section class="overflow-hidden rounded-2xl border border-gray-200/80 bg-white shadow-sm transition hover:shadow-md">
        <header class="border-b border-gray-100 bg-gradient-to-r from-slate-50 to-blue-50/40 px-5 py-4 sm:px-6">
            <div class="flex items-center gap-3">
                <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-indigo-600 text-white shadow-sm">
                    <i class="fas fa-users-cog text-sm"></i>
                </span>
                <div>
                    <h3 class="font-bold text-gray-900">Pembimbing &amp; Mentor Pendamping</h3>
                    <p class="text-xs text-gray-500">Dosen dari institusi pendidikan dan pembimbing profesional di bandara</p>
                </div>
            </div>
        </header>

        <div class="grid gap-6 p-5 sm:p-6 md:grid-cols-2">
            {{-- Dosen Pembimbing --}}
            <div class="rounded-xl border border-blue-100 bg-blue-50/30 p-5">
                <div class="flex items-center gap-3">
                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-blue-100 text-blue-700">
                        <i class="fas fa-chalkboard-teacher text-lg"></i>
                    </div>
                    <div class="min-w-0">
                        <p class="text-xs font-semibold uppercase tracking-wider text-blue-700">Dosen / Guru Pembimbing</p>
                        <h4 class="truncate text-base font-bold text-gray-900">{{ $dosenPembimbing }}</h4>
                        <p class="truncate text-xs text-gray-500">{{ $dosenKampus }}</p>
                    </div>
                </div>

                <div class="mt-4 grid gap-2 border-t border-blue-100/70 pt-3 text-xs text-gray-600 sm:grid-cols-2">
                    <p><span class="text-gray-500">NIDN:</span> <span class="font-semibold text-gray-800">{{ $dosenNidn }}</span></p>
                    <p>
                        <span class="text-gray-500">WhatsApp:</span>
                        @if ($dosenWhatsappUrl)
                            <a href="{{ $dosenWhatsappUrl }}" target="_blank" rel="noopener noreferrer" class="font-semibold text-emerald-700 hover:text-emerald-800 hover:underline">{{ $dosenTelepon }}</a>
                        @else
                            -
                        @endif
                    </p>
                    <p class="sm:col-span-2">
                        <span class="text-gray-500">Email:</span>
                        @if ($dosenEmail)
                            <a href="mailto:{{ $dosenEmail }}" class="break-all font-semibold text-blue-700 hover:text-blue-800 hover:underline">{{ $dosenEmail }}</a>
                        @else
                            -
                        @endif
                    </p>
                </div>
            </div>

            {{-- Mentor Lapangan (PT Angkasa Pura Aviasi) --}}
            <div class="rounded-xl border border-amber-100 bg-amber-50/30 p-5">
                <div class="flex items-center gap-3">
                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-amber-100 text-amber-700">
                        <i class="fas fa-user-tie text-lg"></i>
                    </div>
                    <div class="min-w-0">
                        <p class="text-xs font-semibold uppercase tracking-wider text-amber-800">Pembimbing Lapangan (Field Mentor)</p>
                        <h4 class="truncate text-base font-bold text-gray-900">{{ $mentorNama }}</h4>
                        <p class="truncate text-xs text-gray-500">Unit: {{ $mentorDepartemen }}</p>
                    </div>
                </div>

                <div class="mt-4 grid gap-2 border-t border-amber-100/70 pt-3 text-xs text-gray-600 sm:grid-cols-2">
                    <p><span class="text-gray-500">NIP:</span> <span class="font-semibold text-gray-800">{{ $mentorNip }}</span></p>
                    <p><span class="text-gray-500">Pimpinan Unit:</span> <span class="font-semibold text-gray-800">{{ $mentorPimpinan }}</span></p>
                    <p>
                        <span class="text-gray-500">WhatsApp:</span>
                        @if ($mentorWhatsappUrl)
                            <a href="{{ $mentorWhatsappUrl }}" target="_blank" rel="noopener noreferrer" class="font-semibold text-emerald-700 hover:text-emerald-800 hover:underline">{{ $mentorTelepon }}</a>
                        @else
                            -
                        @endif
                    </p>
                    <p class="sm:col-span-2">
                        <span class="text-gray-500">Email:</span>
                        @if ($mentorEmail)
                            <a href="mailto:{{ $mentorEmail }}" class="break-all font-semibold text-blue-700 hover:text-blue-800 hover:underline">{{ $mentorEmail }}</a>
                        @else
                            -
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- Card 4: Ringkasan Kinerja & Dokumen Magang --}}
    <section class="overflow-hidden rounded-2xl border border-gray-200/80 bg-white shadow-sm transition hover:shadow-md">
        <header class="flex flex-wrap items-center justify-between gap-3 border-b border-gray-100 bg-gradient-to-r from-slate-50 to-emerald-50/30 px-5 py-4 sm:px-6">
            <div class="flex items-center gap-3">
                <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-emerald-600 text-white shadow-sm">
                    <i class="fas fa-chart-line text-sm"></i>
                </span>
                <div>
                    <h3 class="font-bold text-gray-900">Rekap Aktivitas &amp; Dokumen Akhir</h3>
                    <p class="text-xs text-gray-500">Statistik pengisian logbook harian, laporan akhir, dan nilai</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('mahasiswa.laporan.index') }}" class="inline-flex items-center gap-1.5 rounded-lg border border-gray-200 bg-white px-3 py-1.5 text-xs font-semibold text-gray-700 hover:bg-gray-50 shadow-sm transition">
                    <i class="fas fa-book-open text-blue-600"></i> Buka Logbook
                </a>
                <a href="{{ route('mahasiswa.laporan-akhir.index') }}" class="inline-flex items-center gap-1.5 rounded-lg border border-gray-200 bg-white px-3 py-1.5 text-xs font-semibold text-gray-700 hover:bg-gray-50 shadow-sm transition">
                    <i class="fas fa-file-alt text-indigo-600"></i> Laporan Akhir
                </a>
            </div>
        </header>

        <div class="p-5 sm:p-6">
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                {{-- Total Laporan --}}
                <div class="rounded-xl border border-gray-100 bg-gray-50/70 p-4">
                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Laporan Terkirim</p>
                    <p class="mt-2 text-2xl font-extrabold text-gray-900">{{ $totalLaporan ?? 0 }}</p>
                    <p class="mt-1 text-xs text-gray-400">Total catatan kegiatan</p>
                </div>

                {{-- Disetujui --}}
                <div class="rounded-xl border border-emerald-100 bg-emerald-50/50 p-4">
                    <p class="text-xs font-semibold uppercase tracking-wider text-emerald-700">Disetujui Mentor</p>
                    <p class="mt-2 text-2xl font-extrabold text-emerald-800">{{ $laporanDisetujui ?? 0 }}</p>
                    <p class="mt-1 text-xs text-emerald-600">Tervalidasi lapangan</p>
                </div>

                {{-- Menunggu Review / Revisi --}}
                <div class="rounded-xl border border-amber-100 bg-amber-50/50 p-4">
                    <p class="text-xs font-semibold uppercase tracking-wider text-amber-700">Perlu Review / Revisi</p>
                    <p class="mt-2 text-2xl font-extrabold text-amber-800">{{ ($laporanPending ?? 0) + ($laporanRevisi ?? 0) }}</p>
                    <p class="mt-1 text-xs text-amber-600">{{ $laporanPending ?? 0 }} baru · {{ $laporanRevisi ?? 0 }} revisi</p>
                </div>

                {{-- Status Nilai --}}
                <div class="rounded-xl border border-blue-100 bg-blue-50/50 p-4">
                    <p class="text-xs font-semibold uppercase tracking-wider text-blue-700">Penilaian Akhir</p>
                    @if ($penilaianTerbaru)
                        <p class="mt-2 text-2xl font-extrabold text-blue-800">
                            {{ number_format(($penilaianTerbaru->nilai_bimbingan + $penilaianTerbaru->nilai_laporan_akhir + $penilaianTerbaru->nilai_presentasi) / 3, 1) }}
                        </p>
                        <p class="mt-1 text-xs text-blue-600">Nilai rata-rata magang</p>
                    @else
                        <p class="mt-2 text-sm font-semibold text-gray-500">Belum Dinilai</p>
                        <p class="mt-1 text-xs text-gray-400">Menunggu akhir masa magang</p>
                    @endif
                </div>
            </div>
        </div>
    </section>
</div>
