@php
    $statusBimbingan = $laporanAkhir?->status ?? 'Belum Diajukan';
    $statusClass = match ($statusBimbingan) {
        'Menunggu Review' => 'border-amber-200 bg-amber-50 text-amber-900',
        'Revisi' => 'border-rose-200 bg-rose-50 text-rose-900',
        'Disetujui' => 'border-emerald-200 bg-emerald-50 text-emerald-900',
        default => 'border-gray-200 bg-gray-50 text-gray-800',
    };
@endphp

<x-app-layout>
    <x-slot name="header">
        <h1 class="text-xl font-semibold text-gray-800">Laporan Akhir Magang / PKL</h1>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800" role="status">{{ session('status') }}</div>
            @endif

            @if ($rekap['ringkasan']['belum_disetujui'] > 0)
                <div class="rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-900" role="status">
                    Ada {{ $rekap['ringkasan']['belum_disetujui'] }} laporan yang belum disetujui dan belum masuk rekap.
                    <a href="{{ route('mahasiswa.laporan.index') }}" class="ml-1 font-semibold underline hover:text-amber-950">Lihat Riwayat Laporan</a>
                </div>
            @endif

            <section class="flex flex-wrap items-center justify-between gap-4 rounded-xl border p-5 {{ $statusClass }}">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide">Status Bimbingan</p>
                    <p class="mt-1 text-lg font-bold">{{ $statusBimbingan }}</p>
                    <div class="mt-2 flex flex-wrap gap-2">
                        @if ($laporanAkhir?->status_mengetahui)
                            <span class="inline-flex items-center gap-1 rounded-full bg-blue-100 px-2.5 py-1 text-xs font-semibold text-blue-800">
                                <i class="fas fa-eye text-[11px]" aria-hidden="true"></i> Mengetahui: {{ $laporanAkhir->mengetahui_oleh }}
                            </span>
                        @endif
                        @if ($laporanAkhir?->status_setuju)
                            <span class="inline-flex items-center gap-1 rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-semibold text-emerald-800">
                                <i class="fas fa-check text-[11px]" aria-hidden="true"></i> Disetujui: {{ $laporanAkhir->disetujui_oleh }}
                            </span>
                        @endif
                    </div>
                    @if ($statusBimbingan === 'Revisi' && $laporanAkhir?->catatan_revisi)
                        <div class="mt-3 rounded-lg border border-rose-200 bg-white/70 p-3 text-sm">
                            <p class="font-semibold">Catatan Pembimbing Sekolah/Kampus</p>
                            <p class="mt-1 whitespace-pre-line">{{ $laporanAkhir->catatan_revisi }}</p>
                        </div>
                    @elseif ($statusBimbingan === 'Menunggu Review')
                        <p class="mt-1 text-sm">Pengajuan Anda sedang ditinjau Pembimbing Sekolah/Kampus.</p>
                    @endif
                </div>

                <div class="flex flex-wrap gap-2">
                    @if ($statusBimbingan === 'Disetujui')
                        <a href="{{ route('mahasiswa.laporan-akhir.cetak') }}" class="inline-flex items-center gap-2 rounded-md bg-emerald-700 px-4 py-2.5 text-sm font-semibold text-white hover:bg-emerald-800">
                            <i class="fas fa-download" aria-hidden="true"></i> Unduh Laporan Akhir (PDF)
                        </a>
                    @elseif ($statusBimbingan === 'Menunggu Review')
                        <button type="button" disabled class="cursor-not-allowed rounded-md bg-gray-200 px-4 py-2.5 text-sm font-semibold text-gray-500">Menunggu review Pembimbing Sekolah/Kampus</button>
                    @elseif ($rekap['siap'])
                        <form method="POST" action="{{ route('mahasiswa.laporan-akhir.ajukan') }}">
                            @csrf
                            <button type="submit" class="inline-flex items-center gap-2 rounded-md bg-blue-700 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-800">
                                <i class="fas fa-paper-plane" aria-hidden="true"></i> {{ $statusBimbingan === 'Revisi' ? 'Ajukan Ulang' : 'Ajukan ke Pembimbing Sekolah/Kampus' }}
                            </button>
                        </form>
                    @else
                        <button type="button" disabled class="cursor-not-allowed rounded-md bg-gray-200 px-4 py-2.5 text-sm font-semibold text-gray-500">{{ $statusBimbingan === 'Revisi' ? 'Ajukan Ulang' : 'Ajukan ke Pembimbing Sekolah/Kampus' }}</button>
                    @endif
                </div>
            </section>

            @if (! $rekap['siap'])
                <section class="rounded-xl border border-blue-200 bg-blue-50 p-6 text-sm text-blue-900" role="status">
                    Belum ada laporan harian yang disetujui. Laporan akhir akan muncul otomatis setelah laporan harianmu disetujui pembimbing.
                    @if (! ($rekap['punya_kelompok'] ?? false))
                        <p class="mt-2">Tombol pengajuan aktif setelah admin menambahkanmu ke kelompok magang.</p>
                    @elseif ($rekap['ringkasan']['disetujui'] === 0)
                        <p class="mt-2">Tombol pengajuan aktif setelah minimal satu laporan harian disetujui pembimbing.</p>
                    @endif
                </section>
            @else
                <section class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
                    <header class="flex flex-wrap items-center justify-between gap-4 border-b border-gray-200 px-5 py-5 sm:px-6">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wider text-blue-700">Pratinjau Rekap</p>
                            <h2 class="mt-1 text-xl font-bold text-gray-900">Laporan Akhir Magang / PKL</h2>
                            <p class="mt-1 text-sm text-gray-500">Dihasilkan otomatis dari laporan harian yang disetujui.</p>
                        </div>
                    </header>

                    <div class="space-y-6 p-5 sm:p-6">
                        <section>
                            <h3 class="text-sm font-bold uppercase tracking-wide text-gray-700">Identitas Peserta</h3>
                            <dl class="mt-3 grid gap-x-8 gap-y-4 sm:grid-cols-2">
                                <div><dt class="text-xs font-medium uppercase text-gray-500">Nama</dt><dd class="mt-1 font-semibold text-gray-900">{{ $rekap['identitas']['nama'] }}</dd></div>
                                <div><dt class="text-xs font-medium uppercase text-gray-500">NIM / NISN</dt><dd class="mt-1 font-semibold text-gray-900">{{ $rekap['identitas']['nim'] }}</dd></div>
                                <div><dt class="text-xs font-medium uppercase text-gray-500">Instansi / Sekolah / Kampus</dt><dd class="mt-1 font-semibold text-gray-900">{{ $rekap['identitas']['kampus'] }}</dd></div>
                                <div><dt class="text-xs font-medium uppercase text-gray-500">Fakultas</dt><dd class="mt-1 font-semibold text-gray-900">{{ $rekap['identitas']['fakultas'] }}</dd></div>
                                <div><dt class="text-xs font-medium uppercase text-gray-500">Jurusan / Program Studi</dt><dd class="mt-1 font-semibold text-gray-900">{{ $rekap['identitas']['prodi'] }}</dd></div>
                                <div><dt class="text-xs font-medium uppercase text-gray-500">Departemen Penempatan</dt><dd class="mt-1 font-semibold text-gray-900">{{ $rekap['identitas']['departemen'] }}</dd></div>
                                <div><dt class="text-xs font-medium uppercase text-gray-500">Periode Magang</dt><dd class="mt-1 font-semibold text-gray-900">{{ $rekap['identitas']['tanggal_mulai'] }} - {{ $rekap['identitas']['tanggal_selesai'] }}</dd></div>
                                <div><dt class="text-xs font-medium uppercase text-gray-500">Pembimbing Sekolah/Kampus</dt><dd class="mt-1 font-semibold text-gray-900">{{ $rekap['identitas']['dosen']['nama'] }} <span class="font-normal text-gray-500">(NIP / NUPTK / ID Pengajar: {{ $rekap['identitas']['dosen']['nidn'] }})</span></dd></div>
                                <div class="sm:col-span-2"><dt class="text-xs font-medium uppercase text-gray-500">Pembimbing Lapangan</dt><dd class="mt-1 font-semibold text-gray-900">{{ $rekap['identitas']['pembimbing']['nama'] }} <span class="font-normal text-gray-500">(NIP: {{ $rekap['identitas']['pembimbing']['nip'] }})</span></dd></div>
                            </dl>
                        </section>

                        <section>
                            <h3 class="text-sm font-bold uppercase tracking-wide text-gray-700">Ringkasan Laporan</h3>
                            <dl class="mt-3 grid grid-cols-2 gap-3 lg:grid-cols-4">
                                <div class="rounded-lg border border-gray-200 bg-gray-50 p-4"><dt class="text-xs text-gray-500">Hari Laporan</dt><dd class="mt-1 text-2xl font-bold text-gray-900">{{ $rekap['ringkasan']['total_hari'] }}</dd></div>
                                <div class="rounded-lg border border-emerald-200 bg-emerald-50 p-4"><dt class="text-xs text-emerald-800">Disetujui</dt><dd class="mt-1 text-2xl font-bold text-emerald-900">{{ $rekap['ringkasan']['disetujui'] }}</dd></div>
                                <div class="rounded-lg border border-amber-200 bg-amber-50 p-4"><dt class="text-xs text-amber-800">Revisi</dt><dd class="mt-1 text-2xl font-bold text-amber-900">{{ $rekap['ringkasan']['revisi'] }}</dd></div>
                                <div class="rounded-lg border border-blue-200 bg-blue-50 p-4"><dt class="text-xs text-blue-800">Belum Diperiksa</dt><dd class="mt-1 text-2xl font-bold text-blue-900">{{ $rekap['ringkasan']['belum_diperiksa'] }}</dd></div>
                            </dl>
                        </section>

                        <section>
                            <h3 class="text-sm font-bold uppercase tracking-wide text-gray-700">Rekap Kegiatan Harian</h3>
                            <div class="mt-3 space-y-5">
                                @foreach ($rekap['minggu'] as $minggu)
                                    <div>
                                        <h4 class="mb-2 font-semibold text-gray-900">Minggu {{ $minggu['nomor'] }}</h4>
                                        <div class="overflow-x-auto rounded-lg border border-gray-200">
                                            <table class="min-w-full divide-y divide-gray-200 text-sm">
                                                <thead class="bg-gray-50 text-xs font-semibold uppercase text-gray-600">
                                                    <tr><th class="px-3 py-2 text-left">No</th><th class="px-3 py-2 text-left">Tanggal</th><th class="px-3 py-2 text-left">Kegiatan</th><th class="px-3 py-2 text-left">Keterangan</th><th class="px-3 py-2 text-left">Foto</th></tr>
                                                </thead>
                                                <tbody class="divide-y divide-gray-100">
                                                    @foreach ($minggu['laporan'] as $laporan)
                                                        <tr class="align-top">
                                                            <td class="px-3 py-3">{{ $laporan['nomor'] }}</td>
                                                            <td class="whitespace-nowrap px-3 py-3">{{ $laporan['tanggal'] }}</td>
                                                            <td class="min-w-48 px-3 py-3 font-medium text-gray-900">{{ $laporan['kegiatan'] }}</td>
                                                            <td class="min-w-48 whitespace-pre-line px-3 py-3">{{ $laporan['keterangan'] }}</td>
                                                            <td class="px-3 py-3">
                                                                @if ($laporan['foto_url'])
                                                                    <a href="{{ $laporan['foto_url'] }}" target="_blank" rel="noopener noreferrer"><img src="{{ $laporan['foto_url'] }}" alt="Dokumentasi {{ $laporan['kegiatan'] }}" class="max-h-20 max-w-24 rounded border border-gray-200 object-contain"></a>
                                                                @else
                                                                    -
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </section>

                        <section class="border-t border-gray-200 pt-6">
                            <div class="mb-4 text-center sm:text-left">
                                <h3 class="text-sm font-bold uppercase tracking-wide text-gray-700">Lembar Pengesahan &amp; Validasi (4 Pihak)</h3>
                                <p class="text-xs text-gray-500">Verifikasi digital dari 1 mahasiswa bersangkutan dan 3 pihak penanggung jawab.</p>
                            </div>

                            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                                {{-- 1. Mahasiswa --}}
                                <div class="flex flex-col justify-between rounded-xl border border-blue-200 bg-blue-50/50 p-4 text-center">
                                    <div>
                                        <span class="inline-flex items-center gap-1 rounded-full bg-blue-100 px-2 py-0.5 text-[11px] font-semibold text-blue-800">
                                            <i class="fas fa-user-graduate"></i> Peserta Magang
                                        </span>
                                        <div class="my-4 flex justify-center">
                                            <div class="rounded-lg border border-dashed border-blue-300 bg-white px-3 py-2 text-xs text-blue-700 shadow-sm">
                                                <i class="fas fa-check-circle text-emerald-600"></i> Terverifikasi Digital
                                                <div class="text-[10px] text-gray-500">{{ $rekap['pengesahan']['mahasiswa']['tanggal'] }}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <p class="font-bold text-gray-900">{{ $rekap['pengesahan']['mahasiswa']['nama'] }}</p>
                                        <p class="text-xs text-gray-500">NIM: {{ $rekap['pengesahan']['mahasiswa']['nomor'] }}</p>
                                    </div>
                                </div>

                                {{-- 2. Pembimbing Lapangan --}}
                                <div class="flex flex-col justify-between rounded-xl border border-emerald-200 bg-emerald-50/50 p-4 text-center">
                                    <div>
                                        <span class="inline-flex items-center gap-1 rounded-full bg-emerald-100 px-2 py-0.5 text-[11px] font-semibold text-emerald-800">
                                            <i class="fas fa-user-tie"></i> Pembimbing Lapangan
                                        </span>
                                        <div class="my-4 flex justify-center">
                                            @if ($laporanAkhir?->status_mengetahui)
                                                <div class="rounded-lg border border-emerald-300 bg-white px-3 py-2 text-xs text-emerald-700 shadow-sm">
                                                    <i class="fas fa-check-circle text-emerald-600"></i> ACC Mengetahui
                                                    <div class="text-[10px] text-gray-500">{{ $rekap['pengesahan']['pembimbing']['tanggal'] }}</div>
                                                </div>
                                            @else
                                                <div class="rounded-lg border border-dashed border-gray-300 bg-gray-50 px-3 py-2 text-xs text-gray-400">
                                                    <i class="fas fa-clock"></i> Menunggu ACC
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div>
                                        <p class="font-bold text-gray-900">{{ $rekap['pengesahan']['pembimbing']['nama'] }}</p>
                                        <p class="text-xs text-gray-500">NIP: {{ $rekap['pengesahan']['pembimbing']['nomor'] }}</p>
                                    </div>
                                </div>

                                {{-- 3. Dosen Pembimbing --}}
                                <div class="flex flex-col justify-between rounded-xl border border-indigo-200 bg-indigo-50/50 p-4 text-center">
                                    <div>
                                        <span class="inline-flex items-center gap-1 rounded-full bg-indigo-100 px-2 py-0.5 text-[11px] font-semibold text-indigo-800">
                                            <i class="fas fa-chalkboard-teacher"></i> Dosen Pembimbing
                                        </span>
                                        <div class="my-4 flex justify-center">
                                            @if ($laporanAkhir?->status_setuju || $statusBimbingan === 'Disetujui')
                                                <div class="rounded-lg border border-indigo-300 bg-white px-3 py-2 text-xs text-indigo-700 shadow-sm">
                                                    <i class="fas fa-signature text-indigo-600"></i> Tanda Tangan Digital
                                                    <div class="text-[10px] text-gray-500">{{ $rekap['pengesahan']['dosen']['tanggal'] }}</div>
                                                </div>
                                            @else
                                                <div class="rounded-lg border border-dashed border-gray-300 bg-gray-50 px-3 py-2 text-xs text-gray-400">
                                                    <i class="fas fa-clock"></i> Menunggu Persetujuan
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div>
                                        <p class="font-bold text-gray-900">{{ $rekap['pengesahan']['dosen']['nama'] }}</p>
                                        <p class="text-xs text-gray-500">NIDN / NIP: {{ $rekap['pengesahan']['dosen']['nomor'] }}</p>
                                    </div>
                                </div>

                                {{-- 4. Pihak Instansi / Pimpinan --}}
                                <div class="flex flex-col justify-between rounded-xl border border-amber-200 bg-amber-50/50 p-4 text-center">
                                    <div>
                                        <span class="inline-flex items-center gap-1 rounded-full bg-amber-100 px-2 py-0.5 text-[11px] font-semibold text-amber-800">
                                            <i class="fas fa-building"></i> Pihak Instansi
                                        </span>
                                        <div class="my-4 flex justify-center">
                                            @if ($statusBimbingan === 'Disetujui' || $laporanAkhir?->status_setuju)
                                                <div class="rounded-lg border border-amber-300 bg-white px-3 py-2 text-xs text-amber-700 shadow-sm">
                                                    <i class="fas fa-stamp text-amber-600"></i> Pengesahan Resmi
                                                    <div class="text-[10px] text-gray-500">{{ $rekap['pengesahan']['pimpinan']['tanggal'] }}</div>
                                                </div>
                                            @else
                                                <div class="rounded-lg border border-dashed border-gray-300 bg-gray-50 px-3 py-2 text-xs text-gray-400">
                                                    <i class="fas fa-clock"></i> Menunggu Pengesahan
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div>
                                        <p class="font-bold text-gray-900">{{ $rekap['pengesahan']['pimpinan']['nama'] }}</p>
                                        <p class="text-xs text-gray-500">{{ $rekap['pengesahan']['pimpinan']['nomor'] }}</p>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                </section>
            @endif
        </div>
    </div>
</x-app-layout>
