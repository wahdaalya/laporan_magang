<x-app-layout>
    <x-slot name="header">
                <div class="flex items-center gap-6">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Data Mahasiswa') }}
            </h2>

            <x-primary-button type="button" x-data="" x-on:click.prevent="$dispatch('open-modal', 'create-mahasiswa')">
                {{ __('+ Tambah Mahasiswa') }}
            </x-primary-button>
        </div>
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
                    @if ($mahasiswas->isEmpty())
                        <p class="text-gray-500">Belum ada data mahasiswa.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-sm">
                                                                <thead class="border-b border-gray-200 text-start text-gray-600">
                                    <tr>
                                        <th class="px-3 py-2 text-start">Nama</th>
                                        <th class="px-3 py-2 text-start">NIM</th>
                                        <th class="px-3 py-2 text-start">Fakultas / Prodi</th>
                                        <th class="px-3 py-2 text-start">Kelompok</th>
                                        <th class="px-3 py-2 text-start">Dosen Pembimbing</th>
                                        <th class="px-3 py-2 text-start">Mentor</th>
                                        <th class="px-3 py-2 text-start">Laporan</th>
                                        <th class="px-3 py-2 text-end">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($mahasiswas as $mahasiswa)
                                        <tr class="border-b border-gray-100">
                                            <td class="px-3 py-2">
                                                <p class="font-medium">{{ $mahasiswa->name }}</p>
                                                <p class="text-gray-500">{{ $mahasiswa->email }}</p>
                                            </td>
                                            <td class="px-3 py-2">{{ $mahasiswa->nim_nip ?? '-' }}</td>
                                            <td class="px-3 py-2">
                                                @if ($mahasiswa->fakultas || $mahasiswa->prodi)
                                                    {{ trim($mahasiswa->fakultas.' / '.$mahasiswa->prodi, ' /') }}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td class="px-3 py-2">{{ $mahasiswa->kelompok?->nama_kelompok ?? '-' }}</td>
                                            <td class="px-3 py-2">{{ $mahasiswa->dosen?->name ?? '-' }}</td>
                                            <td class="px-3 py-2">{{ $mahasiswa->mentor?->name ?? '-' }}</td>
                                                                                        <td class="px-3 py-2">{{ $mahasiswa->laporans_count }}</td>
                                            <td class="px-3 py-2 text-end">
                                                <x-secondary-button type="button" x-data="" x-on:click.prevent="$dispatch('open-modal', 'edit-mahasiswa-{{ $mahasiswa->id }}')">
                                                    {{ __('Edit') }}
                                                </x-secondary-button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                                                </div>
                    @endif

                    @foreach ($mahasiswas as $mahasiswa)
                        <x-modal name="edit-mahasiswa-{{ $mahasiswa->id }}">
                            <form method="post" action="{{ route('mahasiswa.update', $mahasiswa) }}" class="p-6">
                                @csrf
                                @method('put')

                                <h2 class="text-lg font-medium text-gray-900">
                                    {{ __('Edit Mahasiswa') }}: {{ $mahasiswa->name }}
                                </h2>

                                <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-2">
                                    <div>
                                        <x-input-label for="edit_name_{{ $mahasiswa->id }}" value="{{ __('Nama') }}" />
                                        <x-text-input id="edit_name_{{ $mahasiswa->id }}" name="name" type="text" class="mt-1 block w-full" :value="$mahasiswa->name" required />
                                    </div>

                                    <div>
                                        <x-input-label for="edit_email_{{ $mahasiswa->id }}" value="{{ __('Email') }}" />
                                        <x-text-input id="edit_email_{{ $mahasiswa->id }}" name="email" type="email" class="mt-1 block w-full" :value="$mahasiswa->email" required />
                                    </div>

                                    <div>
                                        <x-input-label for="edit_password_{{ $mahasiswa->id }}" value="{{ __('Password (kosongkan jika tidak diubah)') }}" />
                                        <x-text-input id="edit_password_{{ $mahasiswa->id }}" name="password" type="password" class="mt-1 block w-full" />
                                    </div>

                                    <div>
                                        <x-input-label for="edit_nim_nip_{{ $mahasiswa->id }}" value="{{ __('NIM') }}" />
                                        <x-text-input id="edit_nim_nip_{{ $mahasiswa->id }}" name="nim_nip" type="text" class="mt-1 block w-full" :value="$mahasiswa->nim_nip" />
                                    </div>

                                    <div>
                                        <x-input-label for="edit_hp_wa_{{ $mahasiswa->id }}" value="{{ __('HP / WA') }}" />
                                        <x-text-input id="edit_hp_wa_{{ $mahasiswa->id }}" name="hp_wa" type="text" class="mt-1 block w-full" :value="$mahasiswa->hp_wa" />
                                    </div>

                                    <div>
                                        <x-input-label for="edit_fakultas_{{ $mahasiswa->id }}" value="{{ __('Fakultas') }}" />
                                        <x-text-input id="edit_fakultas_{{ $mahasiswa->id }}" name="fakultas" type="text" class="mt-1 block w-full" :value="$mahasiswa->fakultas" />
                                    </div>

                                    <div>
                                        <x-input-label for="edit_prodi_{{ $mahasiswa->id }}" value="{{ __('Prodi') }}" />
                                        <x-text-input id="edit_prodi_{{ $mahasiswa->id }}" name="prodi" type="text" class="mt-1 block w-full" :value="$mahasiswa->prodi" />
                                    </div>

                                    <div>
                                        <x-input-label for="edit_kelompok_id_{{ $mahasiswa->id }}" value="{{ __('Kelompok') }}" />
                                        <select id="edit_kelompok_id_{{ $mahasiswa->id }}" name="kelompok_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            <option value="">{{ __('- Belum ada kelompok -') }}</option>
                                            @foreach ($kelompoks as $kelompok)
                                                <option value="{{ $kelompok->id }}" @selected($mahasiswa->kelompok_id == $kelompok->id)>
                                                    {{ $kelompok->nama_kelompok }}
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
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <x-modal name="create-mahasiswa">
        <form method="post" action="{{ route('mahasiswa.store') }}" class="p-6">
            @csrf

            <h2 class="text-lg font-medium text-gray-900">
                {{ __('Tambah Mahasiswa') }}
            </h2>

            <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <x-input-label for="create_name" value="{{ __('Nama') }}" />
                    <x-text-input id="create_name" name="name" type="text" class="mt-1 block w-full" :value="old('name')" required />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="create_email" value="{{ __('Email') }}" />
                    <x-text-input id="create_email" name="email" type="email" class="mt-1 block w-full" :value="old('email')" required />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="create_password" value="{{ __('Password') }}" />
                    <x-text-input id="create_password" name="password" type="password" class="mt-1 block w-full" required />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="create_nim_nip" value="{{ __('NIM') }}" />
                    <x-text-input id="create_nim_nip" name="nim_nip" type="text" class="mt-1 block w-full" :value="old('nim_nip')" />
                    <x-input-error :messages="$errors->get('nim_nip')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="create_hp_wa" value="{{ __('HP / WA') }}" />
                    <x-text-input id="create_hp_wa" name="hp_wa" type="text" class="mt-1 block w-full" :value="old('hp_wa')" />
                    <x-input-error :messages="$errors->get('hp_wa')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="create_fakultas" value="{{ __('Fakultas') }}" />
                    <x-text-input id="create_fakultas" name="fakultas" type="text" class="mt-1 block w-full" :value="old('fakultas')" />
                    <x-input-error :messages="$errors->get('fakultas')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="create_prodi" value="{{ __('Prodi') }}" />
                    <x-text-input id="create_prodi" name="prodi" type="text" class="mt-1 block w-full" :value="old('prodi')" />
                    <x-input-error :messages="$errors->get('prodi')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="create_kelompok_id" value="{{ __('Kelompok') }}" />
                    <select id="create_kelompok_id" name="kelompok_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">{{ __('- Belum ada kelompok -') }}</option>
                        @foreach ($kelompoks as $kelompok)
                            <option value="{{ $kelompok->id }}" @selected(old('kelompok_id') == $kelompok->id)>
                                {{ $kelompok->nama_kelompok }}
                            </option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('kelompok_id')" class="mt-2" />
                </div>
            </div>

            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">
                    {{ __('Batal') }}
                </x-secondary-button>

                <x-primary-button class="ms-3">
                    {{ __('Simpan') }}
                </x-primary-button>
            </div>
        </form>
    </x-modal>
</x-app-layout>
