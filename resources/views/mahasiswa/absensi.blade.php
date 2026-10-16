<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Cetak Daftar Hadir') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <p class="text-gray-600 mb-6">Pilih bulan dan tahun untuk mencetak daftar hadir kegiatan magang kamu.</p>

                <form action="{{ route('mahasiswa.absensi.cetak', [], false) }}" method="GET" target="_blank" class="flex flex-wrap items-end gap-4">
                    <div>
                        <x-input-label for="bulan" value="Bulan" />
                        <select id="bulan" name="bulan" class="mt-1 block w-40 rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-slate-600 dark:bg-slate-800 dark:text-white dark:placeholder-slate-400">
                            @foreach (['1'=>'Januari','2'=>'Februari','3'=>'Maret','4'=>'April','5'=>'Mei','6'=>'Juni','7'=>'Juli','8'=>'Agustus','9'=>'September','10'=>'Oktober','11'=>'November','12'=>'Desember'] as $value => $label)
                                <option value="{{ $value }}" @selected($bulan == $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <x-input-label for="tahun" value="Tahun" />
                        <select id="tahun" name="tahun" class="mt-1 block w-32 rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-slate-600 dark:bg-slate-800 dark:text-white dark:placeholder-slate-400">
                            @for ($t = now()->year; $t >= now()->year - 2; $t--)
                                <option value="{{ $t }}" @selected($tahun == $t)>{{ $t }}</option>
                            @endfor
                        </select>
                    </div>

                    <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700 sm:ml-4">
                        <i class="fas fa-file-pdf"></i> Cetak PDF
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>