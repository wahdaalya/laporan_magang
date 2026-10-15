<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>[x-cloak] { display: none !important; }</style>
    <style>
        /* ===== MODE TERANG ===== */
        html:not(.dark) body {
            background: linear-gradient(180deg, #E9EFF7 0%, #B9CCE4 55%, #94B1D3 100%) fixed !important;
        }
        html:not(.dark) body > div.min-h-screen {
            background: transparent !important;
        }
        html:not(.dark) aside,
        html:not(.dark) header.sticky {
            background: rgba(255, 255, 255, 0.35) !important;
            backdrop-filter: blur(14px);
            border-color: rgba(255, 255, 255, 0.6) !important;
        }
        html:not(.dark) .card-grad {
            background: linear-gradient(135deg, #FFFFFF 0%, #F5F8FC 45%, #E3EBF4 100%);
        }

        /* ===== MODE GELAP ===== */
        html.dark body {
            background: linear-gradient(180deg, #0A1428 0%, #112447 55%, #1A3563 100%) fixed !important;
        }
        html.dark body > div.min-h-screen {
            background: transparent !important;
        }
        html.dark aside,
        html.dark header.sticky {
            background: rgba(10, 20, 40, 0.55) !important;
            backdrop-filter: blur(14px);
            border-color: rgba(148, 177, 211, 0.15) !important;
        }
        html.dark .card-grad {
            background: linear-gradient(135deg, #16294D 0%, #112240 50%, #0D1C36 100%);
            border-color: rgba(148, 177, 211, 0.18) !important;
        }

        /* Kotak kecil di dalam kartu (mode gelap) */
        html.dark [class*="bg-blue-50/40"] {
            background: linear-gradient(135deg, #1D3866 0%, #162C52 100%) !important;
            border-color: rgba(148, 177, 211, 0.28) !important;
        }
        html.dark [class*="bg-amber-50/40"] {
            background: linear-gradient(135deg, #1D3866 0%, #162C52 100%) !important;
            border-color: rgba(242, 183, 5, 0.40) !important;
        }
        html.dark [class*="bg-blue-50/40"] .text-gray-500,
        html.dark [class*="bg-blue-50/40"] .text-gray-600,
        html.dark [class*="bg-amber-50/40"] .text-gray-500,
        html.dark [class*="bg-amber-50/40"] .text-gray-600 {
            color: #B7C7DE !important;
        }
        
        /* Banner sambutan */
        html:not(.dark) .banner-hero {
            background: linear-gradient(90deg, #2F6DB5 0%, #4A86C8 55%, #6B9FD6 100%);
        }
        html.dark .banner-hero {
            background: linear-gradient(90deg, #10244A 0%, #1A3563 55%, #24477F 100%);
        }
    </style>
    <script>
        try {
            if (localStorage.getItem('theme') === 'dark') {
                document.documentElement.classList.add('dark');
            }
        } catch {}
    </script>

    <title>{{ config('app.name', 'PT Angkasa Pura Aviasi') }}</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* Rapikan tabel: judul kolom selalu rata tengah */
        table thead th { white-space: nowrap; vertical-align: middle; }
        table thead th.td-kiri   { text-align: left !important; }
        table thead th.td-tengah { text-align: center !important; }
        table:not(.table-fixed) thead th,
        table:not(.table-fixed) tbody td { padding: .75rem 1rem !important; }
        table td { vertical-align: middle; }
        table td.td-tengah { text-align: center !important; }
        table td.td-kiri { text-align: left !important; }
        table td.td-tengah > .flex { justify-content: center !important; }
        
        /* Tombol aksi berupa ikon */
        table td .aksi-ikon {
            display: inline-flex; align-items: center; justify-content: center;
            width: 2rem; height: 2rem; padding: 0 !important;
            font-size: .8rem; line-height: 1; border-radius: .5rem;
        }
        table td:has(.aksi-ikon) { text-align: center !important; }
        table td:has(.aksi-ikon) > div { display: flex; justify-content: center; align-items: center; gap: .5rem; }
        table td .aksi-ikon[title="Edit"]           { color: #1d4ed8; background: #eff6ff; border: 1px solid #dbeafe; }
        table td .aksi-ikon[title="Reset Password"] { color: #b45309; background: #fffbeb; border: 1px solid #fde68a; }
        table td .aksi-ikon[title="Hapus"]          { color: #dc2626; background: #fef2f2; border: 1px solid #fecaca; }
        table td .aksi-ikon:hover { filter: brightness(.95); }
        html.dark table td .aksi-ikon[title="Edit"]           { color: #93c5fd; background: rgba(59,130,246,.15); border-color: rgba(147,197,253,.3); }
        html.dark table td .aksi-ikon[title="Reset Password"] { color: #fcd34d; background: rgba(245,158,11,.15); border-color: rgba(252,211,77,.3); }
        html.dark table td .aksi-ikon[title="Hapus"]          { color: #fca5a5; background: rgba(239,68,68,.15); border-color: rgba(252,165,165,.3); }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var angka = /^[#\d\s.,:%\/+\-–—()]*$/;
            var tanggal = /^\d{1,2}\s+[A-Za-z]{3,9}\s+\d{4}/;
            var kode = /^[A-Z]{2,}[-\d]+$/;

            function cocokTengah(td) {
                if (td.querySelector('form, button, img, input, select, [class*="rounded-full"]')) return true;
                var t = td.textContent.replace(/\s+/g, ' ').trim();
                return t === '' || angka.test(t) || tanggal.test(t) || kode.test(t);
            }

            document.querySelectorAll('table:not([data-rata="manual"])').forEach(function (tabel) {
                var baris = Array.prototype.filter.call(tabel.querySelectorAll('tbody tr'), function (r) { return r.children.length > 0; });
                var biasa = baris.filter(function (r) {
                    return !Array.prototype.some.call(r.children, function (c) { return c.colSpan > 1; });
                });

                baris.forEach(function (r) {
                    Array.prototype.forEach.call(r.children, function (c) {
                        if (c.colSpan > 1) c.classList.add('td-tengah');
                    });
                });

                var jumlahKolom = biasa.reduce(function (m, r) { return Math.max(m, r.children.length); }, 0);
                for (var i = 0; i < jumlahKolom; i++) {
                    var sel = biasa.map(function (r) { return r.children[i]; }).filter(Boolean);
                    var isi = sel.filter(function (td) { return td.textContent.trim() !== '' || td.querySelector('form, button, img, input, select'); });
                    if (!isi.length) continue;
                    var cocok = isi.filter(cocokTengah).length;
                    var kelas = (cocok / isi.length >= 0.8) ? 'td-tengah' : 'td-kiri';
                    sel.forEach(function (td) {
                        if (!td.classList.contains('td-tengah') && !td.classList.contains('td-kiri')) td.classList.add(kelas);
                    });
                }
            });
        });
    </script>
        <script>
        document.addEventListener('DOMContentLoaded', function () {
            var angka = /^[#\d\s.,:%\/+\-–—()]*$/;
            var tanggal = /^\d{1,2}\s+[A-Za-z]{3,9}\s+\d{4}/;
            var kode = /^[A-Z]{2,}[-\d]+$/;
            var pusat = /^(id|no|nip|nuptk|nidn|nim|nisn|hp|wa|jumlah|aksi|kode|tanggal|periode|status|laporan|foto|kelompok|catatan)(\W|$)/;
            var kiri = /(nama|email|pimpinan|alamat|instansi|departemen|unit|kegiatan|keterangan|pembimbing|jurusan|fakultas|prodi|mentor|dosen)/;

            function cocokTengah(td) {
                if (td.querySelector('form, button, img, input, select, [class*="rounded-full"]')) return true;
                var t = td.textContent.replace(/\s+/g, ' ').trim();
                return t === '' || angka.test(t) || tanggal.test(t) || kode.test(t);
            }

            document.querySelectorAll('table:not([data-rata="manual"])').forEach(function (tabel) {
                var baris = Array.prototype.filter.call(tabel.querySelectorAll('tbody tr'), function (r) { return r.children.length > 0; });
                var biasa = baris.filter(function (r) {
                    return !Array.prototype.some.call(r.children, function (c) { return c.colSpan > 1; });
                });
                baris.forEach(function (r) {
                    Array.prototype.forEach.call(r.children, function (c) { if (c.colSpan > 1) c.classList.add('td-tengah'); });
                });

                var headRows = tabel.querySelectorAll('thead tr');
                var ths = headRows.length ? Array.prototype.slice.call(headRows[headRows.length - 1].children) : [];
                var jumlahKolom = biasa.reduce(function (m, r) { return Math.max(m, r.children.length); }, 0);
                var thCocok = ths.length === jumlahKolom;

                for (var i = 0; i < jumlahKolom; i++) {
                    var sel = biasa.map(function (r) { return r.children[i]; }).filter(Boolean);
                    var kelas = null;

                    if (thCocok) {
                        var h = ths[i].textContent.toLowerCase().replace(/\s+/g, ' ').trim();
                        if (pusat.test(h)) kelas = 'td-tengah';
                        else if (kiri.test(h)) kelas = 'td-kiri';
                    }
                    if (!kelas) {
                        var isi = sel.filter(function (td) { return td.textContent.trim() !== '' || td.querySelector('form, button, img, input, select'); });
                        if (!isi.length) continue;
                        kelas = (isi.filter(cocokTengah).length / isi.length >= 0.8) ? 'td-tengah' : 'td-kiri';
                    }

                    sel.forEach(function (td) {
                        if (!td.classList.contains('td-tengah') && !td.classList.contains('td-kiri')) td.classList.add(kelas);
                    });
                                        if (thCocok) ths[i].classList.add(kelas);
                }
            });
        });
    </script>
    </script>
        <script>
        document.addEventListener('DOMContentLoaded', function () {
            var aturan = [
                { cocok: /^(ubah|edit)(\s|$)/i, ikon: 'fa-pen', judul: 'Edit' },
                { cocok: /^reset\s*password/i, ikon: 'fa-key', judul: 'Reset Password' },
                { cocok: /^hapus(\s|$)/i, ikon: 'fa-trash', judul: 'Hapus' }
            ];

            document.querySelectorAll('table tbody td').forEach(function (td) {
                td.querySelectorAll('a, button').forEach(function (el) {
                    var teks = el.textContent.replace(/\s+/g, ' ').trim();
                    var cocok = aturan.filter(function (r) { return r.cocok.test(teks); })[0];
                    if (!cocok) return;
                    el.setAttribute('title', cocok.judul);
                    if (!el.getAttribute('aria-label')) el.setAttribute('aria-label', cocok.judul);
                    el.innerHTML = '<i class="fas ' + cocok.ikon + '" aria-hidden="true"></i>';
                    el.classList.add('aksi-ikon');
                });
            });
        });
    </script>

    <style>
    /* Scrollbar kapsul */
::-webkit-scrollbar { width: 5px; height: 5px; }
::-webkit-scrollbar-track { background: transparent; }
::-webkit-scrollbar-corner { background: transparent; }
::-webkit-scrollbar-thumb:vertical {
    min-height: 52px;
    border-top: 14px solid transparent;
    border-bottom: 14px solid transparent;
    background-clip: padding-box;
    background-repeat: no-repeat;
    background-position: top, bottom, center;
    background-size: 5px 3px, 5px 3px, 5px calc(100% - 4px);
    background-image:
        radial-gradient(circle at 50% 2.5px, #bdbdbd 2.5px, transparent 2.9px),
        radial-gradient(circle at 50% .5px, #bdbdbd 2.5px, transparent 2.9px),
        linear-gradient(#bdbdbd, #bdbdbd);
}
::-webkit-scrollbar-thumb:horizontal { min-width: 52px; background: #bdbdbd; border-radius: 10px; }
html.dark ::-webkit-scrollbar-thumb:vertical {
    background-image:
        radial-gradient(circle at 50% 2.5px, #4b5563 2.5px, transparent 2.9px),
        radial-gradient(circle at 50% .5px, #4b5563 2.5px, transparent 2.9px),
        linear-gradient(#4b5563, #4b5563);
}
html.dark ::-webkit-scrollbar-thumb:horizontal { background: #4b5563; }
    </style>
    <style>
        /* ===== Sidebar responsif ===== */
        #sidebar-overlay { display: none; position: fixed; inset: 0; z-index: 40; background: rgba(15, 23, 42, .45); }
        #sidebar-overlay.show { display: block; }

        .sidebar {
            position: fixed; top: 0; bottom: 0; left: -100%; z-index: 50;
            display: flex; flex-direction: column; justify-content: space-between;
            width: 250px; max-width: 85vw; height: 100vh;
            overflow-x: hidden; overflow-y: auto;
            transition: left .25s ease, width .2s ease;
        }
        .sidebar.open { left: 0; }

        @media (max-width: 768px) {
            html.dark aside.sidebar { background: #1e293b !important; backdrop-filter: none !important; }
        }

        @media (min-width: 769px) {
            #app-shell { display: flex; }
            #sidebar-overlay { display: none !important; }
            .sidebar { position: sticky; left: 0; z-index: auto; width: 16rem; max-width: none; flex-shrink: 0; }
            .sidebar.collapsed { width: 4.5rem; }
            .sidebar.collapsed .min-w-0,
            .sidebar.collapsed nav a span,
            .sidebar.collapsed nav > div,
            .sidebar.collapsed > div:last-child { display: none; }
            .sidebar.collapsed nav { padding-left: .5rem; padding-right: .5rem; }
            .sidebar.collapsed nav a { justify-content: center; padding-left: 0; padding-right: 0; }
            .sidebar.collapsed > div:first-child > div:first-child { justify-content: center; padding-left: 0; padding-right: 0; }
        }
    </style>
    <style>
        /* ===== Warna sidebar (mode terang) ===== */
        html:not(.dark) aside.sidebar {
            background: linear-gradient(180deg, #D8E3F1 0%, #CCD9EB 35%, #BCCEE4 100%) !important;
            border-color: rgba(255, 255, 255, .65) !important;
            backdrop-filter: none !important;
        }
        html:not(.dark) aside.sidebar nav a { color: #1e293b; }
        html:not(.dark) aside.sidebar nav a:hover { background: rgba(255, 255, 255, .55); }
        html:not(.dark) aside.sidebar nav a[aria-current="page"] { background: rgba(255, 255, 255, .85); color: #1d4ed8; }
        html:not(.dark) aside.sidebar nav > div { color: #475569; }
        html:not(.dark) aside.sidebar h1 { color: #0f172a; }
        html:not(.dark) aside.sidebar p { color: #475569; }
        html:not(.dark) aside.sidebar > div:last-child p.font-semibold { color: #1d4ed8; }
        html:not(.dark) aside.sidebar .border-slate-100 { border-color: rgba(255, 255, 255, .7); }
    </style>
</head>
<body class="font-sans antialiased bg-slate-50 text-slate-900">
    <div class="min-h-screen bg-slate-50 text-slate-900 dark:bg-slate-900 dark:text-slate-100 lg:flex" id="app-shell" x-data="{
        sidebarOpen: false,
        darkMode: document.documentElement.classList.contains('dark'),
        toggleDarkMode() {
            this.darkMode = !this.darkMode;
            document.documentElement.classList.toggle('dark', this.darkMode);

            try {
                localStorage.setItem('theme', this.darkMode ? 'dark' : 'light');
            } catch {}
        },
    }" x-on:keydown.escape.window="sidebarOpen = false">

        <div id="sidebar-overlay"></div>

        <aside id="sidebar" class="sidebar border-r border-slate-200 bg-white text-slate-800 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100">
            <div>
                <div class="flex items-center gap-3 border-b border-slate-100 px-5 py-5 dark:border-slate-700">
                    <img src="{{ asset('images/logo_bandara.png') }}" alt="Logo Bandara Kualanamu" class="h-11 w-11 shrink-0 rounded-lg bg-white object-contain">
                    <div class="min-w-0">
                        <h1 class="text-sm font-bold leading-tight text-slate-900 dark:text-slate-100">PT Angkasa Pura Aviasi</h1>
                        <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Bandara Kualanamu</p>
                    </div>
                </div>

                <nav class="space-y-1.5 px-3 py-5">
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
                        'flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors hover:bg-slate-100 dark:hover:bg-slate-700',
                        'bg-blue-50 text-blue-700 dark:bg-slate-700 dark:text-blue-200' => $berandaAktif,
                        'text-slate-600 dark:text-slate-300' => ! $berandaAktif,
                    ]) @if ($berandaAktif) aria-current="page" @endif>
                        <i class="fas fa-home w-5 text-center"></i>
                        <span>Beranda</span>
                    </a>

                    @if (Auth::user()?->role === 'MAHASISWA')
                        <a href="{{ route('mahasiswa.laporan.index') }}"
                            @class([
                                'flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors hover:bg-slate-100 dark:hover:bg-slate-700',
                                'bg-blue-50 text-blue-700 dark:bg-slate-700 dark:text-blue-200' => request()->routeIs('mahasiswa.laporan.index'),
                                'text-slate-600 dark:text-slate-300' => ! request()->routeIs('mahasiswa.laporan.index'),
                            ])
                            @if (request()->routeIs('mahasiswa.laporan.index')) aria-current="page" @endif
                        >
                            <i class="fas fa-book-open w-5 text-center" aria-hidden="true"></i>
                            <span>Riwayat Laporan</span>
                        </a>
                        <a href="{{ route('mahasiswa.laporan-akhir.index') }}" @class([
                            'flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors hover:bg-slate-100 dark:hover:bg-slate-700',
                            'bg-blue-50 text-blue-700 dark:bg-slate-700 dark:text-blue-200' => request()->routeIs('mahasiswa.laporan-akhir.*'),
                            'text-slate-600 dark:text-slate-300' => ! request()->routeIs('mahasiswa.laporan-akhir.*'),
                        ])>
                            <i class="fas fa-file-upload w-5 text-center" aria-hidden="true"></i>
                            <span>Laporan Akhir</span>
                        </a>
                        <a href="{{ route('mahasiswa.absensi.index') }}" class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-slate-600 transition-colors hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-700">
                            <i class="fas fa-file-pdf w-5 text-center"></i>
                            <span>Cetak Absensi</span>
                        </a>
                        <a href="{{ route('mahasiswa.penilaian.index') }}" class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-slate-600 transition-colors hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-700">
                            <i class="fas fa-star w-5 text-center"></i>
                            <span>Penilaian Saya</span>
                        </a>
                    @endif

                    @if (Auth::user()?->role === 'DOSEN')
                        <a href="{{ route('dosen.logbook.index') }}" @class([
                                'flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors hover:bg-slate-100 dark:hover:bg-slate-700',
                                'bg-blue-50 text-blue-700 dark:bg-slate-700 dark:text-blue-200' => request()->routeIs('dosen.logbook.*'),
                                'text-slate-600 dark:text-slate-300' => ! request()->routeIs('dosen.logbook.*'),
                        ])>
                            <i class="fas fa-book w-5 text-center" aria-hidden="true"></i>
                            <span>Logbook Mahasiswa</span>
                        </a>
                        <a href="{{ route('dosen.laporan-akhir.index') }}" @class([
                                'flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors hover:bg-slate-100 dark:hover:bg-slate-700',
                                'bg-blue-50 text-blue-700 dark:bg-slate-700 dark:text-blue-200' => request()->routeIs('dosen.laporan-akhir.*'),
                                'text-slate-600 dark:text-slate-300' => ! request()->routeIs('dosen.laporan-akhir.*'),
                        ])>
                            <i class="fas fa-file-signature w-5 text-center" aria-hidden="true"></i>
                            <span>Bimbingan Laporan</span>
                        </a>
                        <a href="{{ route('dosen.penilaian-akhir.index') }}" @class([
                                'flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors hover:bg-slate-100 dark:hover:bg-slate-700',
                                'bg-blue-50 text-blue-700 dark:bg-slate-700 dark:text-blue-200' => request()->routeIs('dosen.penilaian-akhir.*'),
                                'text-slate-600 dark:text-slate-300' => ! request()->routeIs('dosen.penilaian-akhir.*'),
                        ])>
                            <i class="fas fa-graduation-cap w-5 text-center" aria-hidden="true"></i>
                            <span>Penilaian Akhir</span>
                        </a>
                    @endif

                    @if (Auth::user()?->role === 'MENTOR')
                        <a href="{{ route('pembimbingan.laporan.index') }}" @class([
                                'flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors hover:bg-slate-100 dark:hover:bg-slate-700',
                                'bg-blue-50 text-blue-700 dark:bg-slate-700 dark:text-blue-200' => request()->routeIs('pembimbingan.laporan.*'),
                                'text-slate-600 dark:text-slate-300' => ! request()->routeIs('pembimbingan.laporan.*'),
                        ])>
                            <i class="fas fa-file-signature w-5 text-center" aria-hidden="true"></i>
                            <span>Review Laporan</span>
                        </a>
                        <a href="{{ route('pembimbing.peserta') }}" @class([
                                'flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors hover:bg-slate-100 dark:hover:bg-slate-700',
                                'bg-blue-50 text-blue-700 dark:bg-slate-700 dark:text-blue-200' => request()->routeIs('pembimbing.peserta', 'pembimbingan.peserta'),
                                'text-slate-600 dark:text-slate-300' => ! request()->routeIs('pembimbing.peserta', 'pembimbingan.peserta'),
                        ])>
                            <i class="fas fa-users w-5 text-center" aria-hidden="true"></i>
                            <span>Peserta &amp; Penilaian</span>
                        </a>
                    @endif

                    @if (Auth::user()?->role === 'ADMIN')
                        <div class="px-3 pb-1 pt-5 text-[11px] font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">
                            Master Data
                        </div>

                        <a href="{{ route('kampus.index') }}" @class(['flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors hover:bg-slate-100 dark:hover:bg-slate-700', 'bg-blue-50 text-blue-700 dark:bg-slate-700 dark:text-blue-200' => request()->routeIs('kampus.*'), 'text-slate-600 dark:text-slate-300' => ! request()->routeIs('kampus.*')])>
                            <i class="fas fa-university w-5 text-center"></i>
                            <span>Data Instansi</span>
                        </a>

                                                <a href="{{ route('dosen.index') }}" @class(['flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors hover:bg-slate-100 dark:hover:bg-slate-700', 'bg-blue-50 text-blue-700 dark:bg-slate-700 dark:text-blue-200' => request()->routeIs('dosen.index', 'dosen.create', 'dosen.edit'), 'text-slate-600 dark:text-slate-300' => ! request()->routeIs('dosen.index', 'dosen.create', 'dosen.edit')])>
                            <i class="fas fa-chalkboard-teacher w-5 text-center"></i>
                            <span>Pembimbing Sekolah/Kampus</span>
                        </a>

                        <a href="{{ route('departemen.index') }}" @class(['flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors hover:bg-slate-100 dark:hover:bg-slate-700', 'bg-blue-50 text-blue-700 dark:bg-slate-700 dark:text-blue-200' => request()->routeIs('departemen.*'), 'text-slate-600 dark:text-slate-300' => ! request()->routeIs('departemen.*')])>
                            <i class="fas fa-building w-5 text-center"></i>
                            <span>Data Departemen</span>
                        </a>

                        <a href="{{ route('pembimbing.index') }}" @class(['flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors hover:bg-slate-100 dark:hover:bg-slate-700', 'bg-blue-50 text-blue-700 dark:bg-slate-700 dark:text-blue-200' => request()->routeIs('pembimbing.*'), 'text-slate-600 dark:text-slate-300' => ! request()->routeIs('pembimbing.*')])>
                            <i class="fas fa-user-tie w-5 text-center"></i>
                            <span>Pembimbing Lapangan</span>
                        </a>

                        <div class="px-3 pb-1 pt-5 text-[11px] font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">
                            Kelompok PKL
                        </div>

                        <a href="{{ route('kelompok.index') }}" @class(['flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors hover:bg-slate-100 dark:hover:bg-slate-700', 'bg-blue-50 text-blue-700 dark:bg-slate-700 dark:text-blue-200' => request()->routeIs('kelompok.*'), 'text-slate-600 dark:text-slate-300' => ! request()->routeIs('kelompok.*')])>
                            <i class="fas fa-users w-5 text-center"></i>
                            <span>Data Kelompok</span>
                        </a>

                        <a href="{{ route('mahasiswa.index') }}" @class(['flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors hover:bg-slate-100 dark:hover:bg-slate-700', 'bg-blue-50 text-blue-700 dark:bg-slate-700 dark:text-blue-200' => request()->routeIs('mahasiswa.index'), 'text-slate-600 dark:text-slate-300' => ! request()->routeIs('mahasiswa.index')])>
                            <i class="fas fa-user-graduate w-5 text-center"></i>
                            <span>Data Peserta Magang / PKL</span>
                        </a>
                    @endif

                    <a href="{{ route('profile.show') }}" @class([
                        'flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors hover:bg-slate-100 dark:hover:bg-slate-700',
                        'bg-blue-50 text-blue-700 dark:bg-slate-700 dark:text-blue-200' => request()->routeIs('profile.show'),
                        'text-slate-600 dark:text-slate-300' => ! request()->routeIs('profile.show'),
                    ]) @if (request()->routeIs('profile.show')) aria-current="page" @endif>
                        <i class="fas fa-user-circle w-5 text-center" aria-hidden="true"></i>
                        <span>Profil Saya</span>
                    </a>

                    @if (in_array(Auth::user()?->role, ['MAHASISWA', 'DOSEN', 'MENTOR'], true))
                        <a href="{{ route('profile.edit') }}" @class([
                            'flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors hover:bg-slate-100 dark:hover:bg-slate-700',
                            'bg-blue-50 text-blue-700 dark:bg-slate-700 dark:text-blue-200' => request()->routeIs('profile.edit'),
                            'text-slate-600 dark:text-slate-300' => ! request()->routeIs('profile.edit'),
                        ]) @if (request()->routeIs('profile.edit')) aria-current="page" @endif>
                            <i class="fas fa-user-edit w-5 text-center" aria-hidden="true"></i>
                            <span>Edit Profil</span>
                        </a>
                    @endif

                </nav>
            </div>

            <div class="border-t border-slate-100 px-5 py-4 text-xs text-slate-500 dark:border-slate-700 dark:text-slate-400">
                <p class="font-semibold text-blue-700">KNO</p>
                <p class="mt-1">Mendampingi untuk Masa Depan</p>
            </div>
        </aside>

        <div class="flex min-h-screen min-w-0 flex-1 flex-col">

            <header class="sticky top-0 z-30 flex min-h-[72px] items-center justify-between border-b border-slate-200 bg-white/95 px-4 backdrop-blur dark:border-slate-700 dark:bg-slate-800/95 sm:px-6 lg:px-8">
                <div class="flex min-w-0 items-center gap-3">
                    <button type="button" id="sidebar-toggle" aria-label="Buka/tutup menu" class="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-lg text-slate-600 transition hover:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:text-slate-300 dark:hover:bg-slate-700">
                        <i class="fas fa-bars" aria-hidden="true"></i>
                    </button>
                    @isset($header)
                        {{ $header }}
                    @else
                        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                            Dashboard Admin
                        </h2>
                    @endisset
                </div>

                <div class="flex items-center gap-2 sm:gap-3">
                    <button
                        type="button"
                        x-on:click="toggleDarkMode()"
                        x-bind:aria-label="darkMode ? 'Aktifkan mode terang' : 'Aktifkan mode gelap'"
                        x-bind:aria-pressed="darkMode.toString()"
                        x-bind:title="darkMode ? 'Aktifkan mode terang' : 'Aktifkan mode gelap'"
                        class="inline-flex h-10 w-10 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-600 transition hover:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:border-slate-600 dark:bg-slate-700 dark:text-amber-300 dark:hover:bg-slate-600"
                    >
                        <svg x-cloak x-show="!darkMode" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M20.9 13A9 9 0 0 1 11 3.1 9 9 0 1 0 20.9 13Z" />
                        </svg>
                        <svg x-cloak x-show="darkMode" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <circle cx="12" cy="12" r="4" />
                            <path d="M12 2v2m0 16v2M4.93 4.93l1.42 1.42m11.3 11.3 1.42 1.42M2 12h2m16 0h2M4.93 19.07l1.42-1.42m11.3-11.3 1.42-1.42" />
                        </svg>
                    </button>
                    @if ($profileSummary)
                        <div x-data="{ open: false }" x-on:click.outside="open = false" x-on:keydown.escape.window="open = false" class="relative">
                            <button type="button" x-on:click="open = !open" x-bind:aria-expanded="open.toString()" aria-haspopup="dialog" aria-label="Buka detail profil" class="flex items-center gap-2 rounded-full p-1 pr-2 transition hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:hover:bg-slate-700">
                                @if ($profileSummary['photo'])
                                    <img src="{{ $profileSummary['photo'] }}" alt="" class="h-9 w-9 rounded-full border border-gray-200 object-cover">
                                @else
                                    <span class="flex h-9 w-9 items-center justify-center rounded-full bg-blue-700 text-sm font-semibold text-white">{{ $profileSummary['initial'] }}</span>
                                @endif
                                <span class="hidden max-w-40 truncate text-sm font-medium text-gray-700 dark:text-slate-200 sm:block">{{ $profileSummary['name'] }}</span>
                                <i class="fas fa-chevron-down text-[10px] text-gray-500 dark:text-slate-400" aria-hidden="true"></i>
                            </button>

                            <section x-cloak x-show="open" x-transition.origin.top.right class="absolute right-0 top-full z-50 mt-3 w-80 max-w-[calc(100vw-2rem)] overflow-hidden rounded-xl border border-gray-200 bg-white text-gray-900 shadow-xl dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100" role="dialog" aria-label="Detail profil pengguna">
                                <div class="flex items-center gap-3 border-b border-gray-100 bg-gray-50 px-4 py-4 dark:border-slate-700 dark:bg-slate-900">
                                    @if ($profileSummary['photo'])
                                        <img src="{{ $profileSummary['photo'] }}" alt="Foto {{ $profileSummary['name'] }}" class="h-14 w-14 shrink-0 rounded-full border border-white object-cover shadow-sm">
                                    @else
                                        <span class="flex h-14 w-14 shrink-0 items-center justify-center rounded-full bg-blue-700 text-xl font-semibold text-white">{{ $profileSummary['initial'] }}</span>
                                    @endif
                                    <div class="min-w-0">
                                        <h2 class="truncate font-semibold text-gray-900 dark:text-slate-100">{{ $profileSummary['name'] }}</h2>
                                        <span class="mt-1 inline-flex max-w-full rounded-full bg-blue-50 px-2.5 py-1 text-xs font-medium text-blue-800">{{ $profileSummary['role'] }}</span>
                                    </div>
                                </div>

                                <dl class="px-4 py-4 text-sm">
                                    <div>
                                        <dt class="text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-slate-400">Email</dt>
                                        <dd class="mt-0.5 break-all text-gray-800 dark:text-slate-200">
                                            @if ($profileSummary['email'])
                                                <a href="mailto:{{ $profileSummary['email'] }}" class="hover:text-blue-700 hover:underline">{{ $profileSummary['email'] }}</a>
                                            @else
                                                -
                                            @endif
                                        </dd>
                                    </div>
                                </dl>

                                <div class="flex gap-2 border-t border-gray-100 p-3 dark:border-slate-700">
                                    <a href="{{ route('profile.show') }}" class="inline-flex flex-1 items-center justify-center gap-2 rounded-md bg-blue-700 px-3 py-2 text-sm font-semibold text-white hover:bg-blue-800">
                                        <i class="fas fa-id-card" aria-hidden="true"></i> Lihat Profil Lengkap
                                    </a>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-md border border-gray-300 px-3 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50 dark:border-slate-600 dark:text-slate-200 dark:hover:bg-slate-700">
                                            <i class="fas fa-sign-out-alt" aria-hidden="true"></i> Keluar
                                        </button>
                                    </form>
                                </div>
                            </section>
                        </div>
                    @endif
                </div>
            </header>

            <main class="flux-page-enter flex-1 px-4 py-5 sm:px-6 lg:px-8 lg:py-8">
                            @if (session()->has('admin_impersonator_id'))
                    <div class="mb-4 flex flex-wrap items-center justify-between gap-3 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-900">
                        <span>Mode Admin: Anda sedang login sebagai <strong>{{ auth()->user()->name }}</strong>.</span>
                        <form method="POST" action="{{ route('admin.impersonate.leave') }}">
                            @csrf
                            <button type="submit" class="rounded-md bg-blue-700 px-3 py-2 text-sm font-semibold text-white hover:bg-blue-800">
                                Keluar / Kembali ke Akun Admin
                            </button>
                        </form>
                    </div>
                @endif    
            {{ $slot }}
            </main>

        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var sidebar = document.getElementById('sidebar');
            var overlay = document.getElementById('sidebar-overlay');
            var tombol = document.getElementById('sidebar-toggle');
            if (!sidebar || !tombol) return;

            var mobile = window.matchMedia('(max-width: 768px)');

            // tooltip untuk ikon saat sidebar diciutkan
            sidebar.querySelectorAll('nav a').forEach(function (a) {
                var teks = a.textContent.replace(/\s+/g, ' ').trim();
                if (teks && !a.title) a.title = teks;
            });

            // ingat pilihan terakhir (desktop)
            try { if (localStorage.getItem('sidebar-collapsed') === '1') sidebar.classList.add('collapsed'); } catch (e) {}

            function tutupMobile() {
                sidebar.classList.remove('open');
                overlay.classList.remove('show');
            }

            tombol.addEventListener('click', function () {
                if (mobile.matches) {
                    var terbuka = sidebar.classList.toggle('open');
                    overlay.classList.toggle('show', terbuka);
                } else {
                    var ciut = sidebar.classList.toggle('collapsed');
                    try { localStorage.setItem('sidebar-collapsed', ciut ? '1' : '0'); } catch (e) {}
                }
            });

            overlay.addEventListener('click', tutupMobile);
            document.addEventListener('keydown', function (e) { if (e.key === 'Escape') tutupMobile(); });
            mobile.addEventListener('change', tutupMobile);
            sidebar.querySelectorAll('nav a').forEach(function (a) {
                a.addEventListener('click', function () { if (mobile.matches) tutupMobile(); });
            });
        });
    </script>
</body>
</html>