@props(['action', 'placeholder', 'value' => '', 'tahun' => null, 'daftarTahun' => null])

<form method="GET" action="{{ $action }}" {{ $attributes->class(['mb-4 w-full']) }}>
    <label for="q" class="sr-only">Cari data</label>
    <div class="flex w-full flex-col gap-2 sm:flex-row sm:items-center">
        <div class="relative w-full max-w-md flex-1">
            <i class="fas fa-search pointer-events-none absolute inset-y-0 left-3 flex items-center text-sm text-gray-400" aria-hidden="true"></i>
            <input
                id="q"
                name="q"
                type="search"
                value="{{ $value }}"
                placeholder="{{ $placeholder }}"
                class="h-10 w-full rounded-lg border border-gray-300 bg-white py-2 pl-9 pr-3 text-sm text-gray-900 placeholder-gray-400 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
            >
        </div>

        @if ($daftarTahun !== null)
            <div class="w-full sm:w-48">
                <label for="tahun" class="sr-only">Pilih Tahun Magang</label>
                <select
                    id="tahun"
                    name="tahun"
                    title="Pilih Tahun Magang"
                    onchange="this.form.submit()"
                    class="h-10 w-full rounded-lg border border-gray-300 bg-white px-3 text-sm text-gray-700 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                >
                    <option value="">Semua Tahun</option>
                    @foreach ($daftarTahun as $th)
                        <option value="{{ $th }}" @selected((string) $tahun === (string) $th)>Tahun {{ $th }}</option>
                    @endforeach
                </select>
            </div>
        @endif

        <div class="flex items-center gap-2">
            <button type="submit" class="inline-flex h-10 items-center justify-center rounded-lg border border-gray-300 bg-white px-4 text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                Cari
            </button>
            @if ($value !== '' || (string) $tahun !== '')
                <a href="{{ $action }}" class="inline-flex h-10 items-center px-3 text-sm font-medium text-gray-500 hover:text-gray-700">
                    Reset
                </a>
            @endif
        </div>
    </div>
</form>