<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Laporan Akhir Mahasiswa (Read-Only)</h2>
    </x-slot>

    <div class="mx-auto max-w-7xl space-y-4">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <form method="GET" class="flex flex-wrap items-center gap-2">
                <input type="text" name="q" value="{{ $q }}" placeholder="Cari mahasiswa / NIM..."
                       class="rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">

                <select name="status" class="rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-slate-600 dark:bg-slate-800 dark:text-white dark:placeholder-slate-400">
                    <option value="">Semua Status</option>
                    @foreach (['Menunggu Review', 'Disetujui', 'Revisi'] as $st)
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
                    <a href="{{ route('auditor.laporan-akhir') }}" class="rounded-lg bg-slate-200 px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-300">
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
                            <th class="px-4 py-3 text-left">#</th>
                            <th class="px-4 py-3 text-left">Mahasiswa</th>
                            <th class="px-4 py-3 text-left">NIM</th>
                            <th class="px-4 py-3 text-left">Judul Laporan</th>
                            <th class="px-4 py-3 text-center">Status</th>
                            <th class="px-4 py-3 text-left">Diajukan</th>
                            <th class="px-4 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($laporanAkhirs as $i => $la)
                            <tr class="hover:bg-slate-50">
                                <td class="px-4 py-3 text-slate-400 whitespace-nowrap">{{ $i + 1 }}</td>
                                <td class="px-4 py-3 font-medium text-slate-900">{{ $la->mahasiswa?->name ?? '-' }}</td>
                                <td class="px-4 py-3 text-slate-500 whitespace-nowrap">{{ $la->mahasiswa?->nim_nip ?? '-' }}</td>
                                <td class="px-4 py-3 text-slate-700 max-w-xs truncate" title="{{ $la->judul_laporan }}">{{ $la->judul_laporan }}</td>
                                <td class="px-4 py-3 text-center whitespace-nowrap">
                                    @php
                                        $badge = match($la->status) {
                                            'Disetujui'       => 'bg-emerald-100 text-emerald-700',
                                            'Revisi'          => 'bg-amber-100 text-amber-700',
                                            'Menunggu Review' => 'bg-blue-100 text-blue-700',
                                            default           => 'bg-slate-100 text-slate-600',
                                        };
                                    @endphp
                                    <span class="rounded-full px-2 py-0.5 text-xs font-semibold {{ $badge }}">{{ $la->status }}</span>
                                </td>
                                <td class="px-4 py-3 text-slate-500 whitespace-nowrap">{{ $la->created_at->format('d/m/Y') }}</td>
                                <td class="px-4 py-3 text-center whitespace-nowrap">
                                    <div class="inline-flex items-center gap-1">
                                        <a href="{{ route('laporan-akhir.download-admin', $la) }}" target="_blank"
                                           class="inline-flex items-center gap-1 rounded bg-rose-50 px-2.5 py-1 text-xs font-medium text-rose-700 hover:bg-rose-100"
                                           title="Unduh Laporan Akhir & Lembar Pengesahan (PDF)">
                                            <i class="fa-solid fa-file-pdf"></i> Unduh PDF
                                        </a>
                                        @if ($la->mahasiswa)
                                            <a href="{{ route('laporan.mahasiswa.download-pdf', $la->mahasiswa) }}" target="_blank"
                                               class="inline-flex items-center gap-1 rounded bg-slate-100 px-2.5 py-1 text-xs font-medium text-slate-700 hover:bg-slate-200"
                                               title="Unduh Seluruh Arsip Laporan Kegiatan (PDF)">
                                                <i class="fa-solid fa-box-archive"></i> Logbook
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="px-4 py-8 text-center text-slate-400">Belum ada laporan akhir.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($laporanAkhirs->hasPages())
                <div class="border-t border-slate-100 px-4 py-3">{{ $laporanAkhirs->withQueryString()->links() }}</div>
            @endif
        </div>
    </div>
</x-app-layout>
