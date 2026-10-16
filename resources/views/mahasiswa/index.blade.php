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

            @if (session('password_baru'))
                <div class="rounded-lg border border-amber-300 bg-amber-50 px-4 py-3 text-sm text-amber-800">
                    <p class="font-semibold">⚠️ Catat/kirim sekarang — password ini tidak akan ditampilkan lagi setelah halaman ini ditutup/di-refresh.</p>
                    <p class="mt-1">
                        Peserta: <strong>{{ session('password_baru')['nama'] }}</strong> ({{ session('password_baru')['email'] ?? '-' }})
                        — Password Baru: <code class="rounded bg-amber-100 px-2 py-0.5 font-mono font-bold">{{ session('password_baru')['password'] }}</code>
                    </p>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div
                    class="p-6 text-gray-900"
                    x-data="{
                        deleteId: null,
                        deleteNama: '',
                        resetId: null,
                        resetNama: '',
                        resetNim: '',
                        hapus(button) {
                            this.deleteId = button.dataset.mahasiswaId;
                            this.deleteNama = button.dataset.mahasiswaName;
                        },
                        get deleteAction() {
                            return this.deleteId ? '{{ url('mahasiswa') }}/' + this.deleteId : '';
                        },
                        bukaResetPassword(id, nama, nim) {
                            this.resetId = id;
                            this.resetNama = nama;
                            this.resetNim = nim;
                            $dispatch('open-modal', 'modal-reset-password');
                        },
                        get resetPasswordAction() {
                            return this.resetId ? '{{ url('mahasiswa') }}/' + this.resetId + '/reset-password' : '';
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
                                        <th class="whitespace-nowrap px-4 py-3 align-middle text-left td-kiri !text-left">Nama</th>
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
                                            <td class="px-4 py-3 align-middle whitespace-nowrap td-kiri !text-left">
                                                <div class="flex items-center justify-start !justify-start text-left !text-left gap-3">
                                                    @if ($mahasiswa->foto_url)
                                                        <img src="{{ $mahasiswa->foto_url }}" alt="Foto {{ $mahasiswa->name }}" class="h-9 w-9 rounded-full object-cover border border-slate-200 shadow-xs shrink-0">
                                                    @else
                                                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-blue-100 text-xs font-bold text-blue-700">
                                                            {{ strtoupper(substr($mahasiswa->name, 0, 1)) }}
                                                        </span>
                                                    @endif
                                                    <div class="text-left !text-left">
                                                        <p class="font-medium text-gray-900 text-left !text-left">{{ $mahasiswa->name }}</p>
                                                        @if ($mahasiswa->email)
                                                            <a href="mailto:{{ $mahasiswa->email }}" class="text-xs text-gray-500 hover:text-blue-700 hover:underline text-left !text-left">{{ $mahasiswa->email }}</a>
                                                        @else
                                                            <p class="text-xs text-gray-500 text-left !text-left">-</p>
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
                                                    <button
                                                        type="button"
                                                        x-on:click="bukaResetPassword({{ $mahasiswa->id }}, @js($mahasiswa->name), @js($mahasiswa->nim_nip))"
                                                        class="inline-flex items-center gap-1 rounded bg-amber-50 px-2.5 py-1.5 text-xs font-semibold text-amber-700 hover:bg-amber-100 transition"
                                                        title="Reset Password {{ $mahasiswa->name }}"
                                                    >
                                                        <i class="fa-solid fa-key"></i>
                                                        <span>Reset</span>
                                                    </button>
                                                    @if (auth()->user()?->role === 'SUPERADMIN')
                                                        <x-danger-button
                                                            type="button"
                                                            data-mahasiswa-id="{{ $mahasiswa->id }}"
                                                            data-mahasiswa-name="{{ $mahasiswa->name }}"
                                                            x-on:click="hapus($el); $dispatch('open-modal', 'confirm-delete-mahasiswa')"
                                                            aria-label="Hapus peserta {{ $mahasiswa->name }}"
                                                        >
                                                            {{ __('Hapus') }}
                                                        </x-danger-button>
                                                    @endif
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

                                    <div class="min-w-0 w-full">
                                        <x-input-label for="edit_kampus_{{ $mahasiswa->id }}" value="{{ __('Instansi / Sekolah / Kampus') }}" />
                                        @php
                                            $daftarKampus = $kampuses->pluck('nama_universitas')->filter()->unique();
                                            if ($mahasiswa->kampus && ! $daftarKampus->contains($mahasiswa->kampus)) {
                                                $daftarKampus->push($mahasiswa->kampus);
                                            }
                                        @endphp
                                        <x-combobox
                                            id="edit_kampus_{{ $mahasiswa->id }}"
                                            name="kampus"
                                            :value="$mahasiswa->kampus"
                                            :options="$daftarKampus"
                                            placeholder="Ketik atau pilih instansi..."
                                        />
                                    </div>

                                    <div class="min-w-0 w-full">
                                        <x-input-label for="edit_fakultas_{{ $mahasiswa->id }}" value="{{ __('Fakultas') }}" />
                                        @php
                                            $daftarFakultas = $kampuses->pluck('fakultas')->filter()->unique();
                                            if ($mahasiswa->fakultas && ! $daftarFakultas->contains($mahasiswa->fakultas)) {
                                                $daftarFakultas->push($mahasiswa->fakultas);
                                            }
                                        @endphp
                                        <x-combobox
                                            id="edit_fakultas_{{ $mahasiswa->id }}"
                                            name="fakultas"
                                            :value="$mahasiswa->fakultas"
                                            :options="$daftarFakultas"
                                            placeholder="Ketik atau pilih fakultas..."
                                        />
                                    </div>

                                    <div class="min-w-0 w-full">
                                        <x-input-label for="edit_prodi_{{ $mahasiswa->id }}" value="{{ __('Program Studi') }}" />
                                        @php
                                            $daftarProdi = $kampuses->pluck('prodi')->filter()->unique();
                                            if ($mahasiswa->prodi && ! $daftarProdi->contains($mahasiswa->prodi)) {
                                                $daftarProdi->push($mahasiswa->prodi);
                                            }
                                        @endphp
                                        <x-combobox
                                            id="edit_prodi_{{ $mahasiswa->id }}"
                                            name="prodi"
                                            :value="$mahasiswa->prodi"
                                            :options="$daftarProdi"
                                            placeholder="Ketik atau pilih program studi..."
                                        />
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

                    @if (auth()->user()?->role === 'SUPERADMIN')
                        @include('mahasiswa._delete_modal')
                    @endif

                    @include('mahasiswa._reset_password_modal')
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

                <div class="min-w-0 w-full">
                    <x-input-label for="create_kampus" value="{{ __('Instansi / Sekolah / Kampus') }}" />
                    <x-combobox
                        id="create_kampus"
                        name="kampus"
                        :value="old('kampus')"
                        :options="$kampuses->pluck('nama_universitas')->filter()->unique()"
                        placeholder="Ketik atau pilih instansi..."
                    />
                    <x-input-error :messages="$errors->get('kampus')" class="mt-2" />
                </div>

                <div class="min-w-0 w-full">
                    <x-input-label for="create_fakultas" value="{{ __('Fakultas') }}" />
                    <x-combobox
                        id="create_fakultas"
                        name="fakultas"
                        :value="old('fakultas')"
                        :options="$kampuses->pluck('fakultas')->filter()->unique()"
                        placeholder="Ketik atau pilih fakultas..."
                    />
                    <x-input-error :messages="$errors->get('fakultas')" class="mt-2" />
                </div>

                <div class="min-w-0 sm:col-span-2 w-full">
                    <x-input-label for="create_prodi" value="{{ __('Program Studi') }}" />
                    <x-combobox
                        id="create_prodi"
                        name="prodi"
                        :value="old('prodi')"
                        :options="$kampuses->pluck('prodi')->filter()->unique()"
                        placeholder="Ketik atau pilih program studi..."
                    />
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
