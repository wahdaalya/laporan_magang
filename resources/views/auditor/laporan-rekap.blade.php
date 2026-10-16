<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Rekap Laporan / Data Peserta (Read-Only)') }}
        </h2>
    </x-slot>

    <div
        class="mx-auto max-w-7xl space-y-4"
        x-data="{
            openModal: false,
            selected: {
                nama: '',
                nim: '',
                email: '',
                instansi: '',
                fakultas: '',
                prodi: '',
                periode: '',
                tanggal_mulai: '',
                tanggal_selesai: '',
                dospem: '',
                dospem_kontak: '',
                mentor: '',
                mentor_kontak: '',
                kelompok: '',
                departemen: ''
            },
            bukaDetail(data) {
                this.selected = data;
                this.openModal = true;
            }
        }"
        x-on:keydown.escape.window="openModal = false"
    >
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <form method="GET" class="flex flex-wrap items-center gap-2">
                <input
                    type="text"
                    name="q"
                    value="{{ $q }}"
                    placeholder="Cari nama mahasiswa atau NIM..."
                    class="rounded-lg border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-slate-600 dark:bg-slate-800 dark:text-white"
                >

                <select
                    name="tahun"
                    class="rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-slate-600 dark:bg-slate-800 dark:text-white"
                >
                    <option value="">Semua Tahun Magang</option>
                    @foreach ($daftarTahun as $t)
                        <option value="{{ $t }}" @selected((int)$tahun === (int)$t)>Tahun {{ $t }}</option>
                    @endforeach
                </select>

                <button type="submit" class="inline-flex items-center gap-1.5 rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700 transition">
                    <i class="fa-solid fa-filter text-xs"></i>
                    <span>Filter</span>
                </button>

                @if ($q || $tahun)
                    <a href="{{ route('auditor.laporan') }}" class="rounded-lg bg-slate-200 px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-300 transition">
                        Reset
                    </a>
                @endif
            </form>

            <span class="inline-flex self-start sm:self-auto items-center gap-1.5 rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-800">
                <i class="fa-solid fa-eye"></i> Mode Auditor (Read-Only)
            </span>
        </div>

        <div class="flex items-center justify-between text-xs text-slate-500 px-1">
            <p><i class="fa-solid fa-circle-info text-blue-500 mr-1"></i> Klik pada baris data untuk melihat informasi lengkap profil peserta magang.</p>
            <p>Total data: <span class="font-semibold text-slate-700">{{ $laporans->total() }}</span></p>
        </div>

        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-slate-50 text-xs font-semibold uppercase tracking-wider text-slate-500 dark:bg-slate-900/60 dark:text-slate-400">
                        <tr>
                            <th class="px-6 py-3.5 text-left">Tanggal</th>
                            <th class="px-6 py-3.5 text-left">Mahasiswa</th>
                            <th class="px-6 py-3.5 text-left">NIM</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                        @forelse ($laporans as $l)
                            @php
                                $mhs = $l->mahasiswa;
                                $klp = $mhs?->kelompok;
                                $instansi = $mhs?->instansi ?: ($mhs?->kampus ?: ($klp?->kampus?->nama_universitas ?? '-'));
                                $fakultas = $mhs?->fakultas ?: ($klp?->kampus?->fakultas ?? '-');
                                $prodi = $mhs?->prodi ?: ($klp?->kampus?->prodi ?? '-');
                                $tglMulai = $klp?->tanggal_mulai ? $klp->tanggal_mulai->format('d M Y') : '-';
                                $tglSelesai = $klp?->tanggal_selesai ? $klp->tanggal_selesai->format('d M Y') : '-';
                                $periode = ($tglMulai !== '-' || $tglSelesai !== '-') ? "{$tglMulai} s/d {$tglSelesai}" : '-';
                                $dospem = $klp?->dosen?->nama ?? '-';
                                $dospemKontak = $klp?->dosen?->email ? $klp->dosen->email . ($klp->dosen->hp_wa ? ' | ' . $klp->dosen->hp_wa : '') : ($klp?->dosen?->hp_wa ?? '-');
                                $mentor = $klp?->pembimbing?->nama ?? '-';
                                $mentorKontak = $klp?->pembimbing?->email ? $klp->pembimbing->email . ($klp->pembimbing->hp_wa ? ' | ' . $klp->pembimbing->hp_wa : '') : ($klp?->pembimbing?->hp_wa ?? '-');
                                $kelompokNama = $klp?->nama_kelompok ?? '-';
                                $departemenNama = $klp?->departemen?->nama_departemen ?? '-';

                                $detailData = [
                                    'nama' => $mhs?->name ?? '-',
                                    'nim' => $mhs?->nim_nip ?? '-',
                                    'email' => $mhs?->email ?? '-',
                                    'instansi' => $instansi,
                                    'fakultas' => $fakultas,
                                    'prodi' => $prodi,
                                    'periode' => $periode,
                                    'tanggal_mulai' => $tglMulai,
                                    'tanggal_selesai' => $tglSelesai,
                                    'dospem' => $dospem,
                                    'dospem_kontak' => $dospemKontak,
                                    'mentor' => $mentor,
                                    'mentor_kontak' => $mentorKontak,
                                    'kelompok' => $kelompokNama,
                                    'departemen' => $departemenNama,
                                ];
                            @endphp
                            <tr
                                x-on:click="bukaDetail(@js($detailData))"
                                class="cursor-pointer transition hover:bg-blue-50/70 dark:hover:bg-slate-700/60 group"
                                title="Klik untuk melihat detail profil {{ $mhs?->name }}"
                            >
                                <td class="px-6 py-4 text-slate-600 whitespace-nowrap text-sm dark:text-slate-300">
                                    <div class="flex items-center gap-2">
                                        <i class="fa-regular fa-calendar text-slate-400 group-hover:text-blue-500"></i>
                                        <span>{{ $l->tanggal ? $l->tanggal->format('d/m/Y') : '-' }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 font-semibold text-slate-900 dark:text-slate-100">
                                    <div class="flex items-center justify-between">
                                        <span>{{ $mhs?->name ?? '-' }}</span>
                                        <span class="text-xs font-normal text-blue-600 opacity-0 group-hover:opacity-100 transition inline-flex items-center gap-1">
                                            <span>Lihat Profil</span>
                                            <i class="fa-solid fa-chevron-right text-[10px]"></i>
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-slate-600 whitespace-nowrap font-mono text-xs dark:text-slate-300">
                                    {{ $mhs?->nim_nip ?? '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-12 text-center text-slate-400">
                                    <i class="fa-solid fa-folder-open text-3xl mb-2 text-slate-300"></i>
                                    <p>Tidak ada data laporan atau peserta yang sesuai filter.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($laporans->hasPages())
                <div class="border-t border-slate-100 px-6 py-4 dark:border-slate-700">
                    {{ $laporans->withQueryString()->links() }}
                </div>
            @endif
        </div>

        {{-- Modal Detail Informasi Lengkap Peserta Magang --}}
        <div
            x-show="openModal"
            class="fixed inset-0 z-50 overflow-y-auto"
            style="display: none;"
            x-cloak
        >
            <!-- Backdrop -->
            <div
                x-show="openModal"
                x-transition:enter="ease-out duration-200"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-150"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity"
                x-on:click="openModal = false"
            ></div>

            <!-- Modal Panel Container -->
            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-6">
                <div
                    x-show="openModal"
                    x-transition:enter="ease-out duration-200"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-150"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    class="relative w-full max-w-2xl transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all dark:bg-slate-800"
                    x-on:click.stop
                >
                    <!-- Header Modal -->
                    <div class="border-b border-slate-100 bg-slate-50/70 px-6 py-4 dark:border-slate-700 dark:bg-slate-800/80">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-100 text-blue-700 shadow-sm dark:bg-blue-900/50 dark:text-blue-300">
                                    <i class="fa-solid fa-id-card text-xl"></i>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-slate-900 dark:text-slate-100">
                                        Data Profil Peserta Magang
                                    </h3>
                                    <p class="text-xs text-slate-500 dark:text-slate-400">
                                        Verifikasi data peserta magang / PKL (Mode Auditor Read-Only)
                                    </p>
                                </div>
                            </div>
                            <button
                                type="button"
                                x-on:click="openModal = false"
                                class="rounded-lg p-1.5 text-slate-400 hover:bg-slate-200/60 hover:text-slate-600 focus:outline-none dark:hover:bg-slate-700 dark:hover:text-slate-200"
                            >
                                <i class="fa-solid fa-xmark text-lg"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Body Modal: Hanya Data Profil Peserta -->
                    <div class="space-y-4 p-6 text-sm text-slate-700 dark:text-slate-200">
                        <!-- Card 1: Mahasiswa & NIM -->
                        <div class="rounded-xl border border-slate-200 bg-slate-50/50 p-4 dark:border-slate-700 dark:bg-slate-700/30">
                            <div class="flex items-center gap-2 font-semibold text-blue-700 dark:text-blue-400 mb-3">
                                <i class="fa-solid fa-user-graduate"></i>
                                <span>Identitas Mahasiswa</span>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs sm:text-sm">
                                <div>
                                    <span class="block text-xs text-slate-400 font-medium">Nama Mahasiswa</span>
                                    <p class="font-bold text-slate-900 dark:text-slate-100 text-base" x-text="selected.nama"></p>
                                </div>
                                <div>
                                    <span class="block text-xs text-slate-400 font-medium">NIM / NISN</span>
                                    <p class="font-mono font-semibold text-slate-800 dark:text-slate-200" x-text="selected.nim"></p>
                                </div>
                                <div class="sm:col-span-2">
                                    <span class="block text-xs text-slate-400 font-medium">Email Akun</span>
                                    <p class="text-slate-600 dark:text-slate-300" x-text="selected.email"></p>
                                </div>
                            </div>
                        </div>

                        <!-- Card 2: Instansi Pendidikan & Prodi -->
                        <div class="rounded-xl border border-slate-200 bg-slate-50/50 p-4 dark:border-slate-700 dark:bg-slate-700/30">
                            <div class="flex items-center gap-2 font-semibold text-indigo-700 dark:text-indigo-400 mb-3">
                                <i class="fa-solid fa-university"></i>
                                <span>Asal Instansi &amp; Akademik</span>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs sm:text-sm">
                                <div class="sm:col-span-2">
                                    <span class="block text-xs text-slate-400 font-medium">Instansi / Sekolah / Kampus</span>
                                    <p class="font-semibold text-slate-900 dark:text-slate-100" x-text="selected.instansi"></p>
                                </div>
                                <div>
                                    <span class="block text-xs text-slate-400 font-medium">Fakultas</span>
                                    <p class="text-slate-800 dark:text-slate-200" x-text="selected.fakultas"></p>
                                </div>
                                <div>
                                    <span class="block text-xs text-slate-400 font-medium">Program Studi / Jurusan</span>
                                    <p class="text-slate-800 dark:text-slate-200" x-text="selected.prodi"></p>
                                </div>
                            </div>
                        </div>

                        <!-- Card 3: Periode Magang & Penempatan -->
                        <div class="rounded-xl border border-slate-200 bg-slate-50/50 p-4 dark:border-slate-700 dark:bg-slate-700/30">
                            <div class="flex items-center gap-2 font-semibold text-emerald-700 dark:text-emerald-400 mb-3">
                                <i class="fa-solid fa-calendar-check"></i>
                                <span>Periode &amp; Penempatan Magang</span>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs sm:text-sm">
                                <div>
                                    <span class="block text-xs text-slate-400 font-medium">Tanggal Masuk (Mulai)</span>
                                    <p class="font-medium text-slate-800 dark:text-slate-200" x-text="selected.tanggal_mulai"></p>
                                </div>
                                <div>
                                    <span class="block text-xs text-slate-400 font-medium">Tanggal Selesai (Akhir)</span>
                                    <p class="font-medium text-slate-800 dark:text-slate-200" x-text="selected.tanggal_selesai"></p>
                                </div>
                                <div>
                                    <span class="block text-xs text-slate-400 font-medium">Kelompok Magang</span>
                                    <p class="text-slate-800 dark:text-slate-200" x-text="selected.kelompok"></p>
                                </div>
                                <div>
                                    <span class="block text-xs text-slate-400 font-medium">Departemen / Unit Penempatan</span>
                                    <p class="text-slate-800 dark:text-slate-200" x-text="selected.departemen"></p>
                                </div>
                            </div>
                        </div>

                        <!-- Card 4: Dosen Pembimbing & Pembimbing Lapangan -->
                        <div class="rounded-xl border border-slate-200 bg-slate-50/50 p-4 dark:border-slate-700 dark:bg-slate-700/30">
                            <div class="flex items-center gap-2 font-semibold text-violet-700 dark:text-violet-400 mb-3">
                                <i class="fa-solid fa-user-tie"></i>
                                <span>Pembimbing</span>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs sm:text-sm">
                                <div>
                                    <span class="block text-xs text-slate-400 font-medium">Dosen Pembimbing (Dospem)</span>
                                    <p class="font-semibold text-slate-900 dark:text-slate-100" x-text="selected.dospem"></p>
                                    <p class="text-xs text-slate-500 mt-0.5" x-text="selected.dospem_kontak"></p>
                                </div>
                                <div>
                                    <span class="block text-xs text-slate-400 font-medium">Pembimbing Lapangan (Mentor)</span>
                                    <p class="font-semibold text-slate-900 dark:text-slate-100" x-text="selected.mentor"></p>
                                    <p class="text-xs text-slate-500 mt-0.5" x-text="selected.mentor_kontak"></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer Modal -->
                    <div class="flex items-center justify-end border-t border-slate-100 bg-slate-50/70 px-6 py-3.5 dark:border-slate-700 dark:bg-slate-800/80">
                        <button
                            type="button"
                            x-on:click="openModal = false"
                            class="inline-flex items-center gap-1.5 rounded-lg border border-slate-300 bg-white px-4 py-2 text-xs font-semibold text-slate-700 shadow-sm hover:bg-slate-100 focus:outline-none transition dark:border-slate-600 dark:bg-slate-700 dark:text-slate-200 dark:hover:bg-slate-600"
                        >
                            <i class="fa-solid fa-times"></i>
                            <span>Tutup</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
