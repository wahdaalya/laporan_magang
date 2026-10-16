<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Data Pembimbing Lapangan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900"
                    x-data="{
                        editId: @js(old('_id') ?: null),
                        showErrors: @js($errors->any()),
                        f: {
    nama: @js(old('nama', '')),
    nip: @js(old('nip', '')),
    email: @js(old('email', '')),
    hp_wa: @js(old('hp_wa', '')),
    departemen_id: @js(old('departemen_id', '')),
    password: '',
},
                        get action() {
                            return this.editId ? '{{ url('pembimbing') }}/' + this.editId : '{{ route('pembimbing.store') }}';
                        },
                        tambah() {
    this.editId = null;
    this.showErrors = false;
    this.f = { nama: '', nip: '', email: '', hp_wa: '', departemen_id: '', password: '' };
},
ubah(d) {
    this.editId = d.id;
    this.showErrors = false;
    this.f = {
        nama: d.nama ?? '', nip: d.nip ?? '', email: d.email ?? '',
        hp_wa: d.hp_wa ?? '', departemen_id: d.departemen_id ?? '', password: '',
    };
},
                    }">
                    @if (session('status'))
                        <div class="mb-4 rounded-lg bg-green-50 px-4 py-3 text-sm text-green-700">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if (session('password_baru'))
                        <div class="mb-4 rounded-lg border border-amber-300 bg-amber-50 px-4 py-3 text-sm text-amber-800">
                            <p class="font-semibold">⚠️ Catat/kirim sekarang — password ini tidak akan ditampilkan lagi setelah halaman ini ditutup/di-refresh.</p>
                            <p class="mt-1">
                                <strong>{{ session('password_baru')['nama'] }}</strong> (
                                @if (session('password_baru')['email'])
                                    <a href="mailto:{{ session('password_baru')['email'] }}" class="hover:underline">{{ session('password_baru')['email'] }}</a>
                                @else
                                    -
                                @endif
                                )
                                — Password: <code class="rounded bg-amber-100 px-2 py-0.5 font-mono">{{ session('password_baru')['password'] }}</code>
                            </p>
                        </div>
                    @endif

                    <x-search-bar :action="route('pembimbing.index')" :value="$q" placeholder="Cari nama, NIP, email, atau departemen" />

                    <div class="mb-4 flex justify-end">
                        <button type="button" x-on:click="tambah(); $dispatch('open-modal', 'form-pembimbing')" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-3 py-1.5 text-sm font-medium text-white hover:bg-blue-700">
                            <i class="fas fa-plus"></i> Tambah Pembimbing Lapangan
                        </button>
                    </div>

                    @if ($pembimbings->isEmpty())
                        @if ($q !== '')
                            <div class="text-sm text-gray-600">
                                <p>Data tidak ditemukan untuk pencarian '{{ $q }}'</p>
                            </div>
                        @else
                            <p class="text-gray-500">Belum ada data pembimbing lapangan.</p>
                        @endif
                    @else
                        <div class="overflow-x-auto rounded-xl border border-gray-200 bg-white dark:border-slate-700 dark:bg-slate-800">
                            <table class="w-full border-slate-200 text-sm dark:border-slate-700">
                                <thead class="bg-gray-100 text-xs font-semibold uppercase tracking-wider text-gray-500 dark:bg-slate-900/80 dark:text-slate-300">
                                    <tr>
                                        <th class="px-6 py-4 text-start">ID</th>
                                        <th class="px-6 py-4 text-start">Nama Pembimbing Lapangan / Mentor</th>
                                        <th class="px-6 py-4 text-start">NIP / ID Pegawai</th>
                                        <th class="px-6 py-4 text-start">Email</th>
                                        <th class="px-6 py-4 text-start">HP/WA</th>
                                        <th class="px-6 py-4 text-start">ID Departemen</th>
<th class="px-6 py-4 text-start">Departemen / Unit Kerja</th>
                                        <th class="px-6 py-4 text-start">Jumlah Kelompok</th>
                                        <th class="px-6 py-4 text-start">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white text-slate-800 dark:bg-slate-800 dark:text-slate-200">
                                    @foreach ($pembimbings as $pembimbing)
                                        <tr class="border-b border-gray-100 bg-white transition hover:bg-gray-50 dark:border-slate-700 dark:bg-slate-800 dark:hover:bg-slate-700/50">
                                            <td class="px-6 py-4 text-gray-500">{{ $pembimbing->id }}</td>
                                            <td class="px-6 py-4 font-medium text-gray-900">{{ $pembimbing->nama }}</td>
                                            <td class="px-6 py-4 text-gray-600">{{ $pembimbing->nip ?? '-' }}</td>
                                            <td class="px-6 py-4 text-gray-600">
                                                @if ($pembimbing->email)
                                                    <a href="mailto:{{ $pembimbing->email }}" class="hover:text-blue-700 hover:underline">{{ $pembimbing->email }}</a>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 text-gray-600">
                                                @php($whatsappUrl = \App\Support\ContactLink::whatsappUrl($pembimbing->hp_wa))
                                                @if ($whatsappUrl)
                                                    <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener noreferrer" class="hover:text-emerald-700 hover:underline">{{ $pembimbing->hp_wa }}</a>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                           <td class="px-6 py-4 text-gray-600">{{ $pembimbing->departemen_id ?? '-' }}</td>
<td class="px-6 py-4 text-gray-600">{{ $pembimbing->departemen?->nama_departemen ?? '-' }}</td>
                                            <td class="px-6 py-4 text-gray-600">{{ $pembimbing->kelompoks_count }}</td>
                                            <td class="px-6 py-4">
                                                <div class="flex items-center gap-3">
                                                    <button type="button" x-on:click="ubah(@js($pembimbing->only(['id', 'nama', 'nip', 'email', 'hp_wa', 'departemen_id']))); $dispatch('open-modal', 'form-pembimbing')" class="inline-flex items-center rounded-md border border-gray-200 px-3 py-1.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-200 dark:hover:bg-slate-600">Edit Pembimbing Lapangan</button>
                                                    <form method="POST" action="{{ route('pembimbing.reset-password', $pembimbing) }}" onsubmit="return confirm('Buat password baru untuk {{ $pembimbing->nama }}? Password lama tidak bisa dipakai lagi.')">
                                                        @csrf
                                                        <button type="submit" class="inline-flex items-center rounded-md bg-amber-50 px-3 py-1.5 text-sm font-medium text-amber-700 hover:bg-amber-100 transition">Reset Password</button>
                                                    </form>
                                                    @if (auth()->user()?->role === 'SUPERADMIN')
                                                        <form method="POST" action="{{ route('pembimbing.destroy', $pembimbing) }}" onsubmit="return confirm('Hapus pembimbing ini?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="inline-flex items-center rounded-md border border-transparent bg-red-50 px-3 py-1.5 text-sm font-medium text-red-600 transition hover:bg-red-100 dark:border-rose-800/50 dark:bg-rose-950/60 dark:text-rose-300 dark:hover:bg-rose-900">Hapus</button>
                                                        </form>
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
                        <p>Menampilkan {{ $pembimbings->firstItem() ?? 0 }}-{{ $pembimbings->lastItem() ?? 0 }} dari {{ $pembimbings->total() }} data</p>
                        <div class="self-start sm:self-auto">{{ $pembimbings->links() }}</div>
                    </div>

                    @include('pembimbing._modal')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>