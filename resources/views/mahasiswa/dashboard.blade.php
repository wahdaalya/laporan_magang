<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Mahasiswa') }}
        </h2>
    </x-slot>

    <div class="py-12"
        x-data="{
            editId: @js(old('_id') ?: null),
            showErrors: @js($errors->any()),
            f: {
                tanggal: @js(old('tanggal', '')),
                kegiatan: @js(old('kegiatan', '')),
                keterangan: @js(old('keterangan', '')),
            },
            get action() {
                return this.editId ? '{{ url('laporan') }}/' + this.editId : '{{ route('laporan.store') }}';
            },
            tambah() {
                this.editId = null;
                this.showErrors = false;
                this.f = { tanggal: '', kegiatan: '', keterangan: '' };
            },
            ubah(d) {
                this.editId = d.id;
                this.showErrors = false;
                this.f = { tanggal: d.tanggal ?? '', kegiatan: d.kegiatan ?? '', keterangan: d.keterangan ?? '' };
            },
        }"
    >
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('status'))
                <div class="mb-4 rounded-lg bg-green-50 px-4 py-3 text-sm text-green-700">
                    {{ session('status') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-8">
                <h3 class="font-semibold text-lg mb-2">Informasi Magang</h3>
                @if ($user->kelompok)
                    <p class="text-gray-900">Kelompok: <span class="font-medium">{{ $user->kelompok->nama_kelompok }}</span></p>
                    <p class="text-gray-600 text-sm">
                        Periode: {{ $user->kelompok->tanggal_mulai->format('d M Y') }} s/d {{ $user->kelompok->tanggal_selesai->format('d M Y') }}
                    </p>
                @else
                    <p class="text-gray-500">Anda belum tergabung dalam kelompok magang.</p>
                @endif
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-semibold text-lg">Riwayat Laporan Kegiatan</h3>
                        <button type="button" x-on:click="tambah(); $dispatch('open-modal', 'form-laporan')" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-3 py-1.5 text-sm font-medium text-white hover:bg-blue-700">
                            <i class="fas fa-plus"></i> Tambah Kegiatan
                        </button>
                    </div>

                    @if ($laporans->isEmpty())
                        <p class="text-gray-500">Belum ada laporan kegiatan.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-sm">
                                <thead class="border-b border-gray-200 text-start text-gray-600">
                                    <tr>
                                        <th class="px-3 py-2 text-start">Tanggal</th>
                                        <th class="px-3 py-2 text-start">Kegiatan</th>
                                        <th class="px-3 py-2 text-start">Status</th>
                                        <th class="px-3 py-2 text-start">Catatan Pembimbing</th>
                                        <th class="px-3 py-2 text-start">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($laporans as $laporan)
                                        <tr class="border-b border-gray-100">
                                            <td class="px-3 py-2 whitespace-nowrap">{{ $laporan->tanggal->format('d M Y') }}</td>
                                            <td class="px-3 py-2">
                                                <p class="font-medium">{{ $laporan->kegiatan }}</p>
                                                @if ($laporan->keterangan)
                                                    <p class="text-gray-500">{{ Str::limit($laporan->keterangan, 100) }}</p>
                                                @endif
                                            </td>
                                            <td class="px-3 py-2">
                                                <span @class(['px-2 py-1 rounded-full text-xs font-medium',
                                                    'bg-green-100 text-green-800' => $laporan->status === 'Disetujui',
                                                    'bg-red-100 text-red-800' => in_array($laporan->status, ['Ditolak', 'Revisi']),
                                                    'bg-amber-100 text-amber-800' => $laporan->status === 'Belum Diperiksa',
                                                ])>{{ $laporan->status }}</span>
                                            </td>
                                            <td class="px-3 py-2">{{ $laporan->catatan_pembimbing ?? '-' }}</td>
                                            <td class="px-3 py-2 whitespace-nowrap">
                                                @if ($laporan->status === 'Belum Diperiksa')
                                                    <button type="button"
                                                        x-on:click="ubah(@js($laporan->only(['id', 'tanggal', 'kegiatan', 'keterangan']))); $dispatch('open-modal', 'form-laporan')"
                                                        class="text-blue-600 hover:underline mr-3">Ubah</button>
                                                    <form action="{{ route('laporan.destroy', $laporan) }}" method="POST" class="inline"
                                                        onsubmit="return confirm('Hapus kegiatan ini?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                                                    </form>
                                                @else
                                                    <span class="text-gray-400">-</span>
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

        @include('mahasiswa._modal')
    </div>
</x-app-layout>