<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <h1 class="text-xl font-semibold text-gray-800">Review &amp; Persetujuan Laporan</h1>
            <a href="{{ route('pembimbingan.index') }}" class="text-sm font-medium text-blue-700 hover:underline">Kembali ke Beranda Pembimbing</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl space-y-5 sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800" role="status">{{ session('status') }}</div>
            @endif

            <div class="flex flex-wrap items-center justify-between gap-3 rounded-xl border border-amber-200 bg-amber-50 px-5 py-4">
                <p class="text-sm text-amber-900"><strong>{{ $jumlahPendingHariIni }}</strong> laporan menunggu pemeriksaan hari ini.</p>
                <form action="{{ route('pembimbingan.laporan.setujui-hari-ini') }}" method="POST" onsubmit="return confirm('Setujui semua laporan peserta Anda untuk hari ini?')">
                    @csrf
                    <button type="submit" @disabled($jumlahPendingHariIni === 0) class="inline-flex items-center gap-2 rounded-md bg-emerald-700 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-800 disabled:cursor-not-allowed disabled:opacity-50">
                        <i class="fas fa-check-double" aria-hidden="true"></i> ACC Semua Laporan Hari Ini
                    </button>
                </form>
            </div>

            <form method="GET" action="{{ route('pembimbingan.laporan.index') }}" class="grid gap-3 rounded-xl border border-gray-200 bg-white p-4 shadow-sm sm:grid-cols-2 lg:grid-cols-4 lg:items-end">
                <div>
                    <label for="filter-tanggal" class="mb-1 block text-xs font-medium text-gray-600">Tanggal</label>
                    <input id="filter-tanggal" name="tanggal" type="date" value="{{ $filters['tanggal'] ?? '' }}" class="block w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div>
                    <label for="filter-status" class="mb-1 block text-xs font-medium text-gray-600">Status</label>
                    <select id="filter-status" name="status" class="block w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Semua status</option>
                        @foreach (['Belum Diperiksa', 'Disetujui', 'Revisi', 'Ditolak'] as $status)
                            <option value="{{ $status }}" @selected(($filters['status'] ?? '') === $status)>{{ $status }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="filter-nama" class="mb-1 block text-xs font-medium text-gray-600">Nama Peserta</label>
                    <input id="filter-nama" name="nama" type="search" value="{{ $filters['nama'] ?? '' }}" placeholder="Cari peserta" class="block w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                @if (isset($filters['peserta']))
                    <input type="hidden" name="peserta" value="{{ $filters['peserta'] }}">
                @endif
                <div class="flex gap-2">
                    <button type="submit" class="rounded-md bg-slate-800 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-700">Terapkan</button>
                    <a href="{{ route('pembimbingan.laporan.index') }}" class="rounded-md border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Reset</a>
                </div>
            </form>

            <section class="space-y-4">
                @forelse ($laporans as $laporan)
                    <article class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
                        <div class="flex flex-wrap items-start justify-between gap-3 border-b border-gray-100 px-5 py-4">
                            <div>
                                <p class="font-semibold text-gray-900">{{ $laporan->mahasiswa->name }}</p>
                                <p class="mt-1 text-sm text-gray-500">{{ $laporan->tanggal->format('d M Y') }} · {{ $laporan->mahasiswa->nim_nip ?? 'NIM/NISN tidak tersedia' }}</p>
                            </div>
                            <span @class(['rounded-full px-2.5 py-1 text-xs font-semibold', 'bg-amber-50 text-amber-800' => $laporan->status === 'Belum Diperiksa', 'bg-emerald-50 text-emerald-800' => $laporan->status === 'Disetujui', 'bg-rose-50 text-rose-800' => in_array($laporan->status, ['Revisi', 'Ditolak'])])>{{ $laporan->status }}</span>
                        </div>
                        <div class="grid gap-5 p-5 lg:grid-cols-[minmax(0,1fr)_14rem]">
                            <div class="space-y-4">
                                <div>
                                    <p class="mb-1 text-xs font-semibold uppercase tracking-wide text-gray-500">Kegiatan</p>
                                    <p class="whitespace-pre-line text-sm text-gray-900">{{ $laporan->kegiatan }}</p>
                                </div>
                                <div>
                                    <p class="mb-1 text-xs font-semibold uppercase tracking-wide text-gray-500">Keterangan</p>
                                    <p class="whitespace-pre-line text-sm text-gray-600">{{ $laporan->keterangan ?: 'Tidak ada keterangan.' }}</p>
                                </div>
                                @if ($laporan->catatan_pembimbing)
                                    <p class="rounded-md bg-blue-50 px-3 py-2 text-sm text-blue-900"><strong>Catatan sebelumnya:</strong> {{ $laporan->catatan_pembimbing }}</p>
                                @endif
                                @if ($laporan->status === 'Belum Diperiksa')
                                    <form method="POST" action="{{ route('pembimbingan.laporan.setujui', $laporan) }}" class="space-y-3">
                                        @csrf
                                        @method('PATCH')
                                        <label for="catatan-{{ $laporan->id }}" class="block text-sm font-medium text-gray-700">Catatan / Saran Pembimbing</label>
                                        <textarea id="catatan-{{ $laporan->id }}" name="catatan_pembimbing" rows="2" class="block w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Tulis saran atau catatan untuk peserta">{{ old('catatan_pembimbing') }}</textarea>
                                        <div class="flex flex-wrap gap-2">
                                            <button type="submit" class="inline-flex items-center gap-2 rounded-md bg-emerald-700 px-3 py-2 text-sm font-semibold text-white hover:bg-emerald-800"><i class="fas fa-check" aria-hidden="true"></i> Setujui Laporan</button>
                                            <button type="submit" formaction="{{ route('pembimbingan.laporan.revisi', $laporan) }}" class="inline-flex items-center gap-2 rounded-md border border-amber-300 bg-amber-50 px-3 py-2 text-sm font-semibold text-amber-900 hover:bg-amber-100"><i class="fas fa-comment-dots" aria-hidden="true"></i> Simpan Catatan / Revisi</button>
                                        </div>
                                    </form>
                                @endif
                            </div>
                            <div>
                                <p class="mb-2 text-xs font-semibold uppercase tracking-wide text-gray-500">Foto Bukti</p>
                                @if ($laporan->foto_url)
                                    <a href="{{ $laporan->foto_url }}" target="_blank" rel="noopener noreferrer" class="block">
                                        <img src="{{ $laporan->foto_url }}" alt="Foto kegiatan {{ $laporan->kegiatan }}" class="aspect-[4/3] w-full rounded-lg border border-gray-200 object-cover">
                                    </a>
                                @else
                                    <div class="flex aspect-[4/3] items-center justify-center rounded-lg border border-dashed border-gray-300 bg-gray-50 text-sm text-gray-500">Tidak ada foto bukti</div>
                                @endif
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="rounded-xl border border-dashed border-gray-300 bg-white px-6 py-12 text-center">
                        <p class="font-medium text-gray-800">Tidak ada laporan yang cocok.</p>
                        <p class="mt-1 text-sm text-gray-500">Ubah filter atau tunggu laporan dari peserta bimbingan.</p>
                    </div>
                @endforelse
            </section>
        </div>
    </div>
</x-app-layout>
