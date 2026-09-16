@extends('lecturer.app-lecturer')

@section('ketjudul')
Mata Kuliah oleh
{{ $pengajaranDosen->lecturer->user->name ?? '-' }}
@endsection

@section('judul')
Tambah Materi AR
@endsection

@section('content')

<div class="max-w-2xl">

    <a href="{{ route('pengajaran.show', $pengajaranDosen->id) }}"
        class="inline-flex items-center gap-1.5 text-sm font-medium text-ink/60 hover:text-ink mb-6">
        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
        Kembali ke Mata Kuliah
    </a>

    <div class="rounded-2xl border border-line bg-white p-6 shadow-sm">

        <h2 class="font-display text-lg font-semibold text-ink">
            Tambah Materi AR
        </h2>
        <p class="mt-1 text-sm text-ink/50">
            Unggah model 3D untuk ditampilkan dalam mode Augmented Reality.
        </p>

        <form action="{{ route('materi-ar.store', $pengajaranDosen->id) }}" method="POST"
            enctype="multipart/form-data" class="mt-6 space-y-5">
            @csrf

            {{-- Pertemuan --}}
            <div>
                <label class="block text-sm font-medium text-ink">
                    Pertemuan
                </label>
                <select name="materi_id" required
                    class="mt-1.5 w-full rounded-lg border border-line px-3.5 py-2.5 text-sm
                           text-ink outline-none transition focus:border-teal focus:ring-2 focus:ring-teal/20">
                    <option value="" disabled selected>Pilih pertemuan</option>
                    @forelse ($pertemuan as $p)
                    <option value="{{ $p->id }}" @selected(old('materi_id')==$p->id)>
                        Pertemuan {{ $p->urutan }} — {{ $p->judul }}
                    </option>
                    @empty
                    <option value="" disabled>Belum ada pertemuan, tambahkan materi biasa dulu</option>
                    @endforelse
                </select>
                @error('materi_id')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Judul --}}
            <div>
                <label class="block text-sm font-medium text-ink">
                    Judul Objek AR
                </label>
                <input type="text" name="judul" value="{{ old('judul') }}" required
                    placeholder="Misal: Kubus dan Rumus Volumenya"
                    class="mt-1.5 w-full rounded-lg border border-line px-3.5 py-2.5 text-sm
                           text-ink outline-none transition focus:border-teal focus:ring-2 focus:ring-teal/20">
                @error('judul')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Deskripsi --}}
            <div>
                <label class="block text-sm font-medium text-ink">
                    Deskripsi <span class="text-ink/40">(opsional)</span>
                </label>
                <textarea name="deskripsi" rows="3"
                    class="mt-1.5 w-full rounded-lg border border-line px-3.5 py-2.5 text-sm
                           text-ink outline-none transition focus:border-teal focus:ring-2 focus:ring-teal/20">{{ old('deskripsi') }}</textarea>
                @error('deskripsi')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- File Model --}}
            <div>
                <label class="block text-sm font-medium text-ink">
                    File Model 3D (.glb / .gltf)
                </label>
                <input type="file" name="file_model" accept=".glb,.gltf" required
                    class="mt-1.5 w-full text-sm text-ink/70 border border-line rounded-lg px-3.5 py-2.5
                           file:mr-3 file:px-3 file:py-1.5 file:rounded-md file:border-0
                           file:bg-teal/10 file:text-teal file:text-sm file:font-medium">
                <p class="mt-1.5 text-xs text-ink/45">
                    Maks 20MB. Ekspor dari Blender, atau unduh model gratis dari Sketchfab / Poly Pizza (format glTF Binary).
                    Mahasiswa akan bisa menempatkan model ini langsung di ruang nyata lewat kamera HP — tidak perlu marker/kartu cetak.
                </p>
                @error('file_model')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Aksi --}}
            <div class="flex justify-end gap-3 pt-2">
                <a href="{{ route('pengajaran.show', $pengajaranDosen->id) }}"
                    class="rounded-lg border border-line px-4 py-2.5 text-sm font-semibold text-ink
                           transition hover:bg-paper">
                    Batal
                </a>
                <button type="submit"
                    class="rounded-lg bg-ink px-5 py-2.5 text-sm font-semibold text-white
                           transition hover:bg-ink/90">
                    Simpan Materi AR
                </button>
            </div>

        </form>

    </div>

</div>

@endsection