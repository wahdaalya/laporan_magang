<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Data Kampus') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if ($kampuses->isEmpty())
                        <p class="text-gray-500">Belum ada data kampus.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-sm">
                                <thead class="border-b border-gray-200 text-start text-gray-600">
                                    <tr>
                                        <th class="px-3 py-2 text-start">Universitas</th>
                                        <th class="px-3 py-2 text-start">Alamat</th>
                                        <th class="px-3 py-2 text-start">Fakultas</th>
                                        <th class="px-3 py-2 text-start">Prodi</th>
                                        <th class="px-3 py-2 text-start">Jumlah Kelompok</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($kampuses as $kampus)
                                        <tr class="border-b border-gray-100">
                                            <td class="px-3 py-2 font-medium">{{ $kampus->nama_universitas }}</td>
                                            <td class="px-3 py-2">{{ $kampus->alamat ?? '-' }}</td>
                                            <td class="px-3 py-2">{{ $kampus->fakultas ?? '-' }}</td>
                                            <td class="px-3 py-2">{{ $kampus->prodi ?? '-' }}</td>
                                            <td class="px-3 py-2">{{ $kampus->kelompoks_count }}</td>
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
