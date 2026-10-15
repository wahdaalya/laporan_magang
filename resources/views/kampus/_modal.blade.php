<x-modal name="form-kampus" :show="$errors->any()" maxWidth="lg">
    <form method="POST" x-bind:action="action" class="p-4 sm:p-6">
        @csrf
        <input type="hidden" name="_method" value="PUT" x-bind:disabled="!editId">
        <input type="hidden" name="_id" x-bind:value="editId">

        <div class="flex items-center justify-between">
            <h2 class="text-lg font-medium text-gray-900" x-text="editId ? 'Edit Instansi' : 'Tambah Instansi'"></h2>
            <button type="button" x-on:click="$dispatch('close')" class="text-gray-400">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="mt-6 relative" :class="terbuka.nama_universitas ? 'z-40' : 'z-10'" x-on:click.outside="terbuka.nama_universitas = false">
            <x-input-label for="nama_universitas" value="Nama Instansi / Sekolah / Kampus" />

            <div class="relative mt-1">
                <input
                    id="nama_universitas"
                    name="nama_universitas"
                    type="text"
                    autocomplete="off"
                    placeholder="Contoh: SMK Negeri 1 Medan / Universitas Sumatera Utara"
                    class="block w-full min-w-0 max-w-full rounded-md border-gray-300 pr-9 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-slate-600 dark:bg-slate-800 dark:text-white dark:placeholder-slate-400"
                    x-model="f.nama_universitas"
                    x-on:input="cari('nama_universitas')"
                    x-on:focus="bukaDropdown('nama_universitas')"
                >
                <button type="button" x-on:click="terbuka.nama_universitas = !terbuka.nama_universitas; if (terbuka.nama_universitas) bukaDropdown('nama_universitas')" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600">
                    <i class="fas fa-chevron-down text-xs"></i>
                </button>
            </div>

            <ul x-show="terbuka.nama_universitas" style="width: 100%; max-width: 100%; box-sizing: border-box;" class="relative sm:absolute sm:inset-x-0 sm:left-0 sm:right-0 sm:top-full z-50 mt-1 max-h-48 w-full min-w-0 max-w-full overflow-y-auto overflow-x-hidden rounded-md border border-gray-200 bg-white text-sm shadow-md sm:shadow-xl dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100">
                <template x-if="saran.nama_universitas.length === 0">
                    <li class="px-3 py-2.5 text-xs sm:text-sm text-gray-500 italic dark:text-slate-400">Tidak ada yang cocok — ketik nama instansi baru.</li>
                </template>
                <template x-for="nama in saran.nama_universitas" :key="nama">
                    <li x-on:click="pilih('nama_universitas', nama)" x-text="nama" class="cursor-pointer px-3 py-2.5 text-left text-sm leading-snug break-words whitespace-normal border-b border-gray-100 dark:border-slate-700 last:border-b-0 text-gray-700 hover:bg-indigo-50 dark:text-slate-100 dark:hover:bg-slate-700" :class="nama === f.nama_universitas ? 'bg-indigo-50 font-medium text-indigo-700 dark:bg-slate-700 dark:text-slate-100' : 'text-gray-700 dark:text-slate-100'"></li>
                </template>
            </ul>

            <x-input-error class="mt-2" :messages="$errors->get('nama_universitas')" x-show="showErrors" />
        </div>

        <div class="mt-4 relative" :class="terbuka.alamat ? 'z-30' : 'z-10'" x-on:click.outside="terbuka.alamat = false">
            <x-input-label for="alamat" value="Alamat" />

            <div class="relative mt-1">
                <input
                    id="alamat"
                    name="alamat"
                    type="text"
                    autocomplete="off"
                    placeholder="Ketik atau pilih alamat..."
                    class="block w-full min-w-0 max-w-full rounded-md border-gray-300 pr-9 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-slate-600 dark:bg-slate-800 dark:text-white dark:placeholder-slate-400"
                    x-model="f.alamat"
                    x-on:input="cari('alamat')"
                    x-on:focus="bukaDropdown('alamat')"
                >
                <button type="button" x-on:click="terbuka.alamat = !terbuka.alamat; if (terbuka.alamat) bukaDropdown('alamat')" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600">
                    <i class="fas fa-chevron-down text-xs"></i>
                </button>
            </div>

            <ul x-show="terbuka.alamat" style="width: 100%; max-width: 100%; box-sizing: border-box;" class="relative sm:absolute sm:inset-x-0 sm:left-0 sm:right-0 sm:top-full z-50 mt-1 max-h-48 w-full min-w-0 max-w-full overflow-y-auto overflow-x-hidden rounded-md border border-gray-200 bg-white text-sm shadow-md sm:shadow-xl dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100">
                <template x-if="saran.alamat.length === 0">
                    <li class="px-3 py-2.5 text-xs sm:text-sm text-gray-500 italic dark:text-slate-400">Tidak ada yang cocok — ketik alamat baru.</li>
                </template>
                <template x-for="nama in saran.alamat" :key="nama">
                    <li x-on:click="pilih('alamat', nama)" x-text="nama" class="cursor-pointer px-3 py-2.5 text-left text-sm leading-snug break-words whitespace-normal border-b border-gray-100 dark:border-slate-700 last:border-b-0 text-gray-700 hover:bg-indigo-50 dark:text-slate-100 dark:hover:bg-slate-700" :class="nama === f.alamat ? 'bg-indigo-50 font-medium text-indigo-700 dark:bg-slate-700 dark:text-slate-100' : 'text-gray-700 dark:text-slate-100'"></li>
                </template>
            </ul>

            <x-input-error class="mt-2" :messages="$errors->get('alamat')" x-show="showErrors" />
        </div>

        <div class="mt-4 relative" :class="terbuka.fakultas ? 'z-20' : 'z-10'" x-on:click.outside="terbuka.fakultas = false">
            <x-input-label for="fakultas" value="Fakultas" />

            <div class="relative mt-1">
                <input
                    id="fakultas"
                    name="fakultas"
                    type="text"
                    autocomplete="off"
                    placeholder="Contoh: Teknik Komputer dan Jaringan / Sistem Informasi"
                    class="block w-full min-w-0 max-w-full rounded-md border-gray-300 pr-9 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-slate-600 dark:bg-slate-800 dark:text-white dark:placeholder-slate-400"
                    x-model="f.fakultas"
                    x-on:input="cari('fakultas')"
                    x-on:focus="bukaDropdown('fakultas')"
                >
                <button type="button" x-on:click="terbuka.fakultas = !terbuka.fakultas; if (terbuka.fakultas) bukaDropdown('fakultas')" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600">
                    <i class="fas fa-chevron-down text-xs"></i>
                </button>
            </div>

            <ul x-show="terbuka.fakultas" style="width: 100%; max-width: 100%; box-sizing: border-box;" class="relative sm:absolute sm:inset-x-0 sm:left-0 sm:right-0 sm:top-full z-50 mt-1 max-h-48 w-full min-w-0 max-w-full overflow-y-auto overflow-x-hidden rounded-md border border-gray-200 bg-white text-sm shadow-md sm:shadow-xl dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100">
                <template x-if="saran.fakultas.length === 0">
                    <li class="px-3 py-2.5 text-xs sm:text-sm text-gray-500 italic dark:text-slate-400">Tidak ada yang cocok — ketik fakultas baru.</li>
                </template>
                <template x-for="nama in saran.fakultas" :key="nama">
                    <li x-on:click="pilih('fakultas', nama)" x-text="nama" class="cursor-pointer px-3 py-2.5 text-left text-sm leading-snug break-words whitespace-normal border-b border-gray-100 dark:border-slate-700 last:border-b-0 text-gray-700 hover:bg-indigo-50 dark:text-slate-100 dark:hover:bg-slate-700" :class="nama === f.fakultas ? 'bg-indigo-50 font-medium text-indigo-700 dark:bg-slate-700 dark:text-slate-100' : 'text-gray-700 dark:text-slate-100'"></li>
                </template>
            </ul>

            <x-input-error class="mt-2" :messages="$errors->get('fakultas')" x-show="showErrors" />
        </div>

        <div class="mt-4 relative" :class="terbuka.prodi ? 'z-10' : 'z-0'" x-on:click.outside="terbuka.prodi = false">
            <x-input-label for="prodi" value="Program Studi (opsional, jika berbeda)" />

            <div class="relative mt-1">
                <input
                    id="prodi"
                    name="prodi"
                    type="text"
                    autocomplete="off"
                    placeholder="Contoh: Sistem Informasi"
                    class="block w-full min-w-0 max-w-full rounded-md border-gray-300 pr-9 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-slate-600 dark:bg-slate-800 dark:text-white dark:placeholder-slate-400"
                    x-model="f.prodi"
                    x-on:input="cari('prodi')"
                    x-on:focus="bukaDropdown('prodi')"
                >
                <button type="button" x-on:click="terbuka.prodi = !terbuka.prodi; if (terbuka.prodi) bukaDropdown('prodi')" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600">
                    <i class="fas fa-chevron-down text-xs"></i>
                </button>
            </div>

            <ul x-show="terbuka.prodi" style="width: 100%; max-width: 100%; box-sizing: border-box;" class="relative sm:absolute sm:inset-x-0 sm:left-0 sm:right-0 sm:top-full z-50 mt-1 max-h-48 w-full min-w-0 max-w-full overflow-y-auto overflow-x-hidden rounded-md border border-gray-200 bg-white text-sm shadow-md sm:shadow-xl dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100">
                <template x-if="saran.prodi.length === 0">
                    <li class="px-3 py-2.5 text-xs sm:text-sm text-gray-500 italic dark:text-slate-400">Tidak ada yang cocok — ketik program studi baru.</li>
                </template>
                <template x-for="nama in saran.prodi" :key="nama">
                    <li x-on:click="pilih('prodi', nama)" x-text="nama" class="cursor-pointer px-3 py-2.5 text-left text-sm leading-snug break-words whitespace-normal border-b border-gray-100 dark:border-slate-700 last:border-b-0 text-gray-700 hover:bg-indigo-50 dark:text-slate-100 dark:hover:bg-slate-700" :class="nama === f.prodi ? 'bg-indigo-50 font-medium text-indigo-700 dark:bg-slate-700 dark:text-slate-100' : 'text-gray-700 dark:text-slate-100'"></li>
                </template>
            </ul>

            <x-input-error class="mt-2" :messages="$errors->get('prodi')" x-show="showErrors" />
        </div>

        <div class="mt-6 flex justify-end gap-3">
            <x-secondary-button type="button" x-on:click="$dispatch('close')">Batal</x-secondary-button>
            <x-primary-button>Simpan</x-primary-button>
        </div>
    </form>
</x-modal>