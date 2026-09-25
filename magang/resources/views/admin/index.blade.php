<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Admin') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 gap-4 lg:grid-cols-4">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <p class="text-sm text-gray-500">Total Mahasiswa</p>
                    <p class="text-3xl font-semibold text-gray-900">{{ $statistik['mahasiswa'] }}</p>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <p class="text-sm text-gray-500">Total Dosen</p>
                    <p class="text-3xl font-semibold text-gray-900">{{ $statistik['dosen'] }}</p>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <p class="text-sm text-gray-500">Total Mentor</p>
                    <p class="text-3xl font-semibold text-gray-900">{{ $statistik['mentor'] }}</p>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <p class="text-sm text-gray-500">Laporan Belum Diperiksa</p>
                    <p class="text-3xl font-semibold text-amber-600">{{ $statistik['laporanBelumDiperiksa'] }}</p>
                </div>
            </div>

            <div class="mt-8 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="font-semibold text-lg mb-4">Laporan Terbaru</h3>

                    @if ($laporanTerbaru->isEmpty())
                        <p class="text-gray-500">Belum ada laporan.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-sm">
                                <thead class="border-b border-gray-200 text-start text-gray-600">
                                    <tr>
                                        <th class="px-3 py-2 text-start">Tanggal</th>
                                        <th class="px-3 py-2 text-start">Mahasiswa</th>
                                        <th class="px-3 py-2 text-start">Kegiatan</th>
                                        <th class="px-3 py-2 text-start">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($laporanTerbaru as $laporan)
                                        <tr class="border-b border-gray-100">
                                            <td class="px-3 py-2 whitespace-nowrap">{{ $laporan->tanggal->format('d M Y') }}</td>
                                            <td class="px-3 py-2">{{ $laporan->mahasiswa->name }}</td>
                                            <td class="px-3 py-2">{{ $laporan->kegiatan }}</td>
                                            <td class="px-3 py-2">
                                                <span @class(['px-2 py-1 rounded-full text-xs font-medium',
                                                    'bg-green-100 text-green-800' => $laporan->status === 'Disetujui',
                                                    'bg-red-100 text-red-800' => in_array($laporan->status, ['Ditolak', 'Revisi']),
                                                    'bg-amber-100 text-amber-800' => $laporan->status === 'Belum Diperiksa',
                                                ])>{{ $laporan->status }}</span>
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
