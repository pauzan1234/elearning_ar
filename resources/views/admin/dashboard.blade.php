@extends('admin.app-admin')
@section('ketjudul')
Dashboard
@endsection

@section('judul')
Ringkasan LMS-AR Geometri
@endsection

@section('content')

<div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">

  <div class="bg-white border border-line rounded-2xl p-6">
    <div class="flex items-center justify-between">
      <div class="w-11 h-11 rounded-lg bg-teal/10 flex items-center justify-center">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#2563EB" stroke-width="1.8">
          <path d="M4 19.5A2.5 2.5 0 016.5 17H20" />
          <path d="M6.5 2H20v20H6.5A2.5 2.5 0 014 19.5v-15A2.5 2.5 0 016.5 2z" />
        </svg>
      </div>
      <span class="text-xs font-mono px-2 py-1 rounded-full bg-teal/10 text-teal">Ganjil 2026/2027</span>
    </div>
    <p class="font-display text-3xl font-semibold mt-5">{{ number_format($totalMatkul, 0, ',', '.') }}</p>
    <p class="text-sm text-ink/55 mt-1">Mata Kuliah Aktif</p>
  </div>

  <div class="bg-white border border-line rounded-2xl p-6">
    <div class="flex items-center justify-between">
      <div class="w-11 h-11 rounded-lg bg-coral/10 flex items-center justify-center">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none"
          stroke="#1D4ED8" stroke-width="1.8"
          stroke-linecap="round" stroke-linejoin="round">
          <path d="M2 4.5A2.5 2.5 0 0 1 4.5 2H11v18H4.5A2.5 2.5 0 0 0 2 22V4.5Z" />
          <path d="M22 4.5A2.5 2.5 0 0 0 19.5 2H13v18h6.5A2.5 2.5 0 0 1 22 22V4.5Z" />
        </svg>
      </div>
      <span class="text-xs font-mono px-2 py-1 rounded-full bg-coral/10 text-coral">Materi Pembelajaran</span>
    </div>
    <p class="font-display text-3xl font-semibold mt-5">{{ number_format($totalDosen, 0, ',', '.') }}</p>
    <p class="text-sm text-ink/55 mt-1">Materi Pembelajaran</p>
  </div>

  <div class="bg-white border border-line rounded-2xl p-6">
    <div class="flex items-center justify-between">
      <div class="w-11 h-11 rounded-lg bg-amber/15 flex items-center justify-center">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none"
          stroke="#1D4ED8" stroke-width="1.8"
          stroke-linecap="round" stroke-linejoin="round">

          <!-- Dokumen -->
          <path d="M6 2h9l4 4v16H6a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2z" />

          <!-- Lipatan dokumen -->
          <path d="M15 2v5h5" />

          <!-- Garis materi -->
          <path d="M8 11h8" />
          <path d="M8 15h8" />
          <path d="M8 19h5" />
        </svg>
      </div>
      <span class="text-xs font-mono px-2 py-1 rounded-full bg-amber/15 text-ink">Pertemuan Aktif</span>
    </div>
    <p class="font-display text-3xl font-semibold mt-5">{{ number_format($totalMahasiswa, 0, ',', '.') }}</p>
    <p class="text-sm text-ink/55 mt-1">Pertemuan Aktif</p>
  </div>
  <div class="bg-white border border-line rounded-2xl p-6">
    <div class="flex items-center justify-between">
      <div class="w-11 h-11 rounded-lg bg-amber/15 flex items-center justify-center">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none"
          stroke="#1D4ED8" stroke-width="1.8"
          stroke-linecap="round" stroke-linejoin="round">

          <!-- Clipboard -->
          <rect x="5" y="4" width="14" height="18" rx="2" />

          <!-- Bagian atas clipboard -->
          <path d="M9 4V2h6v2" />

          <!-- Checklist -->
          <path d="M8 10l1.5 1.5L12 9" />
          <path d="M13.5 10H17" />

          <path d="M8 15l1.5 1.5L12 14" />
          <path d="M13.5 15H17" />
        </svg>
      </div>
      <span class="text-xs font-mono px-2 py-1 rounded-full bg-amber/15 text-ink">Tugas & Kuis</span>
    </div>
    <p class="font-display text-3xl font-semibold mt-5">{{ number_format($totalMahasiswa, 0, ',', '.') }}</p>
    <p class="text-sm text-ink/55 mt-1">Tugas & Kuis</p>
  </div>
  <div class="bg-white border border-line rounded-2xl p-6">
    <div class="flex items-center justify-between">
      <div class="w-11 h-11 rounded-lg bg-amber/15 flex items-center justify-center">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none"
          stroke="#1D4ED8" stroke-width="1.8"
          stroke-linecap="round" stroke-linejoin="round">

          <!-- Kepala -->
          <circle cx="12" cy="8" r="3.5" />

          <!-- Badan -->
          <path d="M5 21a7 7 0 0 1 14 0" />
        </svg>
      </div>
      <span class="text-xs font-mono px-2 py-1 rounded-full bg-amber/15 text-ink">Siswa Terdaftar</span>
    </div>
    <p class="font-display text-3xl font-semibold mt-5">{{ number_format($totalMahasiswa, 0, ',', '.') }}</p>
    <p class="text-sm text-ink/55 mt-1">Siswa Terdaftar</p>
  </div>
</div>
@endsection