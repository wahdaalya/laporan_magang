<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard Auditor</h2>
    </x-slot>

    <div class="mx-auto max-w-7xl space-y-6">
        <section>
            <p class="text-xs font-semibold uppercase tracking-wider text-amber-700">Mode Baca Saja (Read-Only)</p>
            <h1 class="mt-2 text-2xl font-bold text-slate-900">Halo, {{ auth()->user()->name }}</h1>
            <p class="mt-1 text-sm text-slate-500">Ringkasan seluruh data sistem Magang / PKL. Anda tidak memiliki izin untuk mengubah data.</p>
        </section>

        @php
            $kartu = [
                ['Total Akun', $totalUser, 'fa-users', 'bg-slate-100 text-slate-700'],
                ['Peserta Magang/PKL', $perRole['MAHASISWA'] ?? 0, 'fa-user-graduate', 'bg-blue-50 text-blue-700'],
                ['Total Laporan Harian', $totalLaporan, 'fa-file-alt', 'bg-indigo-50 text-indigo-700'],
                ['Laporan Menunggu Review', $laporanMenunggu, 'fa-clock', 'bg-amber-50 text-amber-700'],
                ['Laporan Disetujui', $laporanDisetujui, 'fa-check-circle', 'bg-emerald-50 text-emerald-700'],
                ['Total Laporan Akhir', $totalLaporanAkhir, 'fa-file-pdf', 'bg-rose-50 text-rose-700'],
                ['Kelompok', $totalKelompok, 'fa-layer-group', 'bg-cyan-50 text-cyan-700'],
            ];
        @endphp

        <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4">
            @foreach ($kartu as [$label, $nilai, $ikon, $warna])
                <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="flex items-center gap-3">
                        <span class="flex h-10 w-10 items-center justify-center rounded-lg {{ $warna }}">
                            <i class="fa-solid {{ $ikon }} text-lg"></i>
                        </span>
                        <div>
                            <p class="text-2xl font-bold text-slate-900">{{ number_format($nilai) }}</p>
                            <p class="text-xs text-slate-500">{{ $label }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="flex flex-wrap gap-3">
            <a href="{{ route('auditor.mahasiswa') }}"
               class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">
                <i class="fa-solid fa-user-graduate"></i> Daftar Mahasiswa
            </a>
            <a href="{{ route('auditor.laporan') }}"
               class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
                <i class="fa-solid fa-file-alt"></i> Rekap Laporan Harian
            </a>
            <a href="{{ route('auditor.laporan-akhir') }}"
               class="inline-flex items-center gap-2 rounded-lg bg-rose-600 px-4 py-2 text-sm font-medium text-white hover:bg-rose-700">
                <i class="fa-solid fa-file-pdf"></i> Laporan Akhir
            </a>
        </div>

        {{-- Pengguna Terbaru --}}
        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-100 px-6 py-4">
                <h2 class="font-semibold text-slate-900">Pengguna Terbaru</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-slate-50 text-xs font-semibold uppercase text-slate-500">
                        <tr>
                            <th class="px-4 py-3 text-left">Nama</th>
                            <th class="px-4 py-3 text-left">Email</th>
                            <th class="px-4 py-3 text-left">Role</th>
                            <th class="px-4 py-3 text-left">Bergabung</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($penggunaTerbaru as $u)
                            <tr class="hover:bg-slate-50">
                                <td class="px-4 py-3 font-medium text-slate-900">{{ $u->name }}</td>
                                <td class="px-4 py-3 text-slate-500">{{ $u->email }}</td>
                                <td class="px-4 py-3">
                                    <span class="rounded-full bg-slate-100 px-2 py-0.5 text-xs font-semibold text-slate-700">{{ $u->role }}</span>
                                </td>
                                <td class="px-4 py-3 text-slate-500">{{ $u->created_at->format('d/m/Y') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="px-4 py-6 text-center text-slate-400">Belum ada data.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
