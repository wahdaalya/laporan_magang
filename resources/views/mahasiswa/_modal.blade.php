<x-modal name="form-laporan" :show="$errors->any()" maxWidth="lg">
    <form method="POST" x-bind:action="action" enctype="multipart/form-data" class="p-6">
        @csrf
        <input type="hidden" name="_method" value="PUT" x-bind:disabled="!editId">
        <input type="hidden" name="_id" x-bind:value="editId">

        <div class="flex items-center justify-between">
            <h2 class="text-lg font-medium text-gray-900" x-text="editId ? 'Ubah Laporan Kegiatan' : 'Tambah Laporan Kegiatan'"></h2>
            <button type="button" x-on:click="$dispatch('close')" class="text-gray-400">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="mt-6">
            <x-input-label for="tanggal" value="Tanggal" />
            <x-text-input id="tanggal" name="tanggal" type="date" class="mt-1 block w-full" x-model="f.tanggal" />
            <x-input-error class="mt-2" :messages="$errors->get('tanggal')" x-show="showErrors" />
        </div>

        <div class="mt-4">
            <x-input-label for="kegiatan" value="Kegiatan" />
            <x-text-input id="kegiatan" name="kegiatan" type="text" placeholder="Contoh: Membantu input data penumpang" class="mt-1 block w-full" x-model="f.kegiatan" />
            <x-input-error class="mt-2" :messages="$errors->get('kegiatan')" x-show="showErrors" />
        </div>

        <div class="mt-4">
            <x-input-label for="keterangan" value="Keterangan" />
            <textarea id="keterangan" name="keterangan" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" x-model="f.keterangan"></textarea>
            <x-input-error class="mt-2" :messages="$errors->get('keterangan')" x-show="showErrors" />
        </div>

        <div class="mt-4">
            <x-input-label for="foto" value="Foto Bukti Kegiatan" />
            <template x-if="currentFoto">
                <div class="mt-1 mb-2">
                    <p class="text-xs text-gray-500 mb-1">Foto saat ini (biarkan kosong kalau tidak mau ganti):</p>
                    <img :src="currentFoto" class="h-20 rounded border border-gray-200 object-cover">
                </div>
            </template>
            <input id="foto" name="foto" type="file" accept="image/*" class="mt-1 block w-full text-sm text-gray-600 file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:border-slate-600 dark:bg-slate-800 dark:text-white dark:placeholder-slate-400" />
            <x-input-error class="mt-2" :messages="$errors->get('foto')" x-show="showErrors" />
        </div>

        <div class="mt-6 flex justify-end gap-3">
            <x-secondary-button x-on:click="$dispatch('close')">Batal</x-secondary-button>
            <x-primary-button>Simpan</x-primary-button>
        </div>
    </form>
</x-modal>