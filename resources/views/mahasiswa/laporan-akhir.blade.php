<x-app-layout>
    <x-slot name="header">
        <h1 class="text-xl font-semibold text-gray-800">Laporan Akhir Magang / PKL</h1>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
            @if ($rekap['ringkasan']['belum_disetujui'] > 0)
                <div class="rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-900" role="status">
                    Ada {{ $rekap['ringkasan']['belum_disetujui'] }} laporan yang belum disetujui dan belum masuk rekap.
                    <a href="{{ route('mahasiswa.laporan.index') }}" class="ml-1 font-semibold underline hover:text-amber-950">Lihat Riwayat Laporan</a>
                </div>
            @endif

            @if (! $rekap['siap'])
                <section class="rounded-xl border border-blue-200 bg-blue-50 p-6 text-sm text-blue-900" role="status">
                    Belum ada laporan harian yang disetujui. Laporan akhir akan muncul otomatis setelah laporan harianmu disetujui pembimbing.
                </section>
            @else
                <section class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
                    <header class="flex flex-wrap items-center justify-between gap-4 border-b border-gray-200 px-5 py-5 sm:px-6">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wider text-blue-700">Pratinjau Rekap</p>
                            <h2 class="mt-1 text-xl font-bold text-gray-900">Laporan Akhir Magang / PKL</h2>
                            <p class="mt-1 text-sm text-gray-500">Dihasilkan otomatis dari laporan harian yang disetujui.</p>
                        </div>
                        <a href="{{ route('mahasiswa.laporan-akhir.cetak') }}" class="inline-flex items-center gap-2 rounded-md bg-blue-700 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-800">
                            <i class="fas fa-file-pdf" aria-hidden="true"></i> Unduh Laporan Akhir (PDF)
                        </a>
                    </header>

                    <div class="space-y-6 p-5 sm:p-6">
                        <section>
                            <h3 class="text-sm font-bold uppercase tracking-wide text-gray-700">Identitas Peserta</h3>
                            <dl class="mt-3 grid gap-x-8 gap-y-4 sm:grid-cols-2">
                                <div><dt class="text-xs font-medium uppercase text-gray-500">Nama</dt><dd class="mt-1 font-semibold text-gray-900">{{ $rekap['identitas']['nama'] }}</dd></div>
                                <div><dt class="text-xs font-medium uppercase text-gray-500">NIM / NISN</dt><dd class="mt-1 font-semibold text-gray-900">{{ $rekap['identitas']['nim'] }}</dd></div>
                                <div><dt class="text-xs font-medium uppercase text-gray-500">Kampus / Sekolah</dt><dd class="mt-1 font-semibold text-gray-900">{{ $rekap['identitas']['kampus'] }}</dd></div>
                                <div><dt class="text-xs font-medium uppercase text-gray-500">Fakultas</dt><dd class="mt-1 font-semibold text-gray-900">{{ $rekap['identitas']['fakultas'] }}</dd></div>
                                <div><dt class="text-xs font-medium uppercase text-gray-500">Prodi / Jurusan</dt><dd class="mt-1 font-semibold text-gray-900">{{ $rekap['identitas']['prodi'] }}</dd></div>
                                <div><dt class="text-xs font-medium uppercase text-gray-500">Departemen Penempatan</dt><dd class="mt-1 font-semibold text-gray-900">{{ $rekap['identitas']['departemen'] }}</dd></div>
                                <div><dt class="text-xs font-medium uppercase text-gray-500">Periode Magang</dt><dd class="mt-1 font-semibold text-gray-900">{{ $rekap['identitas']['tanggal_mulai'] }} - {{ $rekap['identitas']['tanggal_selesai'] }}</dd></div>
                                <div><dt class="text-xs font-medium uppercase text-gray-500">Dosen / Guru Pembimbing</dt><dd class="mt-1 font-semibold text-gray-900">{{ $rekap['identitas']['dosen']['nama'] }} <span class="font-normal text-gray-500">(NIDN: {{ $rekap['identitas']['dosen']['nidn'] }})</span></dd></div>
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

                        <section class="grid gap-8 border-t border-gray-300 pt-6 sm:grid-cols-2">
                            <div class="text-center"><p class="font-semibold text-gray-900">Dosen / Guru Pembimbing</p><div class="h-24"></div><p class="font-semibold underline">{{ $rekap['identitas']['dosen']['nama'] }}</p></div>
                            <div class="text-center"><p class="font-semibold text-gray-900">Pembimbing Lapangan</p><div class="h-24"></div><p class="font-semibold underline">{{ $rekap['identitas']['pembimbing']['nama'] }}</p></div>
                        </section>
                    </div>
                </section>
            @endif
        </div>
    </div>
</x-app-layout>
