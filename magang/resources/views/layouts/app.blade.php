<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'PT Angkasa Pura Aviasi') }}</title>

    <!-- Fonts & Icons (FontAwesome for Icons) -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-100">
    <div class="min-h-screen flex">
        
        <!-- SIDEBAR UTAMA (BIRU TUA / SLATE 800) -->
        <aside class="w-64 bg-slate-800 text-white flex flex-col justify-between p-4 min-h-screen">
            <div>
                <!-- LOGO & HEADER KNO -->
                <div class="flex items-center gap-3 px-2 py-4 mb-4 border-b border-slate-700">
                    <div class="bg-blue-600 text-white font-bold p-2.5 rounded-lg text-sm tracking-wider">KNO</div>
                    <div>
                        <h1 class="font-bold text-sm leading-tight">PT Angkasa Pura Aviasi</h1>
                        <p class="text-xs text-slate-400">Bandara Kualanamu</p>
                    </div>
                </div>

                <!-- NAVIGATION MENUS -->
                <nav class="space-y-1">
                    
                    <!-- MENU: BERANDA -->
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2 text-sm rounded-lg hover:bg-slate-700 transition font-medium">
                        <i class="fas fa-home w-5 text-center"></i>
                        <span>Beranda</span>
                    </a>

                    @if (Auth::user()?->role === 'DOSEN' || Auth::user()?->role === 'MENTOR')
                        <a href="{{ route('pembimbingan.index') }}" class="flex items-center gap-3 px-3 py-2 text-sm rounded-lg hover:bg-slate-700 transition font-medium">
                            <i class="fas fa-clipboard-check w-5 text-center"></i>
                            <span>Pembimbingan</span>
                        </a>
                    @endif

                    @if (Auth::user()?->role === 'ADMIN')
                        <!-- GRUP 1: MASTER DATA -->
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

                        <!-- GRUP 2: KELOMPOK PKL -->
                        <div class="px-3 pt-5 pb-1 text-xs font-semibold text-slate-400 uppercase tracking-wider">
                            Kelompok PKL
                        </div>

                        <a href="{{ route('kelompok.index') }}" class="flex items-center gap-3 px-3 py-2 text-sm rounded-lg hover:bg-slate-700 transition">
                            <i class="fas fa-users w-5 text-center"></i>
                            <span>Data Kelompok</span>
                        </a>

                        <a href="{{ route('mahasiswa.index') }}" class="flex items-center gap-3 px-3 py-2 text-sm rounded-lg hover:bg-slate-700 transition">
                            <i class="fas fa-user-graduate w-5 text-center"></i>
                            <span>Data Mahasiswa</span>
                        </a>
                    @endif

                </nav>
            </div>

            <!-- FOOTER SIDEBAR -->
            <div class="px-2 py-4 border-t border-slate-700 text-xs text-slate-400">
                <p class="font-semibold text-white">KNO</p>
                <p>Mendampingi untuk Masa Depan</p>
            </div>
        </aside>

        <!-- AREA UTAMA Halaman (HEADER & ISI KONTEN) -->
        <div class="flex-1 flex flex-col min-w-0">
            
            <!-- TOPBAR HEADER -->
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

                <!-- PROFILE & LOGOUT -->
                <div class="flex items-center gap-4">
                    <span class="text-sm font-medium text-gray-700">
                        {{ Auth::user()->name ?? 'Admin Kualanamu' }}
                    </span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-sm text-red-600 hover:text-red-800 font-medium flex items-center gap-1.5 bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded-lg transition">
                            <i class="fas fa-sign-out-alt"></i> Keluar
                        </button>
                    </form>
                </div>
            </header>

            <!-- MAIN CONTENT SLOT -->
            <main class="p-6 flex-1">
                {{ $slot }}
            </main>

        </div>
    </div>
</body>
</html>