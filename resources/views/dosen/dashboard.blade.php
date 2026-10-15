<x-app-layout>
    <x-slot name="header">
        <h1 class="text-xl font-semibold text-gray-800">Beranda Pembimbing Sekolah / Kampus</h1>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl space-y-5 sm:px-6 lg:px-8">
            <section class="banner-hero rounded-xl p-6 text-white shadow-sm sm:p-8">
                <p class="text-sm font-medium text-blue-100">Pemantauan Magang / PKL</p>
                <h2 class="mt-2 text-2xl font-semibold">Selamat Datang, {{ auth()->user()->name }}!</h2>
                <p class="mt-1 text-sm text-blue-100">Ringkasan bimbingan mahasiswa dan siswa yang menjadi tanggung jawab Anda.</p>
            </section>

            <section class="grid gap-4 sm:grid-cols-3" aria-label="Ringkasan bimbingan">
                <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                    <p class="text-sm text-gray-500">Total Peserta Bimbingan</p>
                    <p class="mt-2 text-3xl font-semibold text-gray-900">{{ $totalPeserta }}</p>
                </div>
                <div class="rounded-xl border border-amber-200 bg-amber-50 p-5 shadow-sm">
                    <p class="text-sm text-amber-800">Bimbingan Laporan Perlu Review</p>
                    <p class="mt-2 text-3xl font-semibold text-amber-900">{{ $jumlahReview }}</p>
                </div>
                <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-5 shadow-sm">
                    <p class="text-sm text-emerald-800">Peserta Aktif Magang / PKL</p>
                    <p class="mt-2 text-3xl font-semibold text-emerald-900">{{ $mahasiswaAktif }}</p>
                </div>
            </section>

            <section class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
                <header class="flex flex-wrap items-center justify-between gap-4 border-b border-gray-100 px-5 py-4">
                    <div>
                        <h2 class="font-semibold text-gray-900">Peserta Bimbingan Saya</h2>
                        <p class="mt-1 text-sm text-gray-500">{{ $jumlahKelompokBimbingan }} kelompok · {{ $jumlahMahasiswaKelompok }} peserta</p>
                    </div>
                    <a href="{{ route('dosen.logbook.index') }}" class="text-sm font-medium text-blue-700 hover:underline">Buka Logbook</a>
                </header>

                @forelse ($kelompoksBimbingan as $kelompok)
                    <div class="border-b border-gray-200 last:border-b-0">
                        <div class="flex flex-wrap items-center justify-between gap-3 bg-gray-50 px-5 py-4">
                            <div>
                                <h3 class="font-semibold text-gray-900">{{ $kelompok->nama_kelompok }}</h3>
                                <p class="mt-1 text-xs text-gray-600">
                                    {{ $kelompok->departemen?->nama_departemen ?: '-' }}
                                    · {{ $kelompok->tanggal_mulai?->format('d/m/Y') ?: '-' }} - {{ $kelompok->tanggal_selesai?->format('d/m/Y') ?: '-' }}
                                </p>
                            </div>
                            <p class="text-xs text-gray-600">
                                Mentor:
                                @if ($kelompok->pembimbing?->nama)
                                    <span class="font-medium text-gray-900">{{ $kelompok->pembimbing->nama }}</span>
                                    @php($mentorWhatsappUrl = \App\Support\ContactLink::whatsappUrl($kelompok->pembimbing->hp_wa ?: $kelompok->pembimbing->user?->hp_wa))
                                    @if ($mentorWhatsappUrl)
                                        · <a href="{{ $mentorWhatsappUrl }}" target="_blank" rel="noopener noreferrer" class="text-emerald-700 hover:underline">WhatsApp</a>
                                    @else
                                        · -
                                    @endif
                                @else
                                    -
                                @endif
                            </p>
                        </div>

                        @if ($kelompok->mahasiswas->isEmpty())
                            <p class="px-5 py-5 text-sm text-gray-500">Belum ada peserta pada kelompok ini.</p>
                        @else
                            <ul class="max-h-96 divide-y divide-gray-100 overflow-y-auto">
                                @foreach ($kelompok->mahasiswas as $pesertaKelompok)
                                    @php($statusAkhir = $pesertaKelompok->laporanAkhir?->status ?? 'Belum Diajukan')
                                    <li class="grid gap-3 px-5 py-4 lg:grid-cols-[minmax(12rem,1.1fr)_minmax(14rem,1fr)_minmax(11rem,.8fr)_auto] lg:items-center">
                                        <div class="flex min-w-0 items-center gap-3">
                                            @if ($pesertaKelompok->foto_url)
                                                <img src="{{ $pesertaKelompok->foto_url }}" alt="Foto {{ $pesertaKelompok->name }}" class="h-10 w-10 shrink-0 rounded-full border border-gray-200 object-cover">
                                            @else
                                                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-blue-100 font-semibold text-blue-800">{{ strtoupper(substr($pesertaKelompok->name, 0, 1)) }}</span>
                                            @endif
                                            <div class="min-w-0">
                                                <p class="truncate font-medium text-gray-900">{{ $pesertaKelompok->name }}</p>
                                                <p class="text-xs text-gray-500">{{ $pesertaKelompok->nim_nip ?: '-' }}</p>
                                            </div>
                                        </div>
                                        <div class="min-w-0 text-xs text-gray-600">
                                            <p class="truncate">{{ $pesertaKelompok->kampus ?: ($kelompok->kampus?->nama_universitas ?: ($pesertaKelompok->instansi ?: '-')) }}</p>
                                            <p class="mt-1 truncate">{{ $pesertaKelompok->fakultas ?: ($kelompok->kampus?->fakultas ?: '-') }} · {{ $pesertaKelompok->prodi ?: ($kelompok->kampus?->prodi ?: '-') }}</p>
                                        </div>
                                        <div class="space-y-1 text-xs">
                                            @php($pesertaWhatsappUrl = \App\Support\ContactLink::whatsappUrl($pesertaKelompok->hp_wa))
                                            @if ($pesertaWhatsappUrl)
                                                <a href="{{ $pesertaWhatsappUrl }}" target="_blank" rel="noopener noreferrer" class="text-emerald-700 hover:underline">{{ $pesertaKelompok->hp_wa }}</a>
                                            @else
                                                <p class="text-gray-500">WA: -</p>
                                            @endif
                                            <p class="text-gray-600">Laporan disetujui: {{ $pesertaKelompok->laporan_disetujui_count }}/{{ $pesertaKelompok->laporans_count }}</p>
                                            <p class="text-gray-600">Laporan akhir: {{ $statusAkhir }}</p>
                                        </div>
                                        <a href="{{ route('dosen.logbook.index', ['nama' => $pesertaKelompok->name]) }}" class="inline-flex w-fit items-center rounded-md border border-gray-300 px-3 py-1.5 text-xs font-semibold text-gray-700 hover:bg-gray-50">Detail Bimbingan</a>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                @empty
                    <p class="px-5 py-8 text-center text-sm text-gray-500">Belum ada kelompok bimbingan yang ditetapkan kepada Anda.</p>
                @endforelse
            </section>

        </div>
    </div>
</x-app-layout>
