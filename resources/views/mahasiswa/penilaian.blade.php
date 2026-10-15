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
                    <div class="rounded-xl border border-blue-200 bg-blue-50/80 p-6 text-slate-800" role="status">
                        <div class="flex items-start gap-4">
                            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-blue-600 text-white shadow-sm">
                                <i class="fas fa-user-shield text-xl" aria-hidden="true"></i>
                            </div>
                            <div class="space-y-2">
                                <h4 class="text-base font-bold text-blue-950">Dokumen &amp; Nilai Bersifat Rahasia</h4>
                                <p class="text-sm leading-relaxed text-slate-600">
                                    Sesuai dengan ketentuan akademik, lembar penilaian, evaluasi performa, serta nilai akhir magang dikirimkan langsung oleh Pembimbing Lapangan kepada <strong>Dosen Pembimbing Sekolah/Kampus</strong> untuk keperluan rekapitulasi nilai akademik resmi.
                                </p>
                                <div class="mt-4 rounded-lg border border-blue-200/60 bg-white p-4 text-xs text-slate-500">
                                    <div class="flex items-center gap-2 font-semibold text-blue-900">
                                        <i class="fas fa-info-circle text-blue-600"></i> Informasi untuk Mahasiswa:
                                    </div>
                                    <p class="mt-1">
                                        Rincian nilai dan lembar evaluasi tidak ditampilkan di portal mahasiswa. Pengumuman nilai akhir resmi akan disampaikan langsung oleh pihak program studi / kampus Anda setelah proses yudisium selesai.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>