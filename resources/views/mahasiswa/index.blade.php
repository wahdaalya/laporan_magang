<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Data Peserta Magang / PKL') }}
        </h2>
    </x-slot>

        <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">
            @if (session('status'))
                <div class="rounded-md bg-green-50 px-4 py-3 text-sm text-green-700">
                    {{ session('status') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div
                    class="p-6 text-gray-900"
                    x-data="{
                        deleteId: null,
                        deleteNama: '',
                        hapus(button) {
                            this.deleteId = button.dataset.mahasiswaId;
                            this.deleteNama = button.dataset.mahasiswaName;
                        },
                        get deleteAction() {
                            return this.deleteId ? '{{ url('mahasiswa') }}/' + this.deleteId : '';
                        },
                    }"
                >
                    <x-search-bar :action="route('mahasiswa.index')" :value="$q" :tahun="$tahun" :daftar-tahun="$daftarTahun" placeholder="Cari nama, NIM, email, kampus, atau kelompok" />

                    <div class="mb-4 flex justify-end">
                        <button type="button" x-data="" x-on:click.prevent="$dispatch('open-modal', 'create-mahasiswa')" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-3 py-1.5 text-sm font-medium text-white hover:bg-blue-700">
                            <i class="fas fa-plus"></i> Tambah Peserta
                        </button>
                    </div>

                    @if ($mahasiswas->isEmpty())
                        @if ($q !== '')
                            <div class="text-sm text-gray-600">
                                <p>Data tidak ditemukan untuk pencarian '{{ $q }}'</p>
                            </div>
                        @else
                            <p class="text-gray-500">Belum ada data peserta.</p>
                        @endif
                    @else
                        <div class="overflow-x-auto rounded-xl border border-gray-200 bg-white dark:border-slate-700 dark:bg-slate-800">
                            <table class="min-w-max w-full text-left text-sm dark:border-slate-700">
                                <thead class="border-b border-gray-200 bg-gray-100 text-xs font-semibold uppercase tracking-wide text-gray-500 dark:border-slate-700 dark:bg-slate-900/80 dark:text-slate-300">
                                    <tr>
                                        <th class="whitespace-nowrap px-4 py-3 align-middle text-left">Nama</th>
                                        <th class="whitespace-nowrap px-4 py-3 align-middle text-left">NIM / NISN</th>
                                        <th class="whitespace-nowrap px-4 py-3 align-middle text-left">Instansi Pendidikan</th>
                                        <th class="whitespace-nowrap px-4 py-3 align-middle text-left">Jurusan / Program Studi</th>
                                        <th class="whitespace-nowrap px-4 py-3 align-middle text-left">Kelompok</th>
                                        <th class="whitespace-nowrap px-4 py-3 align-middle text-left">Pembimbing Sekolah/Kampus</th>
                                        <th class="whitespace-nowrap px-4 py-3 align-middle text-left">Pembimbing Lapangan</th>
                                        <th class="whitespace-nowrap px-4 py-3 align-middle text-center">Laporan</th>
                                        <th class="whitespace-nowrap px-4 py-3 align-middle text-right">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 bg-white text-slate-800 dark:divide-slate-700 dark:bg-slate-800 dark:text-slate-200">
                                    @foreach ($mahasiswas as $mahasiswa)
                                        <tr class="bg-white hover:bg-gray-50 dark:bg-slate-800 dark:hover:bg-slate-700/50">
                                            <td class="px-4 py-3 align-middle whitespace-nowrap">
                                                <p class="font-medium text-gray-900">{{ $mahasiswa->name }}</p>
                                                @if ($mahasiswa->email)
                                                    <a href="mailto:{{ $mahasiswa->email }}" class="text-xs text-gray-500 hover:text-blue-700 hover:underline">{{ $mahasiswa->email }}</a>
                                                @else
                                                    <p class="text-xs text-gray-500">-</p>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 align-middle whitespace-nowrap">{{ $mahasiswa->nim_nip ?? '-' }}</td>
                                            <td class="px-4 py-3 align-middle whitespace-nowrap">{{ $mahasiswa->kampus ?? '-' }}</td>
                                            <td class="whitespace-nowrap px-4 py-3 align-middle">
                                                @if ($mahasiswa->fakultas || $mahasiswa->prodi)
                                                    {{ trim($mahasiswa->fakultas.' / '.$mahasiswa->prodi, ' /') }}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 align-middle whitespace-nowrap">{{ $mahasiswa->kelompok?->nama_kelompok ?? '-' }}</td>
                                            <td class="whitespace-nowrap px-4 py-3 align-middle">{{ $mahasiswa->dosen?->nama ?? '-' }}</td>
                                            <td class="max-w-[180px] break-words px-4 py-3 align-middle">{{ $mahasiswa->mentor?->nama ?? '-' }}</td>
                                            <td class="px-4 py-3 align-middle text-center">{{ $mahasiswa->laporans_count }}</td>
                                            <td class="px-4 py-3 align-middle whitespace-nowrap text-right">
    <div class="flex items-center justify-end gap-2">
        <form method="POST" action="{{ route('mahasiswa.impersonate', $mahasiswa->id) }}">
            @csrf
            <x-secondary-button type="submit" title="Masuk sebagai {{ $mahasiswa->name }}">
                Login sebagai Akun Ini
            </x-secondary-button>
        </form>
        <x-secondary-button type="button" x-data="" x-on:click.prevent="$dispatch('open-modal', 'edit-mahasiswa-{{ $mahasiswa->id }}')">
            {{ __('Edit') }}
        </x-secondary-button>
        <x-danger-button
            type="button"
            data-mahasiswa-id="{{ $mahasiswa->id }}"
            data-mahasiswa-name="{{ $mahasiswa->name }}"
            x-on:click="hapus($el); $dispatch('open-modal', 'confirm-delete-mahasiswa')"
            aria-label="Hapus peserta {{ $mahasiswa->name }}"
        >
            {{ __('Hapus') }}
        </x-danger-button>
    </div>
</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                                                </div>
                    @endif

                    <div class="mt-4 flex flex-col gap-3 border-t border-gray-100 pt-4 text-sm text-gray-600 sm:flex-row sm:items-center sm:justify-between">
                        <p>Menampilkan {{ $mahasiswas->firstItem() ?? 0 }}-{{ $mahasiswas->lastItem() ?? 0 }} dari {{ $mahasiswas->total() }} data</p>
                        <div class="self-start sm:self-auto">{{ $mahasiswas->links() }}</div>
                    </div>

                    @foreach ($mahasiswas as $mahasiswa)
                        <x-modal name="edit-mahasiswa-{{ $mahasiswa->id }}">
                            <form
                                method="post"
                                action="{{ route('mahasiswa.update', $mahasiswa) }}"
                                class="p-6"
                                x-data="{
                                    sumber: {
                                        kampus: @js($kampuses->pluck('nama_universitas')->filter()->unique()->values()),
                                        fakultas: @js($kampuses->pluck('fakultas')->filter()->unique()->values()),
                                        prodi: @js($kampuses->pluck('prodi')->filter()->unique()->values()),
                                    },
                                    saran: { kampus: [], fakultas: [], prodi: [] },
                                    terbuka: { kampus: false, fakultas: false, prodi: false },
                                    nilai: { kampus: @js($mahasiswa->kampus ?? ''), fakultas: @js($mahasiswa->fakultas ?? ''), prodi: @js($mahasiswa->prodi ?? '') },
                                    cari(field) {
                                        const q = this.nilai[field].trim().toLowerCase();
                                        this.saran[field] = q === '' ? this.sumber[field] : this.sumber[field].filter(n => n.toLowerCase().includes(q));
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
                                @csrf
                                @method('put')

                                <h2 class="text-lg font-medium text-gray-900">
                                    {{ __('Edit Peserta') }}: {{ $mahasiswa->name }}
                                </h2>

                                <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-2">
                                    <div>
                                        <x-input-label for="edit_name_{{ $mahasiswa->id }}" value="{{ __('Nama') }}" />
                                        <x-text-input id="edit_name_{{ $mahasiswa->id }}" name="name" type="text" class="mt-1 block w-full" :value="$mahasiswa->name" required />
                                    </div>

                                    <div>
                                        <x-input-label for="edit_email_{{ $mahasiswa->id }}" value="{{ __('Email') }}" />
                                        <x-text-input id="edit_email_{{ $mahasiswa->id }}" name="email" type="email" class="mt-1 block w-full" :value="$mahasiswa->email" required />
                                    </div>

                                    <div>
                                        <x-input-label for="edit_password_{{ $mahasiswa->id }}" value="{{ __('Password (kosongkan jika tidak diubah)') }}" />
                                        <x-text-input id="edit_password_{{ $mahasiswa->id }}" name="password" type="password" class="mt-1 block w-full" />
                                    </div>

                                    <div>
                                        <x-input-label for="edit_nim_nip_{{ $mahasiswa->id }}" value="{{ __('NIM / NISN') }}" />
                                        <x-text-input id="edit_nim_nip_{{ $mahasiswa->id }}" name="nim_nip" type="text" class="mt-1 block w-full" :value="$mahasiswa->nim_nip" />
                                    </div>

                                    <div>
                                        <x-input-label for="edit_hp_wa_{{ $mahasiswa->id }}" value="{{ __('HP / WA') }}" />
                                        <x-text-input id="edit_hp_wa_{{ $mahasiswa->id }}" name="hp_wa" type="text" class="mt-1 block w-full" :value="$mahasiswa->hp_wa" />
                                    </div>

                                    <div class="relative" x-on:click.outside="terbuka.kampus = false">
                                        <x-input-label for="edit_kampus_{{ $mahasiswa->id }}" value="{{ __('Instansi / Sekolah / Kampus') }}" />
                                        <div class="relative mt-1">
                                            <input id="edit_kampus_{{ $mahasiswa->id }}" name="kampus" type="text" autocomplete="off" placeholder="Ketik atau pilih instansi..." class="block w-full rounded-md border-gray-300 pr-9 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-slate-600 dark:bg-slate-800 dark:text-white dark:placeholder-slate-400" x-model="nilai.kampus" x-on:input="cari('kampus')" x-on:focus="bukaDropdown('kampus')">
                                            <button type="button" x-on:click="terbuka.kampus = !terbuka.kampus; if (terbuka.kampus) bukaDropdown('kampus')" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600">
                                                <i class="fas fa-chevron-down text-xs"></i>
                                            </button>
                                        </div>
                                        <ul x-show="terbuka.kampus" style="background-color: #ffffff;" class="absolute z-50 mt-1 max-h-48 w-full overflow-auto rounded-md border border-gray-200 bg-white text-sm shadow-lg">
                                            <template x-if="saran.kampus.length === 0">
                                                <li class="px-3 py-2 text-gray-400">Tidak ada yang cocok — ketik nama instansi baru.</li>
                                            </template>
                                            <template x-for="nama in saran.kampus" :key="nama">
                                                <li x-on:click="pilih('kampus', nama)" x-text="nama" class="cursor-pointer px-3 py-2 hover:bg-indigo-50" :class="nama === nilai.kampus ? 'bg-indigo-50 font-medium text-indigo-700' : 'text-gray-700'"></li>
                                            </template>
                                        </ul>
                                    </div>

                                    <div class="relative" x-on:click.outside="terbuka.fakultas = false">
                                        <x-input-label for="edit_fakultas_{{ $mahasiswa->id }}" value="{{ __('Fakultas') }}" />
                                        <div class="relative mt-1">
                                            <input id="edit_fakultas_{{ $mahasiswa->id }}" name="fakultas" type="text" autocomplete="off" placeholder="Ketik atau pilih fakultas..." class="block w-full rounded-md border-gray-300 pr-9 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-slate-600 dark:bg-slate-800 dark:text-white dark:placeholder-slate-400" x-model="nilai.fakultas" x-on:input="cari('fakultas')" x-on:focus="bukaDropdown('fakultas')">
                                            <button type="button" x-on:click="terbuka.fakultas = !terbuka.fakultas; if (terbuka.fakultas) bukaDropdown('fakultas')" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600">
                                                <i class="fas fa-chevron-down text-xs"></i>
                                            </button>
                                        </div>
                                        <ul x-show="terbuka.fakultas" style="background-color: #ffffff;" class="absolute z-50 mt-1 max-h-48 w-full overflow-auto rounded-md border border-gray-200 bg-white text-sm shadow-lg">
                                            <template x-if="saran.fakultas.length === 0">
                                                <li class="px-3 py-2 text-gray-400">Tidak ada yang cocok — ketik fakultas baru.</li>
                                            </template>
                                            <template x-for="nama in saran.fakultas" :key="nama">
                                                <li x-on:click="pilih('fakultas', nama)" x-text="nama" class="cursor-pointer px-3 py-2 hover:bg-indigo-50" :class="nama === nilai.fakultas ? 'bg-indigo-50 font-medium text-indigo-700' : 'text-gray-700'"></li>
                                            </template>
                                        </ul>
                                    </div>

                                    <div class="relative" x-on:click.outside="terbuka.prodi = false">
                                        <x-input-label for="edit_prodi_{{ $mahasiswa->id }}" value="{{ __('Program Studi') }}" />
                                        <div class="relative mt-1">
                                            <input id="edit_prodi_{{ $mahasiswa->id }}" name="prodi" type="text" autocomplete="off" placeholder="Ketik atau pilih program studi..." class="block w-full rounded-md border-gray-300 pr-9 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-slate-600 dark:bg-slate-800 dark:text-white dark:placeholder-slate-400" x-model="nilai.prodi" x-on:input="cari('prodi')" x-on:focus="bukaDropdown('prodi')">
                                            <button type="button" x-on:click="terbuka.prodi = !terbuka.prodi; if (terbuka.prodi) bukaDropdown('prodi')" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600">
                                                <i class="fas fa-chevron-down text-xs"></i>
                                            </button>
                                        </div>
                                        <ul x-show="terbuka.prodi" style="background-color: #ffffff;" class="absolute z-50 mt-1 max-h-48 w-full overflow-auto rounded-md border border-gray-200 bg-white text-sm shadow-lg">
                                            <template x-if="saran.prodi.length === 0">
                                                <li class="px-3 py-2 text-gray-400">Tidak ada yang cocok — ketik program studi baru.</li>
                                            </template>
                                            <template x-for="nama in saran.prodi" :key="nama">
                                                <li x-on:click="pilih('prodi', nama)" x-text="nama" class="cursor-pointer px-3 py-2 hover:bg-indigo-50" :class="nama === nilai.prodi ? 'bg-indigo-50 font-medium text-indigo-700' : 'text-gray-700'"></li>
                                            </template>
                                        </ul>
                                    </div>

                                    <div>
                                        <x-input-label for="edit_kelompok_id_{{ $mahasiswa->id }}" value="{{ __('Kelompok') }}" />
                                        <select id="edit_kelompok_id_{{ $mahasiswa->id }}" name="kelompok_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-slate-600 dark:bg-slate-800 dark:text-white dark:placeholder-slate-400">
                                            <option value="">{{ __('- Belum ada kelompok -') }}</option>
                                            @foreach ($kelompoks as $kelompok)
                                                <option value="{{ $kelompok->id }}" @selected($mahasiswa->kelompok_id == $kelompok->id)>
                                                    {{ $kelompok->nama_kelompok }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="mt-6 flex justify-end">
                                    <x-secondary-button type="button" x-on:click="$dispatch('close')">
                                        {{ __('Batal') }}
                                    </x-secondary-button>

                                    <x-primary-button class="ms-3">
                                        {{ __('Simpan Perubahan') }}
                                    </x-primary-button>
                                </div>
                            </form>
                        </x-modal>
                    @endforeach

                    @include('mahasiswa._delete_modal')
                </div>
            </div>
        </div>
    </div>

    <x-modal name="create-mahasiswa">
        <form
            method="post"
            action="{{ route('mahasiswa.store') }}"
            class="p-6"
            x-data="{
                sumber: {
                    kampus: @js($kampuses->pluck('nama_universitas')->filter()->unique()->values()),
                    fakultas: @js($kampuses->pluck('fakultas')->filter()->unique()->values()),
                    prodi: @js($kampuses->pluck('prodi')->filter()->unique()->values()),
                },
                saran: { kampus: [], fakultas: [], prodi: [] },
                terbuka: { kampus: false, fakultas: false, prodi: false },
                nilai: { kampus: @js(old('kampus', '')), fakultas: @js(old('fakultas', '')), prodi: @js(old('prodi', '')) },
                cari(field) {
                    const q = this.nilai[field].trim().toLowerCase();
                    this.saran[field] = q === '' ? this.sumber[field] : this.sumber[field].filter(n => n.toLowerCase().includes(q));
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
            @csrf

            <h2 class="text-lg font-medium text-gray-900">
                {{ __('Tambah Peserta Magang / PKL') }}
            </h2>

            <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div class="sm:col-span-2" x-data="{
                    kelompokTampil: '',
                    pilih(select) {
                        const opt = select.options[select.selectedIndex];
                        this.kelompokTampil = opt.dataset.kelompok ?? '';
                        this.$refs.calonAnggotaId.value = opt.value ? opt.dataset.id : '';
                        this.$refs.kelompokId.value = opt.value ? opt.dataset.kelompokId : '';
                    },
                }">
                    <x-input-label for="create_name" value="{{ __('Nama Peserta') }}" />
                    <select id="create_name" name="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-slate-600 dark:bg-slate-800 dark:text-white dark:placeholder-slate-400" x-on:change="pilih($event.target)" required>
                        <option value="">{{ __('- Pilih Nama -') }}</option>
                        @foreach ($calonAnggotas as $calon)
                            <option
                                value="{{ $calon->nama }}"
                                data-id="{{ $calon->id }}"
                                data-kelompok-id="{{ $calon->kelompok_id }}"
                                data-kelompok="{{ $calon->kelompok?->nama_kelompok ?? '-' }}"
                            >
                                {{ $calon->nama }} ({{ $calon->kelompok?->nama_kelompok ?? '-' }})
                            </option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />

                    @if ($calonAnggotas->isEmpty())
                        <p class="mt-2 text-sm text-gray-500">{{ __('Belum ada calon anggota. Tambahkan nama lewat form Tambah Kelompok terlebih dahulu.') }}</p>
                    @endif

                    <input type="hidden" name="calon_anggota_id" x-ref="calonAnggotaId">

                    <div class="mt-3">
                        <x-input-label value="{{ __('Kelompok') }}" />
                        <div class="mt-1 block w-full rounded-md border border-gray-300 bg-gray-100 px-3 py-2 text-sm text-gray-700" x-text="kelompokTampil || '{{ __('- Pilih nama dulu -') }}'"></div>
                        <input type="hidden" name="kelompok_id" x-ref="kelompokId">
                    </div>
                </div>

                <div>
                    <x-input-label for="create_email" value="{{ __('Email') }}" />
                    <x-text-input id="create_email" name="email" type="email" class="mt-1 block w-full" :value="old('email')" required />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="create_password" value="{{ __('Password') }}" />
                    <x-text-input id="create_password" name="password" type="password" class="mt-1 block w-full" required />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="create_nim_nip" value="{{ __('NIM / NISN') }}" />
                    <x-text-input id="create_nim_nip" name="nim_nip" type="text" class="mt-1 block w-full" :value="old('nim_nip')" />
                    <x-input-error :messages="$errors->get('nim_nip')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="create_hp_wa" value="{{ __('HP / WA') }}" />
                    <x-text-input id="create_hp_wa" name="hp_wa" type="text" class="mt-1 block w-full" :value="old('hp_wa')" />
                    <x-input-error :messages="$errors->get('hp_wa')" class="mt-2" />
                </div>

                <div class="relative" x-on:click.outside="terbuka.kampus = false">
                    <x-input-label for="create_kampus" value="{{ __('Instansi / Sekolah / Kampus') }}" />
                    <div class="relative mt-1">
                        <input id="create_kampus" name="kampus" type="text" autocomplete="off" placeholder="Ketik atau pilih instansi..." class="block w-full rounded-md border-gray-300 pr-9 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-slate-600 dark:bg-slate-800 dark:text-white dark:placeholder-slate-400" x-model="nilai.kampus" x-on:input="cari('kampus')" x-on:focus="bukaDropdown('kampus')">
                        <button type="button" x-on:click="terbuka.kampus = !terbuka.kampus; if (terbuka.kampus) bukaDropdown('kampus')" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600">
                            <i class="fas fa-chevron-down text-xs"></i>
                        </button>
                    </div>
                    <ul x-show="terbuka.kampus" style="background-color: #ffffff;" class="absolute z-50 mt-1 max-h-48 w-full overflow-auto rounded-md border border-gray-200 bg-white text-sm shadow-lg">
                        <template x-if="saran.kampus.length === 0">
                            <li class="px-3 py-2 text-gray-400">Tidak ada yang cocok — ketik nama instansi baru.</li>
                        </template>
                        <template x-for="nama in saran.kampus" :key="nama">
                            <li x-on:click="pilih('kampus', nama)" x-text="nama" class="cursor-pointer px-3 py-2 hover:bg-indigo-50" :class="nama === nilai.kampus ? 'bg-indigo-50 font-medium text-indigo-700' : 'text-gray-700'"></li>
                        </template>
                    </ul>
                    <x-input-error :messages="$errors->get('kampus')" class="mt-2" />
                </div>

                <div class="relative" x-on:click.outside="terbuka.fakultas = false">
                    <x-input-label for="create_fakultas" value="{{ __('Fakultas') }}" />
                    <div class="relative mt-1">
                        <input id="create_fakultas" name="fakultas" type="text" autocomplete="off" placeholder="Ketik atau pilih fakultas..." class="block w-full rounded-md border-gray-300 pr-9 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-slate-600 dark:bg-slate-800 dark:text-white dark:placeholder-slate-400" x-model="nilai.fakultas" x-on:input="cari('fakultas')" x-on:focus="bukaDropdown('fakultas')">
                        <button type="button" x-on:click="terbuka.fakultas = !terbuka.fakultas; if (terbuka.fakultas) bukaDropdown('fakultas')" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600">
                            <i class="fas fa-chevron-down text-xs"></i>
                        </button>
                    </div>
                    <ul x-show="terbuka.fakultas" style="background-color: #ffffff;" class="absolute z-50 mt-1 max-h-48 w-full overflow-auto rounded-md border border-gray-200 bg-white text-sm shadow-lg">
                        <template x-if="saran.fakultas.length === 0">
                            <li class="px-3 py-2 text-gray-400">Tidak ada yang cocok — ketik fakultas baru.</li>
                        </template>
                        <template x-for="nama in saran.fakultas" :key="nama">
                            <li x-on:click="pilih('fakultas', nama)" x-text="nama" class="cursor-pointer px-3 py-2 hover:bg-indigo-50" :class="nama === nilai.fakultas ? 'bg-indigo-50 font-medium text-indigo-700' : 'text-gray-700'"></li>
                        </template>
                    </ul>
                    <x-input-error :messages="$errors->get('fakultas')" class="mt-2" />
                </div>

                <div class="relative" x-on:click.outside="terbuka.prodi = false">
                    <x-input-label for="create_prodi" value="{{ __('Program Studi') }}" />
                    <div class="relative mt-1">
                        <input id="create_prodi" name="prodi" type="text" autocomplete="off" placeholder="Ketik atau pilih program studi..." class="block w-full rounded-md border-gray-300 pr-9 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-slate-600 dark:bg-slate-800 dark:text-white dark:placeholder-slate-400" x-model="nilai.prodi" x-on:input="cari('prodi')" x-on:focus="bukaDropdown('prodi')">
                        <button type="button" x-on:click="terbuka.prodi = !terbuka.prodi; if (terbuka.prodi) bukaDropdown('prodi')" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600">
                            <i class="fas fa-chevron-down text-xs"></i>
                        </button>
                    </div>
                    <ul x-show="terbuka.prodi" style="background-color: #ffffff;" class="absolute z-50 mt-1 max-h-48 w-full overflow-auto rounded-md border border-gray-200 bg-white text-sm shadow-lg">
                        <template x-if="saran.prodi.length === 0">
                            <li class="px-3 py-2 text-gray-400">Tidak ada yang cocok — ketik program studi baru.</li>
                        </template>
                        <template x-for="nama in saran.prodi" :key="nama">
                            <li x-on:click="pilih('prodi', nama)" x-text="nama" class="cursor-pointer px-3 py-2 hover:bg-indigo-50" :class="nama === nilai.prodi ? 'bg-indigo-50 font-medium text-indigo-700' : 'text-gray-700'"></li>
                        </template>
                    </ul>
                    <x-input-error :messages="$errors->get('prodi')" class="mt-2" />
                </div>

            </div>

            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">
                    {{ __('Batal') }}
                </x-secondary-button>

                <x-primary-button class="ms-3">
                    {{ __('Simpan') }}
                </x-primary-button>
            </div>
        </form>
    </x-modal>
</x-app-layout>
