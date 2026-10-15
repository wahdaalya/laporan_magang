<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>InternTrack | Dashboard Admin</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
:root{--bg:#EEF2F7;--card:#fff;--ink:#1B2E4F;--muted:#5F7390;--pri:#3F628C;--pri2:#5A80AB;--line:#DDE5F0;--soft:#EEF2F7;--shadow:0 6px 18px rgba(30,60,110,.10)}
:root.dark{--bg:#0F1A2B;--card:#16253B;--ink:#E6EEF9;--muted:#9DB0C9;--line:#26395A;--soft:#1D2F4A}
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Inter',system-ui,sans-serif;background:var(--bg);color:var(--ink);display:flex;min-height:100vh}
a{color:inherit;text-decoration:none}
svg.i{width:20px;height:20px;stroke:currentColor;fill:none;stroke-width:1.8;stroke-linecap:round;stroke-linejoin:round;flex:none}
/* sidebar */
.side{width:300px;flex:none;background:var(--card);border-radius:0 32px 32px 0;box-shadow:0 20px 50px rgba(30,60,110,.12);padding:28px 18px;position:sticky;top:0;height:100vh;overflow-y:auto}
.brand{display:flex;align-items:center;gap:14px;padding:0 6px 24px;border-bottom:1px solid var(--line)}
.logo{width:54px;height:54px;border-radius:12px;background:var(--pri);color:#fff;font-weight:700;font-size:18px;display:flex;align-items:center;justify-content:center;flex:none}
.brand b{display:block;font-size:16px;font-weight:600;line-height:1.25}
.brand span{font-size:13px;color:var(--muted)}
.grp{font-size:12px;font-weight:600;letter-spacing:1px;color:var(--muted);text-transform:uppercase;margin:26px 10px 8px}
.nav a{display:flex;align-items:center;gap:14px;padding:13px 14px;border-radius:14px;font-size:15px;font-weight:500;color:var(--muted);margin-bottom:4px}
.nav a:hover{background:var(--soft);color:var(--ink)}
.nav a.on{background:linear-gradient(90deg,var(--pri2),#3F668F);color:#fff;box-shadow:0 6px 14px rgba(63,98,140,.3)}
/* main */
.main{flex:1;min-width:0;padding:28px 40px 40px}
.top{display:flex;align-items:center;justify-content:space-between;margin-bottom:30px}
.top h1{font-size:26px;font-weight:700}
.tools{display:flex;align-items:center;gap:14px}
.ibtn{width:48px;height:48px;border-radius:14px;border:1px solid var(--line);background:var(--card);color:var(--ink);cursor:pointer;display:flex;align-items:center;justify-content:center;box-shadow:var(--shadow)}
.user{display:flex;align-items:center;gap:12px;background:var(--card);padding:6px 16px 6px 6px;border-radius:999px;box-shadow:var(--shadow);font-weight:500;font-size:15px}
.av{width:40px;height:40px;border-radius:50%;background:var(--pri);color:#fff;font-weight:700;display:flex;align-items:center;justify-content:center}
.hero{display:flex;justify-content:space-between;align-items:flex-end;background:linear-gradient(180deg,#E9EFF7 0%,#A9BFDA 55%,#6F93BC 100%);border-radius:32px;padding:32px 36px;box-shadow:0 20px 50px rgba(30,60,110,.12)}
:root.dark .hero{background:linear-gradient(180deg,#22395A,#2F4F7C)}
.hero small{font-size:12px;font-weight:700;letter-spacing:2px;color:var(--pri);text-transform:uppercase}
:root.dark .hero small{color:#B8CCE6}
.hero h2{font-size:30px;font-weight:700;margin:8px 0 6px;color:#1B2E4F}
:root.dark .hero h2{color:#fff}
.hero p{font-size:16px;color:#3F628C}
:root.dark .hero p{color:#D3E0F2}
.date{display:flex;align-items:center;gap:10px;background:#fff;color:#1B2E4F;padding:12px 18px;border-radius:14px;font-weight:500;box-shadow:var(--shadow)}
.date svg{stroke:var(--pri)}
.stats{display:grid;grid-template-columns:repeat(4,1fr);gap:20px;margin-top:24px}
.stat{background:var(--card);border-radius:20px;padding:22px 24px;box-shadow:var(--shadow);border:1px solid var(--line)}
.stat .row{display:flex;justify-content:space-between;align-items:flex-start;gap:10px}
.stat .t{font-size:15px;color:var(--muted);font-weight:500}
.sico{width:48px;height:48px;border-radius:14px;background:var(--soft);color:var(--pri);display:flex;align-items:center;justify-content:center}
.stat .n{font-size:38px;font-weight:700;margin:8px 0 2px;color:var(--ink)}
.stat .d{font-size:14px;color:var(--muted)}
.grid{display:grid;grid-template-columns:2fr 1fr;gap:20px;margin-top:24px}
.panel{background:var(--card);border-radius:24px;box-shadow:var(--shadow);border:1px solid var(--line);overflow:hidden}
.ph{display:flex;justify-content:space-between;align-items:flex-start;padding:26px 30px;border-bottom:1px solid var(--line)}
.ph h3{font-size:20px;font-weight:600}
.ph p{font-size:14px;color:var(--muted);margin-top:4px}
.link{display:inline-flex;align-items:center;gap:8px;font-weight:600;font-size:15px;color:#fff;background:linear-gradient(90deg,var(--pri2),#3F668F);padding:10px 18px;border-radius:12px}
.empty{padding:60px 30px;text-align:center;color:var(--muted)}
.quick{padding:26px 28px}
.quick small{font-size:12px;font-weight:600;letter-spacing:1px;color:var(--muted);text-transform:uppercase}
.quick h3{font-size:20px;font-weight:600;margin:6px 0 14px}
.quick a{display:flex;align-items:center;gap:14px;padding:14px 12px;border-radius:14px;color:var(--ink);font-size:15px;font-weight:500}
.quick a:hover{background:var(--soft)}
.quick a .i:first-child{color:var(--pri)}
.quick a .i:last-child{margin-left:auto;color:var(--muted);width:18px;height:18px}
@media(max-width:1100px){.stats{grid-template-columns:repeat(2,1fr)}.grid{grid-template-columns:1fr}}
@media(max-width:800px){.side{display:none}.main{padding:20px 16px}.hero{flex-direction:column;align-items:flex-start;gap:16px}.stats{grid-template-columns:1fr}}
</style>
</head>
<body>
<aside class="side">
  <div class="brand">
    <div class="logo">KNO</div>
    <div><b>PT Angkasa Pura Aviasi</b><span>Bandara Kualanamu</span></div>
  </div>
  <nav class="nav">
    <a href="{{ url('/admin') }}" class="on" style="margin-top:22px"><svg class="i" viewBox="0 0 24 24"><path d="M3 11 12 3l9 8v10h-6v-6H9v6H3z"/></svg>Beranda</a>
    <div class="grp">Master Data</div>
    <a href="{{ url('/admin/kampus') }}"><svg class="i" viewBox="0 0 24 24"><path d="M3 10 12 4l9 6M5 10v8M9 10v8M15 10v8M19 10v8M3 21h18"/></svg>Data Instansi</a>
    <a href="{{ url('/admin/dosen') }}"><svg class="i" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="12" rx="2"/><path d="M8 20h8M12 16v4"/></svg>Pembimbing Sekolah/Kampus</a>
    <a href="{{ url('/admin/pembimbing') }}"><svg class="i" viewBox="0 0 24 24"><circle cx="12" cy="8" r="4"/><path d="M4 21c0-4.4 3.6-7 8-7s8 2.6 8 7"/></svg>Pembimbing Lapangan</a>
    <a href="{{ url('/admin/departemen') }}"><svg class="i" viewBox="0 0 24 24"><rect x="5" y="3" width="14" height="18" rx="2"/><path d="M9 7h2M13 7h2M9 11h2M13 11h2M10 21v-4h4v4"/></svg>Data Departemen</a>
    <div class="grp">Kelompok PKL</div>
    <a href="{{ url('/admin/kelompok') }}"><svg class="i" viewBox="0 0 24 24"><circle cx="9" cy="8" r="3.5"/><circle cx="17" cy="9" r="2.5"/><path d="M2 20c0-3.6 3-6 7-6s7 2.4 7 6M16 14c3 0 6 1.8 6 5"/></svg>Data Kelompok</a>
    <a href="{{ url('/admin/peserta') }}"><svg class="i" viewBox="0 0 24 24"><path d="m2 9 10-5 10 5-10 5z"/><path d="M6 11v5c0 1.5 2.7 3 6 3s6-1.5 6-3v-5"/></svg>Data Peserta Magang / PKL</a>
    <a href="{{ url('/profil') }}"><svg class="i" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><circle cx="12" cy="10" r="3"/><path d="M6.5 18c1.2-2 3.2-3 5.5-3s4.3 1 5.5 3"/></svg>Profil Saya</a>
  </nav>
</aside>

<main class="main">
  <div class="top">
    <h1>Dashboard Admin</h1>
    <div class="tools">
      <button class="ibtn" id="theme" aria-label="Ganti tema"><svg class="i" viewBox="0 0 24 24"><path d="M21 12.8A9 9 0 1 1 11.2 3 7 7 0 0 0 21 12.8z"/></svg></button>
      <div class="user"><div class="av">A</div>{{ auth()->user()->display_name ?? 'Admin Kualanamu' }}<svg class="i" viewBox="0 0 24 24" style="width:16px;height:16px"><path d="m6 9 6 6 6-6"/></svg></div>
    </div>
  </div>

  <section class="hero">
    <div>
      <small>Ringkasan Operasional</small>
      <h2>Selamat datang kembali</h2>
      <p>Pantau aktivitas peserta Magang / PKL hari ini.</p>
    </div>
    <div class="date"><svg class="i" viewBox="0 0 24 24"><rect x="3" y="5" width="18" height="16" rx="2"/><path d="M3 10h18M8 3v4M16 3v4"/></svg>{{ now()->format('d M Y') }}</div>
  </section>

  <section class="stats">
    <div class="stat"><div class="row"><span class="t">Total Peserta</span><div class="sico"><svg class="i" viewBox="0 0 24 24"><path d="m2 9 10-5 10 5-10 5z"/><path d="M6 11v5c0 1.5 2.7 3 6 3s6-1.5 6-3v-5"/></svg></div></div><div class="n">{{ $totalPeserta ?? 0 }}</div><div class="d">Terdaftar dalam sistem</div></div>
    <div class="stat"><div class="row"><span class="t">Pembimbing Sekolah/Kampus</span><div class="sico"><svg class="i" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="12" rx="2"/><path d="M8 20h8M12 16v4"/></svg></div></div><div class="n">{{ $totalDosen ?? 0 }}</div><div class="d">Guru atau dosen pembimbing</div></div>
    <div class="stat"><div class="row"><span class="t">Total Mentor</span><div class="sico"><svg class="i" viewBox="0 0 24 24"><circle cx="12" cy="8" r="4"/><path d="M4 21c0-4.4 3.6-7 8-7s8 2.6 8 7"/></svg></div></div><div class="n">{{ $totalMentor ?? 0 }}</div><div class="d">Pembimbing lapangan</div></div>
    <div class="stat"><div class="row"><span class="t">Laporan Menunggu</span><div class="sico"><svg class="i" viewBox="0 0 24 24"><rect x="5" y="4" width="14" height="17" rx="2"/><path d="M9 4V3h6v1M9 11h6M9 15h6"/></svg></div></div><div class="n">{{ $laporanMenunggu ?? 0 }}</div><div class="d">Belum diperiksa pembimbing</div></div>
  </section>

  <section class="grid">
    <div class="panel">
      <div class="ph">
        <div><h3>Laporan Terbaru</h3><p>Aktivitas logbook peserta yang terakhir masuk.</p></div>
        <a class="link" href="{{ url('/admin/peserta') }}">Data peserta <svg class="i" viewBox="0 0 24 24" style="width:18px;height:18px"><path d="M5 12h14M13 6l6 6-6 6"/></svg></a>
      </div>
      @forelse(($laporanTerbaru ?? []) as $l)
        <div style="padding:16px 30px;border-bottom:1px solid var(--line)">{{ $l->judul ?? $l->kegiatan ?? 'Laporan' }}</div>
      @empty
        <div class="empty">Belum ada laporan.</div>
      @endforelse
    </div>

    <div class="panel quick">
      <small>Navigasi Cepat</small>
      <h3>Kelola Magang / PKL</h3>
      <a href="{{ url('/admin/peserta') }}"><svg class="i" viewBox="0 0 24 24"><path d="m2 9 10-5 10 5-10 5z"/><path d="M6 11v5c0 1.5 2.7 3 6 3s6-1.5 6-3v-5"/></svg>Peserta<svg class="i" viewBox="0 0 24 24"><path d="M14 4h6v6M20 4l-9 9M18 14v5H5V6h5"/></svg></a>
      <a href="{{ url('/admin/kelompok') }}"><svg class="i" viewBox="0 0 24 24"><circle cx="9" cy="8" r="3.5"/><circle cx="17" cy="9" r="2.5"/><path d="M2 20c0-3.6 3-6 7-6s7 2.4 7 6M16 14c3 0 6 1.8 6 5"/></svg>Kelompok<svg class="i" viewBox="0 0 24 24"><path d="M14 4h6v6M20 4l-9 9M18 14v5H5V6h5"/></svg></a>
      <a href="{{ url('/admin/dosen') }}"><svg class="i" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="12" rx="2"/><path d="M8 20h8M12 16v4"/></svg>Pembimbing Sekolah/Kampus<svg class="i" viewBox="0 0 24 24"><path d="M14 4h6v6M20 4l-9 9M18 14v5H5V6h5"/></svg></a>
    </div>
  </section>
</main>

<script>
(function(){
  var r=document.documentElement;
  try{if(localStorage.getItem('kno-theme')==='dark')r.classList.add('dark');}catch(e){}
  document.getElementById('theme').addEventListener('click',function(){
    r.classList.toggle('dark');
    try{localStorage.setItem('kno-theme',r.classList.contains('dark')?'dark':'light');}catch(e){}
  });
})();
</script>
</body>
</html>