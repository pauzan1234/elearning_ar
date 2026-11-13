@extends('lecturer.app-lecturer-create-materi')

@section('ketjudul')
Absensi Manual
@endsection

@section('judul')
{{ $pengajaran->matakuliah->nama_mk }}
@endsection

@section('content')

<div class="mb-6">
    <a href="{{ route('pengajaran.show', ['id' => $pengajaran->id, 'pertemuan' => $pertemuan->id]) }}"
        class="text-sm font-medium text-ink/60 hover:text-ink">
        ← Kembali ke Pertemuan {{ $pertemuan->pertemuan_ke }}
    </a>
</div>

@if ($errors->any())
<div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
    Terjadi kesalahan, periksa kembali isian kamu.
</div>
@endif

<form method="POST"
    action="{{ route('lecturer.absensi.manual.store', $pengajaran->id) }}"
    x-data="{
        setAll(status) {
            document.querySelectorAll('input[type=radio][value=' + status + ']').forEach(r => r.checked = true);
        }
    }">
    @csrf
    <input type="hidden" name="pertemuan_ke" value="{{ $pertemuan->pertemuan_ke }}">
    <input type="hidden" name="pertemuan_id" value="{{ $pertemuan->id }}">

    <div class="overflow-hidden rounded-2xl border border-line bg-white shadow-sm">

        {{-- Header --}}
        <div class="flex flex-col justify-between gap-4 border-b border-line p-6 lg:flex-row lg:items-center">
            <div>
                <h2 class="font-display text-lg font-semibold text-ink">
                    Absensi Manual — Pertemuan {{ $pertemuan->pertemuan_ke }}
                </h2>
                <p class="mt-1 text-sm text-ink/50">
                    Pilih status kehadiran untuk setiap siswa.
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <span class="text-xs font-medium text-ink/50">Tandai semua:</span>
                <button type="button" @click="setAll('hadir')"
                    class="rounded-lg border border-green-200 bg-green-50 px-3 py-1.5 text-xs font-semibold text-green-700 hover:bg-green-100">
                    Hadir
                </button>
                <button type="button" @click="setAll('izin')"
                    class="rounded-lg border border-blue-200 bg-blue-50 px-3 py-1.5 text-xs font-semibold text-blue-700 hover:bg-blue-100">
                    Izin
                </button>
                <button type="button" @click="setAll('sakit')"
                    class="rounded-lg border border-amber-200 bg-amber-50 px-3 py-1.5 text-xs font-semibold text-amber-700 hover:bg-amber-100">
                    Sakit
                </button>
                <button type="button" @click="setAll('alpha')"
                    class="rounded-lg border border-red-200 bg-red-50 px-3 py-1.5 text-xs font-semibold text-red-700 hover:bg-red-100">
                    Alpha
                </button>
            </div>
        </div>

        {{-- Judul/topik --}}
        <div class="border-b border-line p-6">
            <label class="block text-sm font-medium text-ink">
                Judul / Topik <span class="text-ink/40">(opsional)</span>
            </label>
            <input type="text" name="judul"
                value="{{ old('judul', $sesi ? trim(str_replace('[Manual]', '', $sesi->judul)) : '') }}"
                placeholder="Misal: Bangun Ruang Sisi Datar"
                class="mt-1.5 w-full rounded-lg border border-line px-3.5 py-2.5 text-sm text-ink outline-none transition focus:border-ink">
        </div>

        {{-- Daftar siswa --}}
        <div class="divide-y divide-line">
            @forelse ($pengajaran->mahasiswa as $mhs)
            @php
            $current = old('status.' . $mhs->id, optional($existing->get($mhs->id))->status ?? 'hadir');
            $opsi = [
            'hadir' => ['label' => 'Hadir', 'cls' => 'peer-checked:bg-green-600 peer-checked:text-white peer-checked:border-green-600'],
            'izin' => ['label' => 'Izin', 'cls' => 'peer-checked:bg-blue-600 peer-checked:text-white peer-checked:border-blue-600'],
            'sakit' => ['label' => 'Sakit', 'cls' => 'peer-checked:bg-amber-500 peer-checked:text-white peer-checked:border-amber-500'],
            'alpha' => ['label' => 'Alpha', 'cls' => 'peer-checked:bg-red-600 peer-checked:text-white peer-checked:border-red-600'],
            ];
            @endphp

            <div class="flex flex-col gap-3 p-5 lg:flex-row lg:items-center">

                {{-- Identitas --}}
                <div class="flex min-w-0 flex-1 items-center gap-4">
                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-paper text-sm font-semibold text-ink">
                        {{ strtoupper(substr($mhs->user->name ?? 'M', 0, 1)) }}
                    </div>
                    <div class="min-w-0">
                        <h3 class="truncate text-sm font-semibold text-ink">{{ $mhs->user->name ?? '-' }}</h3>
                        <p class="mt-1 text-xs text-ink/50">NIM: {{ $mhs->nim ?? '-' }}</p>
                    </div>
                </div>

                {{-- Pilihan status (kanan) --}}
                <div class="flex flex-wrap items-center gap-2">
                    @foreach ($opsi as $value => $o)
                    <label class="cursor-pointer">
                        <input type="radio" class="peer sr-only"
                            name="status[{{ $mhs->id }}]" value="{{ $value }}"
                            {{ $current === $value ? 'checked' : '' }}>
                        <span class="inline-block rounded-lg border border-line bg-white px-3.5 py-2 text-xs font-semibold
                                     text-ink/60 transition hover:bg-paper {{ $o['cls'] }}">
                            {{ $o['label'] }}
                        </span>
                    </label>
                    @endforeach
                </div>

                {{-- Catatan --}}
                <input type="text" name="catatan[{{ $mhs->id }}]"
                    value="{{ old('catatan.' . $mhs->id, optional($existing->get($mhs->id))->catatan) }}"
                    placeholder="Catatan (opsional)"
                    class="w-full rounded-lg border border-line px-3 py-2 text-xs text-ink outline-none transition focus:border-ink lg:w-48">
            </div>
            @empty
            <div class="p-6">
                <div class="rounded-xl border border-dashed border-line bg-paper p-8 text-center">
                    <p class="text-sm text-ink/50">Belum ada siswa yang mengambil kelas ini.</p>
                </div>
            </div>
            @endforelse
        </div>

        {{-- Footer --}}
        <div class="flex justify-end gap-3 border-t border-line p-6">
            <a href="{{ route('pengajaran.show', ['id' => $pengajaran->id, 'pertemuan' => $pertemuan->id]) }}"
                class="rounded-lg border border-line px-4 py-2.5 text-sm font-semibold text-ink transition hover:bg-paper">
                Batal
            </a>
            <button type="submit"
                class="rounded-lg bg-ink px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-primaryDark">
                Simpan Absensi
            </button>
        </div>
    </div>
</form>

@endsection