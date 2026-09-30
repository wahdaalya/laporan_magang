<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dokumen Penilaian Peserta') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
                <div class="border-b border-gray-100 px-6 py-5">
                    <h3 class="text-lg font-semibold text-gray-900">Lembar Penilaian</h3>
                    <p class="mt-1 text-sm text-gray-500">Dokumen resmi Magang / PKL yang diunggah pembimbing.</p>
                </div>

                <div class="p-6">
                @if (! $penilaian)
                    <div class="rounded-lg border border-amber-200 bg-amber-50 p-5 text-amber-900" role="status">
                        <div class="flex items-start gap-3">
                            <i class="fas fa-file-circle-exclamation mt-0.5" aria-hidden="true"></i>
                            <div>
                                <h4 class="font-semibold">Lembar Penilaian Belum Tersedia</h4>
                                <p class="mt-1 text-sm">Pembimbing Lapangan / Dosen / Guru Pembimbing belum mengunggah dokumen penilaian.</p>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">
                        <div class="flex min-w-0 items-center gap-4">
                            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-lg bg-red-50 text-red-600">
                                <i class="fas fa-file-pdf text-xl" aria-hidden="true"></i>
                            </div>
                            <div class="min-w-0">
                                <p class="text-xs font-medium uppercase tracking-wide text-gray-500">Nama File</p>
                                <p class="break-all font-medium text-gray-900">{{ basename($penilaian->file) }}</p>
                                <p class="mt-1 text-sm text-gray-500">Tanggal Upload: {{ $penilaian->created_at->format('d M Y') }}</p>
                            </div>
                        </div>
                        <div class="flex flex-wrap gap-2 sm:shrink-0">
                            <a href="{{ route('mahasiswa.penilaian.view', $penilaian) }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center gap-2 rounded-md border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                                <i class="fas fa-eye" aria-hidden="true"></i> Lihat PDF
                            </a>
                            <a href="{{ route('mahasiswa.penilaian.download', $penilaian) }}" class="inline-flex items-center justify-center gap-2 rounded-md bg-blue-700 px-4 py-2 text-sm font-medium text-white hover:bg-blue-800">
                                <i class="fas fa-download" aria-hidden="true"></i> Unduh PDF
                            </a>
                        </div>
                    </div>
                @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>