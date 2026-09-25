<x-app-layout>
    <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Data Kelompok') }}
    </h2>
</x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">
                                   @if (session('status'))
                <div class="rounded-md bg-green-50 px-4 py-3 text-sm text-green-700">
                    {{ session('status') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-4 flex justify-end">
                        <x-primary-button type="button" x-data="" x-on:click.prevent="$dispatch('open-modal', 'create-kelompok')">
                            {{ __('+ Tambah Kelompok') }}
                        </x-primary-button>
                    </div>
                    @if ($kelompoks->isEmpty())
                        <p class="text-gray-500">Belum ada data kelompok.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-sm">
                                <thead class="border-b border-gray-200 text-start text-gray-600">
                                    <tr>
                                        <th class="px-3 py-2 text-start">ID</th>
                                        <th class="px-3 py-2 text-start">Kode Kelompok</th>
                                        <th class="px-3 py-2 text-start">Periode</th>
                                        <th class="px-3 py-2 text-start">Kampus</th>
                                        <th class="px-3 py-2 text-start">Dosen</th>
                                        <th class="px-3 py-2 text-start">Pembimbing</th>
                                        <th class="px-3 py-2 text-start">Departemen</th>
                                        <th class="px-3 py-2 text-start">Jumlah Anggota</th>
                                        <th class="px-3 py-2 text-end">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($kelompoks as $kelompok)
                                        <tr class="border-b border-gray-100">
                                            <td class="px-3 py-2 text-gray-500">#{{ $kelompok->id }}</td>
                                            <td class="px-3 py-2 font-medium">{{ $kelompok->nama_kelompok }}</td>
                                            <td class="px-3 py-2 whitespace-nowrap">
                                                {{ $kelompok->tanggal_mulai->format('d M Y') }} s/d {{ $kelompok->tanggal_selesai->format('d M Y') }}
                                            </td>
                                            <td class="px-3 py-2">{{ $kelompok->kampus?->nama_universitas ?? '-' }}</td>
                                            <td class="px-3 py-2">{{ $kelompok->dosen?->nama ?? '-' }}</td>
                                            <td class="px-3 py-2">{{ $kelompok->pembimbing?->nama ?? '-' }}</td>
                                            <td class="px-3 py-2">{{ $kelompok->departemen?->nama_departemen ?? '-' }}</td>
                                            <td class="px-3 py-2">{{ $kelompok->mahasiswas_count }}</td>
                                            <td class="px-3 py-2 text-end">
                                                <div class="flex items-center justify-end gap-2">
                                                    <x-secondary-button type="button" x-data="" x-on:click.prevent="$dispatch('open-modal', 'edit-kelompok-{{ $kelompok->id }}')">
                                                        {{ __('Edit') }}
                                                    </x-secondary-button>

                                                    <x-danger-button type="button" x-data="" x-on:click.prevent="$dispatch('open-modal', 'delete-kelompok-{{ $kelompok->id }}')">
                                                        {{ __('Hapus') }}
                                                    </x-danger-button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif

                    @foreach ($kelompoks as $kelompok)
                        <x-modal name="edit-kelompok-{{ $kelompok->id }}">
                            <form method="post" action="{{ route('kelompok.update', $kelompok) }}" class="p-6">
                                @csrf
                                @method('put')

                                <h2 class="text-lg font-medium text-gray-900">
                                    {{ __('Edit Kelompok') }} #{{ $kelompok->id }}: {{ $kelompok->nama_kelompok }}
                                </h2>

                                <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-2">
                                    <div class="sm:col-span-2">
                                        <x-input-label value="{{ __('Kode Kelompok') }}" />
                                        <x-text-input type="text" class="mt-1 block w-full bg-gray-100" :value="$kelompok->nama_kelompok" disabled />
                                    </div>

                                    <div>
                                        <x-input-label for="edit_tanggal_mulai_{{ $kelompok->id }}" value="{{ __('Tanggal Mulai') }}" />
                                        <x-text-input id="edit_tanggal_mulai_{{ $kelompok->id }}" name="tanggal_mulai" type="date" class="mt-1 block w-full" :value="$kelompok->tanggal_mulai->format('Y-m-d')" required />
                                    </div>

                                    <div>
                                        <x-input-label for="edit_tanggal_selesai_{{ $kelompok->id }}" value="{{ __('Tanggal Selesai') }}" />
                                        <x-text-input id="edit_tanggal_selesai_{{ $kelompok->id }}" name="tanggal_selesai" type="date" class="mt-1 block w-full" :value="$kelompok->tanggal_selesai->format('Y-m-d')" required />
                                    </div>

                                    <div>
                                        <x-input-label for="edit_kampus_id_{{ $kelompok->id }}" value="{{ __('Kampus') }}" />
                                        <select id="edit_kampus_id_{{ $kelompok->id }}" name="kampus_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            <option value="">{{ __('- Pilih Kampus -') }}</option>
                                            @foreach ($kampuses as $kampus)
                                                <option value="{{ $kampus->id }}" @selected($kelompok->kampus_id == $kampus->id)>
                                                    {{ $kampus->nama_universitas }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div>
                                        <x-input-label for="edit_dosen_id_{{ $kelompok->id }}" value="{{ __('Dosen Pembimbing') }}" />
                                        <select id="edit_dosen_id_{{ $kelompok->id }}" name="dosen_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            <option value="">{{ __('- Pilih Dosen -') }}</option>
                                            @foreach ($dosens as $dosen)
                                                <option value="{{ $dosen->id }}" @selected($kelompok->dosen_id == $dosen->id)>
                                                    {{ $dosen->nama }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div>
                                        <x-input-label for="edit_pembimbing_id_{{ $kelompok->id }}" value="{{ __('Pembimbing Lapangan') }}" />
                                        <select id="edit_pembimbing_id_{{ $kelompok->id }}" name="pembimbing_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            <option value="">{{ __('- Pilih Pembimbing -') }}</option>
                                            @foreach ($pembimbings as $pembimbing)
                                                <option value="{{ $pembimbing->id }}" @selected($kelompok->pembimbing_id == $pembimbing->id)>
                                                    {{ $pembimbing->nama }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div>
                                        <x-input-label for="edit_departemen_id_{{ $kelompok->id }}" value="{{ __('Departemen') }}" />
                                        <select id="edit_departemen_id_{{ $kelompok->id }}" name="departemen_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            <option value="">{{ __('- Pilih Departemen -') }}</option>
                                            @foreach ($departemens as $departemen)
                                                <option value="{{ $departemen->id }}" @selected($kelompok->departemen_id == $departemen->id)>
                                                    {{ $departemen->nama_departemen }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="mt-6 flex justify-end">
                                    <x-secondary-button type="button" x-on:click="$dispatch('close')">
                                        {{ __('Batal') }}
                                    </x-secondary-button>

                                    <x-primary-button class="ms-3">
                                        {{ __('Simpan Perubahan') }}
                                    </x-primary-button>
                                </div>
                            </form>
                        </x-modal>

                        <x-modal name="delete-kelompok-{{ $kelompok->id }}" :show="false" maxWidth="md">
                            <div class="p-6">
                                <h2 class="text-lg font-medium text-gray-900">
                                    {{ __('Yakin mau hapus kelompok ini?') }}
                                </h2>

                                <p class="mt-1 text-sm text-gray-600">
                                    {{ __('Kelompok') }} <strong>{{ $kelompok->nama_kelompok }}</strong> {{ __('akan dihapus permanen. Mahasiswa yang tergabung tidak ikut terhapus, tapi kelompoknya jadi kosong.') }}
                                </p>

                                <div class="mt-6 flex justify-end">
                                    <x-secondary-button type="button" x-on:click="$dispatch('close')">
                                        {{ __('Batal') }}
                                    </x-secondary-button>

                                    <form method="post" action="{{ route('kelompok.destroy', $kelompok) }}" class="ms-3">
                                        @csrf
                                        @method('delete')
                                        <x-danger-button type="submit">
                                            {{ __('Ya, Hapus') }}
                                        </x-danger-button>
                                    </form>
                                </div>
                            </div>
                        </x-modal>

                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <x-modal name="create-kelompok">
        <form
            method="post"
            action="{{ route('kelompok.store') }}"
            class="p-6"
            x-data="{
                search: '',
                results: [],
                selected: [],
                searchTimeout: null,
                doSearch() {
                    clearTimeout(this.searchTimeout);
                    if (this.search.trim() === '') { this.results = []; return; }
                    this.searchTimeout = setTimeout(async () => {
                        const res = await fetch('{{ route('kelompok.mahasiswa.search') }}?q=' + encodeURIComponent(this.search));
                        this.results = await res.json();
                    }, 300);
                },
                tambah(mhs) {
                    if (!this.selected.find(a => a.id === mhs.id)) {
                        this.selected.push(mhs);
                    }
                    this.search = '';
                    this.results = [];
                },
                hapus(id) {
                    this.selected = this.selected.filter(a => a.id !== id);
                },
            }"
        >
            @csrf

            <h2 class="text-lg font-medium text-gray-900">
                {{ __('Tambah Kelompok') }}
            </h2>

            <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div class="sm:col-span-2 rounded-md bg-blue-50 px-4 py-3 text-sm text-blue-700">
                    {{ __('Kode kelompok akan dibuat otomatis oleh sistem setelah disimpan.') }}
                </div>

                <div>
                    <x-input-label for="create_tanggal_mulai" value="{{ __('Tanggal Mulai') }}" />
                    <x-text-input id="create_tanggal_mulai" name="tanggal_mulai" type="date" class="mt-1 block w-full" :value="old('tanggal_mulai')" required />
                    <x-input-error :messages="$errors->get('tanggal_mulai')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="create_tanggal_selesai" value="{{ __('Tanggal Selesai') }}" />
                    <x-text-input id="create_tanggal_selesai" name="tanggal_selesai" type="date" class="mt-1 block w-full" :value="old('tanggal_selesai')" required />
                    <x-input-error :messages="$errors->get('tanggal_selesai')" class="mt-2" />
                </div>

                <div class="sm:col-span-2">
                    <x-input-label value="{{ __('Anggota Kelompok') }}" />

                    <div class="relative mt-1">
                        <input
                            type="text"
                            x-model="search"
                            x-on:input="doSearch()"
                            autocomplete="off"
                            placeholder="{{ __('Ketik nama atau NIM mahasiswa...') }}"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >

                        <ul x-show="results.length > 0" class="absolute z-10 mt-1 max-h-48 w-full overflow-auto rounded-md border border-gray-200 bg-white shadow-lg">
                            <template x-for="mhs in results" :key="mhs.id">
                                <li x-on:click="tambah(mhs)" class="cursor-pointer px-3 py-2 text-sm hover:bg-gray-100">
                                    <span x-text="mhs.name"></span>
                                    <span class="text-gray-500" x-text="'(' + (mhs.nim_nip ?? '-') + ')'"></span>
                                </li>
                            </template>
                        </ul>
                    </div>

                    <p class="mt-2 text-sm text-gray-500" x-show="selected.length === 0">
                        {{ __('Belum ada anggota dipilih.') }}
                    </p>

                    <ul class="mt-2 divide-y divide-gray-100 rounded-md border border-gray-200" x-show="selected.length > 0">
                        <template x-for="anggota in selected" :key="anggota.id">
                            <li class="flex items-center justify-between px-3 py-2 text-sm">
                                <span>
                                    <span x-text="anggota.name"></span>
                                    <span class="text-gray-500" x-text="'(' + (anggota.nim_nip ?? '-') + ')'"></span>
                                </span>
                                <input type="hidden" name="mahasiswa_ids[]" :value="anggota.id">
                                <button type="button" x-on:click="hapus(anggota.id)" class="font-medium text-red-600 hover:text-red-800">
                                    {{ __('Hapus') }}
                                </button>
                            </li>
                        </template>
                    </ul>

                    <x-input-error :messages="$errors->get('mahasiswa_ids')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="create_kampus_id" value="{{ __('Kampus') }}" />
                    <select id="create_kampus_id" name="kampus_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">{{ __('- Pilih Kampus -') }}</option>
                        @foreach ($kampuses as $kampus)
                            <option value="{{ $kampus->id }}" @selected(old('kampus_id') == $kampus->id)>
                                {{ $kampus->nama_universitas }}
                            </option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('kampus_id')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="create_dosen_id" value="{{ __('Dosen Pembimbing') }}" />
                    <select id="create_dosen_id" name="dosen_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">{{ __('- Pilih Dosen -') }}</option>
                        @foreach ($dosens as $dosen)
                            <option value="{{ $dosen->id }}" @selected(old('dosen_id') == $dosen->id)>
                                {{ $dosen->nama }}
                            </option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('dosen_id')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="create_pembimbing_id" value="{{ __('Pembimbing Lapangan') }}" />
                    <select id="create_pembimbing_id" name="pembimbing_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">{{ __('- Pilih Pembimbing -') }}</option>
                        @foreach ($pembimbings as $pembimbing)
                            <option value="{{ $pembimbing->id }}" @selected(old('pembimbing_id') == $pembimbing->id)>
                                {{ $pembimbing->nama }}
                            </option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('pembimbing_id')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="create_departemen_id" value="{{ __('Departemen') }}" />
                    <select id="create_departemen_id" name="departemen_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">{{ __('- Pilih Departemen -') }}</option>
                        @foreach ($departemens as $departemen)
                            <option value="{{ $departemen->id }}" @selected(old('departemen_id') == $departemen->id)>
                                {{ $departemen->nama_departemen }}
                            </option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('departemen_id')" class="mt-2" />
                </div>
            </div>

            <div class="mt-6 flex justify-end">
                <x-secondary-button type="button" x-on:click="$dispatch('close')">
                    {{ __('Batal') }}
                </x-secondary-button>

                <x-primary-button class="ms-3">
                    {{ __('Simpan') }}
                </x-primary-button>
            </div>
        </form>
    </x-modal>
</x-app-layout>