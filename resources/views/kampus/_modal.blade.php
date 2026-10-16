<x-modal name="form-kampus" :show="$errors->any()" maxWidth="lg">
    <form method="POST" x-bind:action="action" class="p-4 sm:p-6">
        @csrf
        <input type="hidden" name="_method" value="PUT" x-bind:disabled="!editId">
        <input type="hidden" name="_id" x-bind:value="editId">

        <div class="flex items-center justify-between">
            <h2 class="text-lg font-medium text-gray-900" x-text="editId ? 'Edit Instansi' : 'Tambah Instansi'"></h2>
            <button type="button" x-on:click="$dispatch('close')" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="mt-6">
            <x-input-label for="nama_universitas" value="Nama Instansi / Sekolah / Kampus" />
            <x-text-input
                id="nama_universitas"
                name="nama_universitas"
                type="text"
                placeholder="Contoh: SMK Negeri 1 Medan / Universitas Sumatera Utara"
                class="mt-1 block w-full"
                x-model="f.nama_universitas"
                required
            />
            <x-input-error class="mt-2" :messages="$errors->get('nama_universitas')" x-show="showErrors" />
        </div>

        <div class="mt-4">
            <x-input-label for="alamat" value="Alamat" />
            <x-text-input
                id="alamat"
                name="alamat"
                type="text"
                placeholder="Contoh: Jl. Dr. Mansyur No. 9, Medan"
                class="mt-1 block w-full"
                x-model="f.alamat"
            />
            <x-input-error class="mt-2" :messages="$errors->get('alamat')" x-show="showErrors" />
        </div>

        <div class="mt-4">
            <x-input-label for="fakultas" value="Fakultas" />
            @php
                $opsiFakultas = collect([
                    'Fakultas Ilmu Komputer & TI',
                    'Fakultas Teknik',
                    'Fakultas Ekonomi dan Bisnis',
                    'Fakultas Ilmu Sosial dan Ilmu Politik',
                    'Fakultas Hukum',
                    'Fakultas Pertanian',
                    'SMK / Sekolah Menengah Kejuruan',
                ])->merge($kampuses->pluck('fakultas'))->filter()->unique()->values();
            @endphp
            <select
                id="fakultas"
                name="fakultas"
                x-model="f.fakultas"
                class="mt-1 block w-full max-w-full min-w-0 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-slate-600 dark:bg-slate-800 dark:text-white dark:placeholder-slate-400 text-sm truncate"
                style="width: 100%; max-width: 100%; box-sizing: border-box;"
            >
                <option value="">{{ __('- Pilih Fakultas -') }}</option>
                @foreach ($opsiFakultas as $item)
                    <option value="{{ $item }}">{{ $item }}</option>
                @endforeach
            </select>
            <x-input-error class="mt-2" :messages="$errors->get('fakultas')" x-show="showErrors" />
        </div>

        <div class="mt-4">
            <x-input-label for="prodi" value="Program Studi / Jurusan" />
            @php
                $opsiProdi = collect([
                    'Teknologi Informasi',
                    'Teknik Informatika',
                    'Sistem Informasi',
                    'Teknik Komputer dan Jaringan',
                    'Rekayasa Perangkat Lunak',
                    'Teknik Elektro',
                    'Teknik Mesin',
                    'Manajemen',
                    'Akuntansi',
                    'Administrasi Bisnis',
                    'Ilmu Komunikasi',
                ])->merge($kampuses->pluck('prodi'))->filter()->unique()->values();
            @endphp
            <select
                id="prodi"
                name="prodi"
                x-model="f.prodi"
                class="mt-1 block w-full max-w-full min-w-0 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-slate-600 dark:bg-slate-800 dark:text-white dark:placeholder-slate-400 text-sm truncate"
                style="width: 100%; max-width: 100%; box-sizing: border-box;"
            >
                <option value="">{{ __('- Pilih Program Studi / Jurusan -') }}</option>
                @foreach ($opsiProdi as $item)
                    <option value="{{ $item }}">{{ $item }}</option>
                @endforeach
            </select>
            <x-input-error class="mt-2" :messages="$errors->get('prodi')" x-show="showErrors" />
        </div>

        <div class="mt-6 flex justify-end gap-3">
            <x-secondary-button type="button" x-on:click="$dispatch('close')">Batal</x-secondary-button>
            <x-primary-button>Simpan</x-primary-button>
        </div>
    </form>
</x-modal>