@extends('lecturer.app-lecturer')

@section('ketjudul')
Tambah materi interaktif
@endsection

@section('judul')
Materi Interaktif (HTML)
@endsection

@section('content')
<div class="mx-auto max-w-2xl rounded-2xl border border-line bg-white p-6 shadow-sm">

    <h2 class="font-display text-lg font-semibold text-ink">Upload Materi Interaktif</h2>
    <p class="mt-1 text-sm text-ink/50">
        Unggah satu file <code>.html</code> yang sudah berisi HTML, CSS, dan JavaScript
        (library seperti Three.js boleh dimuat lewat CDN).
    </p>

    <form method="POST" action="{{ route('materi-html.store', $pengajaranDosen->id) }}"
        enctype="multipart/form-data" class="mt-6 space-y-4">
        @csrf

        <div>
            <label class="block text-sm font-medium text-ink">Judul</label>
            <input type="text" name="judul" value="{{ old('judul') }}" required
                placeholder="Misal: Luas Permukaan Bola"
                class="mt-1.5 w-full rounded-lg border border-line px-3.5 py-2.5 text-sm text-ink outline-none transition focus:border-ink">
            @error('judul') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-ink">
                Deskripsi <span class="text-ink/40">(opsional)</span>
            </label>
            <textarea name="deskripsi" rows="3"
                class="mt-1.5 w-full rounded-lg border border-line px-3.5 py-2.5 text-sm text-ink outline-none transition focus:border-ink">{{ old('deskripsi') }}</textarea>
            @error('deskripsi') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-ink">File HTML</label>
            <input type="file" name="file" accept=".html,.htm" required
                class="mt-1.5 block w-full text-sm text-ink file:mr-3 file:rounded-lg file:border-0
                       file:bg-ink file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white">
            <p class="mt-1 text-xs text-ink/40">Maksimal 10 MB.</p>
            @error('file') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>

        <div class="flex justify-end gap-3 pt-2">
            <a href="{{ url()->previous() }}"
                class="rounded-lg border border-line px-4 py-2.5 text-sm font-semibold text-ink transition hover:bg-paper">
                Batal
            </a>
            <button type="submit"
                class="rounded-lg bg-ink px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-primaryDark">
                Simpan
            </button>
        </div>
    </form>
</div>
@endsection