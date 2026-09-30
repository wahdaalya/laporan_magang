<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">Dashboard Pembimbing Magang / PKL</h2>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800" role="status">{{ session('status') }}</div>
            @endif

            <section class="rounded-xl bg-gradient-to-r from-slate-800 to-slate-700 p-6 text-white shadow-sm sm:p-8">
                <p class="text-sm font-medium text-slate-300">Dashboard Pembimbing Lapangan</p>
                <h1 class="mt-2 text-2xl font-semibold">Selamat Datang, {{ auth()->user()->name }}!</h1>
                <p class="mt-1 text-sm text-slate-300">Ringkasan peserta dan laporan Magang / PKL yang Anda bimbing.</p>
            </section>

            <section class="grid gap-4 sm:grid-cols-3" aria-label="Ringkasan bimbingan">
                <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                    <p class="text-sm text-gray-500">Total Peserta Bimbingan</p>
                    <p class="mt-2 text-3xl font-semibold text-gray-900">{{ $totalPeserta }}</p>
                </div>
                <div class="rounded-xl border border-amber-200 bg-amber-50 p-5 shadow-sm">
                    <p class="text-sm text-amber-800">Laporan Belum Diperiksa</p>
                    <p class="mt-2 text-3xl font-semibold text-amber-900">{{ $totalLaporanPending }}</p>
                </div>
                <div class="rounded-xl border border-rose-200 bg-rose-50 p-5 shadow-sm">
                    <p class="text-sm text-rose-800">Belum Isi Laporan Hari Ini</p>
                    <p class="mt-2 text-3xl font-semibold text-rose-900">{{ $totalBelumLapor }}</p>
                </div>
            </section>

            <section class="overflow-hidden rounded-xl border border-rose-200 bg-white shadow-sm">
                <div class="flex flex-wrap items-center justify-between gap-4 border-b border-rose-100 px-5 py-4">
                    <div>
                        <h2 class="font-semibold text-gray-900">Pengingat Laporan Hari Ini</h2>
                        <p class="mt-1 text-sm text-gray-500">{{ $totalBelumLapor }} peserta belum mengisi laporan hari ini.</p>
                    </div>
                    @php($emailPenerima = $belumLaporHariIni->pluck('email')->filter()->implode(','))
                    @if ($belumLaporHariIni->isNotEmpty())
                        @if ($emailPenerima)
                            <a href="mailto:{{ $emailPenerima }}?subject={{ rawurlencode('Pengingat laporan Magang / PKL hari ini') }}&amp;body={{ rawurlencode('Mohon isi laporan kegiatan Magang / PKL hari ini. Terima kasih.') }}" class="inline-flex items-center gap-2 rounded-md bg-rose-700 px-4 py-2 text-sm font-semibold text-white hover:bg-rose-800">
                                <i class="fas fa-bell" aria-hidden="true"></i> Ingatkan Belum Laporan
                            </a>
                        @else
                            <span class="rounded-md bg-gray-100 px-4 py-2 text-sm font-medium text-gray-500">-</span>
                        @endif
                    @else
                        <span class="rounded-md bg-gray-100 px-4 py-2 text-sm font-medium text-gray-500">Semua laporan hari ini sudah masuk</span>
                    @endif
                </div>
                @if ($belumLaporHariIni->isNotEmpty())
                    <ul class="divide-y divide-gray-100">
                        @foreach ($belumLaporHariIni as $peserta)
                            <li class="flex flex-wrap items-center justify-between gap-3 px-5 py-3 text-sm">
                                <span class="font-medium text-gray-900">{{ $peserta->name }}</span>
                                @if ($peserta->email)
                                    <a href="mailto:{{ $peserta->email }}?subject={{ rawurlencode('Pengingat laporan Magang / PKL hari ini') }}" class="text-blue-700 hover:underline">{{ $peserta->email }}</a>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                @endif
            </section>

            <section class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
                <div class="flex flex-wrap items-center justify-between gap-3 border-b border-gray-100 px-5 py-4">
                    <div>
                        <h2 class="font-semibold text-gray-900">Peserta Bimbingan</h2>
                        <p class="mt-1 text-sm text-gray-500">Kehadiran laporan hari ini dan progress periode Magang / PKL.</p>
                    </div>
                    <a href="{{ route('pembimbing.peserta') }}" class="text-sm font-medium text-blue-700 hover:underline">Daftar Peserta &amp; Penilaian</a>
                </div>

                @if ($pesertas->isEmpty())
                    <p class="px-5 py-8 text-center text-sm text-gray-500">Belum ada peserta yang ditugaskan kepada Anda.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-50 text-xs font-semibold uppercase tracking-wide text-gray-600">
                                <tr>
                                    <th class="px-4 py-3 text-left">Peserta</th>
                                    <th class="px-4 py-3 text-left">Sekolah / Kampus</th>
                                    <th class="px-4 py-3 text-left">Jurusan / Prodi</th>
                                    <th class="px-4 py-3 text-center">Laporan Hari Ini</th>
                                    <th class="px-4 py-3 text-left">Progress Durasi</th>
                                    <th class="px-4 py-3 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach ($pesertas as $peserta)
                                    <tr class="align-middle hover:bg-gray-50/70">
                                        <td class="px-4 py-3">
                                            <div class="flex items-center gap-3">
                                                @if ($peserta->foto_url)
                                                    <img src="{{ $peserta->foto_url }}" alt="Foto {{ $peserta->name }}" class="h-10 w-10 rounded-full border border-gray-200 object-cover">
                                                @else
                                                    <span class="flex h-10 w-10 items-center justify-center rounded-full bg-slate-100 font-semibold text-slate-600">{{ strtoupper(substr($peserta->name, 0, 1)) }}</span>
                                                @endif
                                                <div>
                                                    <p class="font-medium text-gray-900">{{ $peserta->name }}</p>
                                                    <p class="text-xs text-gray-500">{{ $peserta->nim_nip ?? '-' }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 text-gray-600">{{ $peserta->instansi_tampil }}</td>
                                        <td class="px-4 py-3 text-gray-600">{{ $peserta->jurusan_tampil }}</td>
                                        <td class="px-4 py-3 text-center">
                                            <span @class(['inline-flex rounded-full px-2.5 py-1 text-xs font-medium', 'bg-emerald-50 text-emerald-700' => $peserta->status_laporan_hari_ini === 'Sudah Mengisi', 'bg-rose-50 text-rose-700' => $peserta->status_laporan_hari_ini === 'Belum Mengisi'])>{{ $peserta->status_laporan_hari_ini }}</span>
                                        </td>
                                        <td class="min-w-40 px-4 py-3">
                                            <div class="flex items-center gap-2">
                                                <progress class="h-2 w-28 accent-emerald-600" value="{{ $peserta->progress_durasi }}" max="100" aria-label="Progress durasi {{ $peserta->name }}">{{ $peserta->progress_durasi }}%</progress>
                                                <span class="text-xs text-gray-600">{{ $peserta->progress_durasi }}%</span>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 text-right">
                                            <a href="{{ route('pembimbingan.laporan.index', ['peserta' => $peserta->id]) }}" class="inline-flex items-center gap-2 rounded-md border border-gray-300 px-3 py-1.5 text-xs font-semibold text-gray-700 hover:bg-gray-50">Lihat Detail / Laporan</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </section>
        </div>
    </div>
</x-app-layout>
