<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>[x-cloak] { display: none !important; }</style>

    <title>{{ config('app.name', 'PT Angkasa Pura Aviasi') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-100">
    <div class="min-h-screen flex">

        <aside class="w-64 shrink-0 self-start sticky top-0 h-screen overflow-y-auto bg-slate-800 text-white flex flex-col justify-between p-4">
            <div>
                <div class="flex items-center gap-3 px-2 py-4 mb-4 border-b border-slate-700">
                    <div class="bg-blue-600 text-white font-bold p-2.5 rounded-lg text-sm tracking-wider">KNO</div>
                    <div>
                        <h1 class="font-bold text-sm leading-tight">PT Angkasa Pura Aviasi</h1>
                        <p class="text-xs text-slate-400">Bandara Kualanamu</p>
                    </div>
                </div>

                <nav class="space-y-1">
                    @php
                        $role = Auth::user()?->role;
                        $berandaRoute = match ($role) {
                            'ADMIN' => 'admin.index',
                            'DOSEN' => 'dosen.dashboard',
                            'MENTOR' => 'pembimbingan.index',
                            default => 'dashboard',
                        };
                        $berandaAktif = match ($role) {
                            'ADMIN' => request()->routeIs('admin.index'),
                            'DOSEN' => request()->routeIs('dosen.dashboard'),
                            'MENTOR' => request()->routeIs('pembimbingan.index'),
                            default => request()->routeIs('dashboard', 'mahasiswa.dashboard'),
                        };
                    @endphp

                    <a href="{{ route($berandaRoute) }}" @class([
                        'flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition hover:bg-slate-700',
                        'bg-slate-700 text-white' => $berandaAktif,
                        'text-slate-200' => ! $berandaAktif,
                    ]) @if ($berandaAktif) aria-current="page" @endif>
                        <i class="fas fa-home w-5 text-center"></i>
                        <span>Beranda</span>
                    </a>

                    @if (Auth::user()?->role === 'MAHASISWA')
                        <a href="{{ route('mahasiswa.laporan.index') }}"
                            @class([
                                'flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition hover:bg-slate-700',
                                'bg-slate-700 text-white' => request()->routeIs('mahasiswa.laporan.index'),
                                'text-slate-200' => ! request()->routeIs('mahasiswa.laporan.index'),
                            ])
                            @if (request()->routeIs('mahasiswa.laporan.index')) aria-current="page" @endif
                        >
                            <i class="fas fa-book-open w-5 text-center" aria-hidden="true"></i>
                            <span>Riwayat Laporan</span>
                        </a>
                        <a href="{{ route('mahasiswa.laporan-akhir.index') }}" @class([
                            'flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition hover:bg-slate-700',
                            'bg-slate-700 text-white' => request()->routeIs('mahasiswa.laporan-akhir.*'),
                            'text-slate-200' => ! request()->routeIs('mahasiswa.laporan-akhir.*'),
                        ])>
                            <i class="fas fa-file-upload w-5 text-center" aria-hidden="true"></i>
                            <span>Laporan Akhir</span>
                        </a>
                        <a href="{{ route('mahasiswa.absensi.index') }}" class="flex items-center gap-3 px-3 py-2 text-sm rounded-lg hover:bg-slate-700 transition font-medium">
                            <i class="fas fa-file-pdf w-5 text-center"></i>
                            <span>Cetak Absensi</span>
                        </a>
                        <a href="{{ route('mahasiswa.penilaian.index') }}" class="flex items-center gap-3 px-3 py-2 text-sm rounded-lg hover:bg-slate-700 transition font-medium">
                            <i class="fas fa-star w-5 text-center"></i>
                            <span>Penilaian Saya</span>
                        </a>
                    @endif

                    @if (Auth::user()?->role === 'DOSEN')
                        <a href="{{ route('dosen.logbook.index') }}" @class([
                            'flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition hover:bg-slate-700',
                            'bg-slate-700 text-white' => request()->routeIs('dosen.logbook.*'),
                            'text-slate-200' => ! request()->routeIs('dosen.logbook.*'),
                        ])>
                            <i class="fas fa-book w-5 text-center" aria-hidden="true"></i>
                            <span>Logbook Mahasiswa</span>
                        </a>
                        <a href="{{ route('dosen.laporan-akhir.index') }}" @class([
                            'flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition hover:bg-slate-700',
                            'bg-slate-700 text-white' => request()->routeIs('dosen.laporan-akhir.*'),
                            'text-slate-200' => ! request()->routeIs('dosen.laporan-akhir.*'),
                        ])>
                            <i class="fas fa-file-signature w-5 text-center" aria-hidden="true"></i>
                            <span>Bimbingan Laporan</span>
                        </a>
                        <a href="{{ route('dosen.penilaian-akhir.index') }}" @class([
                            'flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition hover:bg-slate-700',
                            'bg-slate-700 text-white' => request()->routeIs('dosen.penilaian-akhir.*'),
                            'text-slate-200' => ! request()->routeIs('dosen.penilaian-akhir.*'),
                        ])>
                            <i class="fas fa-graduation-cap w-5 text-center" aria-hidden="true"></i>
                            <span>Penilaian Akhir</span>
                        </a>
                    @endif

                    @if (Auth::user()?->role === 'MENTOR')
                        <a href="{{ route('pembimbingan.laporan.index') }}" @class([
                            'flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition hover:bg-slate-700',
                            'bg-slate-700 text-white' => request()->routeIs('pembimbingan.laporan.*'),
                            'text-slate-200' => ! request()->routeIs('pembimbingan.laporan.*'),
                        ])>
                            <i class="fas fa-file-signature w-5 text-center" aria-hidden="true"></i>
                            <span>Review Laporan</span>
                        </a>
                        <a href="{{ route('pembimbing.peserta') }}" @class([
                            'flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition hover:bg-slate-700',
                            'bg-slate-700 text-white' => request()->routeIs('pembimbing.peserta', 'pembimbingan.peserta'),
                            'text-slate-200' => ! request()->routeIs('pembimbing.peserta', 'pembimbingan.peserta'),
                        ])>
                            <i class="fas fa-users w-5 text-center" aria-hidden="true"></i>
                            <span>Peserta &amp; Penilaian</span>
                        </a>
                    @endif

                    @if (Auth::user()?->role === 'ADMIN')
                        <div class="px-3 pt-5 pb-1 text-xs font-semibold text-slate-400 uppercase tracking-wider">
                            Master Data
                        </div>

                        <a href="{{ route('kampus.index') }}" class="flex items-center gap-3 px-3 py-2 text-sm rounded-lg hover:bg-slate-700 transition">
                            <i class="fas fa-university w-5 text-center"></i>
                            <span>Data Kampus</span>
                        </a>

                        <a href="{{ route('dosen.index') }}" class="flex items-center gap-3 px-3 py-2 text-sm rounded-lg hover:bg-slate-700 transition">
                            <i class="fas fa-chalkboard-teacher w-5 text-center"></i>
                            <span>Data Dosen</span>
                        </a>

                        <a href="{{ route('pembimbing.index') }}" class="flex items-center gap-3 px-3 py-2 text-sm rounded-lg hover:bg-slate-700 transition">
                            <i class="fas fa-user-tie w-5 text-center"></i>
                            <span>Data Pembimbing</span>
                        </a>

                        <a href="{{ route('departemen.index') }}" class="flex items-center gap-3 px-3 py-2 text-sm rounded-lg hover:bg-slate-700 transition">
                            <i class="fas fa-building w-5 text-center"></i>
                            <span>Data Departemen</span>
                        </a>

                        <div class="px-3 pt-5 pb-1 text-xs font-semibold text-slate-400 uppercase tracking-wider">
                            Kelompok PKL
                        </div>

                        <a href="{{ route('kelompok.index') }}" class="flex items-center gap-3 px-3 py-2 text-sm rounded-lg hover:bg-slate-700 transition">
                            <i class="fas fa-users w-5 text-center"></i>
                            <span>Data Kelompok</span>
                        </a>

                        <a href="{{ route('mahasiswa.index') }}" class="flex items-center gap-3 px-3 py-2 text-sm rounded-lg hover:bg-slate-700 transition">
                            <i class="fas fa-user-graduate w-5 text-center"></i>
                            <span>Data Peserta Magang / PKL</span>
                        </a>
                    @endif

                    <a href="{{ route('profile.show') }}" @class([
                        'flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition hover:bg-slate-700',
                        'bg-slate-700 text-white' => request()->routeIs('profile.show'),
                        'text-slate-200' => ! request()->routeIs('profile.show'),
                    ]) @if (request()->routeIs('profile.show')) aria-current="page" @endif>
                        <i class="fas fa-user-circle w-5 text-center" aria-hidden="true"></i>
                        <span>Profil Saya</span>
                    </a>

                    @if (in_array(Auth::user()?->role, ['MAHASISWA', 'DOSEN', 'MENTOR'], true))
                        <a href="{{ route('profile.edit') }}" @class([
                            'flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition hover:bg-slate-700',
                            'bg-slate-700 text-white' => request()->routeIs('profile.edit'),
                            'text-slate-200' => ! request()->routeIs('profile.edit'),
                        ]) @if (request()->routeIs('profile.edit')) aria-current="page" @endif>
                            <i class="fas fa-user-edit w-5 text-center" aria-hidden="true"></i>
                            <span>Edit Profil</span>
                        </a>
                    @endif

                </nav>
            </div>

            <div class="px-2 py-4 border-t border-slate-700 text-xs text-slate-400">
                <p class="font-semibold text-white">KNO</p>
                <p>Mendampingi untuk Masa Depan</p>
            </div>
        </aside>

        <div class="flex-1 flex flex-col min-w-0">

            <header class="bg-white border-b px-6 py-4 flex justify-between items-center shadow-sm">
                <div>
                    @isset($header)
                        {{ $header }}
                    @else
                        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                            Dashboard Admin
                        </h2>
                    @endisset
                </div>

                <div class="flex items-center">
                    @if ($profileSummary)
                        <div x-data="{ open: false }" x-on:click.outside="open = false" x-on:keydown.escape.window="open = false" class="relative">
                            <button type="button" x-on:click="open = !open" x-bind:aria-expanded="open.toString()" aria-haspopup="dialog" aria-label="Buka detail profil" class="flex items-center gap-2 rounded-full p-1 pr-2 transition hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                @if ($profileSummary['photo'])
                                    <img src="{{ $profileSummary['photo'] }}" alt="" class="h-9 w-9 rounded-full border border-gray-200 object-cover">
                                @else
                                    <span class="flex h-9 w-9 items-center justify-center rounded-full bg-blue-700 text-sm font-semibold text-white">{{ $profileSummary['initial'] }}</span>
                                @endif
                                <span class="hidden max-w-40 truncate text-sm font-medium text-gray-700 sm:block">{{ $profileSummary['name'] }}</span>
                                <i class="fas fa-chevron-down text-[10px] text-gray-500" aria-hidden="true"></i>
                            </button>

                            <section x-cloak x-show="open" x-transition.origin.top.right class="absolute right-0 top-full z-50 mt-3 w-80 max-w-[calc(100vw-2rem)] overflow-hidden rounded-xl border border-gray-200 bg-white text-gray-900 shadow-xl" role="dialog" aria-label="Detail profil pengguna">
                                <div class="flex items-center gap-3 border-b border-gray-100 bg-gray-50 px-4 py-4">
                                    @if ($profileSummary['photo'])
                                        <img src="{{ $profileSummary['photo'] }}" alt="Foto {{ $profileSummary['name'] }}" class="h-14 w-14 shrink-0 rounded-full border border-white object-cover shadow-sm">
                                    @else
                                        <span class="flex h-14 w-14 shrink-0 items-center justify-center rounded-full bg-blue-700 text-xl font-semibold text-white">{{ $profileSummary['initial'] }}</span>
                                    @endif
                                    <div class="min-w-0">
                                        <h2 class="truncate font-semibold text-gray-900">{{ $profileSummary['name'] }}</h2>
                                        <span class="mt-1 inline-flex max-w-full rounded-full bg-blue-50 px-2.5 py-1 text-xs font-medium text-blue-800">{{ $profileSummary['role'] }}</span>
                                    </div>
                                </div>

                                <dl class="px-4 py-4 text-sm">
                                    <div>
                                        <dt class="text-xs font-medium uppercase tracking-wide text-gray-500">Email</dt>
                                        <dd class="mt-0.5 break-all text-gray-800">
                                            @if ($profileSummary['email'])
                                                <a href="mailto:{{ $profileSummary['email'] }}" class="hover:text-blue-700 hover:underline">{{ $profileSummary['email'] }}</a>
                                            @else
                                                -
                                            @endif
                                        </dd>
                                    </div>
                                </dl>

                                <div class="flex gap-2 border-t border-gray-100 p-3">
                                    <a href="{{ route('profile.show') }}" class="inline-flex flex-1 items-center justify-center gap-2 rounded-md bg-blue-700 px-3 py-2 text-sm font-semibold text-white hover:bg-blue-800">
                                        <i class="fas fa-id-card" aria-hidden="true"></i> Lihat Profil Lengkap
                                    </a>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-md border border-gray-300 px-3 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                                            <i class="fas fa-sign-out-alt" aria-hidden="true"></i> Keluar
                                        </button>
                                    </form>
                                </div>
                            </section>
                        </div>
                    @endif
                </div>
            </header>

            <main class="p-6 flex-1">
                {{ $slot }}
            </main>

        </div>
    </div>
</body>
</html>