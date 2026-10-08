@extends('admin.app-admin')

@section('ketjudul')
DAFTAR
@endsection

@section('judul')
Kelas
@endsection

@section('content')



<a href="{{ route('prodi.create') }}"
    class="inline-flex items-center px-4 py-2 bg-gray-800 text-white text-xs font-semibold uppercase tracking-widest rounded-md hover:bg-gray-700">
    + Tambah Prodi
</a>
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

        @if (session('success'))
        <div class="mb-4 p-4 rounded-md bg-green-100 text-green-800 text-sm">
            {{ session('success') }}
        </div>
        @endif

        <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Prodi</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($prodis as $prodi)
                    <tr>
                        <td class="px-6 py-4 text-sm text-gray-700">
                            {{ $prodis->firstItem() + $loop->index }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $prodi->nama_prodi }}</td>
                        <td class="px-6 py-4 text-right">
                            <div class="inline-flex items-center gap-2">
                                <a href="{{ route('prodi.edit', $prodi) }}"
                                    class="inline-flex items-center px-3 py-1.5 bg-indigo-600 text-white text-xs font-semibold uppercase tracking-widest rounded-md hover:bg-indigo-500">
                                    Edit
                                </a>

                                <form method="POST"
                                    action="{{ route('prodi.destroy', $prodi) }}"
                                    onsubmit="return confirm('Yakin ingin menghapus prodi {{ $prodi->nama_prodi }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="inline-flex items-center px-3 py-1.5 bg-red-600 text-white text-xs font-semibold uppercase tracking-widest rounded-md hover:bg-red-500">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500">
                            Belum ada data.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">{{ $prodis->links() }}</div>
    </div>
</div>


@endsection