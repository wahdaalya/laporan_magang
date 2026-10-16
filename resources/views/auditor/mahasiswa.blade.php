<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Daftar Mahasiswa (Read-Only)</h2>
    </x-slot>

    <div class="mx-auto max-w-7xl space-y-4">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <form method="GET" class="flex flex-wrap items-center gap-2">
                <input type="text" name="q" value="{{ $q }}" placeholder="Cari nama / NIM / email..."
                       class="rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">

                <select name="tahun" class="rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-slate-600 dark:bg-slate-800 dark:text-white dark:placeholder-slate-400">
                    <option value="">Semua Tahun Magang</option>
                    @foreach ($daftarTahun as $t)
                        <option value="{{ $t }}" @selected((int)$tahun === (int)$t)>Tahun {{ $t }}</option>
                    @endforeach
                </select>

                <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">
                    <i class="fa-solid fa-filter mr-1"></i> Filter
                </button>
                @if ($q || $tahun)
                    <a href="{{ route('auditor.mahasiswa') }}" class="rounded-lg bg-slate-200 px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-300">
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
                            <th class="px-4 py-3 text-left">Nama</th>
                            <th class="px-4 py-3 text-left">NIM</th>
                            <th class="px-4 py-3 text-left">Email</th>
                            <th class="px-4 py-3 text-left">Kelompok</th>
                            <th class="px-4 py-3 text-center">Jml Laporan</th>
                            <th class="px-4 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($mahasiswas as $i => $m)
                            <tr class="hover:bg-slate-50">
                                <td class="px-4 py-3 text-slate-400 whitespace-nowrap">{{ $mahasiswas->firstItem() + $i }}</td>
                                <td class="px-4 py-3 font-medium text-slate-900">{{ $m->name }}</td>
                                <td class="px-4 py-3 text-slate-500 whitespace-nowrap">{{ $m->nim_nip ?? '-' }}</td>
                                <td class="px-4 py-3 text-slate-500">{{ $m->email }}</td>
                                <td class="px-4 py-3 text-slate-500">{{ $m->kelompok?->nama_kelompok ?? '-' }}</td>
                                <td class="px-4 py-3 text-center whitespace-nowrap">
                                    <span class="rounded-full bg-blue-100 px-2 py-0.5 text-xs font-semibold text-blue-700">{{ $m->laporans_count }}</span>
                                </td>
                                <td class="px-4 py-3 text-center whitespace-nowrap">
                                    <a href="{{ route('laporan.mahasiswa.download-pdf', $m) }}" target="_blank"
                                       class="inline-flex items-center gap-1 rounded bg-rose-50 px-2.5 py-1 text-xs font-medium text-rose-700 hover:bg-rose-100"
                                       title="Unduh Arsip Lengkap Laporan (PDF)">
                                        <i class="fa-solid fa-file-pdf"></i> Arsip PDF
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="px-4 py-8 text-center text-slate-400">Tidak ada data mahasiswa.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($mahasiswas->hasPages())
                <div class="border-t border-slate-100 px-4 py-3">{{ $mahasiswas->withQueryString()->links() }}</div>
            @endif
        </div>
    </div>
</x-app-layout>
