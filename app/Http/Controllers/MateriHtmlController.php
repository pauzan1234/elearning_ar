<?php

namespace App\Http\Controllers;

use App\Models\Materi;
use App\Models\MateriFile;
use App\Models\PengajaranDosen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MateriHtmlController extends Controller
{
    /** Form upload materi interaktif (HTML + JS) */
    public function create(PengajaranDosen $pengajaranDosen)
    {
        $this->authorizeDosen($pengajaranDosen);

        return view('lecturer.materi-html.create', compact('pengajaranDosen'));
    }

    /** Simpan materi + file html */
    public function store(Request $request, PengajaranDosen $pengajaranDosen)
    {
        $this->authorizeDosen($pengajaranDosen);

        $data = $request->validate([
            'judul'     => ['required', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string'],
            'file'      => ['required', 'file', 'mimes:html,htm', 'max:10240'], // 10 MB
        ]);

        $upload = $request->file('file');

        // Disimpan di disk PRIVATE (local), bukan public, supaya hanya
        // bisa dibuka lewat method file() di bawah (dengan header sandbox).
        $path = $upload->storeAs('materi-html', Str::uuid() . '.html', 'local');

        DB::transaction(function () use ($data, $pengajaranDosen, $path, $upload) {
            $materi = Materi::create([
                'pengajaran_id' => $pengajaranDosen->id,
                'judul'         => $data['judul'],
                'deskripsi'     => $data['deskripsi'] ?? null,
                'urutan'        => (Materi::where('pengajaran_id', $pengajaranDosen->id)->max('urutan') ?? 0) + 1,
            ]);

            $materi->files()->create([
                'tipe'      => MateriFile::TIPE_HTML,
                'file_path' => $path,
                'nama_asli' => $upload->getClientOriginalName(),
                'urutan'    => 1,
            ]);
        });

        // GANTI nama route ini dengan route halaman pembelajaran dosen milikmu
        return redirect()
            ->route('lecturer.pengajaran.show', $pengajaranDosen->id)
            ->with('success', 'Materi interaktif berhasil ditambahkan.');
    }

    /**
     * Sajikan file HTML ke iframe / tab baru.
     * Header CSP "sandbox" membuat halaman berjalan di origin terisolasi:
     * script tetap jalan, tapi tidak bisa menyentuh sesi/cookie aplikasi,
     * baik saat di-embed maupun dibuka langsung di tab baru.
     */
    public function file(MateriFile $materiFile)
    {
        abort_unless($materiFile->tipe === MateriFile::TIPE_HTML, 404);
        abort_unless(Storage::disk('local')->exists($materiFile->file_path), 404);

        // Materi disembunyikan: hanya dosen pemilik yang boleh membuka file-nya
        $materi = $materiFile->materi;
        if ($materi->is_hidden) {
            $lecturer = auth()->user()?->lecturer; // sesuaikan relasi User -> Lecturer
            $pemilik  = $lecturer && (int) $materi->pengajaran->dosen_id === (int) $lecturer->id;
            abort_unless($pemilik, 404);
        }

        return response()->file(
            Storage::disk('local')->path($materiFile->file_path),
            [
                'Content-Type'            => 'text/html; charset=UTF-8',
                'Content-Security-Policy' => 'sandbox allow-scripts allow-pointer-lock allow-fullscreen',
                'X-Content-Type-Options'  => 'nosniff',
            ]
        );
    }

    /**
     * Pastikan yang mengakses adalah dosen pemilik pengajaran ini.
     * SESUAIKAN dengan relasi auth milikmu.
     */
    private function authorizeDosen(PengajaranDosen $pengajaranDosen): void
    {
        $lecturer = auth()->user()?->lecturer; // sesuaikan relasi User -> Lecturer

        abort_unless(
            $lecturer && (int) $pengajaranDosen->dosen_id === (int) $lecturer->id,
            403
        );
    }
}
