<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Kelas;
use App\Models\PengajaranDosen;
use App\Models\Pertemuan;
use App\Models\SesiAbsensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AbsensiManualController extends Controller
{
    public function create(Request $request, $kelasId)
    {
        $kelas = Kelas::with(['matakuliah', 'mahasiswa.user'])->findOrFail($kelasId);

        $this->pastikanDosenMengajar($kelas);

        $pertemuan = Pertemuan::where('kelas_id', $kelas->id)
            ->findOrFail($request->query('pertemuan'));

        // Sesi manual yang sudah ada untuk pertemuan ini (untuk prefill/edit)
        $sesi = SesiAbsensi::where('kelas_id', $kelas->id)
            ->where('pertemuan_ke', $pertemuan->pertemuan_ke)
            ->where('judul', 'like', '[Manual]%')
            ->latest()
            ->first();

        $existing = $sesi
            ? Absensi::where('sesi_absensi_id', $sesi->id)->get()->keyBy('mahasiswa_id')
            : collect();

        return view('lecturer.absensi-manual', [
            'pengajaran' => $kelas,   // view memakai nama $pengajaran
            'pertemuan'  => $pertemuan,
            'sesi'       => $sesi,
            'existing'   => $existing,
        ]);
    }

    public function store(Request $request, $kelasId)
    {
        $kelas = Kelas::with('mahasiswa')->findOrFail($kelasId);

        $lecturer = $this->pastikanDosenMengajar($kelas);

        $data = $request->validate([
            'pertemuan_id' => ['required', 'integer'],
            'pertemuan_ke' => ['required', 'integer', 'min:1'],
            'judul'        => ['nullable', 'string', 'max:200'],
            'status'       => ['required', 'array'],
            'status.*'     => ['required', Rule::in(['hadir', 'izin', 'sakit', 'alpha'])],
            'catatan'      => ['nullable', 'array'],
            'catatan.*'    => ['nullable', 'string', 'max:500'],
        ]);

        $validIds = $kelas->mahasiswa->pluck('id')->all();

        DB::transaction(function () use ($data, $kelas, $lecturer, $validIds) {
            $judul = '[Manual] ' . ($data['judul'] ?: 'Pertemuan ' . $data['pertemuan_ke']);

            $sesi = SesiAbsensi::where('kelas_id', $kelas->id)
                ->where('pertemuan_ke', $data['pertemuan_ke'])
                ->where('judul', 'like', '[Manual]%')
                ->first();

            if ($sesi) {
                $sesi->update(['judul' => $judul]);
            } else {
                $sesi = SesiAbsensi::create([
                    'kelas_id'     => $kelas->id,
                    'dosen_id'     => $lecturer->id,
                    'pertemuan_ke' => $data['pertemuan_ke'],
                    'judul'        => $judul,
                    'token'        => Str::random(64),
                    'durasi_menit' => 0,
                    'dibuka_pada'  => now(),
                    'ditutup_pada' => now(),
                ]);
            }

            foreach ($data['status'] as $mahasiswaId => $status) {
                if (!in_array((int) $mahasiswaId, $validIds, true)) {
                    continue;
                }

                Absensi::updateOrCreate(
                    [
                        'sesi_absensi_id' => $sesi->id,
                        'mahasiswa_id'    => $mahasiswaId,
                    ],
                    [
                        'status'      => $status,
                        'waktu_absen' => $status === 'hadir' ? now() : null,
                        'catatan'     => $data['catatan'][$mahasiswaId] ?? null,
                    ]
                );
            }
        });

        return redirect()
            ->route('pengajaran.show', [
                'id'        => $kelas->id,
                'pertemuan' => $data['pertemuan_id'],
            ])
            ->with('success', 'Absensi manual berhasil disimpan.');
    }

    /**
     * Pastikan user login adalah dosen yang mengajar di kelas ini.
     * Mengembalikan model lecturer.
     */
    private function pastikanDosenMengajar(Kelas $kelas)
    {
        $lecturer = Auth::user()->lecturer;

        if (!$lecturer) {
            abort(403, 'Akun ini tidak terdaftar sebagai dosen.');
        }

        $mengajar = PengajaranDosen::where('kelas_id', $kelas->id)
            ->where('dosen_id', $lecturer->id)
            ->exists();

        if (!$mengajar) {
            abort(403, 'Anda tidak mengajar di kelas ini.');
        }

        return $lecturer;
    }
}
