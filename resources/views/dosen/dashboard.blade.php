<x-app-layout>
    <x-slot name="header">
        <h1 class="text-xl font-semibold text-gray-800">Beranda Dosen / Guru Pembimbing</h1>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl space-y-5 sm:px-6 lg:px-8">
            <section class="rounded-xl bg-gradient-to-r from-slate-800 to-blue-800 p-6 text-white shadow-sm sm:p-8">
                <p class="text-sm font-medium text-blue-100">Pemantauan Magang / PKL</p>
                <h2 class="mt-2 text-2xl font-semibold">Selamat Datang, {{ auth()->user()->name }}!</h2>
                <p class="mt-1 text-sm text-blue-100">Ringkasan bimbingan mahasiswa dan siswa yang menjadi tanggung jawab Anda.</p>
            </section>

            <section class="grid gap-4 sm:grid-cols-3" aria-label="Ringkasan bimbingan">
                <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                    <p class="text-sm text-gray-500">Total Mahasiswa Bimbingan</p>
                    <p class="mt-2 text-3xl font-semibold text-gray-900">{{ $totalPeserta }}</p>
                </div>
                <div class="rounded-xl border border-amber-200 bg-amber-50 p-5 shadow-sm">
                    <p class="text-sm text-amber-800">Bimbingan Laporan Perlu Review</p>
                    <p class="mt-2 text-3xl font-semibold text-amber-900">{{ $jumlahReview }}</p>
                </div>
                <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-5 shadow-sm">
                    <p class="text-sm text-emerald-800">Mahasiswa Aktif Magang</p>
                    <p class="mt-2 text-3xl font-semibold text-emerald-900">{{ $mahasiswaAktif }}</p>
                </div>
            </section>

            <section class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
                <div class="flex flex-wrap items-center justify-between gap-3 border-b border-gray-100 px-5 py-4">
                    <div>
                        <h2 class="font-semibold text-gray-900">Mahasiswa Bimbingan</h2>
                        <p class="mt-1 text-sm text-gray-500">Perusahaan, pembimbing lapangan, dan perkembangan periode.</p>
                    </div>
                    <a href="{{ route('dosen.logbook.index') }}" class="text-sm font-medium text-blue-700 hover:underline">Buka Logbook</a>
                </div>

                @if ($pesertas->isEmpty())
                    <p class="px-5 py-10 text-center text-sm text-gray-500">Belum ada mahasiswa yang ditugaskan kepada Anda.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-50 text-xs font-semibold uppercase tracking-wide text-gray-600">
                                <tr>
                                    <th class="whitespace-nowrap px-4 py-3 text-left">Mahasiswa / Siswa</th>
                                    <th class="whitespace-nowrap px-4 py-3 text-left">NIM / NISN</th>
                                    <th class="px-4 py-3 text-left">Perusahaan Magang</th>
                                    <th class="whitespace-nowrap px-4 py-3 text-left">Pembimbing Lapangan</th>
                                    <th class="px-4 py-3 text-left">Progress Magang</th>
                                    <th class="px-4 py-3 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach ($pesertas as $peserta)
                                    <tr class="align-middle hover:bg-gray-50/70">
                                        <td class="px-4 py-3">
                                            <div class="flex items-center gap-3">
                                                @if ($peserta->foto_url)
                                                    <img src="{{ $peserta->foto_url }}" alt="Foto {{ $peserta->name }}" class="h-10 w-10 rounded-full border border-gray-200 object-cover">
                                                @else
                                                    <span class="flex h-10 w-10 items-center justify-center rounded-full bg-slate-100 font-semibold text-slate-600">{{ strtoupper(substr($peserta->name, 0, 1)) }}</span>
                                                @endif
                                                <span class="font-medium text-gray-900">{{ $peserta->name }}</span>
                                            </div>
                                        </td>
                                        <td class="whitespace-nowrap px-4 py-3 text-gray-600">{{ $peserta->nim_nip ?? '-' }}</td>
                                        <td class="px-4 py-3 text-gray-600">{{ $peserta->perusahaan_tampil }}</td>
                                        <td class="px-4 py-3 text-gray-600">{{ $peserta->mentor_tampil }}</td>
                                        <td class="min-w-44 px-4 py-3">
                                            <div class="mb-1 flex items-center justify-between gap-2 text-xs">
                                                <span @class(['font-medium', 'text-emerald-700' => $peserta->status_magang === 'Aktif', 'text-amber-700' => $peserta->status_magang === 'Akan Dimulai', 'text-gray-500' => $peserta->status_magang === 'Selesai'])>{{ $peserta->status_magang }}</span>
                                                <span class="text-gray-500">{{ $peserta->progress_durasi }}%</span>
                                            </div>
                                            <progress class="h-2 w-full accent-emerald-600" value="{{ $peserta->progress_durasi }}" max="100" aria-label="Progress {{ $peserta->name }}">{{ $peserta->progress_durasi }}%</progress>
                                        </td>
                                        <td class="px-4 py-3 text-right">
                                            <a href="{{ route('dosen.logbook.index', ['nama' => $peserta->name]) }}" class="inline-flex items-center rounded-md border border-gray-300 px-3 py-1.5 text-xs font-semibold text-gray-700 hover:bg-gray-50">Detail Bimbingan</a>
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
