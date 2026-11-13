<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Tugas;
use App\Models\TugasJawaban;
use App\Models\TugasJawabanFile;
use App\Models\PengajaranMahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class TugasController extends Controller
{
    public function show(Tugas $tugas)
    {
        $this->authorizeTugas($tugas);
        $tugas->load('files', 'pengajaranDosen');

        $mahasiswaId = auth()->user()->student->id;

        $jawabanSaya = TugasJawaban::with('files')
            ->where('tugas_id', $tugas->id)
            ->where('mahasiswa_id', $mahasiswaId)
            ->first();

        return view('student.tugas.show', compact('tugas', 'jawabanSaya'));
    }



    public function submit(Request $request, Tugas $tugas)
    {
        $this->authorizeTugas($tugas);

        // Tolak di server jika deadline sudah lewat
        if ($tugas->deadline && now()->greaterThan($tugas->deadline)) {
            return redirect()
                ->route('student.tugas.show', $tugas)
                ->withErrors(['files' => 'Batas pengumpulan sudah berakhir.']);
        }

        $student = auth()->user()->student;

        if (!$student) {
            abort(403, 'Akun ini tidak terdaftar sebagai siswa.');
        }

        // Rapikan catatan: trim, ubah string kosong menjadi null
        $request->merge([
            'catatan_siswa' => filled($request->input('catatan_siswa'))
                ? trim($request->input('catatan_siswa'))
                : null,
        ]);

        $request->validate([
            'files'         => 'nullable|array|max:20',
            'files.*'       => 'file|mimes:pdf,jpg,jpeg,png|max:10240', // max 10MB/file
            'catatan_siswa' => 'nullable|string|max:5000',
        ], [
            'files.*.mimes'     => 'File harus berformat PDF, JPG, atau PNG.',
            'files.*.max'       => 'Ukuran tiap file maksimal 10 MB.',
            'files.*.uploaded'  => 'File gagal diunggah. Coba lagi.',
            'catatan_siswa.max' => 'Catatan maksimal 5000 karakter.',
        ]);

        $uploadedFiles = array_values(array_filter($request->file('files') ?? []));
        $adaFileBaru   = count($uploadedFiles) > 0;
        $adaCatatan    = filled($request->input('catatan_siswa'));

        // Jawaban lama (jika ini kumpul ulang)
        $jawabanLama = TugasJawaban::with('files')
            ->where('tugas_id', $tugas->id)
            ->where('mahasiswa_id', $student->id)
            ->first();

        $adaFileLama = $jawabanLama && $jawabanLama->files->isNotEmpty();

        // Minimal salah satu harus ada: file baru, file lama, atau catatan
        if (!$adaFileBaru && !$adaCatatan && !$adaFileLama) {
            return back()
                ->withInput()
                ->withErrors(['files' => 'Isi catatan atau unggah minimal satu file.']);
        }

        $newPaths = [];   // dibersihkan jika transaksi gagal
        $oldPaths = [];   // dihapus dari disk setelah commit

        try {
            DB::transaction(function () use (
                $request,
                $tugas,
                $student,
                $uploadedFiles,
                $adaFileBaru,
                &$newPaths,
                &$oldPaths
            ) {
                $jawaban = TugasJawaban::firstOrNew([
                    'tugas_id'     => $tugas->id,
                    'mahasiswa_id' => $student->id,
                ]);

                $jawaban->waktu_submit  = now();
                $jawaban->catatan_siswa = $request->input('catatan_siswa');
                $jawaban->status        = 'menunggu_koreksi';

                // reset koreksi lama kalau ini resubmit
                $jawaban->skor            = null;
                $jawaban->catatan_koreksi = null;
                $jawaban->dikoreksi_oleh  = null;
                $jawaban->dikoreksi_at    = null;
                $jawaban->save();

                // Ganti file hanya jika ada file baru
                if ($adaFileBaru) {
                    foreach ($jawaban->files as $oldFile) {
                        $oldPaths[] = $oldFile->file_path;
                        $oldFile->delete();
                    }

                    foreach ($uploadedFiles as $i => $file) {
                        $ext  = strtolower($file->guessExtension() ?: $file->getClientOriginalExtension());
                        $type = $ext === 'pdf' ? 'pdf' : 'foto';
                        $path = $file->store('tugas-jawaban', 'public');

                        $newPaths[] = $path;

                        TugasJawabanFile::create([
                            'tugas_jawaban_id' => $jawaban->id,
                            'file_path'        => $path,
                            'file_type'        => $type,
                            'urutan'           => $i + 1,
                        ]);
                    }
                }
            });
        } catch (\Throwable $e) {
            Storage::disk('public')->delete($newPaths);

            report($e);

            return back()
                ->withInput()
                ->withErrors(['files' => 'Gagal menyimpan jawaban. Silakan coba lagi.']);
        }

        // transaksi sudah commit, aman menghapus file lama dari disk
        Storage::disk('public')->delete($oldPaths);

        return redirect()
            ->route('student.tugas.show', $tugas)
            ->with('success', 'Jawaban berhasil dikumpulkan.');
    }
    private function authorizeTugas(Tugas $tugas): void
    {
        $student = auth()->user()->student;
        abort_unless($student, 403, 'Akun ini tidak terdaftar sebagai mahasiswa.');

        $tugas->loadMissing('pengajaranDosen');
        abort_unless(
            PengajaranMahasiswa::where('kelas_id', $tugas->pengajaranDosen->kelas_id)
                ->where('mahasiswa_id', $student->id)
                ->exists(),
            403,
            'Kamu tidak terdaftar pada kelas tugas ini.'
        );
    }
}
