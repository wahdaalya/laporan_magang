<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Pembimbingan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="font-semibold text-lg mb-4">Mahasiswa Bimbingan</h3>

                    @if ($mahasiswas->isEmpty())
                        <p class="text-gray-500">Belum ada mahasiswa bimbingan.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-sm">
                                <thead class="border-b border-gray-200 text-start text-gray-600">
                                    <tr>
                                        <th class="px-3 py-2 text-start">Nama</th>
                                        <th class="px-3 py-2 text-start">NIM</th>
                                        <th class="px-3 py-2 text-start">Kelompok</th>
                                        <th class="px-3 py-2 text-start">Total Laporan</th>
                                        <th class="px-3 py-2 text-start">Belum Diperiksa</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($mahasiswas as $mahasiswa)
                                        <tr class="border-b border-gray-100">
                                            <td class="px-3 py-2 font-medium">{{ $mahasiswa->name }}</td>
                                            <td class="px-3 py-2">{{ $mahasiswa->nim_nip ?? '-' }}</td>
                                            <td class="px-3 py-2">{{ $mahasiswa->kelompok?->nama_kelompok ?? '-' }}</td>
                                            <td class="px-3 py-2">{{ $mahasiswa->jumlah_laporan }}</td>
                                            <td class="px-3 py-2">
                                                <span @class(['px-2 py-1 rounded-full text-xs font-medium',
                                                    'bg-amber-100 text-amber-800' => $mahasiswa->jumlah_belum_diperiksa > 0,
                                                    'bg-gray-100 text-gray-600' => $mahasiswa->jumlah_belum_diperiksa === 0,
                                                ])>{{ $mahasiswa->jumlah_belum_diperiksa }}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="font-semibold text-lg mb-4">Laporan Menunggu Pemeriksaan</h3>

                    @if ($laporanBelumDiperiksa->isEmpty())
                        <p class="text-gray-500">Tidak ada laporan yang menunggu pemeriksaan.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-sm">
                                <thead class="border-b border-gray-200 text-start text-gray-600">
                                    <tr>
                                        <th class="px-3 py-2 text-start">Tanggal</th>
                                        <th class="px-3 py-2 text-start">Mahasiswa</th>
                                        <th class="px-3 py-2 text-start">Kegiatan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($laporanBelumDiperiksa as $laporan)
                                        <tr class="border-b border-gray-100">
                                            <td class="px-3 py-2 whitespace-nowrap">{{ $laporan->tanggal->format('d M Y') }}</td>
                                            <td class="px-3 py-2">{{ $laporan->mahasiswa->name }}</td>
                                            <td class="px-3 py-2">
                                                <p class="font-medium">{{ $laporan->kegiatan }}</p>
                                                @if ($laporan->keterangan)
                                                    <p class="text-gray-500">{{ Str::limit($laporan->keterangan, 100) }}</p>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
