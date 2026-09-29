<div
    x-data="{
        sumber: {
            departemen: @js($pilihan),
            pimpinan: @js($pilihanPimpinan),
        },
        saran: { departemen: [], pimpinan: [] },
        terbuka: { departemen: false, pimpinan: false },
        nilai: {
            departemen: @js(old('nama_departemen', $departemen->nama_departemen ?? '')),
            pimpinan: @js(old('nama_pimpinan', $departemen->nama_pimpinan ?? '')),
        },
        cari(field) {
            const q = this.nilai[field].trim().toLowerCase();
            this.saran[field] = q === ''
                ? this.sumber[field]
                : this.sumber[field].filter(n => n.toLowerCase().includes(q));
            this.terbuka[field] = true;
        },
        bukaDropdown(field) {
            this.saran[field] = this.sumber[field];
            this.terbuka[field] = true;
        },
        pilih(field, nama) {
            this.nilai[field] = nama;
            this.terbuka[field] = false;
        },
    }"
>
    <div class="relative" x-on:click.outside="terbuka.departemen = false">
        <x-input-label for="nama_departemen" value="Nama Departemen" />

        <div class="relative mt-1">
            <input
                id="nama_departemen"
                name="nama_departemen"
                type="text"
                autocomplete="off"
                placeholder="Ketik atau pilih nama departemen..."
                class="block w-full rounded-md border-gray-300 pr-9 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                x-model="nilai.departemen"
                x-on:input="cari('departemen')"
                x-on:focus="bukaDropdown('departemen')"
            >
            <button type="button" x-on:click="terbuka.departemen = !terbuka.departemen; if (terbuka.departemen) bukaDropdown('departemen')" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600">
                <i class="fas fa-chevron-down text-xs"></i>
            </button>
        </div>

        <ul x-show="terbuka.departemen" style="background-color: #ffffff;" class="absolute z-50 mt-1 max-h-48 w-full overflow-auto rounded-md border border-gray-200 bg-white text-sm shadow-lg">
            <template x-if="saran.departemen.length === 0">
                <li class="px-3 py-2 text-gray-400">Tidak ada yang cocok — ketik nama departemen baru.</li>
            </template>
            <template x-for="nama in saran.departemen" :key="nama">
                <li x-on:click="pilih('departemen', nama)" x-text="nama" class="cursor-pointer px-3 py-2 hover:bg-indigo-50" :class="nama === nilai.departemen ? 'bg-indigo-50 font-medium text-indigo-700' : 'text-gray-700'"></li>
            </template>
        </ul>

        <x-input-error class="mt-2" :messages="$errors->get('nama_departemen')" />
    </div>

    <div class="mt-4 relative" x-on:click.outside="terbuka.pimpinan = false">
        <x-input-label for="nama_pimpinan" value="Nama Pimpinan" />

        <div class="relative mt-1">
            <input
                id="nama_pimpinan"
                name="nama_pimpinan"
                type="text"
                autocomplete="off"
                placeholder="Ketik atau pilih nama pimpinan..."
                class="block w-full rounded-md border-gray-300 pr-9 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                x-model="nilai.pimpinan"
                x-on:input="cari('pimpinan')"
                x-on:focus="bukaDropdown('pimpinan')"
            >
            <button type="button" x-on:click="terbuka.pimpinan = !terbuka.pimpinan; if (terbuka.pimpinan) bukaDropdown('pimpinan')" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600">
                <i class="fas fa-chevron-down text-xs"></i>
            </button>
        </div>

        <ul x-show="terbuka.pimpinan" style="background-color: #ffffff;" class="absolute z-50 mt-1 max-h-48 w-full overflow-auto rounded-md border border-gray-200 bg-white text-sm shadow-lg">
            <template x-if="saran.pimpinan.length === 0">
                <li class="px-3 py-2 text-gray-400">Tidak ada yang cocok — ketik nama pimpinan baru.</li>
            </template>
            <template x-for="nama in saran.pimpinan" :key="nama">
                <li x-on:click="pilih('pimpinan', nama)" x-text="nama" class="cursor-pointer px-3 py-2 hover:bg-indigo-50" :class="nama === nilai.pimpinan ? 'bg-indigo-50 font-medium text-indigo-700' : 'text-gray-700'"></li>
            </template>
        </ul>

        <x-input-error class="mt-2" :messages="$errors->get('nama_pimpinan')" />
    </div>

    <div class="mt-6 flex items-center gap-3">
        <x-primary-button>Simpan</x-primary-button>
        <a href="{{ route('departemen.index') }}" class="text-sm text-gray-600 hover:underline">Batal</a>
    </div>
</div>