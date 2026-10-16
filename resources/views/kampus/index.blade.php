<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Data Instansi Pendidikan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div
                    class="p-6 text-gray-900"
                    x-data="{
                        editId: @js(old('_id') ?: null),
                        showErrors: @js($errors->any()),
                        f: {
                            nama_universitas: @js(old('nama_universitas', '')),
                            alamat: @js(old('alamat', '')),
                            fakultas: @js(old('fakultas', '')),
                            prodi: @js(old('prodi', '')),
                        },
                        get action() {
                            return this.editId ? '{{ url('kampus') }}/' + this.editId : '{{ route('kampus.store') }}';
                        },
                        tambah() {
                            this.editId = null;
                            this.showErrors = false;
                            this.f = { nama_universitas: '', alamat: '', fakultas: '', prodi: '' };
                        },
                        ubah(d) {
                            this.editId = d.id;
                            this.showErrors = false;
                            this.f = {
                                nama_universitas: d.nama_universitas ?? '', alamat: d.alamat ?? '',
                                fakultas: d.fakultas ?? '', prodi: d.prodi ?? '',
                            };
                        },
                        deleteId: null,
                        deleteNama: '',
                        hapus(d) {
                            this.deleteId = d.id;
                            this.deleteNama = d.nama_universitas;
                        },
                        get deleteAction() {
                            return this.deleteId ? '{{ url('kampus') }}/' + this.deleteId : '';
                        },
                    }"
                >
                    @if (session('status'))
                        <div class="mb-4 rounded-lg bg-green-50 px-4 py-3 text-sm text-green-700">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="mb-4 flex justify-end">
                        <button type="button" x-on:click="tambah(); $dispatch('open-modal', 'form-kampus')" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-3 py-1.5 text-sm font-medium text-white hover:bg-blue-700">
                            <i class="fas fa-plus"></i> Tambah Instansi
                        </button>
                    </div>

                    @if ($kampuses->isEmpty())
                        <p class="text-gray-500">Belum ada data instansi.</p>
                    @else
                        <div class="overflow-x-auto rounded-xl border border-gray-200 bg-white dark:border-slate-700 dark:bg-slate-800">
                            <table class="w-full border-slate-200 text-sm dark:border-slate-700">
                                <thead class="bg-gray-100 text-xs font-semibold uppercase tracking-wider text-gray-500 dark:bg-slate-900/80 dark:text-slate-300">
                                    <tr>
                                        <th class="px-6 py-4 text-center">Nama Instansi</th>
                                        <th class="px-6 py-4 text-center">Alamat</th>
                                        <th class="px-6 py-4 text-center">Fakultas / Prodi</th>
                                        <th class="px-6 py-4 text-center">Jumlah Kelompok</th>
                                        <th class="px-6 py-4 text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white text-slate-800 dark:bg-slate-800 dark:text-slate-200">
                                    @foreach ($kampuses as $kampus)
                                    <tr class="border-b border-gray-100 bg-white transition hover:bg-gray-50 dark:border-slate-700 dark:bg-slate-800 dark:hover:bg-slate-700/50">
                                            <td class="px-6 py-4 text-center font-medium text-gray-900">{{ $kampus->nama_universitas }}</td>
                                            <td class="px-6 py-4 text-center text-gray-600">{{ $kampus->alamat ?? '-' }}</td>
                                            <td class="px-6 py-4 text-center text-gray-600">{{ trim(($kampus->fakultas ?? '').' / '.($kampus->prodi ?? ''), ' /') ?: '-' }}</td>
                                            <td class="px-6 py-4 text-center text-gray-600">{{ $kampus->kelompoks_count }}</td>
                                            <td class="px-6 py-4 text-center">
                                                <div class="flex items-center justify-center gap-3">
                                                    <button type="button" x-on:click="ubah(@js($kampus->only(['id', 'nama_universitas', 'alamat', 'fakultas', 'prodi']))); $dispatch('open-modal', 'form-kampus')" class="inline-flex items-center rounded-md border border-gray-200 px-3 py-1.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-200 dark:hover:bg-slate-600">Edit Instansi</button>
                                                    <button type="button" x-on:click="hapus(@js($kampus->only(['id', 'nama_universitas']))); $dispatch('open-modal', 'confirm-delete-kampus')" class="inline-flex items-center rounded-md border border-transparent bg-red-50 px-3 py-1.5 text-sm font-medium text-red-600 transition hover:bg-red-100 dark:border-rose-800/50 dark:bg-rose-950/60 dark:text-rose-300 dark:hover:bg-rose-900">Hapus</button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif

                    @include('kampus._modal')
                    @include('kampus._delete_modal')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>