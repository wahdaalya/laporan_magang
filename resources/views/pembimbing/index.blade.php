<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Data Pembimbing') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900"
                    x-data="{
                        editId: @js(old('_id') ?: null),
                        showErrors: @js($errors->any()),
                        f: {
    nama: @js(old('nama', '')),
    nip: @js(old('nip', '')),
    email: @js(old('email', '')),
    hp_wa: @js(old('hp_wa', '')),
    departemen_id: @js(old('departemen_id', '')),
    password: '',
},
                        get action() {
                            return this.editId ? '{{ url('pembimbing') }}/' + this.editId : '{{ route('pembimbing.store') }}';
                        },
                        tambah() {
    this.editId = null;
    this.showErrors = false;
    this.f = { nama: '', nip: '', email: '', hp_wa: '', departemen_id: '', password: '' };
},
ubah(d) {
    this.editId = d.id;
    this.showErrors = false;
    this.f = {
        nama: d.nama ?? '', nip: d.nip ?? '', email: d.email ?? '',
        hp_wa: d.hp_wa ?? '', departemen_id: d.departemen_id ?? '', password: '',
    };
},
                    }">
                    @if (session('status'))
                        <div class="mb-4 rounded-lg bg-green-50 px-4 py-3 text-sm text-green-700">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="mb-4 flex justify-end">
                        <button type="button" x-on:click="tambah(); $dispatch('open-modal', 'form-pembimbing')" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-3 py-1.5 text-sm font-medium text-white hover:bg-blue-700">
                            <i class="fas fa-plus"></i> Tambah Pembimbing
                        </button>
                    </div>

                    @if ($pembimbings->isEmpty())
                        <p class="text-gray-500">Belum ada data pembimbing.</p>
                    @else
                        <div class="overflow-x-auto border border-gray-200 rounded-xl">
                            <table class="w-full text-sm">
                                <thead class="bg-gray-100 text-xs font-semibold uppercase tracking-wider text-gray-500">
                                    <tr>
                                        <th class="px-6 py-4 text-start">ID</th>
                                        <th class="px-6 py-4 text-start">Nama</th>
                                        <th class="px-6 py-4 text-start">NIP</th>
                                        <th class="px-6 py-4 text-start">Email</th>
                                        <th class="px-6 py-4 text-start">HP/WA</th>
                                        <th class="px-6 py-4 text-start">ID Departemen</th>
<th class="px-6 py-4 text-start">Departemen</th>
                                        <th class="px-6 py-4 text-start">Jumlah Kelompok</th>
                                        <th class="px-6 py-4 text-start">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pembimbings as $pembimbing)
                                        <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                                            <td class="px-6 py-4 text-gray-500">{{ $pembimbing->id }}</td>
                                            <td class="px-6 py-4 font-medium text-gray-900">{{ $pembimbing->nama }}</td>
                                            <td class="px-6 py-4 text-gray-600">{{ $pembimbing->nip ?? '-' }}</td>
                                            <td class="px-6 py-4 text-gray-600">{{ $pembimbing->email ?? '-' }}</td>
                                            <td class="px-6 py-4 text-gray-600">{{ $pembimbing->hp_wa ?? '-' }}</td>
                                           <td class="px-6 py-4 text-gray-600">{{ $pembimbing->departemen_id ?? '-' }}</td>
<td class="px-6 py-4 text-gray-600">{{ $pembimbing->departemen?->nama_departemen ?? '-' }}</td>
                                            <td class="px-6 py-4 text-gray-600">{{ $pembimbing->kelompoks_count }}</td>
                                            <td class="px-6 py-4">
                                                <div class="flex items-center gap-3">
                                                    <button type="button" x-on:click="ubah(@js($pembimbing->only(['id', 'nama', 'nip', 'email', 'hp_wa', 'departemen_id']))); $dispatch('open-modal', 'form-pembimbing')" class="inline-flex items-center rounded-md border border-gray-200 px-3 py-1.5 text-sm font-medium text-gray-700 hover:bg-gray-50 transition">Ubah</button>
                                                    <form method="POST" action="{{ route('pembimbing.destroy', $pembimbing) }}" onsubmit="return confirm('Hapus pembimbing ini?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="inline-flex items-center rounded-md bg-red-50 px-3 py-1.5 text-sm font-medium text-red-600 hover:bg-red-100 transition">Hapus</button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif

                    @include('pembimbing._modal')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>