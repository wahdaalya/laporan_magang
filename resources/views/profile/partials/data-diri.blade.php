@php
    $kelompok = $user->kelompok;
    $fotoUrl = $user->foto_url;

    $data = [
        'Nama' => $user->name,
        'Email' => $user->email,
        'NIM / NISN' => $user->nim_nip,
        'Kampus / Sekolah' => $user->kampus ?? $kelompok?->kampus?->nama_universitas,
        'Fakultas' => $user->fakultas,
        'Prodi / Jurusan' => $user->prodi,
        'No. HP/WA' => $user->hp_wa,
        'Kode Kelompok' => $kelompok?->nama_kelompok,
        'Periode Magang / PKL' => $kelompok
            ? $kelompok->tanggal_mulai->format('d M Y').' - '.$kelompok->tanggal_selesai->format('d M Y')
            : null,
        'Departemen' => $kelompok?->departemen?->nama_departemen,
        'Dosen / Guru Pembimbing' => $kelompok?->dosen?->nama ?? $user->dosen?->name,
        'Pembimbing Lapangan' => $kelompok?->pembimbing?->nama ?? $user->mentor?->name,
    ];
@endphp

<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">Data Diri Peserta</h2>
        <p class="mt-1 text-sm text-gray-600">
            Data ini diisi oleh admin. Kalau ada yang tidak sesuai, silakan hubungi admin.
        </p>
    </header>

    <div class="mt-6 flex flex-col gap-8 sm:flex-row sm:items-stretch">
        {{-- Foto pasfoto --}}
        <div x-data="{ gagal: {{ $fotoUrl ? 'false' : 'true' }} }" class="flex w-full shrink-0 flex-col items-center justify-center gap-3 sm:w-48">
            <div class="relative aspect-[3/4] w-full max-w-[200px] overflow-hidden rounded-md border border-gray-200 bg-gray-100 shadow-sm">
                @if ($fotoUrl)
                    <img
                        src="{{ $fotoUrl }}"
                        alt="Foto {{ $user->name }}"
                        x-show="!gagal"
                        x-on:error="gagal = true"
                        x-init="if ($el.complete && $el.naturalWidth === 0) gagal = true"
                        class="absolute inset-0 h-full w-full object-cover object-center"
                    >
                @endif

                <div
                    x-show="gagal"
                    class="absolute inset-0 flex items-center justify-center bg-blue-600 text-5xl font-semibold text-white"
                >
                    {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                </div>
            </div>
            <span class="flex w-full max-w-full items-center justify-center rounded-full bg-emerald-50 px-3 py-2 text-center text-xs font-medium leading-snug text-emerald-700 sm:px-2">
                Status: Peserta Aktif Magang / PKL
            </span>
        </div>

        {{-- Data diri --}}
        <dl class="grid min-w-0 flex-1 grid-cols-1 gap-x-8 gap-y-1 sm:grid-cols-2">
            @foreach ($data as $label => $nilai)
                <div class="min-w-0 border-b border-gray-100 pb-0.5">
                    <dt class="text-xs font-medium uppercase leading-tight tracking-wide text-gray-500">{{ $label }}</dt>
                    <dd class="mt-0.5 break-words text-sm font-medium leading-tight text-gray-900">{{ $nilai ?: '-' }}</dd>
                </div>
            @endforeach
        </dl>
    </div>
</section>