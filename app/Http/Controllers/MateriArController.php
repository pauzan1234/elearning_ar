<?php

namespace App\Http\Controllers;

use App\Models\Materi;
use App\Models\MateriAr;
use App\Models\PengajaranDosen;
use App\Models\PengajaranMahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MateriArController extends Controller
{
    public function create(PengajaranDosen $pengajaranDosen)
    {
        abort_unless($pengajaranDosen->lecturer->user_id === Auth::id(), 403);

        $pertemuan = Materi::where('pengajaran_id', $pengajaranDosen->id)->get();

        return view('lecturer.materi-ar.create', compact('pengajaranDosen', 'pertemuan'));
    }

    public function store(Request $request, PengajaranDosen $pengajaranDosen)
    {
        abort_unless($pengajaranDosen->lecturer->user_id === Auth::id(), 403);

        $validated = $request->validate([
            'materi_id'  => 'required|exists:materis,id',
            'judul'      => 'required|string|max:255',
            'deskripsi'  => 'nullable|string',
            'file_model' => 'required|file|max:20480', // 20MB
        ]);

        $ext = strtolower($request->file('file_model')->getClientOriginalExtension());
        if (!in_array($ext, ['glb', 'gltf'])) {
            return back()->withErrors(['file_model' => 'File harus berformat .glb atau .gltf'])->withInput();
        }

        $validated['file_model'] = $request->file('file_model')->store('materi-ar/model', 'public');

        $materiAr = MateriAr::create($validated);

        return redirect()->route('materi-ar.show', $materiAr->id)
            ->with('success', 'Materi AR berhasil ditambahkan');
    }

    public function edit(MateriAr $materiAr)
    {
        $pengajaranDosen = $materiAr->materi->pengajaranDosen;
        abort_unless($pengajaranDosen->lecturer->user_id === Auth::id(), 403);

        $pertemuan = Materi::where('pengajaran_id', $pengajaranDosen->id)->get();

        return view('lecturer.materi-ar.edit', compact('materiAr', 'pertemuan', 'pengajaranDosen'));
    }

    public function update(Request $request, MateriAr $materiAr)
    {
        $pengajaranDosen = $materiAr->materi->pengajaranDosen;
        abort_unless($pengajaranDosen->lecturer->user_id === Auth::id(), 403);

        $validated = $request->validate([
            'judul'      => 'required|string|max:255',
            'deskripsi'  => 'nullable|string',
            'file_model' => 'nullable|file|max:20480',
        ]);

        if ($request->hasFile('file_model')) {
            $ext = strtolower($request->file('file_model')->getClientOriginalExtension());
            if (!in_array($ext, ['glb', 'gltf'])) {
                return back()->withErrors(['file_model' => 'File harus berformat .glb atau .gltf'])->withInput();
            }

            Storage::disk('public')->delete($materiAr->file_model);
            $validated['file_model'] = $request->file('file_model')->store('materi-ar/model', 'public');
        }

        $materiAr->update($validated);

        return redirect()->route('pengajaran.show', $pengajaranDosen->id)
            ->with('success', 'Materi AR berhasil diperbarui');
    }

    public function destroy(MateriAr $materiAr)
    {
        $pengajaranDosen = $materiAr->materi->pengajaranDosen;
        abort_unless($pengajaranDosen->lecturer->user_id === Auth::id(), 403);

        Storage::disk('public')->delete($materiAr->file_model);
        $materiAr->delete();

        return back()->with('success', 'Materi AR berhasil dihapus');
    }

    /**
     * Halaman viewer AR untuk mahasiswa (markerless, pakai <model-viewer>)
     */
    public function show(MateriAr $materiAr)
    {
        return view('lecturer.materi-ar.viewer', compact('materiAr'));
    }

    public function show_mhs(MateriAr $materiAr)
    {
        $mahasiswa = auth()->user()->student;

        $kelasId = $materiAr->materi->pengajaranDosen->kelas_id;

        $terdaftar = PengajaranMahasiswa::where('kelas_id', $kelasId)
            ->where('mahasiswa_id', $mahasiswa->id)
            ->exists();

        abort_unless($terdaftar, 403, 'Kamu tidak terdaftar di kelas ini.');

        return view('student.materi-ar.show', compact('materiAr'));
    }
}
