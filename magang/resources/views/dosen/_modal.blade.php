<x-modal name="form-dosen" :show="$errors->any()" maxWidth="lg">
    <form method="POST" x-bind:action="action" class="p-6">
        @csrf
        <input type="hidden" name="_method" value="PUT" x-bind:disabled="!editId">
        <input type="hidden" name="_id" x-bind:value="editId">

        <div class="flex items-center justify-between">
            <h2 class="text-lg font-medium text-gray-900" x-text="editId ? 'Ubah Dosen' : 'Tambah Dosen'"></h2>
            <button type="button" x-on:click="$dispatch('close')" class="text-gray-400">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="mt-6">
            <x-input-label for="nama" value="Nama Dosen" />
            <x-text-input id="nama" name="nama" type="text" class="mt-1 block w-full" x-model="f.nama" />
            <x-input-error class="mt-2" :messages="$errors->get('nama')" x-show="showErrors" />
        </div>

        <div class="mt-4">
            <x-input-label for="nidn" value="NIDN " />
            <x-text-input id="nidn" name="nidn" type="text" inputmode="numeric" maxlength="10" placeholder="10 angka" class="mt-1 block w-full" x-model="f.nidn" />
            <x-input-error class="mt-2" :messages="$errors->get('nidn')" x-show="showErrors" />
        </div>

        <div class="mt-4">
            <x-input-label for="email" value="Email " />
            <x-text-input id="email" name="email" type="email" placeholder="nama@kampus.ac.id" class="mt-1 block w-full" x-model="f.email" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" x-show="showErrors" />
        </div>

        <div class="mt-4">
            <x-input-label for="hp_wa" value="No. HP/WA " />
            <x-text-input id="hp_wa" name="hp_wa" type="tel" placeholder="08xxxxxxxxxx" class="mt-1 block w-full" x-model="f.hp_wa" />
            <x-input-error class="mt-2" :messages="$errors->get('hp_wa')" x-show="showErrors" />
        </div>

        <div class="mt-4">
            <x-input-label for="fakultas" value="Fakultas " />
            <x-text-input id="fakultas" name="fakultas" type="text" class="mt-1 block w-full" x-model="f.fakultas" />
            <x-input-error class="mt-2" :messages="$errors->get('fakultas')" x-show="showErrors" />
        </div>

        <div class="mt-4">
            <x-input-label for="prodi" value="Prodi " />
            <x-text-input id="prodi" name="prodi" type="text" class="mt-1 block w-full" x-model="f.prodi" />
            <x-input-error class="mt-2" :messages="$errors->get('prodi')" x-show="showErrors" />
        </div>

        <div class="mt-6 flex justify-end gap-3">
            <x-secondary-button x-on:click="$dispatch('close')">Batal</x-secondary-button>
            <x-primary-button>Simpan</x-primary-button>
        </div>
    </form>
</x-modal>