<?php

namespace App\Http\Controllers;


use App\Models\Kelas;
use App\Models\SesiAbsensi;
use App\Models\Absensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class SesiAbsensiController extends Controller
{
    public function store(Request $request, $pengajaran)
    {
        $request->validate([
            'pertemuan_ke' => 'required|integer|min:1',
            'judul' => 'nullable|string|max:255',
            'durasi_menit' => 'required|integer|min:1|max:180',
        ]);

        $kelas = Kelas::findOrFail($pengajaran);

        $lecturer = Auth::user()->lecturer;

        if (!$lecturer) {
            abort(403, 'Akun ini tidak terdaftar sebagai dosen.');
        }

        $mengajar = $kelas->pengajaranDosen()
            ->where('dosen_id', $lecturer->id)
            ->exists();

        if (!$mengajar) {
            abort(403, 'Anda tidak mengajar di kelas ini.');
        }

        $pertemuanKe = (int) $request->pertemuan_ke;
        $durasiMenit = (int) $request->durasi_menit;

        $dibukaPada = now();

        $sesi = SesiAbsensi::create([
            'kelas_id' => $kelas->id,
            'dosen_id' => $lecturer->id,
            'pertemuan_ke' => $pertemuanKe,
            'judul' => $request->judul,
            'token' => Str::random(40),
            'durasi_menit' => $durasiMenit,
            'dibuka_pada' => $dibukaPada,
            'ditutup_pada' => $dibukaPada->copy()->addMinutes($durasiMenit),
        ]);

        return redirect()
            ->route('lecturer.absensi.show', $sesi->id)
            ->with('success', 'Sesi absensi dibuka.');
    }

    public function show(SesiAbsensi $sesi)
    {
        return view('lecturer.absensi.show', compact('sesi'));
    }

    public function tutup(SesiAbsensi $sesi)
    {
        $sesi->update(['ditutup_pada' => now()]);

        return back()->with('success', 'Sesi absensi ditutup.');
    }

    public function count(SesiAbsensi $sesi)
    {
        return response()->json([
            'total' => $sesi->absensi()->count(),
        ]);
    }

    public function rekap(SesiAbsensi $sesi)
    {
        $sesi->load(['kelas.matakuliah', 'kelas.mahasiswa.user', 'absensi']);

        return view('lecturer.absensi.rekap', compact('sesi'));
    }
    public function updateStatus(Request $request, SesiAbsensi $sesi)
    {
        $data = $request->validate([
            'status'     => ['required', 'array'],
            'status.*'   => ['required', 'in:izin,sakit,alpha'],
            'catatan'    => ['nullable', 'array'],
            'catatan.*'  => ['nullable', 'string', 'max:255'],
        ]);

        $sesi->load(['kelas.mahasiswa', 'absensi']);

        $idMahasiswaKelas = $sesi->kelas->mahasiswa->pluck('id')->all();
        $absensiExisting  = $sesi->absensi->keyBy('mahasiswa_id');

        DB::transaction(function () use ($data, $sesi, $idMahasiswaKelas, $absensiExisting) {
            foreach ($data['status'] as $mahasiswaId => $status) {
                $mahasiswaId = (int) $mahasiswaId;

                // Abaikan mahasiswa yang bukan anggota kelas ini
                if (! in_array($mahasiswaId, $idMahasiswaKelas, true)) {
                    continue;
                }

                $existing = $absensiExisting->get($mahasiswaId);

                // Mahasiswa yang sudah scan QR tidak boleh diubah
                if ($existing && $existing->waktu_absen) {
                    continue;
                }

                // Alpha = tidak ada baris di tabel absensi
                if ($status === 'alpha') {
                    $existing?->delete();
                    continue;
                }

                Absensi::updateOrCreate(
                    [
                        'sesi_absensi_id' => $sesi->id,
                        'mahasiswa_id'    => $mahasiswaId,
                    ],
                    [
                        'status'      => $status,
                        'catatan'     => $data['catatan'][$mahasiswaId] ?? null,
                        'waktu_absen' => null,
                    ]
                );
            }
        });

        return redirect()
            ->route('lecturer.absensi.rekap', $sesi)
            ->with('success', 'Rekap kehadiran berhasil diperbarui.');
    }
}
