@php
    $role = $user->role;
    $roleLabel = match ($role) {
        'ADMIN' => 'Administrator',
        'MENTOR' => 'Pembimbing Lapangan',
        'DOSEN' => 'Dosen / Guru Pembimbing',
        'MAHASISWA' => 'Peserta Magang / PKL',
        default => 'Pengguna',
    };
    $roleBadgeColor = match ($role) {
        'ADMIN' => 'border-emerald-200 bg-emerald-50 text-emerald-700',
        'MENTOR' => 'border-amber-200 bg-amber-50 text-amber-800',
        'DOSEN' => 'border-indigo-200 bg-indigo-50 text-indigo-700',
        'MAHASISWA' => 'border-blue-200 bg-blue-50 text-blue-700',
        default => 'border-slate-200 bg-slate-50 text-slate-700',
    };
    $roleIcon = match ($role) {
        'ADMIN' => 'fa-shield-alt',
        'MENTOR' => 'fa-user-tie',
        'DOSEN' => 'fa-chalkboard-teacher',
        'MAHASISWA' => 'fa-user-graduate',
        default => 'fa-user',
    };
    $displayName = match ($role) {
        'MENTOR' => $pembimbingProfile?->nama ?: $user->name,
        'DOSEN' => $dosenProfile?->nama ?: $user->name,
        default => $user->name,
    };
    $profileEmail = match ($role) {
        'MENTOR' => $pembimbingProfile?->email ?: $user->email,
        'DOSEN' => $dosenProfile?->email ?: $user->email,
        default => $user->email,
    };
    $profilePhone = match ($role) {
        'MENTOR' => $pembimbingProfile?->hp_wa ?: $user->hp_wa,
        'DOSEN' => $dosenProfile?->hp_wa ?: $user->hp_wa,
        default => $user->hp_wa,
    };
    $profileWhatsappUrl = \App\Support\ContactLink::whatsappUrl($profilePhone);
    $profileIdentifier = match ($role) {
        'MENTOR' => $pembimbingProfile?->nip ?: $user->nim_nip,
        'DOSEN' => $dosenProfile?->nidn ?: $user->nim_nip,
        'MAHASISWA' => $user->nim_nip,
        default => null,
    };
    $subTitle = match ($role) {
        'ADMIN' => 'Administrator Sistem · PT Angkasa Pura Aviasi',
        'MENTOR' => 'Field Mentor · ' . ($pembimbingProfile?->departemen?->nama_departemen ?: ($user->unit ?: 'Unit Kerja Bandara Kualanamu')),
        'DOSEN' => 'Pembimbing Akademik · ' . ($dosenProfile?->kampus ?: ($user->kampus ?: ($user->instansi ?: 'Institusi Pendidikan'))),
        'MAHASISWA' => 'Peserta Magang · ' . ($user->kampus ?: ($user->kelompok?->kampus?->nama_universitas ?: ($user->instansi ?: 'Kampus / Sekolah'))),
        default => 'PT Angkasa Pura Aviasi',
    };
@endphp

<x-app-layout>
    <x-slot name="header">
        <div>
            <h1 class="text-xl font-bold tracking-tight text-gray-900">Profil Saya</h1>
            <p class="text-xs text-gray-500">Informasi akun dan penugasan Anda.</p>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
            @if (session('status') === 'profile-updated')
                <div class="flex items-center gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-medium text-emerald-800" role="status">
                    <i class="fas fa-check-circle" aria-hidden="true"></i>
                    <span>Perubahan profil berhasil disimpan.</span>
                </div>
            @endif

            <section class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-slate-900 via-slate-800 to-blue-950 p-6 text-white shadow-lg sm:p-8">
                <div class="relative flex flex-col gap-6 sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex min-w-0 items-center gap-5">
                        @if ($user->foto_url)
                            <img src="{{ $user->foto_url }}" alt="Foto profil {{ $displayName }}" class="h-24 w-24 shrink-0 rounded-full border-4 border-white/20 object-cover shadow-lg">
                        @else
                            <span class="flex h-24 w-24 shrink-0 items-center justify-center rounded-full bg-blue-700 text-3xl font-bold text-white shadow-lg" aria-label="Inisial {{ $displayName }}">
                                {{ strtoupper(substr($displayName ?: 'U', 0, 1)) }}
                            </span>
                        @endif
                        <div class="min-w-0 space-y-2">
                            <span class="inline-flex items-center gap-2 rounded-full border px-3 py-1 text-xs font-semibold {{ $roleBadgeColor }}">
                                <i class="fas {{ $roleIcon }}" aria-hidden="true"></i> {{ $roleLabel }}
                            </span>
                            @if ($profileIdentifier)
                                <p class="font-mono text-xs text-slate-300">
                                    {{ $role === 'DOSEN' ? 'NIDN' : ($role === 'MENTOR' ? 'NIP' : 'NIM / NISN') }}: {{ $profileIdentifier }}
                                </p>
                            @endif
                            <h2 class="break-words text-2xl font-bold text-white">{{ $displayName }}</h2>
                            <p class="text-sm text-blue-200">{{ $subTitle }}</p>
                            <div class="flex flex-wrap gap-x-5 gap-y-2 text-xs text-slate-200">
                                <span class="inline-flex items-center gap-2 break-all">
                                    <i class="fas fa-envelope text-blue-300" aria-hidden="true"></i>
                                    @if ($profileEmail)
                                        <a href="mailto:{{ $profileEmail }}" class="hover:text-white hover:underline">{{ $profileEmail }}</a>
                                    @else
                                        -
                                    @endif
                                </span>
                                <span class="inline-flex items-center gap-2">
                                    <i class="fab fa-whatsapp text-emerald-400" aria-hidden="true"></i>
                                    @if ($profileWhatsappUrl)
                                        <a href="{{ $profileWhatsappUrl }}" target="_blank" rel="noopener noreferrer" class="hover:text-white hover:underline">{{ $profilePhone }}</a>
                                    @else
                                        -
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>

                    <a href="{{ route('profile.edit') }}" class="inline-flex shrink-0 items-center justify-center gap-2 rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-300 focus:ring-offset-2 focus:ring-offset-slate-900">
                        <i class="fas fa-user-edit" aria-hidden="true"></i> Edit Profil
                    </a>
                </div>
            </section>

            <p class="text-xs text-gray-500">Data ini diisi oleh admin. Jika ada yang tidak sesuai, hubungi admin.</p>

            <div class="space-y-6">
                @if ($role === 'MAHASISWA')
                    @include('profile.partials.mahasiswa-profile')
                @elseif ($role === 'DOSEN')
                    @include('profile.partials.dosen-profile')
                @elseif ($role === 'MENTOR')
                    @include('profile.partials.mentor-profile')
                @else
                    @include('profile.partials.admin-profile')
                @endif
            </div>
        </div>
    </div>
</x-app-layout>