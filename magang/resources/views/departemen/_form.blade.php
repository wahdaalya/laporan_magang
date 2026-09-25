<div>
    <x-input-label for="nama_departemen" value="Nama Departemen" />
    <select id="nama_departemen" name="nama_departemen" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
        <option value="">-- Pilih Departemen --</option>
        @foreach ($pilihan as $nama)
            <option value="{{ $nama }}" @selected(old('nama_departemen', $departemen->nama_departemen ?? '') === $nama)>
                {{ $nama }}
            </option>
        @endforeach
    </select>
    <x-input-error class="mt-2" :messages="$errors->get('nama_departemen')" />
</div>

<div class="mt-4">
    <x-input-label for="nama_pimpinan" value="Nama Pimpinan" />
    <x-text-input id="nama_pimpinan" name="nama_pimpinan" type="text" class="mt-1 block w-full" :value="old('nama_pimpinan', $departemen->nama_pimpinan ?? '')" />
    <x-input-error class="mt-2" :messages="$errors->get('nama_pimpinan')" />
</div>

<div class="mt-6 flex items-center gap-3">
    <x-primary-button>Simpan</x-primary-button>
    <a href="{{ route('departemen.index') }}" class="text-sm text-gray-600 hover:underline">Batal</a>
</div>