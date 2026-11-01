@extends('lecturer.app-lecturer-create-materi')

@section('judul')
Rekap Absensi - Pertemuan {{ $sesi->pertemuan_ke }}
@endsection

@section('content')
<div class="bg-paper min-h-screen">
    <div class="mx-auto max-w-5xl px-6 py-10">

        {{-- Header --}}
        <div class="mb-6 flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
            <div>
                <p class="text-sm text-ink/50">
                    {{ $sesi->kelas->matakuliah->nama_mk }} • Pertemuan {{ $sesi->pertemuan_ke }}
                </p>
                <h1 class="font-display text-xl font-semibold text-ink">
                    Rekap Kehadiran
                </h1>
            </div>

            <div class="text-sm text-ink/50">
                Dibuka: {{ $sesi->dibuka_pada->format('d M Y, H:i') }}
                — Ditutup: {{ $sesi->ditutup_pada->format('H:i') }}
            </div>
        </div>

        {{-- Flash message --}}
        @if (session('success'))
        <div class="mb-4 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('success') }}
        </div>
        @endif

        @if ($errors->any())
        <div class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-600">
            Terjadi kesalahan saat menyimpan. Periksa kembali isian Anda.
        </div>
        @endif

        {{-- Ringkasan --}}
        @php
        $totalMahasiswa = $sesi->kelas->mahasiswa->count();
        $totalHadir = $sesi->absensi->where('status', 'hadir')->count();
        $totalIzin = $sesi->absensi->where('status', 'izin')->count();
        $totalSakit = $sesi->absensi->where('status', 'sakit')->count();
        $totalAlpha = $totalMahasiswa - $totalHadir - $totalIzin - $totalSakit;
        @endphp

        <div class="mb-6 grid grid-cols-2 gap-4 sm:grid-cols-5">
            <div class="rounded-xl border border-line bg-white p-4 text-center">
                <p class="text-2xl font-bold text-ink">{{ $totalMahasiswa }}</p>
                <p class="text-xs text-ink/50">Total Siswa</p>
            </div>
            <div class="rounded-xl border border-line bg-white p-4 text-center">
                <p class="text-2xl font-bold text-green-600">{{ $totalHadir }}</p>
                <p class="text-xs text-ink/50">Hadir</p>
            </div>
            <div class="rounded-xl border border-line bg-white p-4 text-center">
                <p class="text-2xl font-bold text-blue-600">{{ $totalIzin }}</p>
                <p class="text-xs text-ink/50">Izin</p>
            </div>
            <div class="rounded-xl border border-line bg-white p-4 text-center">
                <p class="text-2xl font-bold text-amber-600">{{ $totalSakit }}</p>
                <p class="text-xs text-ink/50">Sakit</p>
            </div>
            <div class="rounded-xl border border-line bg-white p-4 text-center">
                <p class="text-2xl font-bold text-red-500">{{ $totalAlpha }}</p>
                <p class="text-xs text-ink/50">Alpha</p>
            </div>
        </div>

        {{-- Tabel (form massal) --}}
        <form method="POST" action="{{ route('lecturer.absensi.update-status', $sesi) }}">
            @csrf
            @method('PUT')

            <div class="overflow-x-auto rounded-2xl border border-line bg-white shadow-sm">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-line bg-paper text-left text-xs font-semibold uppercase text-ink/50">
                            <th class="px-5 py-3">Nama</th>
                            <th class="px-5 py-3">NIM</th>
                            <th class="px-5 py-3">Status</th>
                            <th class="px-5 py-3">Waktu Absen</th>
                            <th class="px-5 py-3">Catatan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-line">
                        @forelse ($sesi->kelas->mahasiswa as $mhs)
                        @php
                        $absen = $sesi->absensi->firstWhere('mahasiswa_id', $mhs->id);
                        $sudahScan = $absen && $absen->waktu_absen;
                        $statusSekarang = old("status.{$mhs->id}", $absen->status ?? 'alpha');
                        $catatanSekarang = old("catatan.{$mhs->id}", $absen->catatan ?? '');
                        @endphp
                        <tr>
                            <td class="px-5 py-3 font-medium text-ink">
                                {{ $mhs->user->name ?? '-' }}
                            </td>
                            <td class="px-5 py-3 text-ink/60">
                                {{ $mhs->nim ?? '-' }}
                            </td>

                            {{-- Status --}}
                            <td class="px-5 py-3">
                                @if ($sudahScan)
                                {{-- Sudah scan QR: terkunci --}}
                                <span class="rounded-full bg-green-50 px-2.5 py-1 text-xs font-semibold text-green-700">
                                    Hadir
                                </span>
                                @else
                                <select name="status[{{ $mhs->id }}]"
                                    class="rounded-lg border border-line bg-white px-2.5 py-1.5 text-xs font-semibold text-ink focus:outline-none focus:ring-2 focus:ring-ink/20">
                                    <option value="alpha" @selected($statusSekarang==='alpha' )>Alpha</option>
                                    <option value="izin" @selected($statusSekarang==='izin' )>Izin</option>
                                    <option value="sakit" @selected($statusSekarang==='sakit' )>Sakit</option>
                                </select>
                                @error("status.{$mhs->id}")
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                                @endif
                            </td>

                            {{-- Waktu absen --}}
                            <td class="px-5 py-3 text-ink/60">
                                {{ $absen?->waktu_absen?->format('H:i:s') ?? '-' }}
                            </td>

                            {{-- Catatan --}}
                            <td class="px-5 py-3">
                                @if ($sudahScan)
                                <span class="text-ink/40">-</span>
                                @else
                                <input type="text" name="catatan[{{ $mhs->id }}]" value="{{ $catatanSekarang }}"
                                    maxlength="255" placeholder="Opsional, mis. surat dokter"
                                    class="w-full min-w-[160px] rounded-lg border border-line bg-white px-2.5 py-1.5 text-xs text-ink placeholder:text-ink/30 focus:outline-none focus:ring-2 focus:ring-ink/20">
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-5 py-8 text-center text-ink/50">
                                Belum ada siswa terdaftar di kelas ini.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($sesi->kelas->mahasiswa->isNotEmpty())
            <div class="mt-4 flex items-center justify-between">
                <p class="text-xs text-ink/50">
                    Mahasiswa yang sudah scan QR tidak dapat diubah.
                </p>
                <button type="submit"
                    class="rounded-xl bg-ink px-5 py-2.5 text-sm font-semibold text-white transition hover:opacity-90">
                    Simpan Perubahan
                </button>
            </div>
            @endif
        </form>

    </div>
</div>
@endsection