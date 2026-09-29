<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <h1 class="text-xl font-semibold text-gray-800">Bimbingan Laporan Akhir</h1>
            <a href="{{ route('dosen.dashboard') }}" class="text-sm font-medium text-blue-700 hover:underline">Kembali ke Beranda</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl space-y-4 sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800" role="status">{{ session('status') }}</div>
            @endif

            @forelse ($laporanAkhirs as $laporanAkhir)
                <article class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
                    <div class="flex flex-wrap items-center justify-between gap-3 border-b border-gray-100 px-5 py-4">
                        <div>
                            <h2 class="font-semibold text-gray-900">{{ $laporanAkhir->mahasiswa->name }}</h2>
                            <p class="mt-1 text-sm text-gray-500">{{ $laporanAkhir->mahasiswa->nim_nip ?? 'NIM/NISN tidak tersedia' }} · {{ $laporanAkhir->judul_laporan }} · Diunggah {{ $laporanAkhir->updated_at->format('d M Y H:i') }}</p>
                        </div>
                        <span @class(['rounded-full px-2.5 py-1 text-xs font-semibold', 'bg-amber-50 text-amber-800' => $laporanAkhir->status === 'Menunggu Review', 'bg-emerald-50 text-emerald-800' => $laporanAkhir->status === 'Disetujui', 'bg-rose-50 text-rose-800' => $laporanAkhir->status === 'Revisi'])>{{ $laporanAkhir->status }}</span>
                    </div>
                    <div class="grid gap-5 p-5 md:grid-cols-[minmax(0,1fr)_auto] md:items-start">
                        <div>
                            @if ($laporanAkhir->catatan_revisi)
                                <p class="mb-4 rounded-md bg-amber-50 px-3 py-2 text-sm text-amber-900"><strong>Catatan sebelumnya:</strong> {{ $laporanAkhir->catatan_revisi }}</p>
                            @endif
                            <p class="text-sm text-gray-600">Draf laporan akhir PDF dari mahasiswa/siswa untuk ditinjau.</p>
                            <a href="{{ route('dosen.laporan-akhir.download', $laporanAkhir) }}" class="mt-4 inline-flex items-center gap-2 rounded-md border border-gray-300 px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                                <i class="fas fa-download" aria-hidden="true"></i> Unduh Draf PDF
                            </a>
                        </div>
                        @if ($laporanAkhir->status !== 'Disetujui')
                            <form method="POST" action="{{ route('dosen.laporan-akhir.update', $laporanAkhir) }}" class="w-full space-y-3 md:w-96">
                                @csrf
                                @method('PATCH')
                                <div>
                                    <label for="catatan-akhir-{{ $laporanAkhir->id }}" class="mb-1 block text-sm font-medium text-gray-700">Catatan Revisi</label>
                                    <textarea id="catatan-akhir-{{ $laporanAkhir->id }}" name="catatan_revisi" rows="3" class="block w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Tulis catatan untuk mahasiswa/siswa">{{ old('catatan_revisi') }}</textarea>
                                </div>
                                <div class="flex flex-wrap gap-2">
                                    <button type="submit" name="keputusan" value="revisi" class="inline-flex items-center gap-2 rounded-md border border-amber-300 bg-amber-50 px-3 py-2 text-sm font-semibold text-amber-900 hover:bg-amber-100">Simpan Catatan Revisi</button>
                                    <button type="submit" name="keputusan" value="setujui" class="inline-flex items-center gap-2 rounded-md bg-emerald-700 px-3 py-2 text-sm font-semibold text-white hover:bg-emerald-800">Setujui Laporan Akhir</button>
                                </div>
                            </form>
                        @endif
                    </div>
                </article>
            @empty
                <div class="rounded-xl border border-dashed border-gray-300 bg-white px-6 py-12 text-center">
                    <p class="font-medium text-gray-900">Belum ada draf laporan akhir.</p>
                    <p class="mt-1 text-sm text-gray-500">Draf dari mahasiswa/siswa bimbingan akan muncul di halaman ini.</p>
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>
