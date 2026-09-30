@php
    $nip = $pembimbingProfile?->nip ?: ($user->nim_nip ?: '-');
    $departemen = $pembimbingProfile?->departemen?->nama_departemen ?: ($user->unit ?: '-');
    $pimpinanDepartemen = $pembimbingProfile?->departemen?->nama_pimpinan ?: '-';
    $emailMentor = $pembimbingProfile?->email ?: $user->email;
    $kontakMentor = $pembimbingProfile?->hp_wa ?: ($user->hp_wa ?: '-');
    $whatsappMentorUrl = \App\Support\ContactLink::whatsappUrl($kontakMentor);
    $pesertaList = $pesertaBimbingan ?? collect();
    $kelompokList = $kelompokBimbingan ?? collect();
@endphp

<div class="space-y-6">
    {{-- Grid 2 Kolom: Info Perusahaan & Ruang Lingkup Bimbingan --}}
    <div class="grid gap-6 lg:grid-cols-2">
        {{-- Card 1: Identitas Profesional & Perusahaan --}}
        <section class="overflow-hidden rounded-2xl border border-gray-200/80 bg-white shadow-sm transition hover:shadow-md">
            <header class="flex items-center justify-between border-b border-gray-100 bg-gradient-to-r from-amber-50/60 to-slate-50 px-5 py-4 sm:px-6">
                <div class="flex items-center gap-3">
                    <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-amber-600 text-white shadow-sm">
                        <i class="fas fa-user-tie text-sm"></i>
                    </span>
                    <div>
                        <h3 class="font-bold text-gray-900">Identitas Pembimbing Lapangan</h3>
                        <p class="text-xs text-gray-500">Field Mentor · PT Angkasa Pura Aviasi</p>
                    </div>
                </div>
                <span class="inline-flex items-center rounded-full border border-amber-200 bg-amber-50 px-2.5 py-0.5 text-xs font-semibold text-amber-800">
                    Pembimbing Lapangan
                </span>
            </header>

            <div class="p-5 sm:p-6">
                <dl class="grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-2">
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wider text-gray-400">NIP / ID Pegawai</dt>
                        <dd class="mt-1 font-mono text-sm font-bold text-gray-900">{{ $nip }}</dd>
                    </div>

                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wider text-gray-400">Jabatan / Peran</dt>
                        <dd class="mt-1 text-sm font-semibold text-gray-900">Field Mentor / Pembimbing Magang</dd>
                    </div>

                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wider text-gray-400">Email</dt>
                        <dd class="mt-1 break-all text-sm font-medium text-gray-900">
                            @if ($emailMentor)
                                <a href="mailto:{{ $emailMentor }}" class="hover:text-amber-800 hover:underline">{{ $emailMentor }}</a>
                            @else
                                -
                            @endif
                        </dd>
                    </div>

                    <div class="sm:col-span-2">
                        <dt class="text-xs font-semibold uppercase tracking-wider text-gray-400">Instansi Perusahaan</dt>
                        <dd class="mt-1 text-sm font-bold text-slate-900">PT Angkasa Pura Aviasi</dd>
                    </div>

                    <div class="sm:col-span-2">
                        <dt class="text-xs font-semibold uppercase tracking-wider text-gray-400">Departemen / Unit Kerja</dt>
                        <dd class="mt-1 inline-flex items-center gap-2 rounded-lg bg-amber-50 px-3 py-1.5 text-sm font-bold text-amber-900 border border-amber-200/70">
                            <i class="fas fa-building text-amber-700"></i>
                            {{ $departemen }}
                        </dd>
                    </div>

                    <div class="sm:col-span-2">
                        <dt class="text-xs font-semibold uppercase tracking-wider text-gray-400">Pimpinan Departemen</dt>
                        <dd class="mt-1 text-sm font-semibold text-gray-900">{{ $pimpinanDepartemen }}</dd>
                    </div>

                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wider text-gray-400">Lokasi Kerja</dt>
                        <dd class="mt-1 text-sm text-gray-700">Bandara Kualanamu (KNO)</dd>
                    </div>

                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wider text-gray-400">Kontak WhatsApp</dt>
                        <dd class="mt-1 text-sm font-medium text-gray-900">
                            @if ($whatsappMentorUrl)
                                <a href="{{ $whatsappMentorUrl }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1.5 text-emerald-600 hover:text-emerald-700 hover:underline">
                                    <i class="fab fa-whatsapp"></i> {{ $kontakMentor }}
                                </a>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </dd>
                    </div>
                </dl>
            </div>
        </section>

        {{-- Card 2: Tanggung Jawab & Lingkup Pembimbingan --}}
        <section class="overflow-hidden rounded-2xl border border-gray-200/80 bg-white shadow-sm transition hover:shadow-md">
            <header class="flex items-center justify-between border-b border-gray-100 bg-gradient-to-r from-slate-50 to-amber-50/50 px-5 py-4 sm:px-6">
                <div class="flex items-center gap-3">
                    <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-slate-800 text-white shadow-sm">
                        <i class="fas fa-tasks text-sm"></i>
                    </span>
                    <div>
                        <h3 class="font-bold text-gray-900">Tanggung Jawab Lapangan</h3>
                        <p class="text-xs text-gray-500">Pendampingan kerja praktik &amp; logbook</p>
                    </div>
                </div>
                <span class="inline-flex items-center rounded-full bg-amber-50 px-2.5 py-0.5 text-xs font-semibold text-amber-800 border border-amber-200">
                    {{ $totalBimbingan ?? $pesertaList->count() }} Peserta Aktif
                </span>
            </header>

            <div class="p-5 sm:p-6 space-y-4">
                <dl class="grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-2">
                    <div class="sm:col-span-2">
                        <dt class="text-xs font-semibold uppercase tracking-wider text-gray-400">Tugas Pokok Pembimbing</dt>
                        <dd class="mt-1 text-xs text-gray-600 leading-relaxed">
                            Mengarahkan kegiatan harian peserta di unit kerja, memeriksa dan menyetujui logbook harian, mengawasi standar operasional bandara, serta mengunggah lembar nilai akhir Magang / PKL.
                        </dd>
                    </div>

                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wider text-gray-400">Unit Operasional</dt>
                        <dd class="mt-1 text-sm font-semibold text-gray-800">Kualanamu International Airport</dd>
                    </div>

                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wider text-gray-400">Format Penilaian</dt>
                        <dd class="mt-1 text-sm font-semibold text-gray-800">Unggah Lembar Nilai PDF</dd>
                    </div>
                </dl>

                <div class="rounded-xl border border-amber-100 bg-amber-50/50 p-4">
                    <div class="flex flex-wrap items-baseline justify-between gap-2">
                        <h4 class="text-xs font-bold uppercase tracking-wider text-amber-900">Kelompok Bimbingan</h4>
                        <span class="text-xs font-semibold text-amber-800">{{ $totalKelompokBimbingan ?? $kelompokList->count() }} kelompok · {{ $totalBimbingan ?? $pesertaList->count() }} peserta</span>
                    </div>
                    @if ($kelompokList->isNotEmpty())
                        <ul class="mt-3 grid gap-2 sm:grid-cols-2">
                            @foreach ($kelompokList as $kelompokBimbinganItem)
                                <li class="rounded-md border border-amber-100 bg-white px-3 py-2 text-sm font-semibold text-gray-800">
                                    {{ $kelompokBimbinganItem->nama_kelompok ?: '-' }}
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="mt-2 text-sm text-gray-500">-</p>
                    @endif
                </div>

                <div class="grid grid-cols-2 gap-3 pt-2">
                    <a href="{{ route('pembimbingan.index') }}" class="flex items-center justify-center gap-2 rounded-xl bg-slate-100 px-4 py-2.5 text-xs font-bold text-slate-800 hover:bg-slate-200 transition">
                        <i class="fas fa-home"></i> Beranda Mentor
                    </a>
                    <a href="{{ route('pembimbingan.laporan.index') }}" class="flex items-center justify-center gap-2 rounded-xl bg-amber-50 px-4 py-2.5 text-xs font-bold text-amber-800 hover:bg-amber-100 transition">
                        <i class="fas fa-check-double"></i> Periksa Laporan
                    </a>
                </div>
            </div>
        </section>
    </div>

    {{-- Card 3: Daftar Peserta Bimbingan Lapangan --}}
    <section class="overflow-hidden rounded-2xl border border-gray-200/80 bg-white shadow-sm transition hover:shadow-md">
        <header class="flex flex-wrap items-center justify-between gap-3 border-b border-gray-100 bg-gradient-to-r from-slate-50 to-amber-50/30 px-5 py-4 sm:px-6">
            <div class="flex items-center gap-3">
                <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-slate-800 text-white shadow-sm">
                    <i class="fas fa-users text-sm"></i>
                </span>
                <div>
                    <h3 class="font-bold text-gray-900">Daftar Peserta Bimbingan Lapangan</h3>
                    <p class="text-xs text-gray-500">Mahasiswa / siswa yang Anda dampingi di unit {{ $departemen }}</p>
                </div>
            </div>
            <a href="{{ route('pembimbingan.peserta') }}" class="inline-flex items-center gap-1.5 rounded-lg bg-amber-700 px-3.5 py-1.5 text-xs font-semibold text-white hover:bg-amber-800 shadow-sm transition">
                <i class="fas fa-file-upload"></i> Unggah Penilaian Peserta
            </a>
        </header>

        <div class="p-5 sm:p-6">
            @if ($pesertaList->isEmpty())
                <div class="rounded-xl border border-dashed border-gray-200 p-8 text-center">
                    <i class="fas fa-user-friends text-3xl text-gray-300"></i>
                    <p class="mt-2 text-sm font-semibold text-gray-600">Belum ada peserta yang ditugaskan.</p>
                    <p class="text-xs text-gray-400">Penugasan kelompok atau peserta dilakukan oleh administrator sistem.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50 text-xs font-semibold uppercase tracking-wider text-gray-500">
                            <tr>
                                <th class="px-4 py-3 text-left">Nama Peserta</th>
                                <th class="px-4 py-3 text-left">NIM / NISN</th>
                                <th class="px-4 py-3 text-left">Asal Kampus / Sekolah</th>
                                <th class="px-4 py-3 text-left">Kelompok</th>
                                <th class="px-4 py-3 text-left">No. Kontak</th>
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
                                                <span class="flex h-9 w-9 items-center justify-center rounded-full bg-amber-100 text-xs font-bold text-amber-800">
                                                    {{ strtoupper(substr($mhs->name ?? 'P', 0, 1)) }}
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
                                    <td class="px-4 py-3 text-xs text-gray-700">
                                        {{ $mhs->kampus ?: ($mhs->kelompok?->kampus?->nama_universitas ?: ($mhs->instansi ?: '-')) }}
                                    </td>
                                    <td class="px-4 py-3 text-xs font-semibold text-slate-800">
                                        {{ $mhs->kelompok?->nama_kelompok ?? '-' }}
                                    </td>
                                    <td class="px-4 py-3 text-xs text-gray-600">
                                        @php($pesertaWhatsappUrl = \App\Support\ContactLink::whatsappUrl($mhs->hp_wa))
                                        @if ($pesertaWhatsappUrl)
                                            <a href="{{ $pesertaWhatsappUrl }}" target="_blank" rel="noopener noreferrer" class="text-emerald-700 hover:text-emerald-800 hover:underline">{{ $mhs->hp_wa }}</a>
                                        @else
                                            -
                                        @endif
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
