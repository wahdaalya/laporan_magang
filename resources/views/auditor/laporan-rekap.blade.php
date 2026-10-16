<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Rekap Laporan Harian (Read-Only)</h2>
    </x-slot>

    <div class="mx-auto max-w-7xl space-y-4">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <form method="GET" class="flex flex-wrap items-center gap-2">
                <input type="text" name="q" value="{{ $q }}" placeholder="Cari mahasiswa / NIM..."
                       class="rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">

                <select name="status" class="rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-slate-600 dark:bg-slate-800 dark:text-white dark:placeholder-slate-400">
                    <option value="">Semua Status</option>
                    @foreach (['Belum Diperiksa', 'Mengetahui', 'Disetujui', 'Revisi'] as $st)
                        <option value="{{ $st }}" @selected($status === $st)>{{ $st }}</option>
                    @endforeach
                </select>

                <select name="tahun" class="rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-slate-600 dark:bg-slate-800 dark:text-white dark:placeholder-slate-400">
                    <option value="">Semua Tahun Magang</option>
                    @foreach ($daftarTahun as $t)
                        <option value="{{ $t }}" @selected((int)$tahun === (int)$t)>Tahun {{ $t }}</option>
                    @endforeach
                </select>

                <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">
                    <i class="fa-solid fa-filter mr-1"></i> Filter
                </button>
                @if ($q || $status || $tahun)
                    <a href="{{ route('auditor.laporan') }}" class="rounded-lg bg-slate-200 px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-300">
                        Reset
                    </a>
                @endif
            </form>

            <span class="inline-flex self-start sm:self-auto rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-800">
                <i class="fa-solid fa-eye mr-1"></i> Mode Baca Saja
            </span>
        </div>

        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-slate-50 text-xs font-semibold uppercase text-slate-500">
                        <tr>
                            <th class="px-4 py-3 text-left">Tanggal</th>
                            <th class="px-4 py-3 text-left">Mahasiswa</th>
                            <th class="px-4 py-3 text-left">NIM</th>
                            <th class="px-4 py-3 text-left">Kegiatan</th>
                            <th class="px-4 py-3 text-center">Status</th>
                            <th class="px-4 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($laporans as $l)
                            <tr class="hover:bg-slate-50">
                                <td class="px-4 py-3 text-slate-500 whitespace-nowrap">{{ $l->tanggal->format('d/m/Y') }}</td>
                                <td class="px-4 py-3 font-medium text-slate-900">{{ $l->mahasiswa?->name ?? '-' }}</td>
                                <td class="px-4 py-3 text-slate-500 whitespace-nowrap">{{ $l->mahasiswa?->nim_nip ?? '-' }}</td>
                                <td class="px-4 py-3 text-slate-700 max-w-xs truncate">{{ $l->kegiatan }}</td>
                                <td class="px-4 py-3 text-center whitespace-nowrap">
                                    @php
                                        $badge = match($l->status) {
                                            'Disetujui'     => 'bg-emerald-100 text-emerald-700',
                                            'Mengetahui'    => 'bg-indigo-100 text-indigo-700',
                                            'Revisi'        => 'bg-amber-100 text-amber-700',
                                            default         => 'bg-slate-100 text-slate-600',
                                        };
                                    @endphp
                                    <span class="rounded-full px-2 py-0.5 text-xs font-semibold {{ $badge }}">{{ $l->status }}</span>
                                </td>
                                <td class="px-4 py-3 text-center whitespace-nowrap">
                                    @if ($l->mahasiswa)
                                        <a href="{{ route('laporan.mahasiswa.download-pdf', $l->mahasiswa) }}" target="_blank"
                                           class="inline-flex items-center gap-1 rounded bg-rose-50 px-2.5 py-1 text-xs font-medium text-rose-700 hover:bg-rose-100"
                                           title="Unduh Arsip Laporan Lengkap (PDF)">
                                            <i class="fa-solid fa-file-pdf"></i> Arsip PDF
                                        </a>
                                    @else
                                        <span class="text-xs text-slate-400">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="px-4 py-8 text-center text-slate-400">Belum ada laporan.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($laporans->hasPages())
                <div class="border-t border-slate-100 px-4 py-3">{{ $laporans->withQueryString()->links() }}</div>
            @endif
        </div>
    </div>
</x-app-layout>
