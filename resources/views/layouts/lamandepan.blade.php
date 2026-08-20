<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>E-Learning UNWIR — Next Gen LMS</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            brand: {
              50: '#EEF2FF',
              100: '#E0E7FF',
              500: '#6366F1',
              600: '#4F46E5',
              700: '#4338CA',
              900: '#312E81',
            },
            surface: {
              bg: '#0F172A',
              card: '#1E293B',
              border: '#334155',
            },
            emerald: {
              400: '#34D399',
              500: '#10B981',
            }
          },
          fontFamily: {
            sans: ['Plus Jakarta Sans', 'sans-serif'],
            mono: ['JetBrains Mono', 'monospace'],
          },
        }
      }
    }
  </script>
  <style>
    body {
      background-color: #090D16;
      color: #F8FAFC;
    }

    .grid-pattern {
      background-size: 40px 40px;
      background-image:
        linear-gradient(to right, rgba(255, 255, 255, 0.03) 1px, transparent 1px),
        linear-gradient(to bottom, rgba(255, 255, 255, 0.03) 1px, transparent 1px);
    }

    .glow-effect {
      background: radial-gradient(circle at 50% 0%, rgba(99, 102, 241, 0.18) 0%, transparent 70%);
    }

    .glass-panel {
      background: rgba(30, 41, 59, 0.7);
      backdrop-filter: blur(16px);
      -webkit-backdrop-filter: blur(16px);
      border: 1px solid rgba(255, 255, 255, 0.08);
    }

    .glass-card {
      background: rgba(255, 255, 255, 0.03);
      backdrop-filter: blur(12px);
      border: 1px solid rgba(255, 255, 255, 0.07);
    }

    .glass-card:hover {
      border-color: rgba(99, 102, 241, 0.4);
      background: rgba(255, 255, 255, 0.05);
    }
  </style>
</head>

<body class="font-sans antialiased selection:bg-brand-500 selection:text-white">

  <!-- ============ NAVBAR ============ -->
  <header class="sticky top-0 z-50 glass-panel border-b border-white/10">
    <div class="max-w-7xl mx-auto px-6 lg:px-8 h-20 flex items-center justify-between">
      <a href="/" class="flex items-center gap-3 group">
        <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-brand-600 to-indigo-400 flex items-center justify-center shadow-lg shadow-brand-500/30 group-hover:scale-105 transition-transform">
          <span class="text-white font-bold text-xl tracking-tight">U</span>
        </div>
        <div class="flex flex-col">
          <span class="text-lg font-bold tracking-tight text-white flex items-center gap-2">
            E-Learning UNWIR
            <span class="text-[10px] font-mono font-medium bg-brand-500/20 text-brand-300 border border-brand-500/30 px-2 py-0.5 rounded-full">v2.5</span>
          </span>
          <span class="text-xs text-slate-400 font-mono">Universitas Wiralodra</span>
        </div>
      </a>

      <nav class="hidden md:flex items-center gap-8 text-sm font-medium text-slate-300">
        <a href="#dashboard-preview" class="hover:text-white transition-colors">Workspace</a>
        <a href="#fitur" class="hover:text-white transition-colors">Fitur</a>
        <a href="#kursus" class="hover:text-white transition-colors">Mata Kuliah</a>
        <a href="#alur" class="hover:text-white transition-colors">Alur Akademik</a>
      </nav>

      <div class="flex items-center gap-3">
        @if (Route::has('login'))
        @auth
        <a href="{{ url('/dashboard') }}" class="px-5 py-2.5 bg-brand-600 hover:bg-brand-500 text-white rounded-xl text-sm font-semibold transition-all shadow-lg shadow-brand-600/30 flex items-center gap-2">
          <span>Masuk Dashboard</span>
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
          </svg>
        </a>
        @else
        <a href="{{ route('login') }}" class="px-4 py-2.5 text-slate-300 hover:text-white text-sm font-medium transition-colors">
          Log in
        </a>
        @if (Route::has('register'))
        <a href="{{ route('register') }}" class="px-5 py-2.5 bg-brand-600 hover:bg-brand-500 text-white rounded-xl text-sm font-semibold transition-all shadow-lg shadow-brand-600/25 border border-brand-400/30">
          Registrasi SIAKAD
        </a>
        @endif
        @endauth
        @endif
      </div>
    </div>
  </header>

  <!-- ============ HERO SECTION ============ -->
  <section class="relative pt-20 pb-20 lg:pt-28 lg:pb-32 overflow-hidden grid-pattern">
    <div class="absolute inset-0 glow-effect pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-6 lg:px-8 relative z-10">
      <div class="text-center max-w-3xl mx-auto">
        <div class="inline-flex items-center gap-2 font-mono text-xs font-medium text-emerald-400 bg-emerald-500/10 border border-emerald-500/20 rounded-full px-4 py-2 mb-8 backdrop-blur-md">
          <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
          Sistem Pembelajaran Terintegrasi SIAKAD Real-time
        </div>

        <h1 class="text-4xl sm:text-6xl font-extrabold tracking-tight text-white leading-[1.15]">
          Pusat Pembelajaran Digital <br>
          <span class="bg-clip-text text-transparent bg-gradient-to-r from-brand-300 via-indigo-200 to-emerald-400">Generasi Baru UNWIR</span>
        </h1>

        <p class="text-base sm:text-lg text-slate-400 mt-6 leading-relaxed">
          Akses seluruh modul perkuliahan, kuis interaktif, rekapan presensi, dan penilaian akademik dalam satu platform modern berkecepatan tinggi.
        </p>

        <div class="flex flex-wrap items-center justify-center gap-4 mt-10">
          <a href="{{ route('login') }}" class="px-8 py-4 bg-gradient-to-r from-brand-600 to-indigo-600 hover:from-brand-500 hover:to-indigo-500 text-white font-semibold rounded-2xl transition-all shadow-xl shadow-brand-600/30 border border-indigo-400/30 flex items-center gap-3">
            <span>Masuk Portal Akun SIAKAD</span>
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
            </svg>
          </a>
          <a href="#fitur" class="px-7 py-4 glass-card hover:bg-white/10 text-slate-200 font-semibold rounded-2xl transition-all flex items-center gap-2">
            Eksplorasi Fitur Baru
          </a>
        </div>
      </div>

      <!-- ============ INTERACTIVE DASHBOARD PREVIEW ============ -->
      <div id="dashboard-preview" class="mt-16 lg:mt-24 max-w-5xl mx-auto">
        <div class="glass-panel p-3 lg:p-4 rounded-3xl shadow-2xl border border-white/10 relative">
          <!-- Window Header -->
          <div class="flex items-center justify-between px-4 py-3 border-b border-white/10 mb-4">
            <div class="flex items-center gap-2">
              <span class="w-3 h-3 rounded-full bg-rose-500/80 inline-block"></span>
              <span class="w-3 h-3 rounded-full bg-amber-500/80 inline-block"></span>
              <span class="w-3 h-3 rounded-full bg-emerald-500/80 inline-block"></span>
              <span class="text-xs font-mono text-slate-400 ml-2">elearning.unwir.ac.id/workspace/dashboard</span>
            </div>
            <span class="text-xs font-mono bg-white/5 text-slate-300 px-3 py-1 rounded-md border border-white/10">Semester Genap 2025/2026</span>
          </div>

          <!-- Dashboard Mock Content -->
          <div class="grid lg:grid-cols-3 gap-4 p-2">
            <!-- Main Content Area -->
            <div class="lg:col-span-2 space-y-4">
              <!-- Active Course Banner -->
              <div class="p-6 rounded-2xl bg-gradient-to-r from-brand-900/80 to-slate-800 border border-brand-500/30 relative overflow-hidden">
                <span class="font-mono text-[11px] font-semibold text-brand-300 uppercase tracking-widest bg-brand-500/20 px-2.5 py-1 rounded-md border border-brand-500/30">Kelas Berjalan Sekarang</span>
                <h3 class="text-xl font-bold text-white mt-3">Pemrograman Web Lanjut</h3>
                <p class="text-xs text-slate-300 mt-1">Pertemuan 09 — Integrasi API & Service Worker</p>
                <div class="mt-5 flex items-center justify-between">
                  <div class="flex items-center gap-3">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-ping"></span>
                    <span class="text-xs font-mono text-emerald-300">Sesi Live Webcast Aktif</span>
                  </div>
                  <button class="px-4 py-2 bg-brand-500 hover:bg-brand-400 text-white text-xs font-semibold rounded-lg transition-all shadow-md">Gabung Kelas</button>
                </div>
              </div>

              <!-- Metrics -->
              <div class="grid grid-cols-2 gap-4">
                <div class="p-4 rounded-xl glass-card">
                  <p class="text-xs font-mono text-slate-400">Total SKS Diambil</p>
                  <p class="text-2xl font-bold text-white mt-1">22 <span class="text-xs font-normal text-slate-400">SKS</span></p>
                  <div class="w-full bg-slate-700/50 h-1.5 rounded-full mt-3 overflow-hidden">
                    <div class="bg-brand-500 h-full rounded-full" style="width: 85%"></div>
                  </div>
                </div>
                <div class="p-4 rounded-xl glass-card">
                  <p class="text-xs font-mono text-slate-400">Rata-rata Presensi</p>
                  <p class="text-2xl font-bold text-emerald-400 mt-1">98.5%</p>
                  <p class="text-[11px] text-slate-400 mt-1">Tercatat otomatis via QR & Session</p>
                </div>
              </div>
            </div>

            <!-- Sidebar Panel -->
            <div class="space-y-4">
              <div class="p-4 rounded-2xl glass-card">
                <h4 class="text-xs font-mono text-slate-300 font-semibold uppercase tracking-wider mb-3">Tugas & Kuis Mendatang</h4>
                <div class="space-y-3">
                  <div class="p-3 rounded-lg bg-white/5 border border-white/5 flex items-start gap-3">
                    <div class="w-2 h-2 rounded-full bg-amber-400 mt-1.5 shrink-0"></div>
                    <div>
                      <p class="text-xs font-semibold text-white">UTS Prak. Pemrograman Web</p>
                      <p class="text-[10px] font-mono text-slate-400 mt-0.5">Tenggat: Hari Ini, 23:59 WIB</p>
                    </div>
                  </div>
                  <div class="p-3 rounded-lg bg-white/5 border border-white/5 flex items-start gap-3">
                    <div class="w-2 h-2 rounded-full bg-indigo-400 mt-1.5 shrink-0"></div>
                    <div>
                      <p class="text-xs font-semibold text-white">Kuis 2 Manajemen Keuangan</p>
                      <p class="text-[10px] font-mono text-slate-400 mt-0.5">Tenggat: Besok, 14:00 WIB</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ============ STATS STRIP ============ -->
  <section class="border-y border-white/10 bg-slate-900/50 backdrop-blur-md">
    <div class="max-w-7xl mx-auto px-6 lg:px-8 py-10 grid grid-cols-2 lg:grid-cols-4 gap-8">
      <div class="border-l-2 border-brand-500 pl-4">
        <p class="font-mono text-3xl font-extrabold text-white">150+</p>
        <p class="text-xs font-medium text-slate-400 mt-1">Mata Kuliah Digital</p>
      </div>
      <div class="border-l-2 border-emerald-500 pl-4">
        <p class="font-mono text-3xl font-extrabold text-white">12.000+</p>
        <p class="text-xs font-medium text-slate-400 mt-1">Mahasiswa Aktif Terkoneksi</p>
      </div>
      <div class="border-l-2 border-amber-500 pl-4">
        <p class="font-mono text-3xl font-extrabold text-white">6</p>
        <p class="text-xs font-medium text-slate-400 mt-1">Fakultas Terintegrasi</p>
      </div>
      <div class="border-l-2 border-indigo-500 pl-4">
        <p class="font-mono text-3xl font-extrabold text-white">450+</p>
        <p class="text-xs font-medium text-slate-400 mt-1">Dosen & Pengampu Sesi</p>
      </div>
    </div>
  </section>

  <!-- ============ BENTO GRID FITUR ============ -->
  <section id="fitur" class="max-w-7xl mx-auto px-6 lg:px-8 py-24">
    <div class="text-center max-w-2xl mx-auto mb-16">
      <span class="font-mono text-xs font-semibold uppercase tracking-widest text-brand-400 bg-brand-500/10 px-3 py-1 rounded-full border border-brand-500/20">Fitur Generasi Baru</span>
      <h2 class="text-3xl sm:text-4xl font-extrabold text-white tracking-tight mt-4">Platform Berkecepatan Tinggi untuk Pembelajaran Efektif</h2>
    </div>

    <div class="grid md:grid-cols-3 gap-6">
      <!-- Card 1 (Large) -->
      <div class="md:col-span-2 glass-card p-8 rounded-3xl border border-white/10 hover:border-brand-500/40 transition-all flex flex-col justify-between group">
        <div>
          <div class="w-12 h-12 rounded-2xl bg-brand-500/20 text-brand-300 flex items-center justify-center font-bold text-xl mb-6 group-hover:scale-110 transition-transform">
            ⚡
          </div>
          <h3 class="text-xl font-bold text-white">Struktur Modul Teratur & RPS Interactive</h3>
          <p class="text-slate-400 text-sm leading-relaxed mt-2">Setiap materi disusun runtut berdasarkan minggu perkuliahan dan RPS resmi UNWIR. Dilengkapi pemutar media langsung tanpa mengunduh file berulang kali.</p>
        </div>
        <div class="mt-8 pt-6 border-t border-white/5 flex items-center gap-4 text-xs font-mono text-brand-300">
          <span>✓ Auto Sync Modul</span>
          <span>✓ Support PDF & Video streaming</span>
        </div>
      </div>

      <!-- Card 2 -->
      <div class="glass-card p-8 rounded-3xl border border-white/10 hover:border-emerald-500/40 transition-all flex flex-col justify-between group">
        <div>
          <div class="w-12 h-12 rounded-2xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center font-bold text-xl mb-6 group-hover:scale-110 transition-transform">
            🎯
          </div>
          <h3 class="text-xl font-bold text-white">Presensi Otomatis</h3>
          <p class="text-slate-400 text-sm leading-relaxed mt-2">Kehadiran tercatat akurat saat mengakses ruang kelas tanpa perlu pengisian manual di grup WhatsApp.</p>
        </div>
        <span class="text-xs font-mono text-emerald-400 mt-6 block">Real-time Verification</span>
      </div>

      <!-- Card 3 -->
      <div class="glass-card p-8 rounded-3xl border border-white/10 hover:border-indigo-500/40 transition-all flex flex-col justify-between group">
        <div>
          <div class="w-12 h-12 rounded-2xl bg-indigo-500/20 text-indigo-300 flex items-center justify-center font-bold text-xl mb-6 group-hover:scale-110 transition-transform">
            📝
          </div>
          <h3 class="text-xl font-bold text-white">Pengumpulan Tugas & Kuis</h3>
          <p class="text-slate-400 text-sm leading-relaxed mt-2">Sistem penghitung mundur transparan untuk mencegah keterlambatan penyerahan tugas.</p>
        </div>
        <span class="text-xs font-mono text-indigo-300 mt-6 block">Auto Deadline Alert</span>
      </div>

      <!-- Card 4 (Large) -->
      <div class="md:col-span-2 glass-card p-8 rounded-3xl border border-white/10 hover:border-amber-500/40 transition-all flex flex-col justify-between group">
        <div>
          <div class="w-12 h-12 rounded-2xl bg-amber-500/20 text-amber-400 flex items-center justify-center font-bold text-xl mb-6 group-hover:scale-110 transition-transform">
            📊
          </div>
          <h3 class="text-xl font-bold text-white">Rekapitulasi Nilai & Transparansi Hasil Evaluation</h3>
          <p class="text-slate-400 text-sm leading-relaxed mt-2">Dapatkan umpan balik nilai dari kuis, UTS, dan UAS secara langsung setelah diperiksa oleh dosen pengampu.</p>
        </div>
        <div class="mt-8 pt-6 border-t border-white/5 flex items-center gap-4 text-xs font-mono text-amber-400">
          <span>✓ Statistik Performa Kelas</span>
          <span>✓ Laporan Transparan</span>
        </div>
      </div>
    </div>
  </section>

  <!-- ============ MATA KULIAH POPULER ============ -->
  <section id="kursus" class="py-24 bg-slate-900/40 border-y border-white/5">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
      <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-12">
        <div>
          <span class="font-mono text-xs font-semibold uppercase tracking-widest text-brand-400">Katalog Digital</span>
          <h2 class="text-3xl font-extrabold text-white mt-2">Mata Kuliah Teraktif Semester Ini</h2>
        </div>
        <a href="#" class="inline-flex items-center gap-2 text-sm font-semibold text-brand-400 hover:text-brand-300 font-mono">
          Lihat Seluruh Katalog
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
          </svg>
        </a>
      </div>

      <!-- Course Cards -->
      <div class="grid md:grid-cols-3 gap-6">

        <article class="glass-card rounded-3xl overflow-hidden border border-white/10 hover:border-brand-500/50 transition-all flex flex-col justify-between">
          <div class="p-6">
            <div class="flex items-center justify-between">
              <span class="font-mono text-[10px] font-semibold text-indigo-300 bg-indigo-500/20 px-2.5 py-1 rounded-md border border-indigo-500/30">Teknik Informatika</span>
              <span class="font-mono text-xs text-slate-400">3 SKS</span>
            </div>
            <h3 class="text-lg font-bold text-white mt-4 hover:text-brand-300 transition-colors">Pemrograman Web Lanjut</h3>
            <p class="text-xs text-slate-400 mt-2">Pengembangan REST API, Framework Modern & Microservices.</p>
          </div>
          <div class="p-6 pt-0 border-t border-white/5 mt-4 flex items-center justify-between text-xs text-slate-400 font-mono">
            <span>142 Mahasiswa</span>
            <span class="text-brand-400 font-semibold">Buka Kelas →</span>
          </div>
        </article>

        <article class="glass-card rounded-3xl overflow-hidden border border-white/10 hover:border-brand-500/50 transition-all flex flex-col justify-between">
          <div class="p-6">
            <div class="flex items-center justify-between">
              <span class="font-mono text-[10px] font-semibold text-rose-300 bg-rose-500/20 px-2.5 py-1 rounded-md border border-rose-500/30">Manajemen</span>
              <span class="font-mono text-xs text-slate-400">3 SKS</span>
            </div>
            <h3 class="text-lg font-bold text-white mt-4 hover:text-brand-300 transition-colors">Manajemen Keuangan Perusahaan</h3>
            <p class="text-xs text-slate-400 mt-2">Analisis portofolio, risiko investasi & struktur modal.</p>
          </div>
          <div class="p-6 pt-0 border-t border-white/5 mt-4 flex items-center justify-between text-xs text-slate-400 font-mono">
            <span>118 Mahasiswa</span>
            <span class="text-brand-400 font-semibold">Buka Kelas →</span>
          </div>
        </article>

        <article class="glass-card rounded-3xl overflow-hidden border border-white/10 hover:border-brand-500/50 transition-all flex flex-col justify-between">
          <div class="p-6">
            <div class="flex items-center justify-between">
              <span class="font-mono text-[10px] font-semibold text-amber-300 bg-amber-500/20 px-2.5 py-1 rounded-md border border-amber-500/30">Hukum</span>
              <span class="font-mono text-xs text-slate-400">2 SKS</span>
            </div>
            <h3 class="text-lg font-bold text-white mt-4 hover:text-brand-300 transition-colors">Pengantar Hukum Perdata</h3>
            <p class="text-xs text-slate-400 mt-2">Dasar-dasar hukum perikatan, perjanjian & hukum benda.</p>
          </div>
          <div class="p-6 pt-0 border-t border-white/5 mt-4 flex items-center justify-between text-xs text-slate-400 font-mono">
            <span>96 Mahasiswa</span>
            <span class="text-brand-400 font-semibold">Buka Kelas →</span>
          </div>
        </article>

      </div>
    </div>
  </section>

  <!-- ============ ALUR AKADEMIK ============ -->
  <section id="alur" class="max-w-7xl mx-auto px-6 lg:px-8 py-24">
    <div class="text-center max-w-xl mx-auto mb-16">
      <span class="font-mono text-xs font-semibold uppercase tracking-widest text-brand-400">Panduan Penggunaan</span>
      <h2 class="text-3xl font-extrabold text-white mt-2">4 Langkah Mudah Akses Kelas</h2>
    </div>

    <div class="grid md:grid-cols-4 gap-6 relative">
      <div class="glass-card p-6 rounded-2xl border border-white/10 relative">
        <span class="font-mono text-2xl font-black text-brand-500/40">01</span>
        <h3 class="text-base font-bold text-white mt-2">Autentikasi SIAKAD</h3>
        <p class="text-xs text-slate-400 leading-relaxed mt-2">Masuk menggunakan NIM dan password akun SIAKAD resmi milikmu.</p>
      </div>

      <div class="glass-card p-6 rounded-2xl border border-white/10 relative">
        <span class="font-mono text-2xl font-black text-brand-500/40">02</span>
        <h3 class="text-base font-bold text-white mt-2">Otomatisasi KRS</h3>
        <p class="text-xs text-slate-400 leading-relaxed mt-2">Mata kuliah yang kamu kontrak di KRS akan langsung tersedia di dashboard.</p>
      </div>

      <div class="glass-card p-6 rounded-2xl border border-white/10 relative">
        <span class="font-mono text-2xl font-black text-brand-500/40">03</span>
        <h3 class="text-base font-bold text-white mt-2">Interaksi Kelas</h3>
        <p class="text-xs text-slate-400 leading-relaxed mt-2">Unduh berkas modul, isi kuis online, dan serahkan tugas tepat waktu.</p>
      </div>

      <div class="glass-card p-6 rounded-2xl border border-white/10 relative">
        <span class="font-mono text-2xl font-black text-emerald-500/60">04</span>
        <h3 class="text-base font-bold text-white mt-2">Pantau Hasil Nilai</h3>
        <p class="text-xs text-slate-400 leading-relaxed mt-2">Cek transparansi rekap nilai tugas, UTS, hingga UAS kapan saja.</p>
      </div>
    </div>
  </section>

  <!-- ============ CTA BANNER ============ -->
  <section class="max-w-7xl mx-auto px-6 lg:px-8 py-16">
    <div class="glass-panel rounded-3xl p-10 lg:p-16 text-center border border-brand-500/30 relative overflow-hidden">
      <div class="absolute inset-0 glow-effect pointer-events-none"></div>
      <h2 class="text-3xl sm:text-4xl font-extrabold text-white tracking-tight relative z-10">
        Siap Memulai Perkuliahan Hari Ini?
      </h2>
      <p class="text-slate-400 text-sm mt-3 max-w-md mx-auto relative z-10">
        Gunakan kredensial akun SIAKAD milikmu untuk masuk secara instan.
      </p>
      <div class="mt-8 relative z-10">
        <a href="{{ route('login') }}" class="px-8 py-4 bg-gradient-to-r from-brand-600 to-indigo-600 hover:from-brand-500 hover:to-indigo-500 text-white font-semibold rounded-2xl transition-all shadow-xl shadow-brand-600/30 inline-flex items-center gap-2">
          <span>Masuk E-Learning UNWIR</span>
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
          </svg>
        </a>
      </div>
    </div>
  </section>

  <!-- ============ FOOTER ============ -->
  <footer class="border-t border-white/10 bg-slate-950 text-slate-400 text-xs">
    <div class="max-w-7xl mx-auto px-6 lg:px-8 py-12 grid grid-cols-2 md:grid-cols-5 gap-8">
      <div class="col-span-2">
        <div class="flex items-center gap-3">
          <div class="w-8 h-8 rounded-lg bg-brand-600 flex items-center justify-center text-white font-bold">U</div>
          <span class="text-base font-bold text-white">E-Learning UNWIR</span>
        </div>
        <p class="mt-4 text-slate-400 leading-relaxed max-w-sm">
          Platform Sistem Informasi Pembelajaran Digital Resmi Universitas Wiralodra Indramayu.
        </p>
      </div>
      <div>
        <p class="font-semibold text-white uppercase font-mono tracking-wider mb-3">Sistem</p>
        <ul class="space-y-2">
          <li><a href="#fitur" class="hover:text-white transition-colors">Fitur Platform</a></li>
          <li><a href="#kursus" class="hover:text-white transition-colors">Katalog Kelas</a></li>
          <li><a href="#alur" class="hover:text-white transition-colors">Panduan SIAKAD</a></li>
        </ul>
      </div>
      <div>
        <p class="font-semibold text-white uppercase font-mono tracking-wider mb-3">Situs Kampus</p>
        <ul class="space-y-2">
          <li><a href="#" class="hover:text-white transition-colors">Portal UNWIR</a></li>
          <li><a href="#" class="hover:text-white transition-colors">Portal SIAKAD</a></li>
          <li><a href="#" class="hover:text-white transition-colors">Perpustakaan Digital</a></li>
        </ul>
      </div>
      <div>
        <p class="font-semibold text-white uppercase font-mono tracking-wider mb-3">Bantuan</p>
        <ul class="space-y-2">
          <li><a href="#" class="hover:text-white transition-colors">Layanan BAAK</a></li>
          <li><a href="#" class="hover:text-white transition-colors">Helpdesk IT</a></li>
        </ul>
      </div>
    </div>
    <div class="border-t border-white/5 py-6">
      <div class="max-w-7xl mx-auto px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-4 font-mono text-[11px] text-slate-400">
        <p>© 2026 E-Learning UNWIR — Universitas Wiralodra. All rights reserved.</p>
        <p>Built with Laravel 12 & Tailwind CSS</p>
      </div>
    </div>
  </footer>

</body>

</html>