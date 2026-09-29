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
                                <thead class="border-b border-gray-200 text-gray-600">
                                    <tr>
                                        <th class="px-5 py-3 text-center align-middle whitespace-nowrap">ID</th>
                                        <th class="px-5 py-3 text-center align-middle whitespace-nowrap">Kode Kelompok</th>
                                        <th class="px-5 py-3 text-center align-middle whitespace-nowrap">Periode</th>
                                        <th class="px-5 py-3 text-center align-middle whitespace-nowrap">Kampus</th>
                                        <th class="px-5 py-3 text-center align-middle whitespace-nowrap">Dosen</th>
                                        <th class="px-5 py-3 text-center align-middle whitespace-nowrap">Pembimbing</th>
                                        <th class="px-5 py-3 text-center align-middle whitespace-nowrap">Departemen</th>
                                        <th class="px-5 py-3 text-center align-middle whitespace-nowrap">Jumlah Anggota</th>
                                        <th class="px-5 py-3 text-center align-middle whitespace-nowrap">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($kelompoks as $kelompok)
                                        <tr class="border-b border-gray-100">
                                            <td class="px-5 py-4 align-middle whitespace-nowrap text-center text-gray-500">#{{ $kelompok->id }}</td>
                                            <td class="px-5 py-4 align-middle whitespace-nowrap font-medium">{{ $kelompok->nama_kelompok }}</td>
                                            <td class="px-5 py-4 align-middle whitespace-nowrap">
                                                {{ $kelompok->tanggal_mulai->format('d M Y') }} - {{ $kelompok->tanggal_selesai->format('d M Y') }}
                                            </td>
                                            <td class="px-5 py-4 align-middle whitespace-nowrap">{{ $kelompok->kampus?->nama_universitas ?? '-' }}</td>
                                            <td class="px-5 py-4 align-middle whitespace-nowrap">{{ $kelompok->dosen?->nama ?? '-' }}</td>
                                            <td class="px-5 py-4 align-middle whitespace-nowrap">{{ $kelompok->pembimbing?->nama ?? '-' }}</td>
                                            <td class="px-5 py-4 align-middle whitespace-nowrap">{{ $kelompok->departemen?->nama_departemen ?? '-' }}</td>
                                            <td class="px-5 py-4 align-middle whitespace-nowrap text-center">{{ $kelompok->mahasiswas_count }}</td>
                                            <td class="px-5 py-4 align-middle whitespace-nowrap text-end">
                                                <div class="flex items-center justify-end gap-3">
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
                            <form method="POST" action="{{ route('kelompok.update', $kelompok->id, false) }}" class="p-6">
                                @csrf
                                @method('PUT')

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
                                        <x-input-label for="edit_dosen_id_{{ $kelompok->id }}" value="{{ __('Dosen / Guru Pembimbing') }}" />
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
                                    {{ __('Kelompok') }} <strong>{{ $kelompok->nama_kelompok }}</strong> {{ __('akan dihapus permanen. Peserta yang tergabung tidak ikut terhapus, tetapi kelompoknya menjadi kosong.') }}
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

                        <x-modal name="kelola-anggota-{{ $kelompok->id }}" :show="false">
                            <div class="p-6">
                                <h2 class="text-lg font-medium text-gray-900">
                                    {{ __('Anggota') }} {{ $kelompok->nama_kelompok }}
                                </h2>

                                @if ($kelompok->mahasiswas->isEmpty())
                                    <p class="mt-4 text-sm text-gray-500">{{ __('Belum ada anggota.') }}</p>
                                @else
                                    <ul class="mt-4 divide-y divide-gray-100 rounded-md border border-gray-200">
                                        @foreach ($kelompok->mahasiswas as $anggota)
                                            <li class="flex items-center justify-between px-3 py-2 text-sm">
                                                <span>{{ $anggota->name }}</span>
                                                <span class="text-gray-500">{{ $anggota->nim_nip ?? '-' }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif

                                <form method="post" action="{{ route('kelompok.anggota.store', $kelompok) }}" class="mt-4">
                                    @csrf

                                    <x-input-label for="mahasiswa_id_{{ $kelompok->id }}" value="{{ __('Tambah dari Data Peserta') }}" />
                                    <select id="mahasiswa_id_{{ $kelompok->id }}" name="mahasiswa_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                        <option value="">{{ __('- Pilih Peserta -') }}</option>
                                        @foreach ($mahasiswaBebas as $calon)
                                            <option value="{{ $calon->id }}">{{ $calon->name }} ({{ $calon->nim_nip ?? '-' }})</option>
                                        @endforeach
                                    </select>

                                    @if ($mahasiswaBebas->isEmpty())
                                        <p class="mt-2 text-sm text-gray-500">{{ __('Semua peserta sudah memiliki kelompok. Tambahkan peserta baru lewat menu Data Peserta.') }}</p>
                                    @endif

                                    <div class="mt-4 flex justify-end">
                                        <x-secondary-button type="button" x-on:click="$dispatch('close')">
                                            {{ __('Tutup') }}
                                        </x-secondary-button>

                                        <x-primary-button class="ms-3">
                                            {{ __('Tambahkan') }}
                                        </x-primary-button>
                                    </div>
                                </form>
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
                namaBaru: '',
                daftar: [],
                tambah() {
                    const nama = this.namaBaru.trim();
                    if (nama === '') { return; }
                    this.daftar.push(nama);
                    this.namaBaru = '';
                },
                hapus(index) {
                    this.daftar.splice(index, 1);
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
                    <x-input-label value="{{ __('Nama Anggota') }}" />

                    <div class="mt-1 flex gap-2">
                        <input
                            type="text"
                            x-model="namaBaru"
                            x-on:keydown.enter.prevent="tambah()"
                            autocomplete="off"
                            placeholder="{{ __('Ketik nama peserta, lalu klik + Tambah') }}"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >
                        <x-secondary-button type="button" x-on:click="tambah()">
                            {{ __('+ Tambah') }}
                        </x-secondary-button>
                    </div>

                    <p class="mt-2 text-sm text-gray-500" x-show="daftar.length === 0">
                        {{ __('Belum ada nama yang ditambahkan.') }}
                    </p>

                    <ul class="mt-2 divide-y divide-gray-100 rounded-md border border-gray-200" x-show="daftar.length > 0">
                        <template x-for="(nama, index) in daftar" :key="index">
                            <li class="flex items-center justify-between px-3 py-2 text-sm">
                                <span x-text="nama"></span>
                                <input type="hidden" name="nama_anggota[]" :value="nama">
                                <button type="button" x-on:click="hapus(index)" class="font-medium text-red-600 hover:text-red-800">
                                    {{ __('Hapus') }}
                                </button>
                            </li>
                        </template>
                    </ul>

                    <x-input-error :messages="$errors->get('nama_anggota')" class="mt-2" />
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
                    <x-input-label for="create_dosen_id" value="{{ __('Dosen / Guru Pembimbing') }}" />
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