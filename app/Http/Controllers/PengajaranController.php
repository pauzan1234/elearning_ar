<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Pengajaran;
use App\Models\Student;
use App\Models\Kelas;
use App\Models\PengajaranDosen;
use App\Models\PengajaranMahasiswa;
use Illuminate\Support\Facades\Auth;
use App\Models\SesiAbsensi;
use App\Models\Tugas;
use App\Exports\RekapNilaiExport;
use App\Models\PengajuanKelas;
use App\Models\Pertemuan;
use App\Models\Quiz;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class PengajaranController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | TAMBAH MATA KULIAH / DOSEN
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $request->validate([
            'lecturer_id' => [
                'required',
                'exists:lecturer,id',
            ],

            'kode_mk' => [
                'required',
                'exists:matakuliah,kode_mk',
            ],
        ]);

        try {

            $hasil = DB::transaction(function () use ($request) {

                /*
                 * Cari kelas berdasarkan kode MK
                 */
                $kelas = Kelas::where(
                    'kode_mk',
                    $request->kode_mk
                )->first();


                /*
                 * Kalau kelas belum ada,
                 * buat kelas A
                 */
                if (!$kelas) {

                    $jumlahKelas = Kelas::where(
                        'kode_mk',
                        $request->kode_mk
                    )->count();

                    $kodeKelas = chr(65 + $jumlahKelas);

                    $kelas = Kelas::create([
                        'kode_mk'    => $request->kode_mk,
                        'kode_kelas' => $kodeKelas,
                    ]);
                }


                /*
                 * Cek dosen sudah ada atau belum
                 */
                $sudahAda = PengajaranDosen::where(
                    'dosen_id',
                    $request->lecturer_id
                )
                    ->where(
                        'kelas_id',
                        $kelas->id
                    )
                    ->exists();


                if ($sudahAda) {

                    return [
                        'success' => false,
                        'message' =>
                        'Dosen tersebut sudah terdaftar pada kelas ini.',
                    ];
                }


                /*
                 * Simpan dosen ke kelas
                 */
                PengajaranDosen::create([
                    'dosen_id' => $request->lecturer_id,
                    'kelas_id' => $kelas->id,
                ]);


                return [
                    'success' => true,
                    'message' =>
                    'Mata kuliah berhasil ditambahkan.',
                ];
            });


            return response()->json($hasil);
        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' =>
                'Gagal menyimpan data.',
                'error' =>
                $e->getMessage(),
            ], 500);
        }
    }


    /*
    |--------------------------------------------------------------------------
    | TAMPILKAN MATA KULIAH DOSEN
    |--------------------------------------------------------------------------
    */

    public function matakuliah($lecturer)
    {
        try {

            $pengajaranDosen = PengajaranDosen::with([
                'kelas.matakuliah'
            ])
                ->where('dosen_id', $lecturer)
                ->get();


            $data = $pengajaranDosen->map(function ($item) {

                return [

                    'kelas_id' =>
                    $item->kelas->id,

                    'kode_mk' =>
                    $item->kelas->matakuliah->kode_mk,

                    'nama_mk' =>
                    $item->kelas->matakuliah->nama_mk,

                    'sks' =>
                    $item->kelas->matakuliah->sks,

                    'kode_kelas' =>
                    $item->kelas->kode_kelas,

                ];
            });


            return response()->json($data);
        } catch (\Exception $e) {

            return response()->json([

                'message' =>
                'Gagal mengambil data mata kuliah.',

                'error' =>
                $e->getMessage(),

            ], 500);
        }
    }


    /*
    |--------------------------------------------------------------------------
    | HALAMAN PESERTA MATA KULIAH
    |--------------------------------------------------------------------------
    */

    public function show_mk()
    {
        $pengajaran = PengajaranDosen::with([

            'lecturer.user',

            'lecturer.prodi',

            'kelas.matakuliah',

        ])
            ->latest()
            ->get();


        return view(
            'admin.peserta-mk',
            compact('pengajaran')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | SEARCH MAHASISWA
    |--------------------------------------------------------------------------
    */

    public function searchStudents(Request $request, Kelas $kelas)
    {
        try {

            $search = trim($request->get('search', ''));

            $students = Student::with([
                'user',
                'prodi'
            ])
                ->when($search, function ($query) use ($search) {

                    $query->where(function ($q) use ($search) {

                        $q->where('nim', 'like', "%{$search}%")
                            ->orWhereHas('user', function ($user) use ($search) {

                                $user->where(
                                    'name',
                                    'like',
                                    "%{$search}%"
                                );
                            });
                    });
                })
                ->orderBy('nim')
                ->limit(20)
                ->get();

            return response()->json($students);
        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data mahasiswa.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function tambahPeserta(Request $request, Kelas $kelas)
    {
        $request->validate([
            'student_ids' => [
                'required',
                'array',
                'min:1',
            ],

            'student_ids.*' => [
                'integer',
                'exists:students,id',
            ],
        ]);

        $pengajaran = Kelas::where(
            'id',
            $kelas->id
        )->firstOrFail();

        $data = collect($request->student_ids)
            ->unique()
            ->map(function ($studentId) use ($pengajaran) {

                return [
                    'kelas_id' => $pengajaran->id,
                    'mahasiswa_id' => $studentId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            })
            ->toArray();

        PengajaranMahasiswa::insertOrIgnore($data);

        return response()->json([
            'success' => true,
            'message' => count($data)
                . ' mahasiswa berhasil ditambahkan.',
        ]);
    }
    /*
    |--------------------------------------------------------------------------
    | MATA KULIAH SAYA
    |--------------------------------------------------------------------------
    */

    public function mk_saya()
    {
        $lecturer = auth()->user()->lecturer;

        if (!$lecturer) {
            abort(403, 'Data dosen tidak ditemukan.');
        }

        $lecturerId = $lecturer->id;

        $kelas = Kelas::with([
            'pengajaranDosen' => function ($query) use ($lecturerId) {
                $query->where('dosen_id', $lecturerId)->with('lecturer');
            },
            'matakuliah'
        ])
            ->whereHas('pengajaranDosen', function ($query) use ($lecturerId) {
                $query->where('dosen_id', $lecturerId);
            })
            ->get();

        return view('lecturer.matakuliah-saya', compact('kelas'));
    }


    /*
    |--------------------------------------------------------------------------
    | DETAIL PENGAJARAN
    |--------------------------------------------------------------------------
    */



    public function show(Request $request, $id)
    {
        $kelas = Kelas::with([
            'pengajaranDosen.lecturer.user', // untuk daftar nama dosen pengampu, ditampilkan di @section('ketjudul')
            'matakuliah',
            'mahasiswa.user', // <-- ini yang baru
        ])
            ->findOrFail($id); // id kelas

        // Cari dosen yang sedang login
        $lecturer = Auth::user()->lecturer;

        if (!$lecturer) {
            abort(403, 'Akun ini tidak terdaftar sebagai dosen.');
        }

        // Cari record pengajaran_dosen milik dosen ini untuk kelas ini
        $pengajaranDosen = PengajaranDosen::with([
            'materi.files' => function ($query) {
                $query->orderBy('urutan');
            }
        ])
            ->where('kelas_id', $kelas->id)
            ->where('dosen_id', $lecturer->id)
            ->first();

        if (!$pengajaranDosen) {
            // dosen ini tidak mengajar di kelas ini, jangan izinkan akses
            abort(403, 'Anda tidak mengajar di kelas ini.');
        }


        $pengajuanPending = PengajuanKelas::where('kelas_id', $kelas->id)
            ->where('status', 'pending')
            ->with('mahasiswa.user')
            ->latest()
            ->get();

        $pertemuanList = Pertemuan::where('kelas_id', $kelas->id)->orderBy('pertemuan_ke')->get();
        if ($pertemuanList->isEmpty()) {
            $pertemuanList = collect([
                Pertemuan::create(['kelas_id' => $kelas->id, 'pertemuan_ke' => 1])
            ]);
        }

        // Pertemuan aktif: dari ?pertemuan=ID, default yang pertama
        $pertemuan = $pertemuanList->firstWhere('id', (int) $request->query('pertemuan'))
            ?? $pertemuanList->first();

        $materiList = $pengajaranDosen->materi()
            ->where('pertemuan_id', $pertemuan->id)
            ->with(['files', 'materiAr'])
            ->orderBy('urutan')
            ->orderByDesc('created_at')
            ->get();

        $sesiAbsensiList = SesiAbsensi::where('kelas_id', $kelas->id)
            ->where('pertemuan_ke', $pertemuan->pertemuan_ke)
            ->latest('dibuka_pada')
            ->get();

        $quizList = Quiz::where('pengajaran_dosen_id', $pengajaranDosen->id)
            ->where('pertemuan_id', $pertemuan->id)
            ->withCount('questions')
            ->latest()
            ->get();

        $tugasList = Tugas::where('pengajaran_dosen_id', $pengajaranDosen->id)
            ->where('pertemuan_id', $pertemuan->id)
            ->withCount('jawaban')
            ->latest()
            ->get();

        // $pengajuanPending tetap sama (tingkat kelas, bukan per pertemuan)

        return view('lecturer.show', [
            'pengajaran'       => $kelas,
            'pengajaranDosen'  => $pengajaranDosen,
            'pertemuanList'    => $pertemuanList,
            'pertemuan'        => $pertemuan,
            'materiList'       => $materiList,
            'sesiAbsensiList'  => $sesiAbsensiList,
            'quizList'         => $quizList,
            'tugasList'        => $tugasList,
            'pengajuanPending' => $pengajuanPending,
        ]);
    }
    public function daftarPeserta(Kelas $kelas)
    {
        try {

            $peserta = PengajaranMahasiswa::with([
                'student.user',
                'student.prodi',
            ])
                ->where('kelas_id', $kelas->id)
                ->get();

            return response()->json([
                'success' => true,
                'data' => $peserta,
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil daftar peserta.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    public function destroy($id)
    {
        $kelas = Kelas::findOrFail($id);
        $kelas->delete();

        return response()->json([
            'success' => true,
            'message' => 'Kelas berhasil dihapus'
        ]);
    }

    public function storePertemuan($kelasId)
    {
        $next = (Pertemuan::where('kelas_id', $kelasId)->max('pertemuan_ke') ?? 0) + 1;
        $p = Pertemuan::create(['kelas_id' => $kelasId, 'pertemuan_ke' => $next]);

        return redirect()
            ->route('pengajaran.show', ['id' => $kelasId, 'pertemuan' => $p->id])
            ->with('success', "Pertemuan {$next} berhasil ditambahkan.");
    }

    public function rekapNilai(PengajaranDosen $pengajaranDosen)
    {
        // Pastikan pengajaranDosen ini milik dosen yang sedang login
        abort_unless(
            $pengajaranDosen->dosen_id === auth()->user()->lecturer->id,
            403
        );

        $pengajaranDosen->load('kelas.matakuliah');

        $namaMk = $pengajaranDosen->kelas->matakuliah->nama_mk;
        $kodeKelas = $pengajaranDosen->kelas->kode_kelas;

        $fileName = 'Rekap-Nilai-' . Str::slug($namaMk) . '-' . $kodeKelas . '-' . now()->format('Ymd_His') . '.xlsx';

        return Excel::download(new RekapNilaiExport($pengajaranDosen->id), $fileName);
    }
}
