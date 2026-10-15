<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Rekap Laporan Harian (Read-Only)</h2>
    </x-slot>

    <div class="mx-auto max-w-7xl space-y-4">
        <div class="flex justify-end">
            <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-800">
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
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($laporans as $l)
                            <tr class="hover:bg-slate-50">
                                <td class="px-4 py-3 text-slate-500">{{ $l->tanggal->format('d/m/Y') }}</td>
                                <td class="px-4 py-3 font-medium text-slate-900">{{ $l->mahasiswa?->name ?? '-' }}</td>
                                <td class="px-4 py-3 text-slate-500">{{ $l->mahasiswa?->nim_nip ?? '-' }}</td>
                                <td class="px-4 py-3 text-slate-700 max-w-xs truncate">{{ $l->kegiatan }}</td>
                                <td class="px-4 py-3 text-center">
                                    @php
                                        $badge = match($l->status) {
                                            'Disetujui'     => 'bg-emerald-100 text-emerald-700',
                                            'Revisi'        => 'bg-amber-100 text-amber-700',
                                            default         => 'bg-slate-100 text-slate-600',
                                        };
                                    @endphp
                                    <span class="rounded-full px-2 py-0.5 text-xs font-semibold {{ $badge }}">{{ $l->status }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="px-4 py-8 text-center text-slate-400">Belum ada laporan.</td></tr>
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
