<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <h2 class="text-xl font-semibold leading-tight text-gray-800">Semua Laporan Kegiatan Magang / PKL</h2>
            <a href="{{ route('mahasiswa.dashboard') }}" class="text-sm font-medium text-blue-700 hover:underline">Kembali ke Laporan Mingguan</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl space-y-4 sm:px-6 lg:px-8">
            <div class="rounded-xl border border-gray-200 bg-white shadow-sm" style="max-width:100%;">
                <div class="border-b border-gray-100 px-6 py-5"
                     style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:12px;">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Riwayat Laporan Kegiatan</h3>
                        <p class="mt-1 text-sm text-gray-500">Seluruh laporan Magang / PKL yang telah Anda kirim.</p>
                    </div>

                    <a href="{{ route('mahasiswa.laporan.download-pdf') }}"
                            class="inline-flex items-center gap-2 rounded-md bg-blue-700 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-800">
                        <i class="fas fa-file-pdf" aria-hidden="true"></i>
                        Download Arsip PDF
                    </a>
                </div>

                @if ($laporans->isEmpty())
                    <p class="px-6 py-8 text-center text-sm text-gray-500">Belum ada laporan kegiatan.</p>
                @else
                    <div style="overflow-x:auto; max-width:100%;">
                        <table class="divide-y divide-gray-200 text-sm" style="width:100%; min-width:760px; table-layout:fixed;">
                            <colgroup>
                                <col style="width:130px;">
                                <col>
                                <col style="width:130px;">
                                <col style="width:200px;">
                                <col style="width:100px;">
                            </colgroup>
                            <thead class="bg-gray-50 text-xs font-semibold uppercase tracking-wider text-gray-600">
                                <tr>
                                    <th class="whitespace-nowrap px-4 py-3 text-left">Tanggal</th>
                                    <th class="px-4 py-3 text-left">Kegiatan</th>
                                    <th class="whitespace-nowrap px-4 py-3 text-left">Status</th>
                                    <th class="px-4 py-3 text-left">Catatan Pembimbing</th>
                                    <th class="whitespace-nowrap px-4 py-3 text-left">Foto</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach ($laporans as $laporan)
                                    <tr class="align-middle hover:bg-gray-50/70">
                                        <td class="whitespace-nowrap px-4 py-3 align-middle">{{ $laporan->tanggal->format('d M Y') }}</td>
                                        <td class="px-4 py-3 align-middle" style="overflow-wrap:anywhere; word-break:break-word;">
                                            <p class="font-medium text-gray-900">{{ $laporan->kegiatan }}</p>
                                            @if ($laporan->keterangan)
                                                <p class="mt-1 text-sm text-gray-500">{{ $laporan->keterangan }}</p>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 align-middle">{{ $laporan->status }}</td>
                                        <td class="px-4 py-3 align-middle" style="overflow-wrap:anywhere; word-break:break-word;">{{ $laporan->catatan_pembimbing ?? '-' }}</td>
                                        <td class="px-4 py-3 align-middle">
                                            @if ($laporan->foto_url)
                                                <a href="{{ $laporan->foto_url }}" target="_blank" rel="noopener noreferrer">
                                                    <img src="{{ $laporan->foto_url }}" alt="Foto kegiatan: {{ $laporan->kegiatan }}" class="h-14 w-14 rounded-md border border-gray-200 object-cover">
                                                </a>
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
            </div>
        </div>
    </div>
</x-app-layout>