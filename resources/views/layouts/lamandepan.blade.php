<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- logo unwir -->
  <link rel="icon" type="image/png" href="{{ asset('image/logoUnwir.png') }}">
  <title>{{ config('app.name', 'LMS-AR Geospace') }}</title>

  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,400;0,9..144,500;0,9..144,600;0,9..144,700;1,9..144,500&family=Inter:wght@400;500;600;700;800&family=IBM+Plex+Mono:wght@400;500&display=swap" rel="stylesheet">
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            ink: '#1E1B4B', // deep indigo — dulu navy
            paper: '#F5F3FF', // lavender putih — dulu biru muda
            amber: '#34D399', // emerald — aksen icon
            teal: '#7C3AED', // violet — aksen utama/link
            coral: '#5B21B6', // violet gelap — tombol CTA
            line: '#E4DBFB', // border lavender
          },
          fontFamily: {
            display: ['Fraunces', 'serif'],
            sans: ['Inter', 'sans-serif'],
            mono: ['IBM Plex Mono', 'monospace'],
          },
        }
      }
    }
  </script>
  <style>
    body {
      background-color: #F5F8FC;
    }

    .dot-grid {
      background-image: radial-gradient(#C4B5FD 1px, transparent 1px);
      background-size: 22px 22px;
    }

    .highlight-mark {
      position: relative;
      white-space: nowrap;
      background: #A78BFA;
    }

    .highlight-mark::after {
      content: "";
      position: absolute;
      left: -2px;
      right: -2px;
      bottom: 2px;
      height: 0.5em;
      background: #93C5FD;
      z-index: -1;
      transform: rotate(-1deg);
    }

    .tab-label {
      writing-mode: vertical-rl;
    }

    ::selection {
      background: #C4B5FD;
      color: #1E1B4B;
    }
  </style>
</head>

<body class="font-sans text-ink antialiased">

  <!-- ============ NAVBAR ============ -->
  <header class="sticky top-0 z-50 bg-paper/90 backdrop-blur border-b border-line">
    <div class="max-w-7xl mx-auto px-6 lg:px-10 h-20 flex items-center justify-between">
      <a href="/" class="flex items-center gap-2.5">
        <span class="w-9 h-9 rounded-lg bg-ink flex items-center justify-center overflow-hidden">
          <img src="{{ asset('image/logo.png') }}"
            alt="Logo"
            class="w-full h-full object-contain">
        </span>
        <span class="font-display text-xl font-semibold tracking-tight">LMS-AR Geospace</span>
      </a>

      <nav class="hidden lg:flex items-center gap-9 text-[15px] font-medium text-ink/70">
        <a href="#fitur" class="hover:text-ink transition-colors">Fitur</a>
        <a href="#kursus" class="hover:text-ink transition-colors">Pembelajaran</a>
        <a href="#cara-kerja" class="hover:text-ink transition-colors">Cara Pakai</a>
        <a href="#testimoni" class="hover:text-ink transition-colors">Testimoni</a>
        <a href="{{ asset('apklms/lms.apk') }}" download="lms-ar.apk" class="hover:text-ink transition-colors">Download APK</a>

      </nav>

      <div class="flex items-center gap-3">

        @if (Route::has('login'))
        @auth
        <a
          href="{{ url('/dashboard') }}"
          class="inline-block px-5 py-1.5 border-[#19140035] hover:border-[#1915014a] border text-[#1b1b18] rounded-sm text-sm leading-normal">
          Dashboard
        </a>
        @else
        <a
          href="{{ route('login') }}"
          class="inline-block px-5 py-1.5 text-[#1b1b18] border border-transparent hover:border-[#19140035] rounded-sm text-sm leading-normal">
          Log in
        </a>

        {{-- Register dinonaktifkan: tidak bisa diklik & tidak menuju URL --}}
        @if (Route::has('register'))
        <span
          aria-disabled="true"
          title="Pendaftaran dinonaktifkan"
          class="hidden sm:inline-block px-5 py-1.5 border border-[#19140035] text-[#1b1b18] rounded-sm text-sm leading-normal opacity-50 cursor-not-allowed select-none pointer-events-none">
          Register
        </span>
        @endif
        @endauth
        @endif

        {{-- Tombol hamburger (hanya mobile/tablet) --}}
        <button
          id="menu-toggle"
          type="button"
          aria-label="Buka menu"
          aria-expanded="false"
          aria-controls="mobile-menu"
          class="lg:hidden inline-flex items-center justify-center w-10 h-10 rounded-lg border border-line text-ink hover:bg-white transition-colors">
          <svg id="icon-open" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
            <path d="M4 6h16M4 12h16M4 18h16" />
          </svg>
          <svg id="icon-close" class="hidden" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
            <path d="M6 6l12 12M18 6L6 18" />
          </svg>
        </button>

      </div>
    </div>

    {{-- Menu navigasi mobile --}}
    <nav id="mobile-menu" class="hidden lg:hidden border-t border-line bg-paper">
      <div class="max-w-7xl mx-auto px-6 py-3 flex flex-col text-[15px] font-medium text-ink/80">
        <a href="#fitur" class="mobile-link py-3 border-b border-line hover:text-ink">Fitur</a>
        <a href="#kursus" class="mobile-link py-3 border-b border-line hover:text-ink">Pembelajaran</a>
        <a href="#cara-kerja" class="mobile-link py-3 border-b border-line hover:text-ink">Cara Pakai</a>
        <a href="#testimoni" class="mobile-link py-3 border-b border-line hover:text-ink">Testimoni</a>
        <a href="{{ asset('apklms/lms.apk') }}" download="lms-ar.apk" class="mobile-link py-3 hover:text-ink">Download APK</a>
      </div>
    </nav>
  </header>

  <!-- ============ HERO ============ -->
  <section class="relative overflow-hidden bg-paper">
    <!-- soft glow blobs instead of dot-grid -->
    <div class="pointer-events-none absolute -top-32 -right-32 w-[500px] h-[500px] rounded-full bg-teal/20 blur-3xl"></div>
    <div class="pointer-events-none absolute top-1/2 -left-40 w-[400px] h-[400px] rounded-full bg-amber/20 blur-3xl"></div>

    <div class="max-w-7xl mx-auto px-6 lg:px-10 pt-20 pb-24 lg:pt-28 lg:pb-32 grid lg:grid-cols-2 gap-16 items-center relative">

      <div>
        <span class="inline-flex items-center gap-2 font-mono text-xs uppercase tracking-wider text-ink bg-white border border-line rounded-full px-3 py-1.5 shadow-sm">
          <span class="relative flex w-2 h-2">
            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-teal opacity-60"></span>
            <span class="relative inline-flex rounded-full w-2 h-2 bg-teal"></span>
          </span>
          Semester Ganjil 2026/2027 — Belajar Aktif
        </span>

        <h1 class="font-display text-[2.75rem] sm:text-5xl lg:text-[3.4rem] leading-[1.08] font-semibold tracking-tight mt-6">
          Satu Tempat untuk<br class="hidden sm:block">
          Semua Pembelajaran
          <span class="relative inline-block">
            <span class="relative z-10">Geometrimu.</span>
            <svg class="absolute left-0 -bottom-1 w-full h-3" viewBox="0 0 200 12" preserveAspectRatio="none">
              <path d="M2 9 C 50 2, 150 2, 198 9" stroke="url(#grad)" stroke-width="5" fill="none" stroke-linecap="round" />
              <defs>
                <linearGradient id="grad" x1="0" y1="0" x2="1" y2="0">
                  <stop offset="0%" stop-color="#7C3AED" />
                  <stop offset="100%" stop-color="#34D399" />
                </linearGradient>
              </defs>
            </svg>
          </span>
        </h1>

        <p class="text-lg text-ink/65 leading-relaxed mt-6 max-w-md">
          LMS-AR mengintegrasikan materi geometri, eksplorasi Augmented Reality (AR), tugas, kuis, latihan kemampuan spasial, dan evaluasi dalam satu platform pembelajaran yang interaktif. Dirancang untuk membantu siswa memahami konsep geometri secara visual, mengembangkan kemampuan spasial, serta membangun resiliensi dalam menghadapi berbagai tantangan pembelajaran.
        </p>

        <div class="flex flex-wrap items-center gap-4 mt-9">
          <a href="{{ route('login') }}" class="inline-flex items-center gap-2 bg-ink text-paper font-medium px-6 py-3.5 rounded-full hover:bg-ink/90 transition-colors">
            Masuk ke E-Learning
            <svg width="14" height="14" viewBox="0 0 14 14" fill="none">
              <path d="M1 7H13M13 7L7.5 1.5M13 7L7.5 12.5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
          </a>
          <a href="#cara-kerja" class="inline-flex items-center gap-2 font-medium text-ink/80 hover:text-ink px-2 py-3.5 transition-colors underline decoration-ink/20 underline-offset-4 hover:decoration-ink/60">
            Lihat cara pakainya
          </a>
        </div>

        <div class="flex items-center gap-5 mt-10 pt-8 border-t border-line">
          <div class="flex -space-x-3">
            <img class="w-10 h-10 rounded-full border-2 border-paper object-cover" src="https://i.pravatar.cc/80?img=32" alt="">
            <img class="w-10 h-10 rounded-full border-2 border-paper object-cover" src="https://i.pravatar.cc/80?img=47" alt="">
            <img class="w-10 h-10 rounded-full border-2 border-paper object-cover" src="https://i.pravatar.cc/80?img=15" alt="">
          </div>
          <p class="text-sm text-ink/60">
            Digunakan oleh
            <span class="font-semibold text-ink">
              {{ number_format($jumlahMahasiswa, 0, ',', '.') }}+
            </span>
            Siswa
          </p>
        </div>
      </div>

      <!-- hero visual: mock dashboard window instead of stacked cards -->
      <div class="relative hidden lg:block">
        <div class="bg-white border border-line rounded-2xl shadow-2xl overflow-hidden">

          <!-- window chrome -->
          <div class="flex items-center gap-1.5 px-4 py-3 border-b border-line bg-paper">
            <span class="w-2.5 h-2.5 rounded-full bg-ink/15"></span>
            <span class="w-2.5 h-2.5 rounded-full bg-ink/15"></span>
            <span class="w-2.5 h-2.5 rounded-full bg-ink/15"></span>
            <span class="ml-3 font-mono text-[11px] text-ink/40">elearning.unwir.ac.id/dashboard</span>
          </div>

          <div class="p-6">
            <p class="font-mono text-[11px] uppercase tracking-wider text-ink/40">Pembelajaran aktif kamu</p>

            <div class="space-y-3 mt-4">
              @foreach ($matakuliah as $index => $mk)
              <div class="flex items-center gap-4 p-4 rounded-xl border border-line {{ $index == 0 ? 'bg-teal/5' : 'hover:bg-paper' }} transition-colors">

                <span class="w-10 h-10 shrink-0 rounded-lg flex items-center justify-center font-display font-semibold text-sm
                  {{ $index == 0 ? 'bg-teal text-white' : 'bg-amber/20 text-ink' }}">
                  {{ substr($mk->nama_mk, 0, 1) }}
                </span>

                <div class="min-w-0 flex-1">
                  <p class="font-medium text-sm truncate">{{ $mk->nama_mk }}</p>
                  <p class="text-xs text-ink/45 mt-0.5">{{ $mk->prodi->nama_prodi ?? 'Program Studi' }} · {{ $mk->sks }} SKS</p>
                </div>

                <span class="font-mono text-[11px] text-ink/40 shrink-0">{{ $mk->kode_mk }}</span>
              </div>
              @endforeach
            </div>

            <div class="flex items-center justify-between mt-5 pt-5 border-t border-line">
              <span class="text-xs text-ink/40 font-mono">Diperbarui hari ini</span>
              <span class="inline-flex items-center gap-1 text-xs font-medium text-teal">
                <span class="w-1.5 h-1.5 rounded-full bg-teal"></span>
                ------------
              </span>
            </div>
          </div>
        </div>

        <!-- floating badge -->
        <div class="absolute -bottom-6 -left-6 bg-ink text-paper rounded-2xl px-5 py-4 shadow-xl rotate-[-3deg]">
          <p class="font-display text-2xl font-semibold">{{ number_format($jumlahMahasiswa, 0, ',', '.') }}+</p>
          <p class="text-xs text-paper/50">siswa aktif</p>
        </div>
      </div>

    </div>
  </section>

  <!-- ============ STATS STRIP ============ -->
  <section class="border-y border-line bg-ink text-paper">
    <div class="max-w-7xl mx-auto px-6 lg:px-10 py-10 grid grid-cols-2 lg:grid-cols-4 gap-8">

      {{-- Jumlah Mata Kuliah --}}
      <div>
        <p class="font-display text-3xl font-semibold">
          {{ number_format($jumlahMatakuliah, 0, ',', '.') }}+
        </p>
        <p class="text-sm text-paper/50 mt-1">
          Pembelajaran daring
        </p>
      </div>

      {{-- Jumlah Mahasiswa --}}
      <div>
        <p class="font-display text-3xl font-semibold">
          {{ number_format($jumlahMahasiswa, 0, ',', '.') }}+
        </p>
        <p class="text-sm text-paper/50 mt-1">
          Siswa aktif
        </p>
      </div>

      {{-- Jumlah Program Studi --}}
      <div>
        <p class="font-display text-3xl font-semibold">
          {{ number_format($jumlahProdi, 0, ',', '.') }}
        </p>
        <p class="text-sm text-paper/50 mt-1">
          Kelas
        </p>
      </div>

      {{-- Jumlah Dosen --}}
      <div>
        <p class="font-display text-3xl font-semibold">
          {{ number_format($jumlahDosen, 0, ',', '.') }}+
        </p>
        <p class="text-sm text-paper/50 mt-1">
          Guru
        </p>
      </div>

    </div>
  </section>

  <!-- ============ FITUR ============ -->
  <section id="fitur" class="max-w-7xl mx-auto px-6 lg:px-10 py-24 lg:py-28">
    <div class="max-w-xl">
      <span class="font-mono text-xs uppercase tracking-wider text-ink/40">Mengapa LMS-AR Dikembangkan?</span>
      <h2 class="font-display text-3xl lg:text-4xl font-semibold tracking-tight mt-3">
        Dirancang untuk menghadirkan pembelajaran geometri yang interaktif melalui integrasi LMS dan Augmented Reality (AR), 
      </h2>
      <p>sehingga siswa dapat mengeksplorasi objek 3D, mengembangkan kemampuan spasial, dan membangun resiliensi dalam menyelesaikan tantangan matematika.</p>
    </div>

    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6 mt-14">

      <div class="border border-line rounded-2xl p-7 hover:border-ink/30 hover:-translate-y-1 transition-all bg-white">
        <div class="w-11 h-11 rounded-lg bg-amber/20 flex items-center justify-center">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#0F2A4D" stroke-width="1.8">
            <path d="M12 3l9 4.5-9 4.5-9-4.5L12 3z" />
            <path d="M7 10.5V16c0 1.1 2.24 3 5 3s5-1.9 5-3v-5.5" />
          </svg>
        </div>
        <h3 class="font-display text-lg font-medium mt-5">Materi per pertemuan</h3>
        <p class="text-sm text-ink/60 leading-relaxed mt-2">Pembelajaran geometri tersusun secara bertahap dan terarah, dilengkapi eksplorasi AR, latihan spasial, dan aktivitas yang mendorong resiliensi siswa.</p>
      </div>

      <div class="border border-line rounded-2xl p-7 hover:border-ink/30 hover:-translate-y-1 transition-all bg-white">
        <div class="w-11 h-11 rounded-lg bg-teal/15 flex items-center justify-center">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#0F2A4D" stroke-width="1.8">
            <path d="M9 11l3 3L22 4" />
            <path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11" />
          </svg>
        </div>
        <h3 class="font-display text-lg font-medium mt-5">Tugas & kuis daring</h3>
        <p class="text-sm text-ink/60 leading-relaxed mt-2">Latihan, kuis, dan evaluasi geometri untuk mengembangkan kemampuan spasial serta melatih ketekunan siswa dalam menghadapi tantangan pembelajaran.</p>
      </div>

      <div class="border border-line rounded-2xl p-7 hover:border-ink/30 hover:-translate-y-1 transition-all bg-white">
        <div class="w-11 h-11 rounded-lg bg-coral/15 flex items-center justify-center">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#0F2A4D" stroke-width="1.8">
            <circle cx="12" cy="8" r="4" />
            <path d="M4 21v-1a7 7 0 0114 0v1" />
          </svg>
        </div>
        <h3 class="font-display text-lg font-medium mt-5">Diskusi interaktif</h3>
        <p class="text-sm text-ink/60 leading-relaxed mt-2">Ruang interaksi bagi siswa untuk bertanya, berdiskusi, dan memperoleh umpan balik dari guru dalam memahami konsep geometri dan menyelesaikan tantangan pembelajaran.</p>
      </div>

      <div class="border border-line rounded-2xl p-7 hover:border-ink/30 hover:-translate-y-1 transition-all bg-white">
        <div class="w-11 h-11 rounded-lg bg-amber/20 flex items-center justify-center">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#0F2A4D" stroke-width="1.8">
            <rect x="3" y="4" width="18" height="16" rx="2" />
            <path d="M3 10h18M8 2v4M16 2v4" />
          </svg>
        </div>
        <h3 class="font-display text-lg font-medium mt-5">Jadwal Pembelajaran</h3>
        <p class="text-sm text-ink/60 leading-relaxed mt-2">Jadwal setiap pertemuan tersusun terarah, mulai dari pembelajaran, eksplorasi AR, latihan, hingga evaluasi kemampuan spasial dan resiliensi siswa.</p>
      </div>

      <div class="border border-line rounded-2xl p-7 hover:border-ink/30 hover:-translate-y-1 transition-all bg-white">
        <div class="w-11 h-11 rounded-lg bg-teal/15 flex items-center justify-center">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#0F2A4D" stroke-width="1.8">
            <path d="M12 15a4 4 0 004-4V6a4 4 0 00-8 0v5a4 4 0 004 4z" />
            <path d="M19 11a7 7 0 01-14 0M12 19v3" />
          </svg>
        </div>
        <h3 class="font-display text-lg font-medium mt-5">Presensi otomatis</h3>
        <p class="text-sm text-ink/60 leading-relaxed mt-2">Kehadiran siswa tercatat otomatis melalui aktivitas pembelajaran pada LMS-AR, mulai dari mengakses materi hingga melakukan eksplorasi AR.</p>
      </div>

      <div class="border border-line rounded-2xl p-7 hover:border-ink/30 hover:-translate-y-1 transition-all bg-white">
        <div class="w-11 h-11 rounded-lg bg-coral/15 flex items-center justify-center">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#0F2A4D" stroke-width="1.8">
            <path d="M3 3v18h18" />
            <path d="M7 15l4-6 3 4 5-8" />
          </svg>
        </div>
        <h3 class="font-display text-lg font-medium mt-5">Rekap Hasil Belajar</h3>
        <p class="text-sm text-ink/60 leading-relaxed mt-2">Pantau perkembangan hasil belajar, kemampuan spasial, dan proses belajar siswa secara berkala melalui satu sistem.</p>
      </div>

    </div>
  </section>

  <!-- ============ KURSUS POPULER ============ -->
  <section id="kursus" class="bg-white border-y border-line">
    <div class="max-w-7xl mx-auto px-6 lg:px-10 py-24 lg:py-28">
      <div class="flex flex-wrap items-end justify-between gap-6">
        <div>
          <span class="font-mono text-xs uppercase tracking-wider text-ink/40">Eksplorasi Geometri Aktif</span>
          <h2 class="font-display text-3xl lg:text-4xl font-semibold tracking-tight mt-3">Materi geometri interaktif yang dapat dieksplorasi siswa melalui LMS dan Augmented Reality (AR) untuk mengembangkan kemampuan spasial dan resiliensi.</h2>
        </div>
        <a href="#" class="inline-flex items-center gap-1.5 font-medium text-ink border-b border-ink/30 hover:border-ink pb-0.5 transition-colors">
          Lihat semua materi
          <svg width="14" height="14" viewBox="0 0 14 14" fill="none">
            <path d="M1 7H13M13 7L7.5 1.5M13 7L7.5 12.5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" />
          </svg>
        </a>
      </div>

      <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-7 mt-14">

        @forelse ($kursusPopuler as $index => $item)

        @php
        $warna = match ($index) {
        0 => 'from-teal to-ink',
        1 => 'from-coral to-ink',
        default => 'from-amber to-ink',
        };
        @endphp

        <article class="group border border-line rounded-2xl overflow-hidden hover:shadow-lg transition-shadow">

          {{-- Header Card --}}
          <div class="h-44 bg-gradient-to-br {{ $warna }} relative overflow-hidden">

            {{-- Nama Prodi --}}
            <span class="absolute top-3 left-3 font-mono text-[11px] uppercase tracking-wider bg-white/90 text-ink px-2.5 py-1 rounded-full">
              {{ $item->kelas->matakuliah->prodi->nama_prodi ?? 'Program Studi' }}
            </span>

          </div>

          {{-- Isi Card --}}
          <div class="p-6">

            {{-- Nama Mata Kuliah --}}
            <h3 class="font-display text-lg font-medium mt-2">
              {{ $item->kelas->matakuliah->nama_mk ?? 'Pembelajaran' }}
            </h3>

            {{-- SKS dan Kode Mata Kuliah --}}
            <p class="text-sm text-ink/55 mt-1.5">
              {{ $item->kelas->matakuliah->sks ?? 0 }} SKS
              · {{ $item->kelas->matakuliah->kode_mk ?? '-' }}
            </p>

            {{-- Jumlah Mahasiswa --}}
            <div class="flex items-center justify-between mt-5 pt-5 border-t border-line">

              <span class="font-semibold">
                {{ number_format($item->jumlah_mahasiswa, 0, ',', '.') }} siswa
              </span>

              <span class="text-sm text-teal font-medium group-hover:translate-x-1 transition-transform">
                Lihat kelas →
              </span>

            </div>

          </div>

        </article>

        @empty

        <div class="col-span-full text-center py-12">
          <p class="text-ink/50">
            Belum ada pembelajaran yang tersedia.
          </p>
        </div>

        @endforelse

      </div>
    </div>
  </section>

  <!-- ============ CARA KERJA ============ -->
  <section id="cara-kerja" class="max-w-7xl mx-auto px-6 lg:px-10 py-24 lg:py-28">
    <div class="max-w-xl">
      <span class="font-mono text-xs uppercase tracking-wider text-ink/40">Cara Pakai</span>
      <h2 class="font-display text-3xl lg:text-4xl font-semibold tracking-tight mt-3">Empat langkah, dari masuk hingga melihat hasil belajar.</h2>
    </div>

    <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-8 mt-14 relative">
      <div class="hidden lg:block absolute top-6 left-0 right-0 h-px bg-line"></div>

      <div class="relative">
        <span class="font-mono text-sm w-12 h-12 rounded-full bg-ink text-paper flex items-center justify-center relative z-10">01</span>
        <h3 class="font-display text-lg font-medium mt-5">01. Masuk ke LMS-AR</h3>
        <p class="text-sm text-ink/60 leading-relaxed mt-2">Gunakan akun yang telah diberikan untuk masuk ke LMS-AR dan mengakses pembelajaran geometri sesuai kelas.</p>
      </div>
      <div class="relative">
        <span class="font-mono text-sm w-12 h-12 rounded-full bg-ink text-paper flex items-center justify-center relative z-10">02</span>
        <h3 class="font-display text-lg font-medium mt-5">02. Akses materi pembelajaran</h3>
        <p class="text-sm text-ink/60 leading-relaxed mt-2">Pilih materi geometri pada dashboard untuk mempelajari konsep, melihat video, dan mengeksplorasi objek tiga dimensi melalui Augmented Reality (AR).</p>
      </div>
      <div class="relative">
        <span class="font-mono text-sm w-12 h-12 rounded-full bg-ink text-paper flex items-center justify-center relative z-10">03</span>
        <h3 class="font-display text-lg font-medium mt-5">03. Eksplorasi, latihan & kuis</h3>
        <p class="text-sm text-ink/60 leading-relaxed mt-2">Lakukan aktivitas eksplorasi AR, kerjakan latihan kemampuan spasial, dan ikuti kuis sesuai petunjuk pembelajaran. Hadapi setiap tantangan dengan tekun dan reflektif.</p>
      </div>
      <div class="relative">
        <span class="font-mono text-sm w-12 h-12 rounded-full bg-amber text-ink flex items-center justify-center relative z-10">04</span>
        <h3 class="font-display text-lg font-medium mt-5">04. Pantau hasil belajar</h3>
        <p class="text-sm text-ink/60 leading-relaxed mt-2">Lihat hasil latihan, kuis, dan perkembangan kemampuan spasial serta resiliensi untuk mengetahui kemajuan belajar dan bagian yang perlu ditingkatkan.</p>
      </div>
    </div>
  </section>

  <!-- ============ TESTIMONI ============ -->
  <section id="testimoni" class="bg-ink text-paper">
    <div class="max-w-7xl mx-auto px-6 lg:px-10 py-24 lg:py-28">
      <span class="font-mono text-xs uppercase tracking-wider text-paper/40">Testimoni</span>
      <h2 class="font-display text-3xl lg:text-4xl font-semibold tracking-tight mt-3 max-w-lg">Kata Siswa yang Sudah Menggunakan LMS-AR GeoSpace</h2>

      <div class="grid md:grid-cols-3 gap-6 mt-14">
        <div class="bg-white/5 border border-white/10 rounded-2xl p-7">
          <p class="text-paper/80 leading-relaxed">"Semua materi kuliah ada di satu tempat, jadi nggak perlu lagi cari-cari file di grup WhatsApp yang berantakan."</p>
          <div class="flex items-center gap-3 mt-6">
            <img class="w-10 h-10 rounded-full object-cover" src="https://i.pravatar.cc/80?img=5" alt="">
            <div>
              <p class="text-sm font-medium">Dinda Ayu Pratiwi</p>
              <p class="text-xs text-paper/40">Siswi, Kelas X</p>
            </div>
          </div>
        </div>
        <div class="bg-white/5 border border-white/10 rounded-2xl p-7">
          <p class="text-paper/80 leading-relaxed">"Batas waktu tugas kelihatan jelas, jadi saya nggak pernah lagi telat kumpul tugas dari dosen."</p>
          <div class="flex items-center gap-3 mt-6">
            <img class="w-10 h-10 rounded-full object-cover" src="https://i.pravatar.cc/80?img=12" alt="">
            <div>
              <p class="text-sm font-medium">Farhan Ramadhan</p>
              <p class="text-xs text-paper/40">Siswa, Kelas XI</p>
            </div>
          </div>
        </div>
        <div class="bg-white/5 border border-white/10 rounded-2xl p-7">
          <p class="text-paper/80 leading-relaxed">"Nilai UTS dan tugas bisa langsung dicek di sini, jadi lebih tenang nunggu hasilnya dibanding lewat WA dosen."</p>
          <div class="flex items-center gap-3 mt-6">
            <img class="w-10 h-10 rounded-full object-cover" src="https://i.pravatar.cc/80?img=25" alt="">
            <div>
              <p class="text-sm font-medium">Salsabila Putri</p>
              <p class="text-xs text-paper/40">Siswi, Kelas IX</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ============ FOOTER ============ -->
  <footer class="border-t border-line bg-white">
    <div class="max-w-7xl mx-auto px-6 lg:px-10 py-16 grid sm:grid-cols-2 lg:grid-cols-5 gap-10">
      <div class="lg:col-span-2">
        <div class="flex items-center gap-2.5">
          <span class="w-9 h-9 rounded-lg bg-ink flex items-center justify-center">
            <span class="text-amber font-display font-semibold text-lg">U</span>
          </span>
          <span class="font-display text-xl font-semibold">LMS-AR Geospace</span>
        </div>
        <p class="text-sm text-ink/55 mt-4 max-w-xs leading-relaxed">LMS AR dan Geometri</p>
      </div>
      <div>
        <p class="font-medium text-sm">Layanan</p>
        <ul class="text-sm text-ink/55 space-y-2.5 mt-4">
          <li><a href="#fitur" class="hover:text-ink transition-colors">Fitur</a></li>
          <li><a href="#kursus" class="hover:text-ink transition-colors">Pembelajaran</a></li>
          <li><a href="#cara-kerja" class="hover:text-ink transition-colors">Cara Pakai</a></li>
        </ul>
      </div>
      <div>
        <p class="font-medium text-sm">Universitas</p>
        <ul class="text-sm text-ink/55 space-y-2.5 mt-4">
          <li><a href="#" class="hover:text-ink transition-colors">Tentang LMS-AR</a></li>
          <li><a href="#" class="hover:text-ink transition-colors">Kelas</a></li>
          <li><a href="#" class="hover:text-ink transition-colors">Kalender Akademik</a></li>
        </ul>
      </div>
      <div>
        <p class="font-medium text-sm">Bantuan</p>
        <ul class="text-sm text-ink/55 space-y-2.5 mt-4">
          <li><a href="#" class="hover:text-ink transition-colors">Pusat Bantuan</a></li>
          <li><a href="#" class="hover:text-ink transition-colors">Kebijakan Privasi</a></li>
          <li><a href="#" class="hover:text-ink transition-colors">Hubungi kami</a></li>
        </ul>
      </div>
    </div>
    <div class="border-t border-line">
      <div class="max-w-7xl mx-auto px-6 lg:px-10 py-6 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-ink/40">
        <p>© 2026 LMS AR-GEOSPACE. Seluruh hak cipta dilindungi.</p>
        <p>Dibuat dengan cinta untuk dunia pendidikan</p>
      </div>
    </div>
  </footer>

  <script>
    (function() {
      const toggle = document.getElementById('menu-toggle');
      const menu = document.getElementById('mobile-menu');
      const iconOpen = document.getElementById('icon-open');
      const iconClose = document.getElementById('icon-close');

      function setMenu(open) {
        menu.classList.toggle('hidden', !open);
        iconOpen.classList.toggle('hidden', open);
        iconClose.classList.toggle('hidden', !open);
        toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
      }

      toggle.addEventListener('click', function() {
        setMenu(menu.classList.contains('hidden'));
      });

      // Tutup menu setelah salah satu link diklik
      document.querySelectorAll('.mobile-link').forEach(function(link) {
        link.addEventListener('click', function() {
          setMenu(false);
        });
      });
    })();
  </script>

</body>

</html>