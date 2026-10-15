<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Admin') }}
        </h2>
    </x-slot>

    <div class="mx-auto max-w-7xl space-y-6">
        <section class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-blue-700">Pusat Monitoring & Notifikasi</p>
                <h1 class="mt-1 text-2xl font-bold text-slate-900">Dashboard Administrasi</h1>
                <p class="mt-1 text-sm text-slate-500">Pantau aktivitas, input data, dan dokumen masuk dari mahasiswa secara real-time.</p>
            </div>
            <div class="inline-flex items-center gap-2 self-start rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-600 sm:self-auto shadow-xs">
                <i class="far fa-calendar-alt text-blue-600" aria-hidden="true"></i>
                {{ now()->format('d M Y') }}
            </div>
        </section>

        @if ($statistik['laporanBelumDiperiksa'] > 0 || $statistik['laporanAkhirMenunggu'] > 0)
            <div class="rounded-xl border border-amber-200 bg-amber-50/80 p-4 shadow-xs">
                <div class="flex items-start gap-3">
                    <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-amber-100 text-amber-700">
                        <i class="fas fa-bell"></i>
                    </span>
                    <div class="flex-1">
                        <h2 class="text-sm font-semibold text-amber-900">Notifikasi Data Masuk Memerlukan Perhatian</h2>
                        <p class="mt-0.5 text-xs text-amber-800">
                            Terdapat <strong>{{ $statistik['laporanBelumDiperiksa'] }}</strong> laporan harian yang belum diperiksa dan
                            <strong>{{ $statistik['laporanAkhirMenunggu'] }}</strong> dokumen laporan akhir yang menunggu verifikasi/review pembimbing.
                        </p>
                    </div>
                </div>
            </div>
        @endif

        <section class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4" aria-label="Statistik Magang / PKL">
            <article class="rounded-xl border border-slate-200 bg-white p-5 shadow-xs transition hover:shadow-md">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Total Peserta</p>
                        <p class="mt-2 text-3xl font-bold tabular-nums text-slate-900">{{ number_format($statistik['mahasiswa']) }}</p>
                        <p class="mt-1 text-xs text-slate-500">Terdaftar di sistem</p>
                    </div>
                    <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-50 text-blue-700 text-lg"><i class="fas fa-user-graduate" aria-hidden="true"></i></span>
                </div>
            </article>

            <article class="rounded-xl border border-slate-200 bg-white p-5 shadow-xs transition hover:shadow-md">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Laporan Hari Ini</p>
                        <p class="mt-2 text-3xl font-bold tabular-nums text-indigo-700">{{ number_format($statistik['laporanHariIni']) }}</p>
                        <p class="mt-1 text-xs text-slate-500">Total: {{ number_format($statistik['totalLaporan']) }} laporan</p>
                    </div>
                    <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-indigo-50 text-indigo-700 text-lg"><i class="fas fa-calendar-day" aria-hidden="true"></i></span>
                </div>
            </article>

            <article class="rounded-xl border border-slate-200 bg-white p-5 shadow-xs transition hover:shadow-md">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Laporan Belum ACC</p>
                        <p class="mt-2 text-3xl font-bold tabular-nums text-amber-700">{{ number_format($statistik['laporanBelumDiperiksa']) }}</p>
                        <p class="mt-1 text-xs text-slate-500">Menunggu review mentor</p>
                    </div>
                    <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-amber-50 text-amber-700 text-lg"><i class="fas fa-clipboard-list" aria-hidden="true"></i></span>
                </div>
            </article>

            <article class="rounded-xl border border-slate-200 bg-white p-5 shadow-xs transition hover:shadow-md">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Laporan Akhir Masuk</p>
                        <p class="mt-2 text-3xl font-bold tabular-nums text-rose-700">{{ number_format($statistik['laporanAkhirMenunggu']) }}</p>
                        <p class="mt-1 text-xs text-slate-500">Total: {{ number_format($statistik['totalLaporanAkhir']) }} dokumen</p>
                    </div>
                    <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-rose-50 text-rose-700 text-lg"><i class="fas fa-file-signature" aria-hidden="true"></i></span>
                </div>
            </article>
        </section>

        <section class="grid gap-6 xl:grid-cols-[minmax(0,1fr)_19rem]" x-data="{ tab: 'laporan' }">
            <article class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-xs">
                <header class="border-b border-slate-100 px-5 py-4">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h2 class="font-bold text-slate-900">Pusat Pantau Data & Dokumen Masuk</h2>
                            <p class="text-xs text-slate-500">Aktivitas pengisian logbook, berkas, dan pendaftaran terbaru.</p>
                        </div>
                        <div class="flex items-center gap-1.5 rounded-lg bg-slate-100 p-1 text-xs font-semibold">
                            <button type="button" @click="tab = 'laporan'" :class="tab === 'laporan' ? 'bg-white text-blue-700 shadow-xs' : 'text-slate-600 hover:text-slate-900'" class="rounded-md px-3 py-1.5 transition">
                                Laporan Harian ({{ $laporanTerbaru->count() }})
                            </button>
                            <button type="button" @click="tab = 'laporan-akhir'" :class="tab === 'laporan-akhir' ? 'bg-white text-blue-700 shadow-xs' : 'text-slate-600 hover:text-slate-900'" class="rounded-md px-3 py-1.5 transition">
                                Laporan Akhir ({{ $laporanAkhirTerbaru->count() }})
                            </button>
                            <button type="button" @click="tab = 'peserta'" :class="tab === 'peserta' ? 'bg-white text-blue-700 shadow-xs' : 'text-slate-600 hover:text-slate-900'" class="rounded-md px-3 py-1.5 transition">
                                Peserta Baru ({{ $pesertaTerbaru->count() }})
                            </button>
                        </div>
                    </div>
                </header>

                <!-- Panel 1: Laporan Harian -->
                <div x-show="tab === 'laporan'" class="overflow-x-auto">
                    @if ($laporanTerbaru->isEmpty())
                        <p class="px-5 py-10 text-center text-sm text-slate-500">Belum ada laporan kegiatan harian.</p>
                    @else
                        <table class="w-full min-w-[40rem] text-left text-sm">
                            <thead class="bg-slate-50 text-xs font-semibold uppercase tracking-wide text-slate-500">
                                <tr>
                                    <th class="px-5 py-3">Tanggal</th>
                                    <th class="px-5 py-3">Peserta</th>
                                    <th class="px-5 py-3">Kegiatan</th>
                                    <th class="px-5 py-3 text-center">Status</th>
                                    <th class="px-5 py-3 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach ($laporanTerbaru as $laporan)
                                    <tr class="hover:bg-slate-50/80 transition-colors">
                                        <td class="whitespace-nowrap px-5 py-3.5 text-xs text-slate-500">
                                            {{ $laporan->tanggal->format('d/m/Y') }}
                                            <span class="block text-[10px] text-slate-400">{{ $laporan->created_at->diffForHumans() }}</span>
                                        </td>
                                        <td class="whitespace-nowrap px-5 py-3.5">
                                            <div class="font-medium text-slate-900">{{ $laporan->mahasiswa?->name ?? '-' }}</div>
                                            <div class="text-xs text-slate-500">{{ $laporan->mahasiswa?->kelompok?->nama_kelompok ?? '-' }}</div>
                                        </td>
                                        <td class="max-w-xs px-5 py-3.5 text-slate-700">
                                            <p class="truncate text-xs">{{ $laporan->kegiatan }}</p>
                                        </td>
                                        <td class="whitespace-nowrap px-5 py-3.5 text-center">
                                            @php
                                                $badge = match($laporan->status) {
                                                    'Disetujui', 'Sudah Diperiksa' => 'bg-emerald-100 text-emerald-700',
                                                    'Mengetahui' => 'bg-indigo-100 text-indigo-700',
                                                    'Revisi', 'Ditolak' => 'bg-rose-100 text-rose-700',
                                                    default => 'bg-amber-100 text-amber-700',
                                                };
                                            @endphp
                                            <span class="rounded-full px-2.5 py-0.5 text-[11px] font-semibold {{ $badge }}">
                                                {{ $laporan->status }}
                                            </span>
                                        </td>
                                        <td class="whitespace-nowrap px-5 py-3.5 text-center">
                                            @if ($laporan->mahasiswa)
                                                <a href="{{ route('laporan.mahasiswa.download-pdf', $laporan->mahasiswa) }}" target="_blank"
                                                   class="inline-flex items-center gap-1 rounded bg-rose-50 px-2.5 py-1 text-xs font-medium text-rose-700 hover:bg-rose-100"
                                                   title="Unduh Arsip Laporan (PDF)">
                                                    <i class="fa-solid fa-file-pdf"></i> Arsip
                                                </a>
                                            @else
                                                <span class="text-xs text-slate-400">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>

                <!-- Panel 2: Laporan Akhir & Dokumen -->
                <div x-show="tab === 'laporan-akhir'" class="overflow-x-auto" style="display: none;">
                    @if ($laporanAkhirTerbaru->isEmpty())
                        <p class="px-5 py-10 text-center text-sm text-slate-500">Belum ada pengajuan laporan akhir / lembar pengesahan.</p>
                    @else
                        <table class="w-full min-w-[40rem] text-left text-sm">
                            <thead class="bg-slate-50 text-xs font-semibold uppercase tracking-wide text-slate-500">
                                <tr>
                                    <th class="px-5 py-3">Diajukan</th>
                                    <th class="px-5 py-3">Peserta</th>
                                    <th class="px-5 py-3">Judul Dokumen / Laporan</th>
                                    <th class="px-5 py-3 text-center">Status</th>
                                    <th class="px-5 py-3 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach ($laporanAkhirTerbaru as $la)
                                    <tr class="hover:bg-slate-50/80 transition-colors">
                                        <td class="whitespace-nowrap px-5 py-3.5 text-xs text-slate-500">
                                            {{ $la->created_at->format('d/m/Y') }}
                                            <span class="block text-[10px] text-slate-400">{{ $la->created_at->diffForHumans() }}</span>
                                        </td>
                                        <td class="whitespace-nowrap px-5 py-3.5">
                                            <div class="font-medium text-slate-900">{{ $la->mahasiswa?->name ?? '-' }}</div>
                                            <div class="text-xs text-slate-500">{{ $la->mahasiswa?->nim_nip ?? '-' }}</div>
                                        </td>
                                        <td class="max-w-xs px-5 py-3.5 text-slate-700">
                                            <p class="truncate text-xs font-medium">{{ $la->judul_laporan }}</p>
                                        </td>
                                        <td class="whitespace-nowrap px-5 py-3.5 text-center">
                                            @php
                                                $badgeLa = match($la->status) {
                                                    'Disetujui' => 'bg-emerald-100 text-emerald-700',
                                                    'Revisi' => 'bg-rose-100 text-rose-700',
                                                    default => 'bg-blue-100 text-blue-700',
                                                };
                                            @endphp
                                            <span class="rounded-full px-2.5 py-0.5 text-[11px] font-semibold {{ $badgeLa }}">
                                                {{ $la->status }}
                                            </span>
                                        </td>
                                        <td class="whitespace-nowrap px-5 py-3.5 text-center">
                                            <a href="{{ route('laporan-akhir.download-admin', $la) }}" target="_blank"
                                               class="inline-flex items-center gap-1 rounded bg-rose-50 px-2.5 py-1 text-xs font-medium text-rose-700 hover:bg-rose-100"
                                               title="Unduh PDF & Lembar Pengesahan">
                                                <i class="fa-solid fa-file-pdf"></i> Unduh PDF
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>

                <!-- Panel 3: Peserta Baru -->
                <div x-show="tab === 'peserta'" class="overflow-x-auto" style="display: none;">
                    @if ($pesertaTerbaru->isEmpty())
                        <p class="px-5 py-10 text-center text-sm text-slate-500">Belum ada peserta baru.</p>
                    @else
                        <table class="w-full min-w-[36rem] text-left text-sm">
                            <thead class="bg-slate-50 text-xs font-semibold uppercase tracking-wide text-slate-500">
                                <tr>
                                    <th class="px-5 py-3">Terdaftar</th>
                                    <th class="px-5 py-3">Nama Peserta</th>
                                    <th class="px-5 py-3">Asal Kampus / Sekolah</th>
                                    <th class="px-5 py-3">Kelompok</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach ($pesertaTerbaru as $p)
                                    <tr class="hover:bg-slate-50/80 transition-colors">
                                        <td class="whitespace-nowrap px-5 py-3.5 text-xs text-slate-500">
                                            {{ $p->created_at->format('d/m/Y') }}
                                            <span class="block text-[10px] text-slate-400">{{ $p->created_at->diffForHumans() }}</span>
                                        </td>
                                        <td class="whitespace-nowrap px-5 py-3.5">
                                            <div class="font-medium text-slate-900">{{ $p->name }}</div>
                                            <div class="text-xs text-slate-500">{{ $p->email }}</div>
                                        </td>
                                        <td class="whitespace-nowrap px-5 py-3.5 text-xs text-slate-700">
                                            {{ $p->kampus ?? '-' }}
                                        </td>
                                        <td class="whitespace-nowrap px-5 py-3.5 text-xs text-slate-600">
                                            {{ $p->kelompok?->nama_kelompok ?? 'Belum ada kelompok' }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </article>

            <aside class="space-y-4">
                <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-xs">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Navigasi Utama</p>
                        <h2 class="mt-1 font-bold text-slate-900">Kelola Sistem Magang</h2>
                    </div>
                    <nav class="mt-3 divide-y divide-slate-100" aria-label="Akses cepat administrasi">
                        <a href="{{ route('mahasiswa.index') }}" class="flex items-center justify-between gap-3 py-3 text-sm text-slate-600 transition hover:text-blue-700">
                            <span class="flex items-center gap-3"><i class="fas fa-user-graduate w-4 text-center text-blue-600" aria-hidden="true"></i> Data Peserta</span>
                            <i class="fas fa-arrow-right text-xs text-slate-400" aria-hidden="true"></i>
                        </a>
                        <a href="{{ route('kelompok.index') }}" class="flex items-center justify-between gap-3 py-3 text-sm text-slate-600 transition hover:text-blue-700">
                            <span class="flex items-center gap-3"><i class="fas fa-users w-4 text-center text-indigo-600" aria-hidden="true"></i> Kelompok Magang</span>
                            <i class="fas fa-arrow-right text-xs text-slate-400" aria-hidden="true"></i>
                        </a>
                        <a href="{{ route('dosen.index') }}" class="flex items-center justify-between gap-3 py-3 text-sm text-slate-600 transition hover:text-blue-700">
                            <span class="flex items-center gap-3"><i class="fas fa-chalkboard-teacher w-4 text-center text-cyan-700" aria-hidden="true"></i> Pembimbing Kampus</span>
                            <i class="fas fa-arrow-right text-xs text-slate-400" aria-hidden="true"></i>
                        </a>
                        <a href="{{ route('pembimbing.index') }}" class="flex items-center justify-between gap-3 py-3 text-sm text-slate-600 transition hover:text-blue-700">
                            <span class="flex items-center gap-3"><i class="fas fa-user-tie w-4 text-center text-emerald-700" aria-hidden="true"></i> Pembimbing Lapangan</span>
                            <i class="fas fa-arrow-right text-xs text-slate-400" aria-hidden="true"></i>
                        </a>
                        <a href="{{ route('departemen.index') }}" class="flex items-center justify-between gap-3 py-3 text-sm text-slate-600 transition hover:text-blue-700">
                            <span class="flex items-center gap-3"><i class="fas fa-building w-4 text-center text-amber-700" aria-hidden="true"></i> Departemen Instansi</span>
                            <i class="fas fa-arrow-right text-xs text-slate-400" aria-hidden="true"></i>
                        </a>
                    </nav>
                </div>
            </aside>
        </section>
    </div>
</x-app-layout>