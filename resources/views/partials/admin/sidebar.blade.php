<!-- Sidebar Container -->
<aside class="w-64 h-screen bg-[#0F172A] text-[#F8FAFC] flex flex-col border-r border-slate-800">

  <!-- Brand -->
  <div class="h-20 flex items-center gap-2.5 px-6 border-b border-slate-800/80 shrink-0">
    <span class="w-9 h-9 rounded-lg bg-[#F59E0B]/20 border border-[#F59E0B]/30 flex items-center justify-center">
      <span class="text-[#F59E0B] font-display font-semibold text-lg">U</span>
    </span>
    <div class="leading-tight">
      <p class="font-display text-lg font-semibold tracking-tight text-white">E-Learning</p>
      <p class="text-[11px] font-mono uppercase tracking-wider text-[#94A3B8]">Univ. Wiralodra</p>
    </div>
    <button @click="sidebarOpen = false" class="ml-auto lg:hidden text-[#94A3B8] hover:text-white transition-colors">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
        <path d="M18 6L6 18M6 6l12 12" />
      </svg>
    </button>
  </div>

  <!-- Profil singkat -->
  <div class="px-6 py-5 border-b border-slate-800/80 shrink-0">
    <div class="flex items-center gap-3">
      <img src="https://i.pravatar.cc/80?img=32" class="w-11 h-11 rounded-full object-cover border-2 border-[#0284C7]/40" alt="Avatar">
      <div class="min-w-0">
        <p class="text-sm font-medium truncate text-white">{{ Auth::user()->name }}</p>
        <p class="text-xs text-[#94A3B8] truncate font-mono">2210631170045 · TI</p>
      </div>
    </div>
  </div>

  <!-- Navigasi utama -->
  <nav class="flex-1 overflow-y-auto px-4 py-5 space-y-1">

    <!-- Active Link (Dashboard) -->
    <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-lg bg-[#0284C7] text-white text-sm font-medium shadow-sm shadow-[#0284C7]/30 transition-all">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
        <rect x="3" y="3" width="7" height="9" rx="1.5" />
        <rect x="14" y="3" width="7" height="5" rx="1.5" />
        <rect x="14" y="12" width="7" height="9" rx="1.5" />
        <rect x="3" y="16" width="7" height="5" rx="1.5" />
      </svg>
      Dashboard
    </a>

    <!-- Parent: Akun (Dropdown Trigger) -->
    <button
      type="button"
      onclick="document.getElementById('submenuAkun').classList.toggle('hidden'); document.getElementById('chevronAkun').classList.toggle('rotate-180')"
      class="w-full flex items-center justify-between gap-3 px-3 py-2.5 rounded-lg text-[#94A3B8] hover:bg-white/5 hover:text-white text-sm font-medium transition-colors">
      <span class="flex items-center gap-3">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
          <path d="M4 19.5A2.5 2.5 0 016.5 17H20" />
          <path d="M6.5 2H20v20H6.5A2.5 2.5 0 014 19.5v-15A2.5 2.5 0 016.5 2z" />
        </svg>
        Akun
      </span>
      <svg
        id="chevronAkun"
        width="16"
        height="16"
        viewBox="0 0 24 24"
        fill="none"
        stroke="currentColor"
        stroke-width="2"
        class="transition-transform duration-200">
        <path d="M6 9l6 6 6-6" />
      </svg>
    </button>

    <!-- Submenu: Akun -->
    <div id="submenuAkun" class="hidden pl-9 my-1 flex flex-col gap-1 border-l-2 border-slate-800 ml-5">
      <a href="{{ route('akun_dosen.index') }}" class="px-3 py-2 rounded-lg text-[#94A3B8] hover:bg-white/5 hover:text-white text-sm transition-colors">
        Dosen
      </a>
      <a href="{{ route('akun_mahasiswa.index') }}" class="px-3 py-2 rounded-lg text-[#94A3B8] hover:bg-white/5 hover:text-white text-sm transition-colors">
        Mahasiswa
      </a>
    </div>

    <a href="{{ route('matakuliah.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-[#94A3B8] hover:bg-white/5 hover:text-white text-sm font-medium transition-colors">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
        <path d="M9 11l3 3L22 4" />
        <path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11" />
      </svg>
      Matakuliah
    </a>

    <a href="{{ route('matakuliah.pengampu') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-[#94A3B8] hover:bg-white/5 hover:text-white text-sm font-medium transition-colors">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
        <path d="M3 3v18h18" />
        <path d="M7 15l4-6 3 4 5-8" />
      </svg>
      Penugasan MK
    </a>

    <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-[#94A3B8] hover:bg-white/5 hover:text-white text-sm font-medium transition-colors">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
        <rect x="3" y="4" width="18" height="16" rx="2" />
        <path d="M3 10h18M8 2v4M16 2v4" />
      </svg>
      Jadwal Kuliah
    </a>

    <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-[#94A3B8] hover:bg-white/5 hover:text-white text-sm font-medium transition-colors">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
        <path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z" />
      </svg>
      Pesan
    </a>

  </nav>

  <!-- Bottom Nav -->
  <div class="px-4 py-4 border-t border-slate-800/80 shrink-0 space-y-1">
    <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-[#94A3B8] hover:bg-white/5 hover:text-white text-sm font-medium transition-colors">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
        <circle cx="12" cy="12" r="3" />
        <path d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 11-2.83 2.83l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-4 0v-.09A1.65 1.65 0 009 19.4a1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 11-2.83-2.83l.06-.06A1.65 1.65 0 004.6 15a1.65 1.65 0 00-1.51-1H3a2 2 0 010-4h.09A1.65 1.65 0 004.6 9a1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 112.83-2.83l.06.06A1.65 1.65 0 009 4.6a1.65 1.65 0 001-1.51V3a2 2 0 014 0v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 112.83 2.83l-.06.06A1.65 1.65 0 0019.4 9c.36.15.68.4.9.72" />
      </svg>
      Pengaturan
    </a>

    <form id="logout-form" method="POST" action="{{ route('logout') }}">
      @csrf
      <a href="{{ route('logout') }}"
        onclick="event.preventDefault(); this.closest('form').submit();"
        class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-rose-400/80 hover:bg-rose-500/10 hover:text-rose-400 text-sm font-medium transition-colors">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
          <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4" />
          <path d="M16 17l5-5-5-5" />
          <path d="M21 12H9" />
        </svg>
        Keluar
      </a>
    </form>
  </div>

</aside>