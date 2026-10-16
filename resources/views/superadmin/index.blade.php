<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard Super Admin</h2>
    </x-slot>

    <div class="mx-auto max-w-7xl space-y-6">
        <section>
            <p class="text-xs font-semibold uppercase tracking-wider text-rose-700">Kendali Penuh Sistem</p>
            <h1 class="mt-2 text-2xl font-bold text-slate-900">Halo, {{ auth()->user()->name }}</h1>
            <p class="mt-1 text-sm text-slate-500">Ringkasan seluruh data di sistem Magang / PKL.</p>
        </section>

        @php
            $kartu = [
                ['Total Akun', $totalUser, 'fa-users', 'bg-slate-100 text-slate-700'],
                ['Admin', $perRole['ADMIN'] ?? 0, 'fa-user-shield', 'bg-rose-50 text-rose-700'],
                ['Pembimbing Sekolah/Kampus', $perRole['DOSEN'] ?? 0, 'fa-chalkboard-teacher', 'bg-cyan-50 text-cyan-700'],
                ['Pembimbing Lapangan', $perRole['MENTOR'] ?? 0, 'fa-user-tie', 'bg-emerald-50 text-emerald-700'],
                ['Peserta Magang/PKL', $perRole['MAHASISWA'] ?? 0, 'fa-user-graduate', 'bg-blue-50 text-blue-700'],
                ['Kelompok', $totalKelompok, 'fa-layer-group', 'bg-indigo-50 text-indigo-700'],
                ['Instansi Pendidikan', $totalKampus, 'fa-university', 'bg-violet-50 text-violet-700'],
                ['Laporan Menunggu / Total', $laporanMenunggu.' / '.$totalLaporan, 'fa-clipboard-list', 'bg-amber-50 text-amber-700'],
            ];
        @endphp

        <section class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
            @foreach ($kartu as [$label, $nilai, $ikon, $warna])
                <article class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <p class="text-sm font-medium text-slate-500">{{ $label }}</p>
                            <p class="mt-3 text-3xl font-bold tabular-nums text-slate-900">{{ $nilai }}</p>
                        </div>
                        <span class="flex h-10 w-10 items-center justify-center rounded-lg {{ $warna }}">
                            <i class="fas {{ $ikon }}" aria-hidden="true"></i>
                        </span>
                    </div>
                </article>
            @endforeach
        </section>

        <section class="overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm">
            <header class="border-b border-slate-100 px-5 py-4">
                <h2 class="font-semibold text-slate-900">Akun Terbaru</h2>
            </header>
            <div class="overflow-x-auto">
                <table class="min-w-full text-left text-sm">
                    <thead class="bg-slate-50 text-xs uppercase text-slate-500">
                        <tr>
                            <th class="px-5 py-3">Nama</th>
                            <th class="px-5 py-3">Email</th>
                            <th class="px-5 py-3">Role</th>
                            <th class="px-5 py-3">Dibuat</th>
                            <th class="px-5 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($penggunaTerbaru as $u)
                            <tr>
                                <td class="px-5 py-3 font-semibold text-slate-900">{{ $u->name }}</td>
                                <td class="px-5 py-3 text-slate-600">{{ $u->email }}</td>
                                <td class="px-5 py-3">
                                    <span class="rounded-full bg-slate-100 px-2 py-0.5 text-xs font-semibold text-slate-700">{{ $u->role }}</span>
                                </td>
                                <td class="px-5 py-3 text-slate-500">{{ $u->created_at?->format('d M Y') }}</td>
                                <td class="px-5 py-3 text-right">
                                    @if ($u->role !== 'SUPERADMIN' && $u->id !== auth()->id())
                                        <form method="POST" action="{{ route('superadmin.users.destroy', $u) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun {{ $u->name }} ({{ $u->role }})? Semua data terkait juga akan dibersihkan dan tindakan ini tidak dapat dibatalkan.')" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center rounded-md border border-transparent bg-red-50 px-2.5 py-1 text-xs font-semibold text-red-600 transition hover:bg-red-100">
                                                <i class="fa-solid fa-trash mr-1"></i> Hapus
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-xs text-slate-400 italic">Terkunci</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="px-5 py-6 text-center text-slate-500">Belum ada akun.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</x-app-layout>