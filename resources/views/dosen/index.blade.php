<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Data Pembimbing Sekolah / Kampus') }}
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
            nidn: @js(old('nidn', '')),
            kampus: @js(old('kampus', '')),
            email: @js(old('email', '')),
            hp_wa: @js(old('hp_wa', '')),
            password: '',
        },
        get action() {
            return this.editId ? '{{ url('dosen') }}/' + this.editId : '{{ route('dosen.store') }}';
        },
        tambah() {
            this.editId = null;
            this.showErrors = false;
            this.f = { nama: '', nidn: '', kampus: '', email: '', hp_wa: '', password: '' };
        },
        ubah(d) {
            this.editId = d.id;
            this.showErrors = false;
            this.f = {
                nama: d.nama ?? '', nidn: d.nidn ?? '', kampus: d.kampus ?? '', email: d.email ?? '',
                hp_wa: d.hp_wa ?? '', password: '',
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

    <x-search-bar :action="route('dosen.index')" :value="$q" placeholder="Cari nama, ID pengajar, email, atau instansi" />

    <div class="mb-4 flex justify-end">
        <button type="button" x-on:click="tambah(); $dispatch('open-modal', 'form-dosen')" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-3 py-1.5 text-sm font-medium text-white hover:bg-blue-700">
    <i class="fas fa-plus"></i> Tambah Pembimbing Sekolah/Kampus
</button>
    </div>

    @if ($dosens->isEmpty())
        @if ($q !== '')
            <div class="text-sm text-gray-600">
                <p>Data tidak ditemukan untuk pencarian '{{ $q }}'</p>
            </div>
        @else
            <p class="text-gray-500">Belum ada data pembimbing sekolah/kampus.</p>
        @endif
                    @else
                        <div class="overflow-x-auto rounded-xl border border-gray-200 bg-white dark:border-slate-700 dark:bg-slate-800">
    <table class="w-full border-slate-200 text-sm dark:border-slate-700">
        <thead class="bg-gray-100 text-xs font-semibold uppercase tracking-wider text-gray-500 dark:bg-slate-900/80 dark:text-slate-300">
            <tr>
                <th class="px-6 py-4 text-start">ID</th>
                <th class="px-6 py-4 text-start">Nama Guru / Dosen Pembimbing</th>
                <th class="px-6 py-4 text-start">NIP / NUPTK / ID Pengajar</th>
                <th class="px-6 py-4 text-start">Instansi / Sekolah / Kampus</th>
                <th class="px-6 py-4 text-start">Email</th>
                <th class="px-6 py-4 text-start">HP/WA</th>
                <th class="px-6 py-4 text-start">Jumlah Kelompok</th>
                <th class="px-6 py-4 text-start">Aksi</th>
            </tr>
        </thead>
                                <tbody class="bg-white text-slate-800 dark:bg-slate-800 dark:text-slate-200">
                                    @foreach ($dosens as $dosen)
                                        <tr class="border-b border-gray-100 bg-white transition hover:bg-gray-50 dark:border-slate-700 dark:bg-slate-800 dark:hover:bg-slate-700/50">
    <td class="px-6 py-4 text-gray-500">{{ $dosen->id }}</td>
<td class="px-6 py-4 font-medium text-gray-900">{{ $dosen->nama }}</td>
    <td class="px-6 py-4 text-gray-600">{{ $dosen->nidn ?? '-' }}</td>
    <td class="px-6 py-4 text-gray-600">{{ $dosen->kampus ?? '-' }}</td>
    <td class="px-6 py-4 text-gray-600">
        @if ($dosen->email)
            <a href="mailto:{{ $dosen->email }}" class="hover:text-blue-700 hover:underline">{{ $dosen->email }}</a>
        @else
            -
        @endif
    </td>
    <td class="px-6 py-4 text-gray-600">
        @php($whatsappUrl = \App\Support\ContactLink::whatsappUrl($dosen->hp_wa))
        @if ($whatsappUrl)
            <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener noreferrer" class="hover:text-emerald-700 hover:underline">{{ $dosen->hp_wa }}</a>
        @else
            -
        @endif
    </td>
    <td class="px-6 py-4 text-gray-600">{{ $dosen->kelompoks_count }}</td>
    <td class="px-6 py-4">
        <div class="flex items-center gap-3">
            <button type="button" x-on:click="ubah(@js($dosen->only(['id', 'nama', 'nidn', 'kampus', 'email', 'hp_wa']))); $dispatch('open-modal', 'form-dosen')" class="inline-flex items-center rounded-md border border-gray-200 px-3 py-1.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-200 dark:hover:bg-slate-600">Edit Pembimbing Sekolah/Kampus</button>
            <form method="POST" action="{{ route('dosen.reset-password', $dosen) }}" onsubmit="return confirm('Buat password baru untuk {{ $dosen->nama }}? Password lama tidak bisa dipakai lagi.')">
                @csrf
                <button type="submit" class="inline-flex items-center rounded-md bg-amber-50 px-3 py-1.5 text-sm font-medium text-amber-700 hover:bg-amber-100 transition">Reset Password</button>
            </form>
            <form method="POST" action="{{ route('dosen.destroy', $dosen) }}" onsubmit="return confirm('Hapus dosen ini?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="inline-flex items-center rounded-md border border-transparent bg-red-50 px-3 py-1.5 text-sm font-medium text-red-600 transition hover:bg-red-100 dark:border-rose-800/50 dark:bg-rose-950/60 dark:text-rose-300 dark:hover:bg-rose-900">Hapus</button>
            </form>
        </div>
    </td>
</tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif

                    <div class="mt-4 flex flex-col gap-3 border-t border-gray-100 pt-4 text-sm text-gray-600 sm:flex-row sm:items-center sm:justify-between">
                        <p>Menampilkan {{ $dosens->firstItem() ?? 0 }}-{{ $dosens->lastItem() ?? 0 }} dari {{ $dosens->total() }} data</p>
                        <div class="self-start sm:self-auto">{{ $dosens->links() }}</div>
                    </div>

                    @include('dosen._modal')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
