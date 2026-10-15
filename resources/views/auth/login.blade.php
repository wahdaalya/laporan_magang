<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Masuk | Laporan Magang Bandara Kualanamu</title>
<link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
<link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
<link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Inter',system-ui,sans-serif;background:#EEF2F7;color:#1B2E4F;min-height:100vh}
.wrap{max-width:1280px;margin:0 auto;padding:40px 24px;display:grid;grid-template-columns:1fr 1fr;gap:30px;align-items:stretch}
.wrap{zoom:.8}
@media(max-width:900px){.wrap{zoom:1}}
.card{border-radius:32px;box-shadow:0 20px 50px rgba(30,60,110,.12);overflow:hidden;position:relative}
.left{background:#fff;padding:40px;min-height:800px}
.brand{display:flex;align-items:center;gap:16px}
.logo{width:54px;height:54px;border-radius:12px;background:#fff;object-fit:contain;flex:none}
.brand b{display:block;font-size:18px;font-weight:600;color:#1B2E4F}
.brand span{font-size:14px;color:#5F7390}
h1{font-size:26px;font-weight:700;margin:36px 0 22px}
.field{border:1px solid #DDE5F0;border-radius:16px;padding:14px 18px 12px;margin-bottom:20px;background:#fff;position:relative}
.field:focus-within{border:2px solid #C9D8EE;padding:13px 17px 11px;box-shadow:0 0 0 3px rgba(63,98,140,.15)}
.field label{display:flex;align-items:center;gap:8px;font-size:14px;color:#5F7390;margin-bottom:6px}
.field svg{width:16px;height:16px;stroke:#5F7390;fill:none;stroke-width:1.8;stroke-linecap:round;stroke-linejoin:round}
.field input{width:100%;border:0;outline:0;background:#E8F0FE;font:400 17px 'Inter',sans-serif;color:#1B2E4F;padding:6px 0;border-radius:2px}
.eye{position:absolute;right:20px;top:50%;transform:translateY(-50%);background:none;border:0;cursor:pointer;width:28px;height:28px;padding:0}
.eye svg{width:22px;height:22px;stroke:#6B7F99}
.err{color:#B3261E;font-size:13px;margin:-10px 0 14px 4px}
.btn{width:100%;height:60px;border:0;border-radius:14px;margin-top:0;color:#fff;font:700 18px 'Inter',sans-serif;cursor:pointer;background:linear-gradient(90deg,#5A80AB,#3F668F)}
.btn:hover{background:linear-gradient(90deg,#4E73A0,#365C85)}
.illus{position:absolute;left:0;bottom:0;width:100%;height:220px;pointer-events:none}
.right{background:linear-gradient(180deg,#E9EFF7 0%,#A9BFDA 45%,#4F77A3 100%);padding:40px;min-height:800px}
.hero{text-align:center;position:relative;padding-top:6px}
.hero .star{width:84px;height:auto;display:block;margin:0 auto 2px}
.hero .hero-logo{width:120px;height:auto;display:block;margin:0 auto 4px;border-radius:12px}
.hero small{display:block;font-size:10px;font-weight:700;color:#1F4E8C;letter-spacing:.3px}
.hero h2{font-size:30px;font-weight:800;letter-spacing:3px;color:#1B2E4F;margin-top:12px}
.hero p{font-size:18px;color:#3F628C;margin-top:6px}
.hero em{display:block;font-style:normal;font-size:12px;font-weight:700;letter-spacing:2px;color:#3F628C;margin-top:14px;text-transform:uppercase}
.plane{position:absolute;left:0;top:70px;width:42px;opacity:.35;stroke:#fff;fill:none;stroke-width:1.6;stroke-linecap:round;stroke-linejoin:round}
.feat{display:flex;align-items:center;gap:16px;background:#fff;border-radius:16px;padding:18px 20px;box-shadow:0 6px 18px rgba(30,60,110,.10);margin-top:14px}
.feats{margin-top:30px}
.feats .feat:first-child{margin-top:0}
.ico{flex:none;width:44px;height:44px;border-radius:12px;background:#EEF2F7;display:flex;align-items:center;justify-content:center}
.ico svg{width:22px;height:22px;stroke:#3F628C;fill:none;stroke-width:1.8;stroke-linecap:round;stroke-linejoin:round}
.feat b{display:block;font-size:18px;font-weight:500;color:#1B2E4F}
.feat span{font-size:14px;color:#5F7390}
.info{background:#fff;border-radius:16px;padding:24px;margin-top:24px;box-shadow:0 6px 18px rgba(30,60,110,.10)}
.info b{display:block;font-size:18px;font-weight:500;text-transform:uppercase;color:#1B2E4F}
.info span{display:block;font-size:14px;color:#5F7390;margin-top:8px;line-height:1.5}
@media(max-width:900px){.wrap{grid-template-columns:1fr;padding:20px 14px}.left,.right{min-height:0}.left{padding-bottom:240px}}
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
</head>
<body>
<div class="wrap">

  {{-- KARTU KIRI --}}
  <section class="card left">
    <div class="brand">
      <img src="{{ asset('images/logo_bandara.png') }}" alt="Logo Bandara Kualanamu" class="logo">
      <div><b>PT Angkasa Pura Aviasi</b><span>Bandara Internasional Kualanamu</span></div>
    </div>

    <h1>LOGIN</h1>

    <form method="POST" action="{{ route('login') }}">
      @csrf

      <div class="field">
        <label for="email">
          <svg viewBox="0 0 24 24"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/></svg>Email
        </label>
        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username">
      </div>
      @error('email')<div class="err">{{ $message }}</div>@enderror

      <div class="field">
        <label for="password">
          <svg viewBox="0 0 24 24"><rect x="4" y="11" width="16" height="10" rx="2"/><path d="M8 11V7a4 4 0 0 1 8 0v4"/></svg>Password
        </label>
        <input id="password" type="password" name="password" required autocomplete="current-password">
        <button type="button" class="eye" id="eye" aria-label="Tampilkan password">
          <svg viewBox="0 0 24 24"><path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7S2 12 2 12z"/><circle cx="12" cy="12" r="3"/></svg>
        </button>
      </div>
      @error('password')<div class="err">{{ $message }}</div>@enderror

      <button type="submit" class="btn">Masuk</button>
    </form>

    <svg class="illus" viewBox="0 0 625 220" preserveAspectRatio="xMidYMax slice" aria-hidden="true">
      <ellipse cx="130" cy="95" rx="90" ry="14" fill="#EEF2F8"/>
      <ellipse cx="430" cy="80" rx="80" ry="12" fill="#EEF2F8"/>
      <ellipse cx="320" cy="60" rx="70" ry="10" fill="#F3F6FA" opacity=".8"/>
      <rect y="150" width="625" height="22" fill="#B7C7DC"/>
      <rect y="172" width="625" height="48" fill="#A9BDD6"/>
      <rect x="420" y="128" width="205" height="22" fill="#C4D2E4"/>
      <path d="M0 150 Q160 118 330 140 Q480 125 625 150Z" fill="#C9D6E6"/>
      <rect x="528" y="52" width="14" height="110" fill="#8FA8C6"/>
      <rect x="506" y="20" width="58" height="14" rx="3" fill="#3F628C"/>
      <path d="M512 34h46l8 18h-62z" fill="#5F7FA6"/>
      <rect x="500" y="52" width="70" height="8" rx="2" fill="#3F628C"/>
      <rect x="515" y="158" width="40" height="10" fill="#8FA8C6"/>
      <path d="M280 140 L318 62 H350 L335 140Z" fill="#3F628C"/>
      <path d="M305 100h28l-4 22h-28z" fill="#6F8DB3"/>
      <path d="M78 150c0-14 20-22 70-24l250-6c50 0 96 8 118 22 0 8-20 12-60 12H130c-36 0-52-2-52-4z" fill="#fff"/>
      <path d="M96 138h300" stroke="#3F628C" stroke-width="3" fill="none"/>
      <g fill="#7C97BA"><circle cx="150" cy="130" r="2.6"/><circle cx="166" cy="130" r="2.6"/><circle cx="182" cy="130" r="2.6"/><circle cx="198" cy="130" r="2.6"/><circle cx="214" cy="130" r="2.6"/><circle cx="230" cy="130" r="2.6"/><circle cx="246" cy="130" r="2.6"/><circle cx="262" cy="130" r="2.6"/><circle cx="278" cy="130" r="2.6"/><circle cx="294" cy="130" r="2.6"/><circle cx="310" cy="130" r="2.6"/><circle cx="326" cy="130" r="2.6"/><circle cx="342" cy="130" r="2.6"/><circle cx="358" cy="130" r="2.6"/><circle cx="374" cy="130" r="2.6"/></g>
      <path d="M92 124l30-6 6 6-34 4z" fill="#3F628C"/>
      <path d="M200 150l60 24h40l-60-24z" fill="#F4F7FB"/>
      <ellipse cx="300" cy="176" rx="24" ry="8" fill="#fff"/>
      <circle cx="108" cy="168" r="5" fill="#1B2E4F"/><circle cx="332" cy="182" r="5" fill="#1B2E4F"/>
      <rect x="106" y="152" width="4" height="16" fill="#1B2E4F"/>
    </svg>
  </section>

  {{-- KARTU KANAN --}}
  <section class="card right">
    <div class="hero">
      <svg class="plane" viewBox="0 0 24 24"><path d="M17.8 19.2 16 11l3.5-3.5C21 6 21.5 4 21 3c-1-.5-3 0-4.5 1.5L13 8 4.8 6.2c-.5-.1-.9.1-1.1.5l-.3.5c-.2.5-.1 1 .3 1.3L9 12l-2 3H4l-1 1 3 2 2 3 1-1v-3l3-2 3.5 5.3c.3.4.8.5 1.3.3l.5-.2c.4-.3.6-.7.5-1.2z"/></svg>
      <img src="{{ asset('images/logo_bandara.png') }}" alt="Logo Bandara Kualanamu" class="hero-logo">
      <h2>KUALANAMU</h2>
      <p>International Airport</p>
      <em>Operated by Angkasa Pura Aviasi</em>
    </div>

    <div class="feats">
      <div class="feat"><div class="ico"><svg viewBox="0 0 24 24"><rect x="5" y="4" width="14" height="17" rx="2"/><path d="M9 4V3h6v1M9 11h6M9 15h6"/></svg></div>
        <div><b>Logbook Harian Digital</b><span>Catat kegiatan harian magang secara terstruktur &amp; akurat.</span></div></div>
      <div class="feat"><div class="ico"><svg viewBox="0 0 24 24"><rect x="3" y="5" width="18" height="16" rx="2"/><path d="M3 10h18M8 3v4M16 3v4"/><path d="M8 15h2M14 15h2"/></svg></div>
        <div><b>Absensi / Kehadiran</b><span>Rekap dan konfirmasi kehadiran harian peserta magang.</span></div></div>
      <div class="feat"><div class="ico"><svg viewBox="0 0 24 24"><circle cx="12" cy="8" r="4"/><path d="M4 21c0-4.4 3.6-7 8-7s8 2.6 8 7"/></svg></div>
        <div><b>Monitoring Pembimbing</b><span>Verifikasi laporan langsung oleh supervisor unit kerja.</span></div></div>
      <div class="feat"><div class="ico"><svg viewBox="0 0 24 24"><circle cx="12" cy="9" r="6"/><path d="m8.5 14-1.5 8 5-3 5 3-1.5-8"/></svg></div>
        <div><b>Penilaian &amp; Sertifikat Resmi</b><span>Dapatkan rekap nilai akhir dan e-sertifikat magang resmi.</span></div></div>
    </div>

    <div class="info">
      <b>Kualanamu International Airport</b>
      <span>Gerbang modern yang menghubungkan Sumatera Utara dengan berbagai destinasi domestik dan internasional.</span>
    </div>
  </section>
</div>

<script>
document.getElementById('eye').addEventListener('click',function(){
  var p=document.getElementById('password');
  p.type=p.type==='password'?'text':'password';
});
</script>
</body>
</html>