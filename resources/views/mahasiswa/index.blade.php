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
                                                <div class="flex items-center gap-3">
                                                    @if ($mahasiswa->foto_url)
                                                        <img src="{{ $mahasiswa->foto_url }}" alt="Foto {{ $mahasiswa->name }}" class="h-9 w-9 rounded-full object-cover border border-slate-200 shadow-xs">
                                                    @else
                                                        <span class="flex h-9 w-9 items-center justify-center rounded-full bg-blue-100 text-xs font-bold text-blue-700">
                                                            {{ strtoupper(substr($mahasiswa->name, 0, 1)) }}
                                                        </span>
                                                    @endif
                                                    <div>
                                                        <p class="font-medium text-gray-900">{{ $mahasiswa->name }}</p>
                                                        @if ($mahasiswa->email)
                                                            <a href="mailto:{{ $mahasiswa->email }}" class="text-xs text-gray-500 hover:text-blue-700 hover:underline">{{ $mahasiswa->email }}</a>
                                                        @else
                                                            <p class="text-xs text-gray-500">-</p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 align-middle whitespace-nowrap">
                                                <span class="font-mono text-xs text-slate-700">{{ $mahasiswa->nim_nip ?? '-' }}</span>
                                                @if ($mahasiswa->hp_wa)
                                                    <span class="block text-[11px] text-slate-400"><i class="fab fa-whatsapp text-emerald-600 mr-0.5"></i>{{ $mahasiswa->hp_wa }}</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 align-middle whitespace-nowrap">{{ $mahasiswa->kampus ?? '-' }}</td>
                                            <td class="whitespace-nowrap px-4 py-3 align-middle">
                                                @if ($mahasiswa->fakultas || $mahasiswa->prodi)
                                                    {{ trim($mahasiswa->fakultas.' / '.$mahasiswa->prodi, ' /') }}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 align-middle whitespace-nowrap">
                                                <span class="inline-flex rounded-md bg-slate-100 px-2 py-1 text-xs font-medium text-slate-700">
                                                    {{ $mahasiswa->kelompok?->nama_kelompok ?? 'Belum ada kelompok' }}
                                                </span>
                                            </td>
                                            <td class="whitespace-nowrap px-4 py-3 align-middle text-xs">{{ $mahasiswa->dosen?->nama ?? '-' }}</td>
                                            <td class="max-w-[180px] break-words px-4 py-3 align-middle text-xs">{{ $mahasiswa->mentor?->nama ?? '-' }}</td>
                                            <td class="px-4 py-3 align-middle text-center font-semibold text-xs">{{ $mahasiswa->laporans_count }}</td>
                                            <td class="px-4 py-3 align-middle whitespace-nowrap text-right">
                                                <div class="flex items-center justify-end gap-1.5">
                                                    <a href="{{ route('laporan.mahasiswa.download-pdf', $mahasiswa) }}" target="_blank"
                                                       class="inline-flex items-center gap-1 rounded bg-rose-50 px-2 py-1.5 text-xs font-semibold text-rose-700 hover:bg-rose-100"
                                                       title="Unduh Arsip Laporan (PDF)">
                                                        <i class="fa-solid fa-file-pdf"></i>
                                                    </a>
                                                    <form method="POST" action="{{ route('mahasiswa.impersonate', $mahasiswa->id) }}">
                                                        @csrf
                                                        <x-secondary-button type="submit" title="Masuk sebagai {{ $mahasiswa->name }}">
                                                            Login
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
                                enctype="multipart/form-data"
                                class="p-4 sm:p-6 w-full max-w-full"
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
                                    <div class="min-w-0">
                                        <x-input-label for="edit_name_{{ $mahasiswa->id }}" value="{{ __('Nama') }}" />
                                        <x-text-input id="edit_name_{{ $mahasiswa->id }}" name="name" type="text" class="mt-1 block w-full" :value="$mahasiswa->name" required />
                                    </div>

                                    <div class="min-w-0">
                                        <x-input-label for="edit_email_{{ $mahasiswa->id }}" value="{{ __('Email') }}" />
                                        <x-text-input id="edit_email_{{ $mahasiswa->id }}" name="email" type="email" class="mt-1 block w-full" :value="$mahasiswa->email" required />
                                    </div>

                                    <div class="min-w-0">
                                        <x-input-label for="edit_password_{{ $mahasiswa->id }}" value="{{ __('Password (kosongkan jika tidak diubah)') }}" />
                                        <x-text-input id="edit_password_{{ $mahasiswa->id }}" name="password" type="password" class="mt-1 block w-full" />
                                    </div>

                                    <div class="min-w-0">
                                        <x-input-label for="edit_nim_nip_{{ $mahasiswa->id }}" value="{{ __('NIM / NISN') }}" />
                                        <x-text-input id="edit_nim_nip_{{ $mahasiswa->id }}" name="nim_nip" type="text" class="mt-1 block w-full" :value="$mahasiswa->nim_nip" />
                                    </div>

                                    <div class="min-w-0">
                                        <x-input-label for="edit_hp_wa_{{ $mahasiswa->id }}" value="{{ __('HP / WA') }}" />
                                        <x-text-input id="edit_hp_wa_{{ $mahasiswa->id }}" name="hp_wa" type="text" class="mt-1 block w-full" :value="$mahasiswa->hp_wa" />
                                    </div>

                                    <div class="relative min-w-0 w-full" :class="terbuka.kampus ? 'z-30' : 'z-10'" x-on:click.outside="terbuka.kampus = false">
                                        <x-input-label for="edit_kampus_{{ $mahasiswa->id }}" value="{{ __('Instansi / Sekolah / Kampus') }}" />
                                        <div class="relative mt-1 w-full">
                                            <input id="edit_kampus_{{ $mahasiswa->id }}" name="kampus" type="text" autocomplete="off" placeholder="Ketik atau pilih instansi..." class="block w-full min-w-0 max-w-full rounded-md border-gray-300 pr-9 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-slate-600 dark:bg-slate-800 dark:text-white dark:placeholder-slate-400 text-sm" style="width: 100%; max-width: 100%; box-sizing: border-box;" x-model="nilai.kampus" x-on:input="cari('kampus')" x-on:focus="bukaDropdown('kampus')">
                                            <button type="button" x-on:click="terbuka.kampus = !terbuka.kampus; if (terbuka.kampus) bukaDropdown('kampus')" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600">
                                                <i class="fas fa-chevron-down text-xs"></i>
                                            </button>
                                        </div>

                                        <!-- Pilihan Cepat Native Select untuk Mobile -->
                                        <div class="mt-1.5 block sm:hidden">
                                            <select
                                                class="block w-full max-w-full rounded-md border-gray-200 bg-gray-50 text-xs text-gray-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-200"
                                                style="width: 100%; max-width: 100%; box-sizing: border-box;"
                                                x-on:change="if ($event.target.value) { pilih('kampus', $event.target.value); $event.target.value = ''; }"
                                            >
                                                <option value="">{{ __('-- Atau pilih dari daftar instansi --') }}</option>
                                                @foreach ($kampuses->pluck('nama_universitas')->filter()->unique()->values() as $item)
                                                    <option value="{{ $item }}">{{ \Illuminate\Support\Str::limit($item, 38) }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <ul x-show="terbuka.kampus" style="background-color: #ffffff; width: 100%; max-width: 100%; box-sizing: border-box;" class="relative sm:absolute sm:inset-x-0 sm:left-0 sm:right-0 sm:top-full z-50 mt-1 max-h-48 w-full min-w-0 max-w-full overflow-y-auto overflow-x-hidden rounded-md border border-indigo-200 sm:border-gray-200 bg-white text-sm shadow-md sm:shadow-xl">
                                            <template x-if="saran.kampus.length === 0">
                                                <li class="px-3 py-2.5 text-xs sm:text-sm text-gray-500 italic">Tidak ada yang cocok — ketik nama instansi baru.</li>
                                            </template>
                                            <template x-for="nama in saran.kampus" :key="nama">
                                                <li x-on:click="pilih('kampus', nama)" x-text="nama" class="cursor-pointer px-3 py-2.5 text-left text-sm leading-snug break-words whitespace-normal border-b border-gray-100 last:border-b-0 hover:bg-indigo-50 active:bg-indigo-100 transition-colors" :class="nama === nilai.kampus ? 'bg-indigo-50 font-medium text-indigo-700' : 'text-gray-700'"></li>
                                            </template>
                                        </ul>
                                    </div>

                                    <div class="relative min-w-0 w-full" :class="terbuka.fakultas ? 'z-25' : 'z-10'" x-on:click.outside="terbuka.fakultas = false">
                                        <x-input-label for="edit_fakultas_{{ $mahasiswa->id }}" value="{{ __('Fakultas') }}" />
                                        <div class="relative mt-1 w-full">
                                            <input id="edit_fakultas_{{ $mahasiswa->id }}" name="fakultas" type="text" autocomplete="off" placeholder="Ketik atau pilih fakultas..." class="block w-full min-w-0 max-w-full rounded-md border-gray-300 pr-9 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-slate-600 dark:bg-slate-800 dark:text-white dark:placeholder-slate-400 text-sm" style="width: 100%; max-width: 100%; box-sizing: border-box;" x-model="nilai.fakultas" x-on:input="cari('fakultas')" x-on:focus="bukaDropdown('fakultas')">
                                            <button type="button" x-on:click="terbuka.fakultas = !terbuka.fakultas; if (terbuka.fakultas) bukaDropdown('fakultas')" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600">
                                                <i class="fas fa-chevron-down text-xs"></i>
                                            </button>
                                        </div>

                                        <!-- Pilihan Cepat Native Select untuk Mobile -->
                                        <div class="mt-1.5 block sm:hidden">
                                            <select
                                                class="block w-full max-w-full rounded-md border-gray-200 bg-gray-50 text-xs text-gray-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-200"
                                                style="width: 100%; max-width: 100%; box-sizing: border-box;"
                                                x-on:change="if ($event.target.value) { pilih('fakultas', $event.target.value); $event.target.value = ''; }"
                                            >
                                                <option value="">{{ __('-- Atau pilih dari daftar fakultas --') }}</option>
                                                @foreach ($kampuses->pluck('fakultas')->filter()->unique()->values() as $item)
                                                    <option value="{{ $item }}">{{ \Illuminate\Support\Str::limit($item, 38) }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <ul x-show="terbuka.fakultas" style="background-color: #ffffff; width: 100%; max-width: 100%; box-sizing: border-box;" class="relative sm:absolute sm:inset-x-0 sm:left-0 sm:right-0 sm:top-full z-50 mt-1 max-h-48 w-full min-w-0 max-w-full overflow-y-auto overflow-x-hidden rounded-md border border-indigo-200 sm:border-gray-200 bg-white text-sm shadow-md sm:shadow-xl">
                                            <template x-if="saran.fakultas.length === 0">
                                                <li class="px-3 py-2.5 text-xs sm:text-sm text-gray-500 italic">Tidak ada yang cocok — ketik fakultas baru.</li>
                                            </template>
                                            <template x-for="nama in saran.fakultas" :key="nama">
                                                <li x-on:click="pilih('fakultas', nama)" x-text="nama" class="cursor-pointer px-3 py-2.5 text-left text-sm leading-snug break-words whitespace-normal border-b border-gray-100 last:border-b-0 hover:bg-indigo-50 active:bg-indigo-100 transition-colors" :class="nama === nilai.fakultas ? 'bg-indigo-50 font-medium text-indigo-700' : 'text-gray-700'"></li>
                                            </template>
                                        </ul>
                                    </div>

                                    <div class="relative min-w-0 w-full" :class="terbuka.prodi ? 'z-20' : 'z-0'" x-on:click.outside="terbuka.prodi = false">
                                        <x-input-label for="edit_prodi_{{ $mahasiswa->id }}" value="{{ __('Program Studi') }}" />
                                        <div class="relative mt-1 w-full">
                                            <input id="edit_prodi_{{ $mahasiswa->id }}" name="prodi" type="text" autocomplete="off" placeholder="Ketik atau pilih program studi..." class="block w-full min-w-0 max-w-full rounded-md border-gray-300 pr-9 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-slate-600 dark:bg-slate-800 dark:text-white dark:placeholder-slate-400 text-sm" style="width: 100%; max-width: 100%; box-sizing: border-box;" x-model="nilai.prodi" x-on:input="cari('prodi')" x-on:focus="bukaDropdown('prodi')">
                                            <button type="button" x-on:click="terbuka.prodi = !terbuka.prodi; if (terbuka.prodi) bukaDropdown('prodi')" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600">
                                                <i class="fas fa-chevron-down text-xs"></i>
                                            </button>
                                        </div>

                                        <!-- Pilihan Cepat Native Select untuk Mobile -->
                                        <div class="mt-1.5 block sm:hidden">
                                            <select
                                                class="block w-full max-w-full rounded-md border-gray-200 bg-gray-50 text-xs text-gray-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-200"
                                                style="width: 100%; max-width: 100%; box-sizing: border-box;"
                                                x-on:change="if ($event.target.value) { pilih('prodi', $event.target.value); $event.target.value = ''; }"
                                            >
                                                <option value="">{{ __('-- Atau pilih dari daftar program studi --') }}</option>
                                                @foreach ($kampuses->pluck('prodi')->filter()->unique()->values() as $item)
                                                    <option value="{{ $item }}">{{ \Illuminate\Support\Str::limit($item, 38) }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <ul x-show="terbuka.prodi" style="background-color: #ffffff; width: 100%; max-width: 100%; box-sizing: border-box;" class="relative sm:absolute sm:inset-x-0 sm:left-0 sm:right-0 sm:top-full z-50 mt-1 max-h-48 w-full min-w-0 max-w-full overflow-y-auto overflow-x-hidden rounded-md border border-indigo-200 sm:border-gray-200 bg-white text-sm shadow-md sm:shadow-xl">
                                            <template x-if="saran.prodi.length === 0">
                                                <li class="px-3 py-2.5 text-xs sm:text-sm text-gray-500 italic">Tidak ada yang cocok — ketik program studi baru.</li>
                                            </template>
                                            <template x-for="nama in saran.prodi" :key="nama">
                                                <li x-on:click="pilih('prodi', nama)" x-text="nama" class="cursor-pointer px-3 py-2.5 text-left text-sm leading-snug break-words whitespace-normal border-b border-gray-100 last:border-b-0 hover:bg-indigo-50 active:bg-indigo-100 transition-colors" :class="nama === nilai.prodi ? 'bg-indigo-50 font-medium text-indigo-700' : 'text-gray-700'"></li>
                                            </template>
                                        </ul>
                                    </div>

                                    <div class="min-w-0 w-full">
                                        <x-input-label for="edit_kelompok_id_{{ $mahasiswa->id }}" value="{{ __('Kelompok') }}" />
                                        <select id="edit_kelompok_id_{{ $mahasiswa->id }}" name="kelompok_id" class="mt-1 block w-full max-w-full min-w-0 truncate text-ellipsis rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-slate-600 dark:bg-slate-800 dark:text-white dark:placeholder-slate-400 text-sm" style="width: 100%; max-width: 100%; box-sizing: border-box;">
                                            <option value="">{{ __('- Belum ada kelompok -') }}</option>
                                            @foreach ($kelompoks as $kelompok)
                                                <option value="{{ $kelompok->id }}" @selected($mahasiswa->kelompok_id == $kelompok->id) title="{{ $kelompok->nama_kelompok }}">
                                                    {{ \Illuminate\Support\Str::limit($kelompok->nama_kelompok, 36) }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="sm:col-span-2 border-t border-gray-100 pt-3">
                                        <x-input-label for="edit_foto_{{ $mahasiswa->id }}" value="{{ __('Foto Profil Peserta (Opsional)') }}" />
                                        @if ($mahasiswa->foto_url)
                                            <div class="mt-2 mb-2 flex items-center gap-3">
                                                <img src="{{ $mahasiswa->foto_url }}" alt="Foto {{ $mahasiswa->name }}" class="h-10 w-10 rounded-full object-cover border border-slate-200">
                                                <span class="text-xs text-gray-500">Foto profil terpasang</span>
                                            </div>
                                        @endif
                                        <input id="edit_foto_{{ $mahasiswa->id }}" name="foto" type="file" accept="image/*" class="mt-1 block w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" />
                                        <p class="mt-1 text-xs text-gray-500">Format: JPG, JPEG, PNG, WEBP. Maksimal 2 MB.</p>
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
            enctype="multipart/form-data"
            class="p-4 sm:p-6 w-full max-w-full"
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
                <div class="sm:col-span-2 min-w-0 w-full" x-data="{
                    kelompokTampil: '',
                    pilih(select) {
                        const opt = select.options[select.selectedIndex];
                        this.kelompokTampil = opt.dataset.kelompok ?? '';
                        this.$refs.calonAnggotaId.value = opt.value ? opt.dataset.id : '';
                        this.$refs.kelompokId.value = opt.value ? opt.dataset.kelompokId : '';
                    },
                }">
                    <x-input-label for="create_name" value="{{ __('Nama Peserta') }}" />
                    <select
                        id="create_name"
                        name="name"
                        class="mt-1 block w-full max-w-full min-w-0 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-slate-600 dark:bg-slate-800 dark:text-white dark:placeholder-slate-400 text-sm truncate"
                        style="width: 100%; max-width: 100%; box-sizing: border-box;"
                        x-on:change="pilih($event.target)"
                        required
                    >
                        <option value="">{{ __('- Pilih Nama Peserta -') }}</option>
                        @foreach ($calonAnggotas as $calon)
                            <option
                                value="{{ $calon->nama }}"
                                data-id="{{ $calon->id }}"
                                data-kelompok-id="{{ $calon->kelompok_id }}"
                                data-kelompok="{{ $calon->kelompok?->nama_kelompok ?? '-' }}"
                                title="{{ $calon->nama }} ({{ $calon->kelompok?->nama_kelompok ?? '-' }})"
                            >
                                {{ $calon->nama }} {{ $calon->kelompok ? '— ' . \Illuminate\Support\Str::limit($calon->kelompok->nama_kelompok, 24) : '' }}
                            </option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />

                    @if ($calonAnggotas->isEmpty())
                        <p class="mt-2 text-sm text-gray-500">{{ __('Belum ada calon anggota. Tambahkan nama lewat form Tambah Kelompok terlebih dahulu.') }}</p>
                    @endif

                    <input type="hidden" name="calon_anggota_id" x-ref="calonAnggotaId">

                    <div class="mt-3 min-w-0 w-full">
                        <x-input-label value="{{ __('Kelompok') }}" />
                        <div class="mt-1 block w-full min-w-0 max-w-full truncate rounded-md border border-gray-300 bg-gray-100 px-3 py-2 text-sm text-gray-700 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-200" x-text="kelompokTampil || '{{ __('- Pilih nama dulu -') }}'"></div>
                        <input type="hidden" name="kelompok_id" x-ref="kelompokId">
                    </div>
                </div>

                <div class="min-w-0 w-full">
                    <x-input-label for="create_email" value="{{ __('Email') }}" />
                    <x-text-input id="create_email" name="email" type="email" class="mt-1 block w-full min-w-0 max-w-full" :value="old('email')" required />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div class="min-w-0 w-full">
                    <x-input-label for="create_password" value="{{ __('Password') }}" />
                    <x-text-input id="create_password" name="password" type="password" class="mt-1 block w-full min-w-0 max-w-full" required />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <div class="min-w-0 w-full">
                    <x-input-label for="create_nim_nip" value="{{ __('NIM / NISN') }}" />
                    <x-text-input id="create_nim_nip" name="nim_nip" type="text" class="mt-1 block w-full min-w-0 max-w-full" :value="old('nim_nip')" />
                    <x-input-error :messages="$errors->get('nim_nip')" class="mt-2" />
                </div>

                <div class="min-w-0 w-full">
                    <x-input-label for="create_hp_wa" value="{{ __('HP / WA') }}" />
                    <x-text-input id="create_hp_wa" name="hp_wa" type="text" class="mt-1 block w-full min-w-0 max-w-full" :value="old('hp_wa')" />
                    <x-input-error :messages="$errors->get('hp_wa')" class="mt-2" />
                </div>

                <div class="relative min-w-0 w-full" :class="terbuka.kampus ? 'z-30' : 'z-10'" x-on:click.outside="terbuka.kampus = false">
                    <x-input-label for="create_kampus" value="{{ __('Instansi / Sekolah / Kampus') }}" />
                    <div class="relative mt-1 w-full">
                        <input id="create_kampus" name="kampus" type="text" autocomplete="off" placeholder="Ketik atau pilih instansi..." class="block w-full min-w-0 max-w-full rounded-md border-gray-300 pr-9 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-slate-600 dark:bg-slate-800 dark:text-white dark:placeholder-slate-400 text-sm" style="width: 100%; max-width: 100%; box-sizing: border-box;" x-model="nilai.kampus" x-on:input="cari('kampus')" x-on:focus="bukaDropdown('kampus')">
                        <button type="button" x-on:click="terbuka.kampus = !terbuka.kampus; if (terbuka.kampus) bukaDropdown('kampus')" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600">
                            <i class="fas fa-chevron-down text-xs"></i>
                        </button>
                    </div>

                    <!-- Pilihan Cepat Native Select untuk Mobile -->
                    <div class="mt-1.5 block sm:hidden">
                        <select
                            class="block w-full max-w-full rounded-md border-gray-200 bg-gray-50 text-xs text-gray-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-200"
                            style="width: 100%; max-width: 100%; box-sizing: border-box;"
                            x-on:change="if ($event.target.value) { pilih('kampus', $event.target.value); $event.target.value = ''; }"
                        >
                            <option value="">{{ __('-- Atau pilih dari daftar instansi --') }}</option>
                            @foreach ($kampuses->pluck('nama_universitas')->filter()->unique()->values() as $item)
                                <option value="{{ $item }}">{{ \Illuminate\Support\Str::limit($item, 38) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <ul x-show="terbuka.kampus" style="background-color: #ffffff; width: 100%; max-width: 100%; box-sizing: border-box;" class="relative sm:absolute sm:inset-x-0 sm:left-0 sm:right-0 sm:top-full z-50 mt-1 max-h-48 w-full min-w-0 max-w-full overflow-y-auto overflow-x-hidden rounded-md border border-indigo-200 sm:border-gray-200 bg-white text-sm shadow-md sm:shadow-xl">
                        <template x-if="saran.kampus.length === 0">
                            <li class="px-3 py-2.5 text-xs text-gray-400 break-words whitespace-normal italic">Tidak ada yang cocok — ketik nama instansi baru.</li>
                        </template>
                        <template x-for="nama in saran.kampus" :key="nama">
                            <li x-on:click="pilih('kampus', nama)" x-text="nama" class="cursor-pointer px-3 py-2.5 text-left text-sm leading-snug break-words whitespace-normal border-b border-gray-100 last:border-b-0 hover:bg-indigo-50 active:bg-indigo-100 transition-colors" :class="nama === nilai.kampus ? 'bg-indigo-50 font-medium text-indigo-700' : 'text-gray-700'"></li>
                        </template>
                    </ul>
                    <x-input-error :messages="$errors->get('kampus')" class="mt-2" />
                </div>

                <div class="relative min-w-0 w-full" :class="terbuka.fakultas ? 'z-25' : 'z-10'" x-on:click.outside="terbuka.fakultas = false">
                    <x-input-label for="create_fakultas" value="{{ __('Fakultas') }}" />
                    <div class="relative mt-1 w-full">
                        <input id="create_fakultas" name="fakultas" type="text" autocomplete="off" placeholder="Ketik atau pilih fakultas..." class="block w-full min-w-0 max-w-full rounded-md border-gray-300 pr-9 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-slate-600 dark:bg-slate-800 dark:text-white dark:placeholder-slate-400 text-sm" style="width: 100%; max-width: 100%; box-sizing: border-box;" x-model="nilai.fakultas" x-on:input="cari('fakultas')" x-on:focus="bukaDropdown('fakultas')">
                        <button type="button" x-on:click="terbuka.fakultas = !terbuka.fakultas; if (terbuka.fakultas) bukaDropdown('fakultas')" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600">
                            <i class="fas fa-chevron-down text-xs"></i>
                        </button>
                    </div>

                    <!-- Pilihan Cepat Native Select untuk Mobile -->
                    <div class="mt-1.5 block sm:hidden">
                        <select
                            class="block w-full max-w-full rounded-md border-gray-200 bg-gray-50 text-xs text-gray-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-200"
                            style="width: 100%; max-width: 100%; box-sizing: border-box;"
                            x-on:change="if ($event.target.value) { pilih('fakultas', $event.target.value); $event.target.value = ''; }"
                        >
                            <option value="">{{ __('-- Atau pilih dari daftar fakultas --') }}</option>
                            @foreach ($kampuses->pluck('fakultas')->filter()->unique()->values() as $item)
                                <option value="{{ $item }}">{{ \Illuminate\Support\Str::limit($item, 38) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <ul x-show="terbuka.fakultas" style="background-color: #ffffff; width: 100%; max-width: 100%; box-sizing: border-box;" class="relative sm:absolute sm:inset-x-0 sm:left-0 sm:right-0 sm:top-full z-50 mt-1 max-h-48 w-full min-w-0 max-w-full overflow-y-auto overflow-x-hidden rounded-md border border-indigo-200 sm:border-gray-200 bg-white text-sm shadow-md sm:shadow-xl">
                        <template x-if="saran.fakultas.length === 0">
                            <li class="px-3 py-2.5 text-xs text-gray-400 break-words whitespace-normal italic">Tidak ada yang cocok — ketik fakultas baru.</li>
                        </template>
                        <template x-for="nama in saran.fakultas" :key="nama">
                            <li x-on:click="pilih('fakultas', nama)" x-text="nama" class="cursor-pointer px-3 py-2.5 text-left text-sm leading-snug break-words whitespace-normal border-b border-gray-100 last:border-b-0 hover:bg-indigo-50 active:bg-indigo-100 transition-colors" :class="nama === nilai.fakultas ? 'bg-indigo-50 font-medium text-indigo-700' : 'text-gray-700'"></li>
                        </template>
                    </ul>
                    <x-input-error :messages="$errors->get('fakultas')" class="mt-2" />
                </div>

                <div class="relative min-w-0 sm:col-span-2 w-full" :class="terbuka.prodi ? 'z-20' : 'z-0'" x-on:click.outside="terbuka.prodi = false">
                    <x-input-label for="create_prodi" value="{{ __('Program Studi') }}" />
                    <div class="relative mt-1 w-full">
                        <input id="create_prodi" name="prodi" type="text" autocomplete="off" placeholder="Ketik atau pilih program studi..." class="block w-full min-w-0 max-w-full rounded-md border-gray-300 pr-9 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-slate-600 dark:bg-slate-800 dark:text-white dark:placeholder-slate-400 text-sm" style="width: 100%; max-width: 100%; box-sizing: border-box;" x-model="nilai.prodi" x-on:input="cari('prodi')" x-on:focus="bukaDropdown('prodi')">
                        <button type="button" x-on:click="terbuka.prodi = !terbuka.prodi; if (terbuka.prodi) bukaDropdown('prodi')" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600">
                            <i class="fas fa-chevron-down text-xs"></i>
                        </button>
                    </div>

                    <!-- Pilihan Cepat Native Select untuk Mobile -->
                    <div class="mt-1.5 block sm:hidden">
                        <select
                            class="block w-full max-w-full rounded-md border-gray-200 bg-gray-50 text-xs text-gray-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-200"
                            style="width: 100%; max-width: 100%; box-sizing: border-box;"
                            x-on:change="if ($event.target.value) { pilih('prodi', $event.target.value); $event.target.value = ''; }"
                        >
                            <option value="">{{ __('-- Atau pilih dari daftar program studi --') }}</option>
                            @foreach ($kampuses->pluck('prodi')->filter()->unique()->values() as $item)
                                <option value="{{ $item }}">{{ \Illuminate\Support\Str::limit($item, 38) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <ul x-show="terbuka.prodi" style="background-color: #ffffff; width: 100%; max-width: 100%; box-sizing: border-box;" class="relative sm:absolute sm:inset-x-0 sm:left-0 sm:right-0 sm:top-full z-50 mt-1 max-h-48 w-full min-w-0 max-w-full overflow-y-auto overflow-x-hidden rounded-md border border-indigo-200 sm:border-gray-200 bg-white text-sm shadow-md sm:shadow-xl">
                        <template x-if="saran.prodi.length === 0">
                            <li class="px-3 py-2.5 text-xs text-gray-400 break-words whitespace-normal italic">Tidak ada yang cocok — ketik program studi baru.</li>
                        </template>
                        <template x-for="nama in saran.prodi" :key="nama">
                            <li x-on:click="pilih('prodi', nama)" x-text="nama" class="cursor-pointer px-3 py-2.5 text-left text-sm leading-snug break-words whitespace-normal border-b border-gray-100 last:border-b-0 hover:bg-indigo-50 active:bg-indigo-100 transition-colors" :class="nama === nilai.prodi ? 'bg-indigo-50 font-medium text-indigo-700' : 'text-gray-700'"></li>
                        </template>
                    </ul>
                    <x-input-error :messages="$errors->get('prodi')" class="mt-2" />
                </div>

                <div class="sm:col-span-2 border-t border-gray-100 pt-3">
                    <x-input-label for="create_foto" value="{{ __('Foto Profil Peserta (Opsional)') }}" />
                    <input id="create_foto" name="foto" type="file" accept="image/*" class="mt-1 block w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" />
                    <p class="mt-1 text-xs text-gray-500">Format: JPG, JPEG, PNG, WEBP. Maksimal 2 MB.</p>
                    <x-input-error :messages="$errors->get('foto')" class="mt-1" />
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
