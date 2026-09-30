@php
    $nidn = $dosenProfile?->nidn ?: ($user->nim_nip ?: '-');
    $kampus = $dosenProfile?->kampus ?: ($user->kampus ?: ($user->instansi ?: '-'));
    $emailDosen = $dosenProfile?->email ?: $user->email;
    $kontakDosen = $dosenProfile?->hp_wa ?: ($user->hp_wa ?: '-');
    $whatsappDosenUrl = \App\Support\ContactLink::whatsappUrl($kontakDosen);
    $pesertaList = $pesertaBimbingan ?? collect();
    $kelompokList = $kelompokBimbingan ?? collect();
@endphp

<div class="space-y-6">
    {{-- Grid 2 Kolom: Info Akademik Dosen & Ringkasan Penugasan --}}
    <div class="grid gap-6 lg:grid-cols-2">
        {{-- Card 1: Identitas Akademik Dosen --}}
        <section class="overflow-hidden rounded-2xl border border-gray-200/80 bg-white shadow-sm transition hover:shadow-md">
            <header class="flex items-center justify-between border-b border-gray-100 bg-gradient-to-r from-indigo-50/60 to-slate-50 px-5 py-4 sm:px-6">
                <div class="flex items-center gap-3">
                    <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-indigo-600 text-white shadow-sm">
                        <i class="fas fa-chalkboard-teacher text-sm"></i>
                    </span>
                    <div>
                        <h3 class="font-bold text-gray-900">Identitas Dosen / Guru Pembimbing</h3>
                        <p class="text-xs text-gray-500">Profil akademisi dari perguruan tinggi / sekolah mitra</p>
                    </div>
                </div>
                <span class="inline-flex items-center rounded-full border border-indigo-200 bg-indigo-50 px-2.5 py-0.5 text-xs font-semibold text-indigo-700">
                    Pembimbing Akademik
                </span>
            </header>

            <div class="p-5 sm:p-6">
                <dl class="grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-2">
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wider text-gray-400">NIDN / NIP</dt>
                        <dd class="mt-1 font-mono text-sm font-bold text-gray-900">{{ $nidn }}</dd>
                    </div>

                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wider text-gray-400">Jabatan Fungsional / Peran</dt>
                        <dd class="mt-1 text-sm font-semibold text-gray-900">Dosen / Guru Pembimbing</dd>
                    </div>

                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wider text-gray-400">Email</dt>
                        <dd class="mt-1 break-all text-sm font-medium text-gray-900">
                            @if ($emailDosen)
                                <a href="mailto:{{ $emailDosen }}" class="hover:text-indigo-700 hover:underline">{{ $emailDosen }}</a>
                            @else
                                -
                            @endif
                        </dd>
                    </div>

                    <div class="sm:col-span-2">
                        <dt class="text-xs font-semibold uppercase tracking-wider text-gray-400">Asal Kampus / Sekolah</dt>
                        <dd class="mt-1 text-sm font-semibold text-indigo-900">{{ $kampus }}</dd>
                    </div>

                    <div class="sm:col-span-2">
                        <dt class="text-xs font-semibold uppercase tracking-wider text-gray-400">Kontak WhatsApp</dt>
                        <dd class="mt-1 text-sm font-medium text-gray-900">
                            @if ($whatsappDosenUrl)
                                <a href="{{ $whatsappDosenUrl }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1.5 text-emerald-600 hover:text-emerald-700 hover:underline">
                                    <i class="fab fa-whatsapp"></i> {{ $kontakDosen }}
                                </a>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </dd>
                    </div>
                </dl>
            </div>
        </section>

        {{-- Card 2: Tanggung Jawab & Ruang Lingkup Bimbingan --}}
        <section class="overflow-hidden rounded-2xl border border-gray-200/80 bg-white shadow-sm transition hover:shadow-md">
            <header class="flex items-center justify-between border-b border-gray-100 bg-gradient-to-r from-slate-50 to-blue-50/50 px-5 py-4 sm:px-6">
                <div class="flex items-center gap-3">
                    <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-slate-800 text-white shadow-sm">
                        <i class="fas fa-briefcase text-sm"></i>
                    </span>
                    <div>
                        <h3 class="font-bold text-gray-900">Penugasan Pembimbingan</h3>
                        <p class="text-xs text-gray-500">Kerja sama kemitraan magang industri</p>
                    </div>
                </div>
                <span class="inline-flex items-center rounded-full bg-blue-50 px-2.5 py-0.5 text-xs font-semibold text-blue-700 border border-blue-200">
                    {{ $totalBimbingan ?? $pesertaList->count() }} Mahasiswa
                </span>
            </header>

            <div class="p-5 sm:p-6 space-y-4">
                <dl class="grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-2">
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wider text-gray-400">Instansi Penempatan</dt>
                        <dd class="mt-1 text-sm font-semibold text-gray-900">PT Angkasa Pura Aviasi</dd>
                    </div>

                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wider text-gray-400">Lokasi Kerja Praktik</dt>
                        <dd class="mt-1 text-sm font-medium text-gray-800">Bandara Internasional Kualanamu (KNO)</dd>
                    </div>

                    <div class="sm:col-span-2">
                        <dt class="text-xs font-semibold uppercase tracking-wider text-gray-400">Kewenangan Sistem</dt>
                        <dd class="mt-1 text-xs text-gray-600 leading-relaxed">
                            Memantau logbook harian mahasiswa, mereview draf laporan akhir magang, berkoordinasi dengan mentor lapangan PT Angkasa Pura Aviasi, serta memberikan penilaian akademik akhir.
                        </dd>
                    </div>
                </dl>

                <div class="rounded-xl border border-blue-100 bg-blue-50/50 p-4">
                    <div class="flex flex-wrap items-baseline justify-between gap-2">
                        <h4 class="text-xs font-bold uppercase tracking-wider text-blue-900">Kelompok Bimbingan</h4>
                        <span class="text-xs font-semibold text-blue-700">{{ $totalKelompokBimbingan ?? $kelompokList->count() }} kelompok · {{ $totalBimbingan ?? $pesertaList->count() }} mahasiswa</span>
                    </div>
                    @if ($kelompokList->isNotEmpty())
                        <ul class="mt-3 grid gap-2 sm:grid-cols-2">
                            @foreach ($kelompokList as $kelompokBimbinganItem)
                                <li class="rounded-md border border-blue-100 bg-white px-3 py-2 text-sm font-semibold text-gray-800">
                                    {{ $kelompokBimbinganItem->nama_kelompok ?: '-' }}
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="mt-2 text-sm text-gray-500">-</p>
                    @endif
                </div>

                <div class="grid grid-cols-2 gap-3 pt-2">
                    <a href="{{ route('dosen.dashboard') }}" class="flex items-center justify-center gap-2 rounded-xl bg-blue-50 px-4 py-2.5 text-xs font-bold text-blue-700 hover:bg-blue-100 transition">
                        <i class="fas fa-home"></i> Beranda Dosen
                    </a>
                    <a href="{{ route('dosen.laporan-akhir.index') }}" class="flex items-center justify-center gap-2 rounded-xl bg-indigo-50 px-4 py-2.5 text-xs font-bold text-indigo-700 hover:bg-indigo-100 transition">
                        <i class="fas fa-file-signature"></i> Bimbingan Laporan
                    </a>
                </div>
            </div>
        </section>
    </div>

    {{-- Card 3: Daftar Mahasiswa Bimbingan --}}
    <section class="overflow-hidden rounded-2xl border border-gray-200/80 bg-white shadow-sm transition hover:shadow-md">
        <header class="flex flex-wrap items-center justify-between gap-3 border-b border-gray-100 bg-gradient-to-r from-slate-50 to-indigo-50/30 px-5 py-4 sm:px-6">
            <div class="flex items-center gap-3">
                <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-blue-700 text-white shadow-sm">
                    <i class="fas fa-user-graduate text-sm"></i>
                </span>
                <div>
                    <h3 class="font-bold text-gray-900">Mahasiswa / Siswa Bimbingan Anda</h3>
                    <p class="text-xs text-gray-500">Daftar peserta magang yang ditugaskan kepada Anda</p>
                </div>
            </div>
            <a href="{{ route('dosen.logbook.index') }}" class="inline-flex items-center gap-1.5 rounded-lg bg-blue-700 px-3.5 py-1.5 text-xs font-semibold text-white hover:bg-blue-800 shadow-sm transition">
                <i class="fas fa-clipboard-check"></i> Buka Monitoring Logbook
            </a>
        </header>

        <div class="p-5 sm:p-6">
            @if ($pesertaList->isEmpty())
                <div class="rounded-xl border border-dashed border-gray-200 p-8 text-center">
                    <i class="fas fa-user-friends text-3xl text-gray-300"></i>
                    <p class="mt-2 text-sm font-semibold text-gray-600">Belum ada mahasiswa yang ditugaskan.</p>
                    <p class="text-xs text-gray-400">Penugasan mahasiswa bimbingan dilakukan oleh administrator sistem.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50 text-xs font-semibold uppercase tracking-wider text-gray-500">
                            <tr>
                                <th class="px-4 py-3 text-left">Mahasiswa / Siswa</th>
                                <th class="px-4 py-3 text-left">NIM / NISN</th>
                                <th class="px-4 py-3 text-left">Program Studi</th>
                                <th class="px-4 py-3 text-left">Kelompok &amp; Departemen di Bandara</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($pesertaList as $mhs)
                                <tr class="hover:bg-gray-50/70 transition">
                                    <td class="px-4 py-3">
                                        <div class="flex items-center gap-3">
                                            @if ($mhs->foto_url)
                                                <img src="{{ $mhs->foto_url }}" alt="Foto {{ $mhs->name }}" class="h-9 w-9 rounded-full border border-gray-200 object-cover">
                                            @else
                                                <span class="flex h-9 w-9 items-center justify-center rounded-full bg-blue-100 text-xs font-bold text-blue-700">
                                                    {{ strtoupper(substr($mhs->name ?? 'M', 0, 1)) }}
                                                </span>
                                            @endif
                                            <div>
                                                <p class="font-bold text-gray-900 leading-tight">{{ $mhs->name }}</p>
                                                @if ($mhs->email)
                                                    <a href="mailto:{{ $mhs->email }}" class="text-xs text-gray-500 hover:text-blue-700 hover:underline">{{ $mhs->email }}</a>
                                                @else
                                                    <p class="text-xs text-gray-400">-</p>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 font-mono text-xs font-semibold text-gray-700">{{ $mhs->nim_nip ?: '-' }}</td>
                                    <td class="px-4 py-3 text-xs text-gray-700">{{ $mhs->prodi ?: '-' }}</td>
                                    <td class="px-4 py-3 text-xs">
                                        <span class="font-semibold text-slate-800">{{ $mhs->kelompok?->nama_kelompok ?? '-' }}</span>
                                        <span class="block text-gray-500 text-[11px]">{{ $mhs->kelompok?->departemen?->nama_departemen ?? '-' }}</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </section>
</div>
