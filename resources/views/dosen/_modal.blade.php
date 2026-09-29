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
            <x-input-label for="nidn" value="NIDN" />
            <x-text-input id="nidn" name="nidn" type="text" inputmode="numeric" maxlength="10" placeholder="10 angka" class="mt-1 block w-full" x-model="f.nidn" />
            <x-input-error class="mt-2" :messages="$errors->get('nidn')" x-show="showErrors" />
        </div>

        <div class="mt-4 relative" x-on:click.outside="terbuka.kampus = false">
            <x-input-label for="kampus" value="Kampus" />

            <div class="relative mt-1">
                <input
                    id="kampus"
                    name="kampus"
                    type="text"
                    autocomplete="off"
                    placeholder="Ketik atau pilih kampus..."
                    class="block w-full rounded-md border-gray-300 pr-9 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    x-model="f.kampus"
                    x-on:input="cari('kampus')"
                    x-on:focus="bukaDropdown('kampus')"
                >
                <button type="button" x-on:click="terbuka.kampus = !terbuka.kampus; if (terbuka.kampus) bukaDropdown('kampus')" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600">
                    <i class="fas fa-chevron-down text-xs"></i>
                </button>
            </div>

            <ul x-show="terbuka.kampus" style="background-color: #ffffff;" class="absolute z-50 mt-1 max-h-48 w-full overflow-auto rounded-md border border-gray-200 bg-white text-sm shadow-lg">
                <template x-if="saran.kampus.length === 0">
                    <li class="px-3 py-2 text-gray-400">Tidak ada yang cocok — ketik nama kampus baru.</li>
                </template>
                <template x-for="nama in saran.kampus" :key="nama">
                    <li x-on:click="pilih('kampus', nama)" x-text="nama" class="cursor-pointer px-3 py-2 hover:bg-indigo-50" :class="nama === f.kampus ? 'bg-indigo-50 font-medium text-indigo-700' : 'text-gray-700'"></li>
                </template>
            </ul>

            <x-input-error class="mt-2" :messages="$errors->get('kampus')" x-show="showErrors" />
        </div>

        <div class="mt-4">
            <x-input-label for="email" value="Email" />
            <x-text-input id="email" name="email" type="email" placeholder="nama@kampus.ac.id" class="mt-1 block w-full" x-model="f.email" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" x-show="showErrors" />
        </div>

        <div class="mt-4">
            <x-input-label for="password" value="Password" />
            <x-text-input id="password" name="password" type="password" placeholder="Minimal 8 karakter" class="mt-1 block w-full" x-model="f.password" />
            <p class="mt-1 text-xs text-gray-500" x-show="editId">Kosongkan kalau tidak ingin mengubah password.</p>
            <x-input-error class="mt-2" :messages="$errors->get('password')" x-show="showErrors" />
        </div>

        <div class="mt-4">
            <x-input-label for="hp_wa" value="No. HP/WA" />
            <x-text-input id="hp_wa" name="hp_wa" type="tel" placeholder="08xxxxxxxxxx" class="mt-1 block w-full" x-model="f.hp_wa" />
            <x-input-error class="mt-2" :messages="$errors->get('hp_wa')" x-show="showErrors" />
        </div>

        <div class="mt-4 relative" x-on:click.outside="terbuka.fakultas = false">
            <x-input-label for="fakultas" value="Fakultas" />

            <div class="relative mt-1">
                <input
                    id="fakultas"
                    name="fakultas"
                    type="text"
                    autocomplete="off"
                    placeholder="Ketik atau pilih fakultas..."
                    class="block w-full rounded-md border-gray-300 pr-9 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    x-model="f.fakultas"
                    x-on:input="cari('fakultas')"
                    x-on:focus="bukaDropdown('fakultas')"
                >
                <button type="button" x-on:click="terbuka.fakultas = !terbuka.fakultas; if (terbuka.fakultas) bukaDropdown('fakultas')" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600">
                    <i class="fas fa-chevron-down text-xs"></i>
                </button>
            </div>

            <ul x-show="terbuka.fakultas" style="background-color: #ffffff;" class="absolute z-50 mt-1 max-h-48 w-full overflow-auto rounded-md border border-gray-200 bg-white text-sm shadow-lg">
                <template x-if="saran.fakultas.length === 0">
                    <li class="px-3 py-2 text-gray-400">Tidak ada yang cocok — ketik fakultas baru.</li>
                </template>
                <template x-for="nama in saran.fakultas" :key="nama">
                    <li x-on:click="pilih('fakultas', nama)" x-text="nama" class="cursor-pointer px-3 py-2 hover:bg-indigo-50" :class="nama === f.fakultas ? 'bg-indigo-50 font-medium text-indigo-700' : 'text-gray-700'"></li>
                </template>
            </ul>

            <x-input-error class="mt-2" :messages="$errors->get('fakultas')" x-show="showErrors" />
        </div>

        <div class="mt-4 relative" x-on:click.outside="terbuka.prodi = false">
            <x-input-label for="prodi" value="Prodi" />

            <div class="relative mt-1">
                <input
                    id="prodi"
                    name="prodi"
                    type="text"
                    autocomplete="off"
                    placeholder="Ketik atau pilih prodi..."
                    class="block w-full rounded-md border-gray-300 pr-9 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    x-model="f.prodi"
                    x-on:input="cari('prodi')"
                    x-on:focus="bukaDropdown('prodi')"
                >
                <button type="button" x-on:click="terbuka.prodi = !terbuka.prodi; if (terbuka.prodi) bukaDropdown('prodi')" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600">
                    <i class="fas fa-chevron-down text-xs"></i>
                </button>
            </div>

            <ul x-show="terbuka.prodi" style="background-color: #ffffff;" class="absolute z-50 mt-1 max-h-48 w-full overflow-auto rounded-md border border-gray-200 bg-white text-sm shadow-lg">
                <template x-if="saran.prodi.length === 0">
                    <li class="px-3 py-2 text-gray-400">Tidak ada yang cocok — ketik prodi baru.</li>
                </template>
                <template x-for="nama in saran.prodi" :key="nama">
                    <li x-on:click="pilih('prodi', nama)" x-text="nama" class="cursor-pointer px-3 py-2 hover:bg-indigo-50" :class="nama === f.prodi ? 'bg-indigo-50 font-medium text-indigo-700' : 'text-gray-700'"></li>
                </template>
            </ul>

            <x-input-error class="mt-2" :messages="$errors->get('prodi')" x-show="showErrors" />
        </div>

        <div class="mt-6 flex justify-end gap-3">
            <x-secondary-button x-on:click="$dispatch('close')">Batal</x-secondary-button>
            <x-primary-button>Simpan</x-primary-button>
        </div>
    </form>
</x-modal>