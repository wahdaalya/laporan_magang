<x-modal name="form-pembimbing" :show="$errors->any()" maxWidth="lg">
    <form method="POST" x-bind:action="action" class="p-6">
        @csrf
        <input type="hidden" name="_method" value="PUT" x-bind:disabled="!editId">
        <input type="hidden" name="_id" x-bind:value="editId">

        <div class="flex items-center justify-between">
            <h2 class="text-lg font-medium text-gray-900" x-text="editId ? 'Ubah Pembimbing' : 'Tambah Pembimbing'"></h2>
            <button type="button" x-on:click="$dispatch('close')" class="text-gray-400">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="mt-6">
            <x-input-label for="nama" value="Nama Pembimbing" />
            <x-text-input id="nama" name="nama" type="text" class="mt-1 block w-full" x-model="f.nama" />
            <x-input-error class="mt-2" :messages="$errors->get('nama')" x-show="showErrors" />
        </div>

        <div class="mt-4">
            <x-input-label for="nip" value="NIP" />
            <x-text-input id="nip" name="nip" type="text" class="mt-1 block w-full" x-model="f.nip" />
            <x-input-error class="mt-2" :messages="$errors->get('nip')" x-show="showErrors" />
        </div>

        <div class="mt-4">
            <x-input-label for="email" value="Email" />
            <x-text-input id="email" name="email" type="email" placeholder="nama@perusahaan.co.id" class="mt-1 block w-full" x-model="f.email" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" x-show="showErrors" />
        </div>

        <div class="mt-4">
    <x-input-label for="hp_wa" value="No. HP/WA" />
    <x-text-input id="hp_wa" name="hp_wa" type="tel" placeholder="08xxxxxxxxxx" class="mt-1 block w-full" x-model="f.hp_wa" />
    <x-input-error class="mt-2" :messages="$errors->get('hp_wa')" x-show="showErrors" />
</div>

<div class="mt-4">
    <x-input-label for="password" value="Password" />
    <x-text-input id="password" name="password" type="password" placeholder="Minimal 8 karakter" class="mt-1 block w-full" x-model="f.password" />
    <p class="mt-1 text-xs text-gray-500" x-show="editId">Kosongkan kalau tidak ingin mengubah password.</p>
    <x-input-error class="mt-2" :messages="$errors->get('password')" x-show="showErrors" />
</div>

<div class="mt-4">
    <x-input-label for="departemen_id" value="Departemen" />
            <select id="departemen_id" name="departemen_id" x-model="f.departemen_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <option value="">-- Pilih Departemen --</option>
                @foreach ($departemens as $departemen)
                    <option value="{{ $departemen->id }}">{{ $departemen->nama_departemen }}</option>
                @endforeach
            </select>
            <x-input-error class="mt-2" :messages="$errors->get('departemen_id')" x-show="showErrors" />
        </div>

        <div class="mt-6 flex justify-end gap-3">
            <x-secondary-button x-on:click="$dispatch('close')">Batal</x-secondary-button>
            <x-primary-button>Simpan</x-primary-button>
        </div>
    </form>
</x-modal>