<?php

namespace App\Http\Controllers;

use App\Models\Materi;
use App\Models\MateriFile;
use App\Models\PengajaranDosen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class MateriHtmlController extends Controller
{
    /** Form upload materi interaktif (HTML + JS) */

    public function create(Request $request, PengajaranDosen $pengajaranDosen)
    {
        $this->authorizeDosen($pengajaranDosen);

        $pertemuanId = $request->query('pertemuan');

        $materiList = Materi::where('pengajaran_id', $pengajaranDosen->id)
            ->when($pertemuanId, fn($q) => $q->where('pertemuan_id', $pertemuanId))
            ->orderBy('urutan')
            ->orderByDesc('created_at')
            ->get();

        return view('lecturer.materi-html.create', compact('pengajaranDosen', 'materiList', 'pertemuanId'));
    }
    public function store(Request $request, PengajaranDosen $pengajaranDosen)
    {
        abort_unless($pengajaranDosen->lecturer->user_id === Auth::id(), 403);

        $validated = $request->validate([
            // materi harus milik pengajaran ini (pertemuan ikut dari materi)
            'materi_id' => [
                'required',
                Rule::exists('materis', 'id')->where('pengajaran_id', $pengajaranDosen->id),
            ],
            'file' => ['required', 'file', 'mimes:html,htm', 'max:10240'], // 10MB
        ]);

        $upload = $request->file('file');

        $ext = strtolower($upload->getClientOriginalExtension());
        if (!in_array($ext, ['html', 'htm'])) {
            return back()->withErrors(['file' => 'File harus berformat .html atau .htm'])->withInput();
        }

        $materi = Materi::findOrFail($validated['materi_id']);

        // Disk PRIVATE (local), disajikan lewat method file() yang sudah ada
        $path = $upload->storeAs('materi-html', Str::uuid() . '.html', 'local');

        $materi->files()->create([
            'tipe'      => MateriFile::TIPE_HTML,
            'file_path' => $path,
            'nama_asli' => $upload->getClientOriginalName(),
            'urutan'    => ($materi->files()->max('urutan') ?? 0) + 1,
        ]);

        return redirect()
            ->route('pengajaran.show', ['id' => $pengajaranDosen->kelas_id, 'pertemuan' => $materi->pertemuan_id])
            ->with('success', 'Materi interaktif berhasil ditambahkan.');
    }
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
