<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Data Dosen') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900"
    x-data="{
        sumber: {
            kampus: @js($kampuses->pluck('nama_universitas')->filter()->unique()->values()),
            fakultas: @js($kampuses->pluck('fakultas')->filter()->unique()->values()),
            prodi: @js($kampuses->pluck('prodi')->filter()->unique()->values()),
        },
        saran: { kampus: [], fakultas: [], prodi: [] },
        terbuka: { kampus: false, fakultas: false, prodi: false },
        cari(field) {
            const q = (this.f[field] ?? '').trim().toLowerCase();
            this.saran[field] = q === ''
                ? this.sumber[field]
                : this.sumber[field].filter(n => n.toLowerCase().includes(q));
            this.terbuka[field] = true;
        },
        bukaDropdown(field) {
            this.saran[field] = this.sumber[field];
            this.terbuka[field] = true;
        },
        pilih(field, nilai) {
            this.f[field] = nilai;
            this.terbuka[field] = false;
        },
        editId: @js(old('_id') ?: null),
        showErrors: @js($errors->any()),
        f: {
            nama: @js(old('nama', '')),
            nidn: @js(old('nidn', '')),
            kampus: @js(old('kampus', '')),
            email: @js(old('email', '')),
            hp_wa: @js(old('hp_wa', '')),
            fakultas: @js(old('fakultas', '')),
            prodi: @js(old('prodi', '')),
            password: '',
        },
        get action() {
            return this.editId ? '{{ url('dosen') }}/' + this.editId : '{{ route('dosen.store') }}';
        },
        tambah() {
            this.editId = null;
            this.showErrors = false;
            this.f = { nama: '', nidn: '', kampus: '', email: '', hp_wa: '', fakultas: '', prodi: '', password: '' };
        },
        ubah(d) {
            this.editId = d.id;
            this.showErrors = false;
            this.f = {
                nama: d.nama ?? '', nidn: d.nidn ?? '', kampus: d.kampus ?? '', email: d.email ?? '',
                hp_wa: d.hp_wa ?? '', fakultas: d.fakultas ?? '', prodi: d.prodi ?? '', password: '',
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
                <strong>{{ session('password_baru')['nama'] }}</strong> ({{ session('password_baru')['email'] }})
                — Password: <code class="rounded bg-amber-100 px-2 py-0.5 font-mono">{{ session('password_baru')['password'] }}</code>
            </p>
        </div>
    @endif

    <div class="mb-4 flex justify-end">
        <button type="button" x-on:click="tambah(); $dispatch('open-modal', 'form-dosen')" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-3 py-1.5 text-sm font-medium text-white hover:bg-blue-700">
    <i class="fas fa-plus"></i> Tambah Dosen
</button>
    </div>

    @if ($dosens->isEmpty())
                        <p class="text-gray-500">Belum ada data dosen.</p>
                    @else
                        <div class="overflow-x-auto border border-gray-200 rounded-xl">
    <table class="w-full text-sm">
        <thead class="bg-gray-100 text-xs font-semibold uppercase tracking-wider text-gray-500">
            <tr>
                <th class="px-6 py-4 text-start">ID</th>
<th class="px-6 py-4 text-start">Nama</th>
                <th class="px-6 py-4 text-start">NIDN</th>
                <th class="px-6 py-4 text-start">Kampus</th>
                <th class="px-6 py-4 text-start">Email</th>
                <th class="px-6 py-4 text-start">HP/WA</th>
                <th class="px-6 py-4 text-start">Fakultas</th>
                <th class="px-6 py-4 text-start">Prodi</th>
                <th class="px-6 py-4 text-start">Jumlah Kelompok</th>
                <th class="px-6 py-4 text-start">Aksi</th>
            </tr>
        </thead>
                                <tbody>
                                    @foreach ($dosens as $dosen)
                                        <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
    <td class="px-6 py-4 text-gray-500">{{ $dosen->id }}</td>
<td class="px-6 py-4 font-medium text-gray-900">{{ $dosen->nama }}</td>
    <td class="px-6 py-4 text-gray-600">{{ $dosen->nidn ?? '-' }}</td>
    <td class="px-6 py-4 text-gray-600">{{ $dosen->kampus ?? '-' }}</td>
    <td class="px-6 py-4 text-gray-600">{{ $dosen->email ?? '-' }}</td>
    <td class="px-6 py-4 text-gray-600">{{ $dosen->hp_wa ?? '-' }}</td>
    <td class="px-6 py-4 text-gray-600">{{ $dosen->fakultas ?? '-' }}</td>
    <td class="px-6 py-4 text-gray-600">{{ $dosen->prodi ?? '-' }}</td>
    <td class="px-6 py-4 text-gray-600">{{ $dosen->kelompoks_count }}</td>
    <td class="px-6 py-4">
        <div class="flex items-center gap-3">
            <button type="button" x-on:click="ubah(@js($dosen->only(['id', 'nama', 'nidn', 'kampus', 'email', 'hp_wa', 'fakultas', 'prodi']))); $dispatch('open-modal', 'form-dosen')" class="inline-flex items-center rounded-md border border-gray-200 px-3 py-1.5 text-sm font-medium text-gray-700 hover:bg-gray-50 transition">Ubah</button>
            <form method="POST" action="{{ route('dosen.reset-password', $dosen) }}" onsubmit="return confirm('Buat password baru untuk {{ $dosen->nama }}? Password lama tidak bisa dipakai lagi.')">
                @csrf
                <button type="submit" class="inline-flex items-center rounded-md bg-amber-50 px-3 py-1.5 text-sm font-medium text-amber-700 hover:bg-amber-100 transition">Reset Password</button>
            </form>
            <form method="POST" action="{{ route('dosen.destroy', $dosen) }}" onsubmit="return confirm('Hapus dosen ini?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="inline-flex items-center rounded-md bg-red-50 px-3 py-1.5 text-sm font-medium text-red-600 hover:bg-red-100 transition">Hapus</button>
            </form>
        </div>
    </td>
</tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                                        @endif

                    @include('dosen._modal')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
