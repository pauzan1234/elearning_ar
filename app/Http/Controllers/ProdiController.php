<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Prodi;
use Illuminate\Database\QueryException;

class ProdiController extends Controller
{
    public function index()
    {
        $prodiList = Prodi::all();

        return view('admin.dosen.index', compact('prodiList'));
    }
    public function create()
    {
        return view('prodi.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_prodi' => ['required', 'string', 'max:255'],
        ]);

        Prodi::create($validated);

        return redirect()
            ->route('prodi.index')
            ->with('success', 'Prodi berhasil ditambahkan.');
    }
    public function edit(Prodi $prodi)
    {
        return view('prodi.edit', compact('prodi'));
    }

    public function update(Request $request, Prodi $prodi)
    {
        $validated = $request->validate([
            'nama_prodi' => ['required', 'string', 'max:255'],
        ]);

        $prodi->update($validated);

        return redirect()
            ->route('prodi.index')
            ->with('success', 'Prodi berhasil diperbarui.');
    }
    public function destroy(Prodi $prodi)
    {
        try {
            $prodi->delete();
        } catch (QueryException $e) {
            return redirect()
                ->route('prodi.index')
                ->with('error', 'Prodi tidak bisa dihapus karena masih dipakai data lain.');
        }

        return redirect()
            ->route('prodi.index')
            ->with('success', 'Prodi berhasil dihapus.');
    }
}
