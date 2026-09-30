<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <h1 class="text-xl font-semibold text-gray-800">Daftar Peserta &amp; Unggah Penilaian</h1>
            <a href="{{ route('pembimbingan.index') }}" class="text-sm font-medium text-blue-700 hover:underline">Kembali ke Beranda Pembimbing</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl space-y-5 sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800" role="status">{{ session('status') }}</div>
            @endif
            @if ($errors->any())
                <div class="rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800" role="alert">File harus PDF dan berukuran maksimal 10 MB.</div>
            @endif

            <section class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
                <div class="border-b border-gray-100 px-5 py-4">
                    <h2 class="font-semibold text-gray-900">Peserta Bimbingan</h2>
                    <p class="mt-1 text-sm text-gray-500">Data kontak peserta dan unggah lembar penilaian akhir Magang / PKL.</p>
                </div>
                @if ($pesertas->isEmpty())
                    <p class="px-5 py-10 text-center text-sm text-gray-500">Belum ada peserta yang ditugaskan kepada Anda.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-50 text-xs font-semibold uppercase tracking-wide text-gray-600">
                                <tr>
                                    <th class="whitespace-nowrap px-4 py-3 text-left">Peserta</th>
                                    <th class="whitespace-nowrap px-4 py-3 text-left">NIM / NISN</th>
                                    <th class="px-4 py-3 text-left">Asal Sekolah / Kampus</th>
                                    <th class="whitespace-nowrap px-4 py-3 text-left">No. HP / WA</th>
                                    <th class="min-w-80 px-4 py-3 text-left">Dokumen Penilaian PDF</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach ($pesertas as $peserta)
                                    @php($penilaianTerbaru = $peserta->penilaians->first())
                                    <tr class="align-top hover:bg-gray-50/70">
                                        <td class="px-4 py-4 font-medium text-gray-900">{{ $peserta->name }}</td>
                                        <td class="whitespace-nowrap px-4 py-4 text-gray-600">{{ $peserta->nim_nip ?? '-' }}</td>
                                        <td class="px-4 py-4 text-gray-600">{{ $peserta->kampus ?? $peserta->kelompok?->kampus?->nama_universitas ?? $peserta->instansi ?? '-' }}</td>
                                        <td class="whitespace-nowrap px-4 py-4 text-gray-600">
                                            @php($whatsappUrl = \App\Support\ContactLink::whatsappUrl($peserta->hp_wa))
                                            @if ($whatsappUrl)
                                                <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener noreferrer" class="text-emerald-700 hover:text-emerald-800 hover:underline">{{ $peserta->hp_wa }}</a>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="px-4 py-4">
                                            @if ($penilaianTerbaru)
                                                <p class="mb-2 text-xs text-gray-600">Dokumen aktif: {{ basename($penilaianTerbaru->file) }}</p>
                                            @else
                                                <p class="mb-2 text-xs text-amber-700">Belum ada PDF penilaian.</p>
                                            @endif
                                            <form action="{{ route('pembimbingan.penilaian.store', $peserta) }}" method="POST" enctype="multipart/form-data" class="space-y-3">
                                                @csrf
                                                <input id="file-penilaian-{{ $peserta->id }}" name="file" type="file" accept="application/pdf,.pdf" required class="block max-w-full text-xs text-gray-600 file:mr-2 file:rounded-md file:border-0 file:bg-slate-100 file:px-3 file:py-2 file:font-medium file:text-slate-700 hover:file:bg-slate-200">
                                                <div class="grid grid-cols-3 gap-2">
                                                    <div>
                                                        <label for="nilai-bimbingan-{{ $peserta->id }}" class="mb-1 block text-[11px] text-gray-500">Nilai Bimbingan</label>
                                                        <input id="nilai-bimbingan-{{ $peserta->id }}" name="nilai_bimbingan" type="number" min="0" max="100" step="0.01" value="{{ $penilaianTerbaru?->nilai_bimbingan }}" class="block w-full rounded-md border-gray-300 px-2 py-1.5 text-xs" placeholder="0-100">
                                                    </div>
                                                    <div>
                                                        <label for="nilai-laporan-{{ $peserta->id }}" class="mb-1 block text-[11px] text-gray-500">Nilai Laporan</label>
                                                        <input id="nilai-laporan-{{ $peserta->id }}" name="nilai_laporan_akhir" type="number" min="0" max="100" step="0.01" value="{{ $penilaianTerbaru?->nilai_laporan_akhir }}" class="block w-full rounded-md border-gray-300 px-2 py-1.5 text-xs" placeholder="0-100">
                                                    </div>
                                                    <div>
                                                        <label for="nilai-presentasi-{{ $peserta->id }}" class="mb-1 block text-[11px] text-gray-500">Nilai Presentasi</label>
                                                        <input id="nilai-presentasi-{{ $peserta->id }}" name="nilai_presentasi" type="number" min="0" max="100" step="0.01" value="{{ $penilaianTerbaru?->nilai_presentasi }}" class="block w-full rounded-md border-gray-300 px-2 py-1.5 text-xs" placeholder="0-100">
                                                    </div>
                                                </div>
                                                <button type="submit" class="inline-flex items-center gap-2 rounded-md bg-blue-700 px-3 py-2 text-xs font-semibold text-white hover:bg-blue-800"><i class="fas fa-upload" aria-hidden="true"></i> Unggah PDF &amp; Nilai</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </section>
        </div>
    </div>
</x-app-layout>
