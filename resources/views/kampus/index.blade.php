<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-6">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Data Kampus') }}
            </h2>

            <x-primary-button type="button" x-data="" x-on:click.prevent="$dispatch('open-modal', 'create-kampus')">
                {{ __('+ Tambah Kampus') }}
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
                    @if ($kampuses->isEmpty())
                        <p class="text-gray-500">Belum ada data kampus.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-sm">
                                <thead class="border-b border-gray-200 text-start text-gray-600">
                                    <tr>
                                        <th class="px-3 py-2 text-start">Universitas</th>
                                        <th class="px-3 py-2 text-start">Alamat</th>
                                        <th class="px-3 py-2 text-start">Fakultas</th>
                                        <th class="px-3 py-2 text-start">Prodi</th>
                                        <th class="px-3 py-2 text-start">Jumlah Kelompok</th>
                                        <th class="px-3 py-2 text-end">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($kampuses as $kampus)
                                        <tr class="border-b border-gray-100">
                                            <td class="px-3 py-2 font-medium">{{ $kampus->nama_universitas }}</td>
                                            <td class="px-3 py-2">{{ $kampus->alamat ?? '-' }}</td>
                                            <td class="px-3 py-2">{{ $kampus->fakultas ?? '-' }}</td>
                                            <td class="px-3 py-2">{{ $kampus->prodi ?? '-' }}</td>
                                            <td class="px-3 py-2">{{ $kampus->kelompoks_count }}</td>
                                            <td class="px-3 py-2 text-end">
                                                <div class="flex items-center justify-end gap-2">
                                                    <x-secondary-button type="button" x-data="" x-on:click.prevent="$dispatch('open-modal', 'edit-kampus-{{ $kampus->id }}')">
                                                        {{ __('Edit') }}
                                                    </x-secondary-button>

                                                    <x-danger-button type="button" x-data="" x-on:click.prevent="$dispatch('open-modal', 'delete-kampus-{{ $kampus->id }}')">
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

                    @foreach ($kampuses as $kampus)
                        <x-modal name="edit-kampus-{{ $kampus->id }}">
                            <form method="post" action="{{ route('kampus.update', $kampus) }}" class="p-6">
                                @csrf
                                @method('put')

                                <h2 class="text-lg font-medium text-gray-900">
                                    {{ __('Edit Kampus') }}: {{ $kampus->nama_universitas }}
                                </h2>

                                <div class="mt-6 grid grid-cols-1 gap-4">
                                    <div>
                                        <x-input-label for="edit_nama_universitas_{{ $kampus->id }}" value="{{ __('Nama Universitas') }}" />
                                        <x-text-input id="edit_nama_universitas_{{ $kampus->id }}" name="nama_universitas" type="text" class="mt-1 block w-full" :value="$kampus->nama_universitas" required />
                                    </div>

                                    <div>
                                        <x-input-label for="edit_alamat_{{ $kampus->id }}" value="{{ __('Alamat') }}" />
                                        <x-text-input id="edit_alamat_{{ $kampus->id }}" name="alamat" type="text" class="mt-1 block w-full" :value="$kampus->alamat" />
                                    </div>

                                    <div>
                                        <x-input-label for="edit_fakultas_{{ $kampus->id }}" value="{{ __('Fakultas') }}" />
                                        <x-text-input id="edit_fakultas_{{ $kampus->id }}" name="fakultas" type="text" class="mt-1 block w-full" :value="$kampus->fakultas" />
                                    </div>

                                    <div>
                                        <x-input-label for="edit_prodi_{{ $kampus->id }}" value="{{ __('Prodi') }}" />
                                        <x-text-input id="edit_prodi_{{ $kampus->id }}" name="prodi" type="text" class="mt-1 block w-full" :value="$kampus->prodi" />
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

                        <x-modal name="delete-kampus-{{ $kampus->id }}" :show="false" maxWidth="md">
                            <div class="p-6">
                                <h2 class="text-lg font-medium text-gray-900">
                                    {{ __('Yakin mau hapus kampus ini?') }}
                                </h2>

                                <p class="mt-1 text-sm text-gray-600">
                                    {{ __('Kampus') }} <strong>{{ $kampus->nama_universitas }}</strong> {{ __('akan dihapus permanen.') }}
                                </p>

                                <div class="mt-6 flex justify-end">
                                    <x-secondary-button type="button" x-on:click="$dispatch('close')">
                                        {{ __('Batal') }}
                                    </x-secondary-button>

                                    <form method="post" action="{{ route('kampus.destroy', $kampus) }}" class="ms-3">
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

    <x-modal name="create-kampus">
        <form method="post" action="{{ route('kampus.store') }}" class="p-6">
            @csrf

            <h2 class="text-lg font-medium text-gray-900">
                {{ __('Tambah Kampus') }}
            </h2>

            <div class="mt-6 grid grid-cols-1 gap-4">
                <div>
                    <x-input-label for="create_nama_universitas" value="{{ __('Nama Universitas') }}" />
                    <x-text-input id="create_nama_universitas" name="nama_universitas" type="text" class="mt-1 block w-full" :value="old('nama_universitas')" required />
                    <x-input-error :messages="$errors->get('nama_universitas')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="create_alamat" value="{{ __('Alamat') }}" />
                    <x-text-input id="create_alamat" name="alamat" type="text" class="mt-1 block w-full" :value="old('alamat')" />
                    <x-input-error :messages="$errors->get('alamat')" class="mt-2" />
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