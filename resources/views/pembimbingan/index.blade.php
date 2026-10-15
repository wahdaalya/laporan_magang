<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">Dashboard Pembimbing Magang / PKL</h2>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800" role="status">{{ session('status') }}</div>
            @endif

            <section class="banner-hero rounded-xl p-6 text-white shadow-sm sm:p-8">
                <p class="text-sm font-medium text-slate-300">Dashboard Pembimbing Lapangan</p>
                <h1 class="mt-2 text-2xl font-semibold">Selamat Datang, {{ auth()->user()->name }}!</h1>
                <p class="mt-1 text-sm text-slate-300">Ringkasan peserta dan laporan Magang / PKL yang Anda bimbing.</p>
            </section>

            <section class="grid gap-4 sm:grid-cols-3" aria-label="Ringkasan bimbingan">
                <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                    <p class="text-sm text-gray-500">Total Peserta Bimbingan</p>
                    <p class="mt-2 text-3xl font-semibold text-gray-900">{{ $totalPeserta }}</p>
                </div>
                <div class="rounded-xl border border-amber-200 bg-amber-50 p-5 shadow-sm">
                    <p class="text-sm text-amber-800">Laporan Belum Diperiksa</p>
                    <p class="mt-2 text-3xl font-semibold text-amber-900">{{ $totalLaporanPending }}</p>
                </div>
                <div class="rounded-xl border border-rose-200 bg-rose-50 p-5 shadow-sm">
                    <p class="text-sm text-rose-800">Belum Isi Laporan Hari Ini</p>
                    <p class="mt-2 text-3xl font-semibold text-rose-900">{{ $totalBelumLapor }}</p>
                </div>
            </section>

            <section class="overflow-hidden rounded-xl border border-rose-200 bg-white shadow-sm">
                <div class="flex flex-wrap items-center justify-between gap-4 border-b border-rose-100 px-5 py-4">
                    <div>
                        <h2 class="font-semibold text-gray-900">Pengingat Laporan Hari Ini</h2>
                        <p class="mt-1 text-sm text-gray-500">{{ $totalBelumLapor }} peserta belum mengisi laporan hari ini.</p>
                    </div>
                    @php($emailPenerima = $belumLaporHariIni->pluck('email')->filter()->implode(','))
                    @if ($belumLaporHariIni->isNotEmpty())
                        @if ($emailPenerima)
                            <a href="mailto:{{ $emailPenerima }}?subject={{ rawurlencode('Pengingat laporan Magang / PKL hari ini') }}&amp;body={{ rawurlencode('Mohon isi laporan kegiatan Magang / PKL hari ini. Terima kasih.') }}" class="inline-flex items-center gap-2 rounded-md bg-rose-700 px-4 py-2 text-sm font-semibold text-white hover:bg-rose-800">
                                <i class="fas fa-bell" aria-hidden="true"></i> Ingatkan Belum Laporan
                            </a>
                        @else
                            <span class="rounded-md bg-gray-100 px-4 py-2 text-sm font-medium text-gray-500">-</span>
                        @endif
                    @else
                        <span class="rounded-md bg-gray-100 px-4 py-2 text-sm font-medium text-gray-500">Semua laporan hari ini sudah masuk</span>
                    @endif
                </div>
                @if ($belumLaporHariIni->isNotEmpty())
                    <ul class="divide-y divide-gray-100">
                        @foreach ($belumLaporHariIni as $peserta)
                            <li class="flex flex-wrap items-center justify-between gap-3 px-5 py-3 text-sm">
                                <span class="font-medium text-gray-900">{{ $peserta->name }}</span>
                                @if ($peserta->email)
                                    <a href="mailto:{{ $peserta->email }}?subject={{ rawurlencode('Pengingat laporan Magang / PKL hari ini') }}" class="text-blue-700 hover:underline">{{ $peserta->email }}</a>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                @endif
            </section>

            <section class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
                <header class="flex flex-wrap items-center justify-between gap-3 border-b border-gray-100 px-5 py-4">
                    <div>
                        <h2 class="font-semibold text-gray-900">Peserta Bimbingan Saya</h2>
                        <p class="mt-1 text-sm text-gray-500">{{ $jumlahKelompokBimbingan }} kelompok · {{ $jumlahPesertaKelompok }} peserta</p>
                    </div>
                    <a href="{{ route('pembimbingan.laporan.index') }}" class="text-sm font-medium text-blue-700 hover:underline">Periksa Laporan</a>
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
                                Dosen:
                                @if ($kelompok->dosen?->nama)
                                    <span class="font-medium text-gray-900">{{ $kelompok->dosen->nama }}</span>
                                    @php($dosenWhatsappUrl = \App\Support\ContactLink::whatsappUrl($kelompok->dosen->hp_wa ?: $kelompok->dosen->user?->hp_wa))
                                    @if ($dosenWhatsappUrl)
                                        · <a href="{{ $dosenWhatsappUrl }}" target="_blank" rel="noopener noreferrer" class="text-emerald-700 hover:underline">WhatsApp</a>
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
                                    <li class="grid gap-3 px-5 py-4 lg:grid-cols-[minmax(12rem,1.1fr)_minmax(12rem,1fr)_minmax(11rem,.8fr)_auto] lg:items-center">
                                        <div class="flex min-w-0 items-center gap-3">
                                            @if ($pesertaKelompok->foto_url)
                                                <img src="{{ $pesertaKelompok->foto_url }}" alt="Foto {{ $pesertaKelompok->name }}" class="h-10 w-10 shrink-0 rounded-full border border-gray-200 object-cover">
                                            @else
                                                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-amber-100 font-semibold text-amber-800">{{ strtoupper(substr($pesertaKelompok->name, 0, 1)) }}</span>
                                            @endif
                                            <div class="min-w-0">
                                                <p class="truncate font-medium text-gray-900">{{ $pesertaKelompok->name }}</p>
                                                <p class="text-xs text-gray-500">{{ $pesertaKelompok->nim_nip ?: '-' }}</p>
                                            </div>
                                        </div>
                                        <div class="min-w-0 text-xs text-gray-600">
                                            <p class="truncate">{{ $pesertaKelompok->kampus ?: ($kelompok->kampus?->nama_universitas ?: ($pesertaKelompok->instansi ?: '-')) }}</p>
                                            <p class="mt-1 truncate">{{ $pesertaKelompok->prodi ?: ($kelompok->kampus?->prodi ?: '-') }}</p>
                                        </div>
                                        <div class="space-y-1 text-xs">
                                            @php($pesertaWhatsappUrl = \App\Support\ContactLink::whatsappUrl($pesertaKelompok->hp_wa))
                                            @if ($pesertaWhatsappUrl)
                                                <a href="{{ $pesertaWhatsappUrl }}" target="_blank" rel="noopener noreferrer" class="text-emerald-700 hover:underline">{{ $pesertaKelompok->hp_wa }}</a>
                                            @else
                                                <p class="text-gray-500">WA: -</p>
                                            @endif
                                            <p class="text-gray-600">Belum diperiksa: {{ $pesertaKelompok->laporan_belum_diperiksa_count }}</p>
                                            <p class="text-gray-600">Penilaian: {{ $pesertaKelompok->penilaians_count > 0 ? 'Sudah diunggah' : 'Belum diunggah' }}</p>
                                        </div>
                                        @if ($pesertaKelompok->laporan_belum_diperiksa_count > 0)
                                            <a href="{{ route('pembimbingan.laporan.index', ['peserta' => $pesertaKelompok->id]) }}" class="inline-flex w-fit items-center rounded-md bg-amber-100 px-3 py-1.5 text-xs font-semibold text-amber-900 hover:bg-amber-200">Periksa Laporan</a>
                                        @else
                                            <span class="text-xs text-gray-400">-</span>
                                        @endif
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
