<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <h1 class="text-xl font-semibold text-gray-800">Penilaian Akhir</h1>
            <a href="{{ route('dosen.dashboard') }}" class="text-sm font-medium text-blue-700 hover:underline">Kembali ke Beranda</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl space-y-5 sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800" role="status">{{ session('status') }}</div>
            @endif
            <p class="text-sm text-gray-600">Nilai Mentor Lapangan ditampilkan sebagai pembanding. Nilai akhir berikut merupakan penilaian akademik dari kampus/sekolah.</p>

            @forelse ($pesertas as $peserta)
                @php($mentor = $nilaiMentor->get($peserta->id))
                @php($akademik = $peserta->nilaiAkademik)
                <section class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
                    <div class="flex flex-wrap items-center justify-between gap-3 border-b border-gray-100 px-5 py-4">
                        <div>
                            <h2 class="font-semibold text-gray-900">{{ $peserta->name }}</h2>
                            <p class="mt-1 text-sm text-gray-500">{{ $peserta->nim_nip ?? 'NIM/NISN tidak tersedia' }} · {{ $peserta->kampus ?? $peserta->instansi ?? '-' }}</p>
                        </div>
                        @if ($akademik)
                            <span class="rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700">Nilai tersimpan</span>
                        @else
                            <span class="rounded-full bg-gray-100 px-2.5 py-1 text-xs font-semibold text-gray-600">Belum dinilai</span>
                        @endif
                    </div>

                    <div class="grid gap-5 p-5 lg:grid-cols-[minmax(0,1fr)_minmax(0,1.2fr)]">
                        <div class="rounded-lg border border-blue-100 bg-blue-50/60 p-4">
                            <h3 class="text-sm font-semibold text-gray-900">Nilai Mentor Lapangan</h3>
                            @if ($mentor && ($mentor->nilai_bimbingan !== null || $mentor->nilai_laporan_akhir !== null || $mentor->nilai_presentasi !== null))
                                <dl class="mt-3 grid grid-cols-2 gap-y-2 text-sm">
                                    <dt class="text-gray-600">Bimbingan</dt><dd class="text-right font-medium text-gray-900">{{ $mentor->nilai_bimbingan ?? '-' }}</dd>
                                    <dt class="text-gray-600">Laporan Akhir</dt><dd class="text-right font-medium text-gray-900">{{ $mentor->nilai_laporan_akhir ?? '-' }}</dd>
                                    <dt class="text-gray-600">Presentasi</dt><dd class="text-right font-medium text-gray-900">{{ $mentor->nilai_presentasi ?? '-' }}</dd>
                                </dl>
                            @else
                                <p class="mt-2 text-sm text-gray-500">Nilai numerik dari Mentor Lapangan belum tersedia.</p>
                                @if ($mentor)
                                    <a href="{{ $mentor->file_url }}" target="_blank" rel="noopener" class="mt-2 inline-flex text-sm font-medium text-blue-700 hover:underline">Lihat dokumen PDF Mentor</a>
                                @endif
                            @endif
                        </div>

                        <form action="{{ route('dosen.penilaian-akhir.update', $peserta) }}" method="POST" class="space-y-4">
                            @csrf
                            @method('PUT')
                            <h3 class="text-sm font-semibold text-gray-900">Nilai Kampus / Sekolah</h3>
                            <div class="grid gap-3 sm:grid-cols-3">
                                <div>
                                    <label for="bimbingan-{{ $peserta->id }}" class="mb-1 block text-xs font-medium text-gray-600">Nilai Bimbingan</label>
                                    <input id="bimbingan-{{ $peserta->id }}" name="nilai_bimbingan" type="number" min="0" max="100" step="0.01" required value="{{ old('nilai_bimbingan', $akademik?->nilai_bimbingan) }}" class="block w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label for="laporan-{{ $peserta->id }}" class="mb-1 block text-xs font-medium text-gray-600">Nilai Laporan Akhir</label>
                                    <input id="laporan-{{ $peserta->id }}" name="nilai_laporan_akhir" type="number" min="0" max="100" step="0.01" required value="{{ old('nilai_laporan_akhir', $akademik?->nilai_laporan_akhir) }}" class="block w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label for="presentasi-{{ $peserta->id }}" class="mb-1 block text-xs font-medium text-gray-600">Nilai Presentasi</label>
                                    <input id="presentasi-{{ $peserta->id }}" name="nilai_presentasi" type="number" min="0" max="100" step="0.01" required value="{{ old('nilai_presentasi', $akademik?->nilai_presentasi) }}" class="block w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>
                            </div>
                            <div class="flex justify-end">
                                <button type="submit" class="inline-flex items-center gap-2 rounded-md bg-slate-800 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-700">Simpan Nilai Akhir</button>
                            </div>
                        </form>
                    </div>
                </section>
            @empty
                <div class="rounded-xl border border-dashed border-gray-300 bg-white px-6 py-12 text-center text-sm text-gray-500">Belum ada mahasiswa bimbingan.</div>
            @endforelse
        </div>
    </div>
</x-app-layout>
