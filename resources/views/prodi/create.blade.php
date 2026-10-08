@extends('admin.app-admin')

@section('ketjudul')
Tambah
@endsection

@section('judul')
Kelas
@endsection

@section('content')


<div class="py-12">
    <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-sm sm:rounded-lg p-6">

            <form method="POST" action="{{ route('prodi.store') }}">
                @csrf

                <!-- Nama Prodi -->
                <div>
                    <x-input-label for="nama_prodi" :value="__('Nama Kelas')" />
                    <x-text-input
                        id="nama_prodi"
                        name="nama_prodi"
                        type="text"
                        class="mt-1 block w-full"
                        :value="old('nama_prodi')"
                        placeholder="Contoh: Kelas IX Rombel 1"
                        required
                        autofocus />
                    <x-input-error :messages="$errors->get('nama_prodi')" class="mt-2" />
                </div>

                <div class="flex items-center justify-end gap-3 mt-6">
                    <a href="{{ route('prodi.index') }}"
                        class="text-sm text-gray-600 hover:text-gray-900 underline">
                        Batal
                    </a>
                    <x-primary-button>
                        {{ __('Simpan') }}
                    </x-primary-button>
                </div>
            </form>

        </div>
    </div>
</div>


@endsection