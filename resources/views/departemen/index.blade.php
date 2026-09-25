<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Data Departemen') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
    @if (session('status'))
        <div class="mb-4 rounded-lg bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('status') }}
        </div>
    @endif

    <div class="mb-4 flex justify-end">
        <a href="{{ route('departemen.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-3 py-1.5 text-sm font-medium text-white hover:bg-blue-700">
            <i class="fas fa-plus"></i> Tambah Departemen
        </a>
    </div>

    @if ($departemens->isEmpty())
                        <p class="text-gray-500">Belum ada data departemen.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-sm">
                                <div class="overflow-x-auto border border-gray-200 rounded-xl">
    <table class="w-full text-sm">
        <thead class="bg-gray-100 text-xs font-semibold uppercase tracking-wider text-gray-500">
            <tr>
                <th class="px-6 py-4 text-start">Nama Departemen</th>
                <th class="px-6 py-4 text-start">Pimpinan</th>
                <th class="px-6 py-4 text-start">Jumlah Kelompok</th>
                <th class="px-6 py-4 text-start">Aksi</th>
            </tr>
        </thead>
                                <tbody>
                                    @foreach ($departemens as $departemen)
                                        <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
    <td class="px-6 py-4 font-medium text-gray-900">{{ $departemen->nama_departemen }}</td>
    <td class="px-6 py-4 text-gray-600">{{ $departemen->nama_pimpinan ?? '-' }}</td>
    <td class="px-6 py-4 text-gray-600">{{ $departemen->kelompoks_count }}</td>
    <td class="px-6 py-4">
    <div class="flex items-center gap-3">
        <a href="{{ route('departemen.edit', $departemen) }}" class="inline-flex items-center rounded-md border border-gray-200 px-3 py-1.5 text-sm font-medium text-gray-700 hover:bg-gray-50 transition">Ubah</a>
        <form method="POST" action="{{ route('departemen.destroy', $departemen) }}" onsubmit="return confirm('Hapus departemen ini?')">
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
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
