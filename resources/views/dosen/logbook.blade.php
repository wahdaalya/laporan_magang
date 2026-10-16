<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <h1 class="text-xl font-semibold text-gray-800">Logbook Mahasiswa</h1>
            <a href="{{ route('dosen.dashboard') }}" class="text-sm font-medium text-blue-700 hover:underline">Kembali ke Beranda</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl space-y-5 sm:px-6 lg:px-8">
            <section class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                <h2 class="font-semibold text-gray-900">Logbook Terverifikasi Mentor</h2>
                <p class="mt-1 text-sm text-gray-500">Menampilkan kegiatan harian yang telah disetujui Pembimbing Lapangan.</p>
                <form method="GET" action="{{ route('dosen.logbook.index') }}" class="mt-4 grid gap-3 sm:grid-cols-[minmax(0,1fr)_12rem_auto_auto] sm:items-end">
                    <div>
                        <label for="nama" class="mb-1 block text-xs font-medium text-gray-600">Mahasiswa</label>
                        <select id="nama" name="nama" class="block w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-slate-600 dark:bg-slate-800 dark:text-white dark:placeholder-slate-400">
                            <option value="">Semua mahasiswa bimbingan</option>
                            @foreach ($pesertas as $peserta)
                                <option value="{{ $peserta->name }}" @selected(($filters['nama'] ?? '') === $peserta->name)>{{ $peserta->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="bulan" class="mb-1 block text-xs font-medium text-gray-600">Bulan</label>
                        <input id="bulan" name="bulan" type="month" value="{{ $filters['bulan'] ?? '' }}" class="block w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <button type="submit" class="rounded-md bg-slate-800 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-700">Terapkan</button>
                    <a href="{{ route('dosen.logbook.index') }}" class="rounded-md border border-gray-300 px-4 py-2 text-center text-sm font-medium text-gray-700 hover:bg-gray-50">Reset</a>
                </form>
            </section>

            <section class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
                @if ($logbooks->isEmpty())
                    <p class="px-6 py-12 text-center text-sm text-gray-500">Belum ada logbook yang disetujui untuk filter ini.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-50 text-xs font-semibold uppercase tracking-wide text-gray-600">
                                <tr>
                                    <th class="whitespace-nowrap px-4 py-3 text-left">Tanggal</th>
                                    <th class="px-4 py-3 text-left">Mahasiswa</th>
                                    <th class="px-4 py-3 text-left">Kegiatan</th>
                                    <th class="px-4 py-3 text-left">Keterangan</th>
                                    <th class="whitespace-nowrap px-4 py-3 text-center">Status Mentor</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach ($logbooks as $logbook)
                                    <tr class="align-top hover:bg-gray-50/70">
                                        <td class="whitespace-nowrap px-4 py-3 text-gray-600">{{ $logbook->tanggal->format('d M Y') }}</td>
                                        <td class="px-4 py-3 font-medium text-gray-900">{{ $logbook->mahasiswa->name }}</td>
                                        <td class="max-w-md whitespace-pre-line px-4 py-3 text-gray-800">{{ $logbook->kegiatan }}</td>
                                        <td class="max-w-sm whitespace-pre-line px-4 py-3 text-gray-600">{{ $logbook->keterangan ?: '-' }}</td>
                                        <td class="px-4 py-3 text-center"><span class="rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700">Disetujui</span></td>
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
