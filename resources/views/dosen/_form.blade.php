<div>
    <x-input-label for="nama" value="Nama Dosen" />
    <x-text-input id="nama" name="nama" type="text" class="mt-1 block w-full" :value="old('nama', $dosen->nama ?? '')" autofocus />
    <x-input-error class="mt-2" :messages="$errors->get('nama')" />
</div>

<div class="mt-4">
    <x-input-label for="nidn" value="NIDN (opsional)" />
    <x-text-input id="nidn" name="nidn" type="text" inputmode="numeric" maxlength="10" placeholder="10 angka" class="mt-1 block w-full" :value="old('nidn', $dosen->nidn ?? '')" />
    <x-input-error class="mt-2" :messages="$errors->get('nidn')" />
</div>

<div class="mt-4">
    <x-input-label for="email" value="Email (opsional)" />
    <x-text-input id="email" name="email" type="email" placeholder="nama@kampus.ac.id" class="mt-1 block w-full" :value="old('email', $dosen->email ?? '')" />
    <x-input-error class="mt-2" :messages="$errors->get('email')" />
</div>

<div class="mt-4">
    <x-input-label for="hp_wa" value="No. HP/WA (opsional)" />
    <x-text-input id="hp_wa" name="hp_wa" type="tel" placeholder="08xxxxxxxxxx" class="mt-1 block w-full" :value="old('hp_wa', $dosen->hp_wa ?? '')" />
    <x-input-error class="mt-2" :messages="$errors->get('hp_wa')" />
</div>

<div class="mt-4">
    <x-input-label for="fakultas" value="Fakultas (opsional)" />
    <x-text-input id="fakultas" name="fakultas" type="text" class="mt-1 block w-full" :value="old('fakultas', $dosen->fakultas ?? '')" />
    <x-input-error class="mt-2" :messages="$errors->get('fakultas')" />
</div>

<div class="mt-6 flex items-center gap-3">
    <x-primary-button>Simpan</x-primary-button>
    <a href="{{ route('dosen.index') }}" class="text-sm text-gray-600 hover:underline">Batal</a>
</div>