<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Ubah Departemen') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                <form method="POST" action="{{ route('departemen.update', $departemen) }}">
                    @csrf
                    @method('PUT')

                    <div>
                        <x-input-label for="nama_departemen" value="Nama Departemen" />
                        <x-text-input id="nama_departemen" name="nama_departemen" type="text" class="mt-1 block w-full"
                            :value="old('nama_departemen', $departemen->nama_departemen)"
                            placeholder="Ketik nama departemen..." autocomplete="off" required autofocus />
                        <x-input-error class="mt-2" :messages="$errors->get('nama_departemen')" />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="nama_pimpinan" value="Nama Pimpinan" />
                        <x-text-input id="nama_pimpinan" name="nama_pimpinan" type="text" class="mt-1 block w-full"
                            :value="old('nama_pimpinan', $departemen->nama_pimpinan)"
                            placeholder="Ketik nama pimpinan..." autocomplete="off" />
                        <x-input-error class="mt-2" :messages="$errors->get('nama_pimpinan')" />
                    </div>

                    <div class="mt-6 flex items-center gap-3">
                        <x-primary-button>Simpan</x-primary-button>
                        <a href="{{ route('departemen.index') }}" class="text-sm text-gray-600 hover:underline">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>