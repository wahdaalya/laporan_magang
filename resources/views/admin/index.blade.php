<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Admin') }}
        </h2>
    </x-slot>

    <div class="mx-auto max-w-7xl space-y-6">
        <section class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-blue-700">Ringkasan Operasional</p>
                <h1 class="mt-2 text-2xl font-bold text-slate-900">Selamat datang kembali</h1>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Pantau aktivitas peserta Magang / PKL hari ini.</p>
            </div>
            <div class="inline-flex items-center gap-2 self-start rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-600 dark:text-slate-200 sm:self-auto">
                <i class="far fa-calendar text-blue-600" aria-hidden="true"></i>
                {{ now()->format('d M Y') }}
            </div>
        </section>

        <section class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4" aria-label="Statistik Magang / PKL">
            <article class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm transition duration-200 hover:-translate-y-0.5 hover:shadow-md">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="text-sm font-medium text-slate-500 dark:text-slate-300">Total Peserta</p>
                        <p class="mt-3 text-3xl font-bold tabular-nums text-slate-900">{{ number_format($statistik['mahasiswa']) }}</p>
                        <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Terdaftar dalam sistem</p>
                    </div>
                    <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-50 text-blue-700"><i class="fas fa-user-graduate" aria-hidden="true"></i></span>
                </div>
            </article>
            <article class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm transition duration-200 hover:-translate-y-0.5 hover:shadow-md">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="text-sm font-medium text-slate-500 dark:text-slate-300">Pembimbing Sekolah/Kampus</p>
                        <p class="mt-3 text-3xl font-bold tabular-nums text-slate-900">{{ number_format($statistik['dosen']) }}</p>
                        <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Guru atau dosen pembimbing</p>
                    </div>
                    <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-cyan-50 text-cyan-700"><i class="fas fa-chalkboard-teacher" aria-hidden="true"></i></span>
                </div>
            </article>
            <article class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm transition duration-200 hover:-translate-y-0.5 hover:shadow-md">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="text-sm font-medium text-slate-500 dark:text-slate-300">Total Mentor</p>
                        <p class="mt-3 text-3xl font-bold tabular-nums text-slate-900">{{ number_format($statistik['mentor']) }}</p>
                        <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Pembimbing lapangan</p>
                    </div>
                    <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-emerald-50 text-emerald-700"><i class="fas fa-user-tie" aria-hidden="true"></i></span>
                </div>
            </article>
            <article class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm transition duration-200 hover:-translate-y-0.5 hover:shadow-md">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="text-sm font-medium text-slate-500 dark:text-slate-300">Laporan Menunggu</p>
                        <p class="mt-3 text-3xl font-bold tabular-nums text-amber-700">{{ number_format($statistik['laporanBelumDiperiksa']) }}</p>
                        <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Belum diperiksa pembimbing</p>
                    </div>
                    <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-amber-50 text-amber-700"><i class="fas fa-clipboard-list" aria-hidden="true"></i></span>
                </div>
            </article>
        </section>

        <section class="grid gap-6 xl:grid-cols-[minmax(0,1fr)_18rem]">
            <article class="overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm">
                <header class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 px-5 py-4 sm:px-6">
                    <div>
                        <h2 class="font-semibold text-slate-900">Laporan Terbaru</h2>
                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Aktivitas logbook peserta yang terakhir masuk.</p>
                    </div>
                    <a href="{{ route('mahasiswa.index') }}" class="inline-flex items-center gap-2 rounded-md px-3 py-2 text-sm font-semibold text-blue-700 transition hover:bg-blue-50">
                        Data peserta <i class="fas fa-arrow-right text-xs" aria-hidden="true"></i>
                    </a>
                </header>

                @if ($laporanTerbaru->isEmpty())
                    <p class="px-5 py-10 text-center text-sm text-slate-500">Belum ada laporan.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full min-w-[36rem] text-left text-sm">
                            <thead class="bg-slate-50 text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-300">
                                <tr>
                                    <th class="px-5 py-3 sm:px-6">Tanggal</th>
                                    <th class="px-5 py-3">Peserta</th>
                                    <th class="px-5 py-3">Kegiatan</th>
                                    <th class="px-5 py-3 sm:px-6">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach ($laporanTerbaru as $laporan)
                                    <tr class="transition-colors hover:bg-slate-50/80">
                                        <td class="whitespace-nowrap px-5 py-4 text-slate-500 dark:text-slate-200 sm:px-6">{{ $laporan->tanggal->format('d M Y') }}</td>
                                        <td class="whitespace-nowrap px-5 py-4 font-medium text-slate-800 dark:text-slate-200">{{ $laporan->mahasiswa?->name ?? '-' }}</td>
                                        <td class="max-w-sm px-5 py-4 text-slate-600 dark:text-slate-200"><p class="line-clamp-2">{{ $laporan->kegiatan }}</p></td>
                                        <td class="whitespace-nowrap px-5 py-4 sm:px-6">
                                            <span @class(['inline-flex rounded-full border px-2.5 py-1 text-xs font-medium',
                                                'border-emerald-200 bg-emerald-50 text-emerald-700 dark:border-emerald-800/50 dark:bg-emerald-950/60 dark:text-emerald-300' => in_array($laporan->status, ['Disetujui', 'Sudah Diperiksa'], true),
                                                'bg-red-50 text-red-700' => in_array($laporan->status, ['Ditolak', 'Revisi']),
                                                'bg-amber-50 text-amber-700' => $laporan->status === 'Belum Diperiksa',
                                                'bg-slate-100 text-slate-600' => ! in_array($laporan->status, ['Disetujui', 'Sudah Diperiksa', 'Ditolak', 'Revisi', 'Belum Diperiksa'], true),
                                            ])>{{ $laporan->status }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </article>

            <aside class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Navigasi cepat</p>
                    <h2 class="mt-2 font-semibold text-slate-900 dark:text-slate-100">Kelola Magang / PKL</h2>
                </div>
                <nav class="quick-links-divider mt-4 divide-y divide-slate-100 dark:divide-slate-700" aria-label="Akses cepat administrasi">
                    <a href="{{ route('mahasiswa.index') }}" class="flex items-center justify-between gap-3 py-3 text-sm text-slate-600 transition hover:text-blue-700 dark:text-slate-200">
                        <span class="flex items-center gap-3"><i class="fas fa-user-graduate w-4 text-center text-blue-600" aria-hidden="true"></i> Peserta</span>
                        <i class="fas fa-arrow-up-right-from-square text-xs text-slate-400" aria-hidden="true"></i>
                    </a>
                    <a href="{{ route('kelompok.index') }}" class="flex items-center justify-between gap-3 py-3 text-sm text-slate-600 transition hover:text-blue-700 dark:text-slate-200">
                        <span class="flex items-center gap-3"><i class="fas fa-users w-4 text-center text-indigo-600" aria-hidden="true"></i> Kelompok</span>
                        <i class="fas fa-arrow-up-right-from-square text-xs text-slate-400" aria-hidden="true"></i>
                    </a>
                    <a href="{{ route('dosen.index') }}" class="flex items-center justify-between gap-3 py-3 text-sm text-slate-600 transition hover:text-blue-700 dark:text-slate-200">
                        <span class="flex items-center gap-3"><i class="fas fa-chalkboard-teacher w-4 text-center text-cyan-700" aria-hidden="true"></i> Pembimbing Sekolah/Kampus</span>
                        <i class="fas fa-arrow-up-right-from-square text-xs text-slate-400" aria-hidden="true"></i>
                    </a>
                    <a href="{{ route('departemen.index') }}" class="flex items-center justify-between gap-3 py-3 text-sm text-slate-600 transition hover:text-blue-700 dark:text-slate-200">
                        <span class="flex items-center gap-3"><i class="fas fa-building w-4 text-center text-emerald-700" aria-hidden="true"></i> Departemen</span>
                        <i class="fas fa-arrow-up-right-from-square text-xs text-slate-400" aria-hidden="true"></i>
                    </a>
                </nav>
            </aside>
        </section>
    </div>
</x-app-layout>