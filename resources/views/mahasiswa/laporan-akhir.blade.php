<x-app-layout>
    <x-slot name="header">
        <h1 class="text-xl font-semibold text-gray-800">Unggah Draf Laporan Akhir Magang / PKL</h1>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-3xl space-y-5 sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800" role="status">{{ session('status') }}</div>
            @endif

            <section class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                <h2 class="font-semibold text-gray-900">Status Bimbingan</h2>
                @if ($laporanAkhir)
                    <div class="mt-3 flex flex-wrap items-center justify-between gap-3">
                        <div>
                            <p class="text-sm font-medium text-gray-800">{{ $laporanAkhir->judul_laporan }}</p>
                            <p class="mt-1 text-xs text-gray-500">Dikirim {{ $laporanAkhir->updated_at->format('d M Y H:i') }}</p>
                        </div>
                        <span @class(['rounded-full px-2.5 py-1 text-xs font-semibold', 'bg-amber-50 text-amber-800' => $laporanAkhir->status === 'Menunggu Review', 'bg-emerald-50 text-emerald-800' => $laporanAkhir->status === 'Disetujui', 'bg-rose-50 text-rose-800' => $laporanAkhir->status === 'Revisi'])>{{ $laporanAkhir->status }}</span>
                    </div>
                    @if ($laporanAkhir->catatan_revisi)
                        <div class="mt-4 rounded-lg border border-amber-200 bg-amber-50 p-4">
                            <p class="text-xs font-semibold uppercase tracking-wide text-amber-800">Catatan Dosen / Guru Pembimbing</p>
                            <p class="mt-1 whitespace-pre-line text-sm text-amber-950">{{ $laporanAkhir->catatan_revisi }}</p>
                        </div>
                    @endif
                @else
                    <p class="mt-2 text-sm text-gray-500">Belum ada draf laporan akhir yang dikirim.</p>
                @endif
            </section>

            <section class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                <h2 class="font-semibold text-gray-900">{{ $laporanAkhir ? 'Unggah Revisi Draf' : 'Kirim Draf Laporan' }}</h2>
                <p class="mt-1 text-sm text-gray-500">File PDF maksimal 20 MB. Draf terbaru akan menggantikan file sebelumnya.</p>
                <form action="{{ route('mahasiswa.laporan-akhir.store') }}" method="POST" enctype="multipart/form-data" class="mt-5 space-y-4">
                    @csrf
                    <div>
                        <label for="judul-laporan-akhir" class="mb-1 block text-sm font-medium text-gray-700">Judul Laporan Akhir</label>
                        <input id="judul-laporan-akhir" name="judul_laporan" type="text" value="{{ old('judul_laporan', $laporanAkhir?->judul_laporan) }}" required maxlength="255" class="block w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @error('judul_laporan')<p class="mt-2 text-sm text-rose-700">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="file-laporan-akhir" class="mb-1 block text-sm font-medium text-gray-700">Draf Laporan Akhir (PDF)</label>
                        <input id="file-laporan-akhir" name="file" type="file" accept="application/pdf,.pdf" required class="block w-full text-sm text-gray-700 file:mr-3 file:rounded-md file:border-0 file:bg-blue-50 file:px-4 file:py-2 file:font-medium file:text-blue-800 hover:file:bg-blue-100">
                        @error('file')<p class="mt-2 text-sm text-rose-700">{{ $message }}</p>@enderror
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" class="rounded-md bg-blue-700 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-800">Kirim Draf PDF</button>
                    </div>
                </form>
            </section>
        </div>
    </div>
</x-app-layout>
