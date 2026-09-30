<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Peserta Magang / PKL') }}
        </h2>
    </x-slot>

    <div class="py-12"
        x-data="{
            editId: @js(old('_id') ?: null),
            showErrors: @js($errors->any()),
            currentFoto: null,
            f: {
                tanggal: @js(old('tanggal', '')),
                kegiatan: @js(old('kegiatan', '')),
                keterangan: @js(old('keterangan', '')),
            },
            get action() {
                return this.editId ? '{{ url('laporan') }}/' + this.editId : '{{ route('laporan.store') }}';
            },
            tambah() {
                this.editId = null;
                this.showErrors = false;
                this.currentFoto = null;
                this.f = { tanggal: '', kegiatan: '', keterangan: '' };
            },
            ubah(d) {
                this.editId = d.id;
                this.showErrors = false;
                this.currentFoto = d.foto_url ?? null;
                this.f = { tanggal: d.tanggal ?? '', kegiatan: d.kegiatan ?? '', keterangan: d.keterangan ?? '' };
            },
        }"
    >
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('status'))
                <div class="mb-4 rounded-lg bg-green-50 px-4 py-3 text-sm text-green-700">
                    {{ session('status') }}
                </div>
            @endif

            <div class="mb-8 overflow-hidden rounded-2xl bg-gradient-to-r from-blue-700 via-blue-700 to-indigo-800 text-white shadow-lg">
                <div class="grid gap-6 p-6 sm:p-8 lg:grid-cols-[minmax(0,1fr)_17rem] lg:items-center">
                    <div class="min-w-0">
                        <p class="text-sm font-semibold text-blue-100">Informasi Magang / PKL</p>
                        <h3 class="mt-2 text-2xl font-bold leading-tight sm:text-3xl">
                            Selamat Datang Kembali, {{ $user->name }}! <span aria-hidden="true">👋</span>
                        </h3>
                        <p class="mt-3 text-sm text-blue-100 sm:text-base">Tetap semangat mencatat kegiatan harianmu hari ini!</p>
                    </div>

                    <div class="rounded-xl border border-white/20 bg-white/10 p-5 backdrop-blur-sm">
                        @if ($user->kelompok)
                            <p class="text-2xl font-bold leading-tight sm:text-3xl">Sisa {{ $sisaHari }} Hari Lagi</p>
                            <p class="mt-1 text-sm text-blue-100">Masa Magang / PKL Berakhir</p>
                            <div class="mt-5 flex items-end justify-between gap-3">
                                <span class="text-sm text-blue-100">Progress durasi</span>
                                <span class="text-lg font-bold">{{ $progressMagang }}%</span>
                            </div>
                            <progress class="mt-2 h-2 w-full overflow-hidden rounded-full accent-emerald-300" aria-label="Progress durasi Magang / PKL" value="{{ $progressMagang }}" max="100">{{ $progressMagang }}%</progress>
                            <p class="mt-2 text-xs text-blue-100">Dari total durasi {{ $durasiTotal }} hari</p>
                        @else
                            <p class="text-xl font-bold">Periode belum tersedia</p>
                            <p class="mt-2 text-sm text-blue-100">Sisa hari dan progress tampil setelah kelompok Magang / PKL ditetapkan.</p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-4 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h3 class="font-semibold text-lg">Laporan Mingguan</h3>
                            <p class="mt-1 text-sm text-gray-500">
                                {{ $selectedRange['start']->format('d M Y') }} - {{ $selectedRange['end']->format('d M Y') }}
                            </p>
                        </div>
                        <form method="GET" action="{{ route('mahasiswa.dashboard') }}" class="flex items-end gap-2">
                            <div>
                                <label for="week" class="mb-1 block text-xs font-medium text-gray-600">Pilih Minggu</label>
                                <select id="week" name="week" class="rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @foreach ($weekOptions as $weekNumber => $week)
                                        <option value="{{ $weekNumber }}" @selected($selectedWeek === $weekNumber)>
                                            Minggu {{ $weekNumber }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="inline-flex items-center rounded-md bg-gray-800 px-4 py-2 text-xs font-semibold uppercase tracking-wider text-white transition hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                Tampilkan
                            </button>
                        </form>
                    </div>

                    <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
                        <h4 class="text-sm font-semibold text-gray-700">Riwayat Laporan Kegiatan</h4>
                        <button type="button" x-on:click="tambah(); $dispatch('open-modal', 'form-laporan')" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-3 py-1.5 text-sm font-medium text-white hover:bg-blue-700">
                            <i class="fas fa-plus" aria-hidden="true"></i> Tambah Laporan Kegiatan
                        </button>
                    </div>

                    @if ($laporans->isEmpty())
                        <p class="text-gray-500">Belum ada laporan kegiatan pada minggu ini.</p>
                    @else
                        <div class="w-full overflow-hidden rounded-xl border border-gray-200">
                            <table class="w-full table-fixed text-xs sm:text-sm">
                                <thead class="bg-gray-50 text-gray-600">
                                    <tr class="align-middle">
                                        <th class="w-[10%] whitespace-nowrap px-0 py-2 text-center text-[9px] font-semibold uppercase tracking-normal sm:px-2 sm:py-3 sm:text-xs sm:tracking-wider">Foto</th>
                                        <th class="w-[15%] whitespace-nowrap px-0 py-2 text-center text-[9px] font-semibold uppercase tracking-normal sm:px-2 sm:py-3 sm:text-xs sm:tracking-wider">Tanggal</th>
                                        <th class="w-[40%] whitespace-nowrap px-1 py-2 text-start text-[9px] font-semibold uppercase tracking-normal sm:px-2 sm:py-3 sm:text-xs sm:tracking-wider">Kegiatan</th>
                                        <th class="w-[15%] whitespace-nowrap px-0 py-2 text-center text-[9px] font-semibold uppercase tracking-normal sm:px-2 sm:py-3 sm:text-xs sm:tracking-wider">Status</th>
                                        <th aria-label="Catatan Pembimbing" title="Catatan Pembimbing" class="w-[10%] whitespace-nowrap px-0 py-2 text-center text-[9px] font-semibold uppercase tracking-normal sm:px-1 sm:py-3 sm:text-xs sm:tracking-wider 2xl:px-0 2xl:text-[9px] 2xl:tracking-normal">
                                            <span class="hidden 2xl:inline">Catatan Pembimbing</span>
                                            <span class="hidden md:inline 2xl:hidden">Catatan</span>
                                            <span class="md:hidden">Cat.</span>
                                        </th>
                                        <th class="w-[10%] whitespace-nowrap px-0 py-2 text-center text-[9px] font-semibold uppercase tracking-normal sm:px-2 sm:py-3 sm:text-xs sm:tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach ($laporans as $laporan)
                                        <tr class="align-middle transition-colors hover:bg-gray-50/50">
                                            <td class="px-0 py-2 text-center align-middle sm:px-2 sm:py-3">
                                                @if ($laporan->foto_url)
                                                    <a href="{{ $laporan->foto_url }}" target="_blank" rel="noopener noreferrer" class="mx-auto block w-full max-w-12">
                                                        <img src="{{ $laporan->foto_url }}" alt="Foto kegiatan: {{ $laporan->kegiatan }}" class="aspect-square w-full rounded-md border border-gray-200 object-cover">
                                                    </a>
                                                @else
                                                    <span class="text-gray-300">-</span>
                                                @endif
                                            </td>
                                            <td class="break-words px-0 py-2 text-center align-middle sm:px-2 sm:py-3">{{ $laporan->tanggal->format('d M Y') }}</td>
                                            <td class="break-words px-1 py-2 text-start align-middle sm:px-2 sm:py-3">
                                                <p class="break-words font-semibold text-gray-900">{{ $laporan->kegiatan }}</p>
                                                @if ($laporan->keterangan)
                                                    <p class="break-words text-sm text-gray-500">{{ Str::limit($laporan->keterangan, 100) }}</p>
                                                @endif
                                            </td>
                                            <td class="break-words px-0 py-2 text-center align-middle sm:px-2 sm:py-3">
                                                <span @class(['inline-block max-w-full break-words rounded-full px-1 py-1 text-center text-[10px] font-medium sm:px-2 sm:text-xs',
                                                    'bg-green-100 text-green-800' => $laporan->status === 'Disetujui',
                                                    'bg-red-100 text-red-800' => in_array($laporan->status, ['Ditolak', 'Revisi']),
                                                    'bg-amber-100 text-amber-800' => $laporan->status === 'Belum Diperiksa',
                                                ])>{{ $laporan->status }}</span>
                                            </td>
                                            <td class="break-words px-0 py-2 text-center align-middle sm:px-2 sm:py-3">{{ $laporan->catatan_pembimbing ?? '-' }}</td>
                                            <td class="break-words px-0 py-2 text-center align-middle sm:px-2 sm:py-3">
                                                @if ($laporan->status === 'Belum Diperiksa')
                                                    <div class="flex flex-col items-center justify-center gap-1 lg:flex-row lg:flex-wrap lg:gap-2">
                                                    <button type="button"
                                                        x-on:click="ubah(@js($laporan->only(['id', 'tanggal', 'kegiatan', 'keterangan']) + ['foto_url' => $laporan->foto_url])); $dispatch('open-modal', 'form-laporan')"
                                                        aria-label="Ubah laporan" title="Ubah laporan"
                                                        class="inline-flex items-center justify-center gap-1 rounded px-1 py-1 text-blue-600 hover:bg-blue-50 hover:underline">
                                                        <i class="fas fa-pen sm:hidden" aria-hidden="true"></i>
                                                        <span class="sr-only sm:not-sr-only">Ubah</span>
                                                    </button>
                                                    <form action="{{ route('laporan.destroy', $laporan) }}" method="POST" class="inline"
                                                        onsubmit="return confirm('Hapus kegiatan ini?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" aria-label="Hapus laporan" title="Hapus laporan" class="inline-flex items-center justify-center gap-1 rounded px-1 py-1 text-red-600 hover:bg-red-50 hover:underline">
                                                            <i class="fas fa-trash sm:hidden" aria-hidden="true"></i>
                                                            <span class="sr-only sm:not-sr-only">Hapus</span>
                                                        </button>
                                                    </form>
                                                    </div>
                                                @else
                                                    <span class="text-gray-400">-</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif

                    <div class="mt-5 flex justify-end border-t border-gray-100 pt-4">
                        <a href="{{ route('mahasiswa.laporan.index') }}" class="inline-flex items-center gap-2 rounded-md border border-blue-200 bg-white px-4 py-2 text-sm font-semibold text-blue-700 hover:border-blue-300 hover:bg-blue-50">
                            Lihat Semua Laporan <i class="fas fa-arrow-right" aria-hidden="true"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        @include('mahasiswa._modal')
    </div>
</x-app-layout>