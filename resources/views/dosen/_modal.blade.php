<x-modal name="form-dosen" :show="$errors->any()" maxWidth="lg">
    <form method="POST" x-bind:action="action" class="p-4 sm:p-6">
        @csrf
        <input type="hidden" name="_method" value="PUT" x-bind:disabled="!editId">
        <input type="hidden" name="_id" x-bind:value="editId">

        <div class="flex items-center justify-between">
            <h2 class="text-lg font-medium text-gray-900" x-text="editId ? 'Edit Pembimbing Sekolah/Kampus' : 'Tambah Pembimbing Sekolah/Kampus'"></h2>
            <button type="button" x-on:click="$dispatch('close')" class="text-gray-400">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="mt-6">
            <x-input-label for="nama" value="Nama Guru / Dosen Pembimbing" />
            <x-text-input id="nama" name="nama" type="text" class="mt-1 block w-full" x-model="f.nama" />
            <x-input-error class="mt-2" :messages="$errors->get('nama')" x-show="showErrors" />
        </div>

        <div class="mt-4">
            <x-input-label for="nidn" value="NIP / NUPTK / ID Pengajar" />
            <x-text-input id="nidn" name="nidn" type="text" placeholder="Masukkan nomor identitas pengajar" class="mt-1 block w-full" x-model="f.nidn" />
            <x-input-error class="mt-2" :messages="$errors->get('nidn')" x-show="showErrors" />
        </div>

        <div class="mt-4 relative" x-on:click.outside="terbuka.kampus = false">
            <x-input-label for="kampus" value="Pilih Instansi / Sekolah / Kampus" />

            <div class="relative mt-1">
                <input
                    id="kampus"
                    name="kampus"
                    type="text"
                    autocomplete="off"
                    placeholder="Ketik atau pilih instansi..."
                    class="block w-full min-w-0 max-w-full rounded-md border-gray-300 pr-9 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-slate-600 dark:bg-slate-800 dark:text-white dark:placeholder-slate-400"
                    x-model="f.kampus"
                    x-on:input="cari('kampus')"
                    x-on:focus="bukaDropdown('kampus')"
                >
                <button type="button" x-on:click="terbuka.kampus = !terbuka.kampus; if (terbuka.kampus) bukaDropdown('kampus')" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600">
                    <i class="fas fa-chevron-down text-xs"></i>
                </button>
            </div>

            <ul x-show="terbuka.kampus" style="background-color: #ffffff; width: 100%; max-width: 100%; box-sizing: border-box;" class="relative sm:absolute sm:inset-x-0 sm:left-0 sm:right-0 sm:top-full z-50 mt-1 max-h-48 w-full min-w-0 max-w-full overflow-y-auto overflow-x-hidden rounded-md border border-gray-200 bg-white text-sm shadow-md sm:shadow-xl">
                <template x-if="saran.kampus.length === 0">
                    <li class="px-3 py-2.5 text-xs sm:text-sm text-gray-500 italic">Tidak ada yang cocok — ketik nama instansi baru.</li>
                </template>
                <template x-for="nama in saran.kampus" :key="nama">
                    <li x-on:click="pilih('kampus', nama)" x-text="nama" class="cursor-pointer px-3 py-2.5 text-left text-sm leading-snug break-words whitespace-normal border-b border-gray-100 last:border-b-0 hover:bg-indigo-50 active:bg-indigo-100 transition-colors" :class="nama === f.kampus ? 'bg-indigo-50 font-medium text-indigo-700' : 'text-gray-700'"></li>
                </template>
            </ul>

            <x-input-error class="mt-2" :messages="$errors->get('kampus')" x-show="showErrors" />
        </div>

        <div class="mt-4">
            <x-input-label for="email" value="Email" />
            <x-text-input id="email" name="email" type="email" placeholder="nama@instansi.id" class="mt-1 block w-full" x-model="f.email" />
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

        <div class="mt-6 flex justify-end gap-3">
            <x-secondary-button x-on:click="$dispatch('close')">Batal</x-secondary-button>
            <x-primary-button>Simpan</x-primary-button>
        </div>
    </form>
</x-modal>