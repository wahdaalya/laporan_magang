<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Data Kelompok') }}
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
                <div class="text-gray-900" style="padding: 28px 28px 24px;">

                    <!-- Filter, pencarian & tombol -->
                    <div style="display:flex; flex-wrap:wrap; align-items:center; justify-content:space-between; gap:16px; margin-bottom:24px; padding-bottom:20px; border-bottom:1px solid #f1f5f9;">

                        <form method="GET" action="{{ route('kelompok.index') }}" style="display:flex; flex-wrap:wrap; align-items:center; gap:12px; margin:0;">
                            <!-- Input Pencarian -->
                            <div class="relative">
                                <input
                                    type="text"
                                    name="q"
                                    value="{{ $q }}"
                                    placeholder="Cari kelompok..."
                                    style="height:40px; width:240px;"
                                    class="rounded-lg border-gray-200 bg-gray-50/50 py-2 pl-3 pr-8 text-sm placeholder-gray-400 focus:border-blue-500 focus:bg-white focus:ring-blue-500 dark:border-slate-700 dark:bg-slate-800 dark:text-white"
                                >
                                @if($q)
                                    <a href="{{ route('kelompok.index', array_filter(['bulan' => $bulan, 'tahun' => $tahun])) }}" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                        <i class="fas fa-times-circle text-xs"></i>
                                    </a>
                                @endif
                            </div>

                            <!-- Dropdown Filter Bulan -->
                            <!-- Dropdown Filter Bulan -->
                            <select
                                name="bulan"
                                onchange="this.form.submit()"
                                class="rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-slate-600 dark:bg-slate-800 dark:text-white dark:placeholder-slate-400"
                            >
                                <option value="">Semua Bulan</option>
                                @foreach ($daftarBulan as $key => $namaBulan)
                                    <option value="{{ $key }}" @selected($bulan == $key)>{{ $namaBulan }}</option>
                                @endforeach
                            </select>

                            <!-- Dropdown Filter Tahun -->
                            <select
                                name="tahun"
                                onchange="this.form.submit()"
                                class="rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-slate-600 dark:bg-slate-800 dark:text-white dark:placeholder-slate-400"
                            >
                                <option value="">Semua Tahun</option>
                                @foreach ($daftarTahun as $thn)
                                    <option value="{{ $thn }}" @selected($tahun == $thn)>Tahun {{ $thn }}</option>
                                @endforeach
                            </select>

                            <!-- Tombol Cari -->
                            <button type="submit" style="height:40px; padding:0 18px;" class="inline-flex items-center gap-1.5 rounded-lg bg-slate-800 text-sm font-medium text-white shadow-sm transition hover:bg-slate-700 focus:outline-none">
                                <i class="fas fa-search text-xs"></i> Cari
                            </button>

                            <!-- Tombol Reset Filter -->
                            @if($q || $bulan || $tahun)
                                <a href="{{ route('kelompok.index') }}" style="margin-left:4px;" class="inline-flex items-center gap-1 text-xs font-medium text-red-500 hover:text-red-700 transition">
                                    <i class="fas fa-undo"></i> Reset Filter
                                </a>
                            @endif
                        </form>

                        <!-- Tombol Tambah Kelompok -->
                        <button type="button" x-data="" x-on:click.prevent="$dispatch('open-modal', 'create-kelompok')" style="height:40px; padding:0 20px;" class="inline-flex items-center justify-center gap-2 rounded-lg bg-blue-600 text-sm font-medium text-white shadow-sm transition hover:bg-blue-700 focus:outline-none">
                            <i class="fas fa-plus text-xs"></i> Tambah Kelompok
                        </button>
                    </div>
                    @if ($kelompoks->isEmpty())
                        @if ($q !== '' || $tahun)
                            <div class="text-sm text-gray-600">
                                <p>Data tidak ditemukan untuk kriteria pencarian/filter ini.</p>
                            </div>
                        @else
                            <p class="text-gray-500">Belum ada data kelompok.</p>
                        @endif
                    @else
                        <div class="overflow-x-auto rounded-xl border border-gray-200 bg-white dark:border-slate-700 dark:bg-slate-800">
                            <table class="min-w-full border-slate-200 text-sm dark:border-slate-700">
                                <thead class="border-b border-gray-200 bg-gray-100 text-gray-600 dark:border-slate-700 dark:bg-slate-900/80 dark:text-slate-300">
                                    <tr>
                                        <th class="px-5 py-3 text-center align-middle whitespace-nowrap">ID</th>
                                        <th class="px-5 py-3 text-left align-middle whitespace-nowrap">Kelompok</th>
                                        <th class="px-5 py-3 text-left align-middle whitespace-nowrap">Instansi & Departemen</th>
                                        <th class="px-5 py-3 text-center align-middle whitespace-nowrap">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white text-slate-800 dark:bg-slate-800 dark:text-slate-200">
                                    @foreach ($kelompoks as $kelompok)
                                        <tr class="clickable-row border-b border-gray-100 bg-white dark:border-slate-700 dark:bg-slate-800"
                                            data-modal="detail-kelompok-{{ $kelompok->id }}"
                                            tabindex="0" role="button"
                                            aria-label="Lihat detail {{ $kelompok->nama_kelompok }}">
                                            <td class="px-5 py-4 align-middle whitespace-nowrap text-center text-gray-500 font-mono text-xs">#{{ $kelompok->id }}</td>
                                            <td class="px-5 py-4 align-middle whitespace-nowrap">
                                                <div class="flex items-center gap-3">
                                                    <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-indigo-50 text-indigo-600 border border-indigo-100 text-sm">
                                                        <i class="fas fa-users"></i>
                                                    </span>
                                                    <div>
                                                        <div class="font-semibold text-slate-900 dark:text-white">{{ $kelompok->nama_kelompok }}</div>
                                                        <div class="text-xs text-slate-500">
                                                            <i class="far fa-calendar-alt text-[10px] mr-1"></i>{{ $kelompok->tanggal_mulai->format('d/m/Y') }} — {{ $kelompok->tanggal_selesai->format('d/m/Y') }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-5 py-4 align-middle whitespace-nowrap">
                                                <div class="font-medium text-slate-800 dark:text-slate-200">{{ $kelompok->kampus?->nama_universitas ?? '-' }}</div>
                                                <div class="text-xs text-slate-500">{{ $kelompok->departemen?->nama_departemen ?? '-' }}</div>
                                            </td>
                                            <td class="kolom-aksi px-5 py-4 align-middle whitespace-nowrap text-end">
                                                <div class="flex items-center justify-end gap-3">
                                                    <a href="{{ route('kelompok.pdf', $kelompok) }}" class="aksi-ikon" title="Download PDF" aria-label="Download PDF {{ $kelompok->nama_kelompok }}">
                                                        <i class="fas fa-file-pdf" aria-hidden="true"></i>
                                                    </a>
                                                    <x-secondary-button type="button" x-data="" x-on:click.prevent="$dispatch('open-modal', 'edit-kelompok-{{ $kelompok->id }}')">
                                                        {{ __('Edit') }}
                                                    </x-secondary-button>

                                                    <x-danger-button type="button" x-data="" x-on:click.prevent="$dispatch('open-modal', 'delete-kelompok-{{ $kelompok->id }}')">
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
                        <p>Menampilkan {{ $kelompoks->firstItem() ?? 0 }}-{{ $kelompoks->lastItem() ?? 0 }} dari {{ $kelompoks->total() }} data</p>
                        <div class="self-start sm:self-auto">{{ $kelompoks->links() }}</div>
                    </div>

                    @foreach ($kelompoks as $kelompok)
                        <x-modal name="detail-kelompok-{{ $kelompok->id }}" maxWidth="2xl">
                            <div class="p-6">
                                <div class="flex items-start justify-between gap-4 border-b border-gray-100 pb-4">
                                    <div class="flex items-center gap-3">
                                        <span class="flex h-12 w-12 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600 border border-indigo-100 text-lg">
                                            <i class="fas fa-users"></i>
                                        </span>
                                        <div>
                                            <h2 class="text-lg font-bold text-gray-900">{{ $kelompok->nama_kelompok }}</h2>
                                            <p class="text-xs text-gray-500">ID Kelompok: #{{ $kelompok->id }} &middot; {{ $kelompok->kampus?->nama_universitas ?? 'Instansi Umum' }}</p>
                                        </div>
                                    </div>
                                    <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700">
                                        {{ $kelompok->mahasiswas_count }} Anggota
                                    </span>
                                </div>

                                <dl class="mt-5 grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-2">
                                    <div>
                                        <dt class="text-xs font-semibold uppercase tracking-wider text-gray-400">Kode Kelompok</dt>
                                        <dd class="mt-1 text-sm font-medium text-gray-900">{{ $kelompok->nama_kelompok }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-xs font-semibold uppercase tracking-wider text-gray-400">Periode Pelaksanaan</dt>
                                        <dd class="mt-1 text-sm font-medium text-gray-900">{{ $kelompok->tanggal_mulai->format('d M Y') }} — {{ $kelompok->tanggal_selesai->format('d M Y') }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-xs font-semibold uppercase tracking-wider text-gray-400">Instansi Pendidikan</dt>
                                        <dd class="mt-1 text-sm font-medium text-gray-900">{{ $kelompok->kampus?->nama_universitas ?? '-' }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-xs font-semibold uppercase tracking-wider text-gray-400">Departemen Instansi</dt>
                                        <dd class="mt-1 text-sm font-medium text-gray-900">{{ $kelompok->departemen?->nama_departemen ?? '-' }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-xs font-semibold uppercase tracking-wider text-gray-400">Pembimbing Sekolah/Kampus</dt>
                                        <dd class="mt-1 text-sm font-medium text-gray-900">{{ $kelompok->dosen?->nama ?? '-' }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-xs font-semibold uppercase tracking-wider text-gray-400">Pembimbing Lapangan</dt>
                                        <dd class="mt-1 text-sm font-medium text-gray-900">{{ $kelompok->pembimbing?->nama ?? '-' }}</dd>
                                    </div>
                                </dl>

                                <div class="mt-6">
                                    <h3 class="text-xs font-semibold uppercase tracking-wider text-gray-400">Daftar Anggota ({{ $kelompok->mahasiswas_count }})</h3>
                                    @if ($kelompok->mahasiswas->isEmpty())
                                        <p class="mt-2 text-sm text-gray-500">Belum ada anggota di kelompok ini.</p>
                                    @else
                                        <ul class="mt-2 max-h-56 divide-y divide-gray-100 overflow-y-auto rounded-lg border border-gray-200">
                                            @foreach ($kelompok->mahasiswas as $mahasiswa)
                                                <li class="flex items-center justify-between gap-4 px-3 py-2 text-sm">
                                                    <span class="font-medium text-gray-900">{{ $mahasiswa->name }}</span>
                                                    <span class="text-gray-500">{{ $mahasiswa->nim_nip ?? '-' }}</span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </div>

                                <div class="mt-6 flex justify-end">
                                    <a href="{{ route('kelompok.pdf', $kelompok) }}" class="mr-3 inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-xs font-semibold uppercase tracking-wider text-white transition hover:bg-blue-700">
                                        <i class="fas fa-file-pdf" aria-hidden="true"></i> Download PDF
                                    </a>
                                    <x-secondary-button type="button" x-on:click="$dispatch('close')">Tutup</x-secondary-button>
                                </div>
                            </div>
                        </x-modal>
                    @endforeach

                    @foreach ($kelompoks as $kelompok)
                        <x-modal name="edit-kelompok-{{ $kelompok->id }}">
                            <form method="POST" action="{{ route('kelompok.update', $kelompok->id, false) }}" class="p-6">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="nama_kelompok" value="{{ $kelompok->nama_kelompok }}">

                                <h2 class="text-lg font-medium text-gray-900">
                                    {{ __('Edit Kelompok') }} #{{ $kelompok->id }}: {{ $kelompok->nama_kelompok }}
                                </h2>

                                <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-2">
                                    <div class="sm:col-span-2">
                                        <x-input-label value="{{ __('Kode Kelompok') }}" />
                                        <x-text-input type="text" class="mt-1 block w-full bg-gray-100 font-mono text-sm" :value="$kelompok->nama_kelompok" readonly />
                                    </div>

                                    <div>
                                        <x-input-label for="edit_tanggal_mulai_{{ $kelompok->id }}" value="{{ __('Tanggal Mulai') }}" />
                                        <x-text-input id="edit_tanggal_mulai_{{ $kelompok->id }}" name="tanggal_mulai" type="date" class="mt-1 block w-full" :value="$kelompok->tanggal_mulai->format('Y-m-d')" required />
                                    </div>

                                    <div>
                                        <x-input-label for="edit_tanggal_selesai_{{ $kelompok->id }}" value="{{ __('Tanggal Selesai') }}" />
                                        <x-text-input id="edit_tanggal_selesai_{{ $kelompok->id }}" name="tanggal_selesai" type="date" class="mt-1 block w-full" :value="$kelompok->tanggal_selesai->format('Y-m-d')" required />
                                    </div>

                                    <div>
                                    <div>
                                        <x-input-label for="edit_kampus_id_{{ $kelompok->id }}" value="{{ __('Instansi Pendidikan') }}" />
                                        <x-combobox
                                            id="edit_kampus_id_{{ $kelompok->id }}"
                                            name="kampus_id"
                                            :value="$kelompok->kampus?->nama_universitas ?? ''"
                                            :options="$kampuses->pluck('nama_universitas')->filter()->unique()"
                                            placeholder="Ketik atau pilih instansi..."
                                        />
                                    </div>

                                    <div>
                                        <x-input-label for="edit_departemen_id_{{ $kelompok->id }}" value="{{ __('Departemen Instansi') }}" />
                                        <select id="edit_departemen_id_{{ $kelompok->id }}" name="departemen_id" class="mt-1 block w-full max-w-full min-w-0 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-slate-600 dark:bg-slate-800 dark:text-white dark:placeholder-slate-400 text-sm truncate" style="width: 100%; max-width: 100%; box-sizing: border-box;">
                                            <option value="">{{ __('- Pilih Departemen -') }}</option>
                                            @foreach ($departemens as $departemen)
                                                <option value="{{ $departemen->id }}" @selected($kelompok->departemen_id == $departemen->id)>
                                                    {{ $departemen->nama_departemen }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div>
                                        <x-input-label for="edit_dosen_id_{{ $kelompok->id }}" value="{{ __('Pembimbing Sekolah/Kampus') }}" />
                                        <select id="edit_dosen_id_{{ $kelompok->id }}" name="dosen_id" class="mt-1 block w-full max-w-full min-w-0 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-slate-600 dark:bg-slate-800 dark:text-white dark:placeholder-slate-400 text-sm truncate" style="width: 100%; max-width: 100%; box-sizing: border-box;">
                                            <option value="">{{ __('- Pilih Pembimbing Sekolah/Kampus -') }}</option>
                                            @foreach ($dosens as $dosen)
                                                <option value="{{ $dosen->id }}" @selected($kelompok->dosen_id == $dosen->id)>
                                                    {{ $dosen->nama }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div>
                                        <x-input-label for="edit_pembimbing_id_{{ $kelompok->id }}" value="{{ __('Pembimbing Lapangan') }}" />
                                        <select id="edit_pembimbing_id_{{ $kelompok->id }}" name="pembimbing_id" class="mt-1 block w-full max-w-full min-w-0 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-slate-600 dark:bg-slate-800 dark:text-white dark:placeholder-slate-400 text-sm truncate" style="width: 100%; max-width: 100%; box-sizing: border-box;">
                                            <option value="">{{ __('- Pilih Pembimbing Lapangan -') }}</option>
                                            @foreach ($pembimbings as $pembimbing)
                                                <option value="{{ $pembimbing->id }}" @selected($kelompok->pembimbing_id == $pembimbing->id)>
                                                    {{ $pembimbing->nama }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

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

                        <x-modal name="delete-kelompok-{{ $kelompok->id }}" :show="false" maxWidth="md">
                            <div class="p-6">
                                <h2 class="text-lg font-medium text-gray-900">
                                    {{ __('Yakin mau hapus kelompok ini?') }}
                                </h2>

                                <p class="mt-1 text-sm text-gray-600">
                                    {{ __('Kelompok') }} <strong>{{ $kelompok->nama_kelompok }}</strong> {{ __('akan dihapus permanen. Peserta yang tergabung tidak ikut terhapus, tetapi kelompoknya menjadi kosong.') }}
                                </p>

                                <div class="mt-6 flex justify-end">
                                    <x-secondary-button type="button" x-on:click="$dispatch('close')">
                                        {{ __('Batal') }}
                                    </x-secondary-button>

                                    <form method="post" action="{{ route('kelompok.destroy', $kelompok->id, false) }}" class="ms-3">
                                        @csrf
                                        @method('delete')
                                        <x-danger-button type="submit">
                                            {{ __('Ya, Hapus') }}
                                        </x-danger-button>
                                    </form>
                                </div>
                            </div>
                        </x-modal>

                        <x-modal name="kelola-anggota-{{ $kelompok->id }}" :show="false">
                            <div class="p-6">
                                <h2 class="text-lg font-medium text-gray-900">
                                    {{ __('Anggota') }} {{ $kelompok->nama_kelompok }}
                                </h2>

                                @if ($kelompok->mahasiswas->isEmpty())
                                    <p class="mt-4 text-sm text-gray-500">{{ __('Belum ada anggota.') }}</p>
                                @else
                                    <ul class="mt-4 divide-y divide-gray-100 rounded-md border border-gray-200">
                                        @foreach ($kelompok->mahasiswas as $anggota)
                                            <li class="flex items-center justify-between px-3 py-2 text-sm">
                                                <span>{{ $anggota->name }}</span>
                                                <span class="text-gray-500">{{ $anggota->nim_nip ?? '-' }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif

                                <form method="post" action="{{ route('kelompok.anggota.store', $kelompok) }}" class="mt-4">
                                    @csrf

                                    <x-input-label for="mahasiswa_id_{{ $kelompok->id }}" value="{{ __('Tambah dari Data Peserta') }}" />
                                    <select id="mahasiswa_id_{{ $kelompok->id }}" name="mahasiswa_id" class="mt-1 block w-full max-w-full min-w-0 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-slate-600 dark:bg-slate-800 dark:text-white dark:placeholder-slate-400 text-sm truncate" style="width: 100%; max-width: 100%; box-sizing: border-box;" required>
                                        <option value="">{{ __('- Pilih Peserta -') }}</option>
                                        @foreach ($mahasiswaBebas as $calon)
                                            <option value="{{ $calon->id }}">{{ $calon->name }} ({{ $calon->nim_nip ?? '-' }})</option>
                                        @endforeach
                                    </select>

                                    @if ($mahasiswaBebas->isEmpty())
                                        <p class="mt-2 text-sm text-gray-500">{{ __('Semua peserta sudah memiliki kelompok. Tambahkan peserta baru lewat menu Data Peserta.') }}</p>
                                    @endif

                                    <div class="mt-4 flex justify-end">
                                        <x-secondary-button type="button" x-on:click="$dispatch('close')">
                                            {{ __('Tutup') }}
                                        </x-secondary-button>

                                        <x-primary-button class="ms-3">
                                            {{ __('Tambahkan') }}
                                        </x-primary-button>
                                    </div>
                                </form>
                            </div>
                        </x-modal>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <x-modal name="create-kelompok">
        <form
            method="post"
            action="{{ route('kelompok.store') }}"
            class="p-6"
            x-data="{
                namaBaru: '',
                daftar: [],
                tambah() {
                    const nama = this.namaBaru.trim();
                    if (nama === '') { return; }
                    this.daftar.push(nama);
                    this.namaBaru = '';
                },
                hapus(index) {
                    this.daftar.splice(index, 1);
                },
            }"
        >
            @csrf

            <h2 class="text-lg font-medium text-gray-900">
                {{ __('Tambah Kelompok') }}
            </h2>

            <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div class="sm:col-span-2 rounded-md bg-blue-50 px-4 py-3 text-sm text-blue-700">
                    {{ __('Kode kelompok akan dibuat otomatis oleh sistem setelah disimpan.') }}
                </div>

                <div>
                    <x-input-label for="create_tanggal_mulai" value="{{ __('Tanggal Mulai') }}" />
                    <x-text-input id="create_tanggal_mulai" name="tanggal_mulai" type="date" class="mt-1 block w-full" :value="old('tanggal_mulai')" required />
                    <x-input-error :messages="$errors->get('tanggal_mulai')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="create_tanggal_selesai" value="{{ __('Tanggal Selesai') }}" />
                    <x-text-input id="create_tanggal_selesai" name="tanggal_selesai" type="date" class="mt-1 block w-full" :value="old('tanggal_selesai')" required />
                    <x-input-error :messages="$errors->get('tanggal_selesai')" class="mt-2" />
                </div>

                <div class="sm:col-span-2">
                    <x-input-label value="{{ __('Nama Anggota') }}" />

                    <div class="mt-1 flex gap-2">
                        <input
                            type="text"
                            x-model="namaBaru"
                            x-on:keydown.enter.prevent="tambah()"
                            autocomplete="off"
                            placeholder="{{ __('Ketik nama peserta, lalu klik + Tambah') }}"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-slate-600 dark:bg-slate-800 dark:text-white dark:placeholder-slate-400"
                        >
                        <x-secondary-button type="button" x-on:click="tambah()">
                            {{ __('+ Tambah') }}
                        </x-secondary-button>
                    </div>

                    <p class="mt-2 text-sm text-gray-500" x-show="daftar.length === 0">
                        {{ __('Belum ada nama yang ditambahkan.') }}
                    </p>

                    <ul class="mt-2 divide-y divide-gray-100 rounded-md border border-gray-200" x-show="daftar.length > 0">
                        <template x-for="(nama, index) in daftar" :key="index">
                            <li class="flex items-center justify-between px-3 py-2 text-sm">
                                <span x-text="nama"></span>
                                <input type="hidden" name="nama_anggota[]" :value="nama">
                                <button type="button" x-on:click="hapus(index)" class="font-medium text-red-600 hover:text-red-800">
                                    {{ __('Hapus') }}
                                </button>
                            </li>
                        </template>
                    </ul>

                    <x-input-error :messages="$errors->get('nama_anggota')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="create_kampus_id" value="{{ __('Kampus / Instansi') }}" />
                    @php
                        $oldKampusVal = old('kampus_id');
                        if (is_numeric($oldKampusVal)) {
                            $oldKampusVal = $kampuses->firstWhere('id', (int) $oldKampusVal)?->nama_universitas ?? $oldKampusVal;
                        }
                    @endphp
                    <x-combobox
                        id="create_kampus_id"
                        name="kampus_id"
                        :value="$oldKampusVal ?? ''"
                        :options="$kampuses->pluck('nama_universitas')->filter()->unique()"
                        placeholder="Ketik atau pilih kampus / instansi..."
                    />
                    <x-input-error :messages="$errors->get('kampus_id')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="create_departemen_id" value="{{ __('Departemen') }}" />
                    <select id="create_departemen_id" name="departemen_id" class="mt-1 block w-full max-w-full min-w-0 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-slate-600 dark:bg-slate-800 dark:text-white dark:placeholder-slate-400 text-sm truncate" style="width: 100%; max-width: 100%; box-sizing: border-box;">
                        <option value="">{{ __('- Pilih Departemen -') }}</option>
                        @foreach ($departemens as $departemen)
                            <option value="{{ $departemen->id }}" @selected(old('departemen_id') == $departemen->id)>
                                {{ $departemen->nama_departemen }}
                            </option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('departemen_id')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="create_dosen_id" value="{{ __('Pembimbing Sekolah/Kampus') }}" />
                    <select id="create_dosen_id" name="dosen_id" class="mt-1 block w-full max-w-full min-w-0 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-slate-600 dark:bg-slate-800 dark:text-white dark:placeholder-slate-400 text-sm truncate" style="width: 100%; max-width: 100%; box-sizing: border-box;">
                        <option value="">{{ __('- Pilih Pembimbing Sekolah/Kampus -') }}</option>
                        @foreach ($dosens as $dosen)
                            <option value="{{ $dosen->id }}" @selected(old('dosen_id') == $dosen->id)>
                                {{ $dosen->nama }}
                            </option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('dosen_id')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="create_pembimbing_id" value="{{ __('Pembimbing Lapangan') }}" />
                    <select id="create_pembimbing_id" name="pembimbing_id" class="mt-1 block w-full max-w-full min-w-0 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-slate-600 dark:bg-slate-800 dark:text-white dark:placeholder-slate-400 text-sm truncate" style="width: 100%; max-width: 100%; box-sizing: border-box;">
                        <option value="">{{ __('- Pilih Pembimbing Lapangan -') }}</option>
                        @foreach ($pembimbings as $pembimbing)
                            <option value="{{ $pembimbing->id }}" @selected(old('pembimbing_id') == $pembimbing->id)>
                                {{ $pembimbing->nama }}
                            </option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('pembimbing_id')" class="mt-2" />
                </div>

            </div>

            <div class="mt-6 flex justify-end">
                <x-secondary-button type="button" x-on:click="$dispatch('close')">
                    {{ __('Batal') }}
                </x-secondary-button>

                <x-primary-button class="ms-3">
                    {{ __('Simpan') }}
                </x-primary-button>
            </div>
        </form>
    </x-modal>

    <style>
        .clickable-row { cursor: pointer; transition: background-color .15s ease; }
        table td .aksi-ikon[title="Download PDF"] { color: #0f766e; background: #f0fdfa; border: 1px solid #99f6e4; }
        html.dark table td .aksi-ikon[title="Download PDF"] { color: #5eead4; background: rgba(20, 184, 166, .15); border-color: rgba(94, 234, 212, .3); }
        .clickable-row:hover,
        .clickable-row:focus-visible { background-color: #eef4fb !important; outline: none; }
        html.dark .clickable-row:hover,
        html.dark .clickable-row:focus-visible { background-color: rgba(148, 177, 211, .14) !important; }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            function bukaDetail(baris) {
                window.dispatchEvent(new CustomEvent('open-modal', { detail: baris.dataset.modal }));
            }

            document.addEventListener('click', function (e) {
                var baris = e.target.closest('tr.clickable-row');
                if (!baris) return;
                // klik di kolom Aksi / tombol / link / form: jangan buka detail
                if (e.target.closest('td.kolom-aksi, a, button, form, input, select, textarea, label')) return;
                bukaDetail(baris);
            });

            document.addEventListener('keydown', function (e) {
                if ((e.key === 'Enter' || e.key === ' ') && e.target.matches && e.target.matches('tr.clickable-row')) {
                    e.preventDefault();
                    bukaDetail(e.target);
                }
            });
        });
    </script>
</x-app-layout>