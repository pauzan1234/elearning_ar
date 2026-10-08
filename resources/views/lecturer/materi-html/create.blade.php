@extends('lecturer.app-lecturer')

@section('ketjudul')
Pembelajaran oleh
{{ $pengajaranDosen->lecturer->user->name ?? '-' }}
@endsection

@section('judul')
Tambah Materi Interaktif (HTML)
@endsection

@section('content')
<div class="max-w-2xl">

    <a href="{{ route('pengajaran.show', $pengajaranDosen->id) }}"
        class="inline-flex items-center gap-1.5 text-sm font-medium text-ink/60 hover:text-ink mb-6">
        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
        Kembali ke Pembelajaran
    </a>

    <div class="rounded-2xl border border-line bg-white p-6 shadow-sm">

        <h2 class="font-display text-lg font-semibold text-ink">Upload Materi Interaktif</h2>
        <p class="mt-1 text-sm text-ink/50">
            Unggah satu file <code>.html</code> yang sudah berisi HTML, CSS, dan JavaScript
            (library seperti Three.js boleh dimuat lewat CDN).
        </p>

        <form method="POST" action="{{ route('materi-html.store', $pengajaranDosen->id) }}"
            enctype="multipart/form-data" class="mt-6 space-y-5">
            @csrf

            {{-- Pertemuan --}}
            <div>
                <label class="block text-sm font-medium text-ink">Pertemuan</label>
                <select name="materi_id" required
                    class="mt-1.5 w-full rounded-lg border border-line px-3.5 py-2.5 text-sm
                           text-ink outline-none transition focus:border-teal focus:ring-2 focus:ring-teal/20">
                    <option value="" disabled selected>Pilih pertemuan</option>
                    @forelse ($pertemuan as $p)
                    <option value="{{ $p->id }}" @selected(old('materi_id')==$p->id)>
                        {{ $p->judul }}
                    </option>
                    @empty
                    <option value="" disabled>Belum ada pertemuan, tambahkan materi biasa dulu</option>
                    @endforelse
                </select>
                @error('materi_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- File HTML --}}
            <div>
                <label class="block text-sm font-medium text-ink">File HTML</label>
                <input type="file" name="file" accept=".html,.htm" required
                    class="mt-1.5 w-full text-sm text-ink/70 border border-line rounded-lg px-3.5 py-2.5
                           file:mr-3 file:px-3 file:py-1.5 file:rounded-md file:border-0
                           file:bg-teal/10 file:text-teal file:text-sm file:font-medium">
                <p class="mt-1.5 text-xs text-ink/45">Maksimal 10 MB.</p>
                @error('file') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="flex justify-end gap-3 pt-2">
                <a href="{{ route('pengajaran.show', $pengajaranDosen->id) }}"
                    class="rounded-lg border border-line px-4 py-2.5 text-sm font-semibold text-ink
                           transition hover:bg-paper">
                    Batal
                </a>
                <button type="submit"
                    class="rounded-lg bg-ink px-5 py-2.5 text-sm font-semibold text-white
                           transition hover:bg-ink/90">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection