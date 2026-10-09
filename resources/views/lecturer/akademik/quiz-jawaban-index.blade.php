@extends('lecturer.app-lecturer-create-materi')
@section('ketjudul', 'Jawaban Quiz')
@section('judul', 'Daftar Siswa')
@section('content')

@if (session('success'))
<div class="mb-6 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
    {{ session('success') }}
</div>
@endif

<div class="mb-6">
    <a href="{{ route('lecturer.akademik.quiz.list', $quiz->pengajaranDosen) }}" class="text-sm text-teal hover:underline">← Kembali ke daftar quiz</a>
    <h1 class="mt-2 font-display text-xl font-semibold">{{ $quiz->judul }}</h1>
    <p class="mt-1 text-sm text-ink/50">{{ $quiz->pengajaranDosen->kelas->matakuliah->nama_mk }} · Kelas {{ $quiz->pengajaranDosen->kelas->kode_kelas }}</p>
</div>

<div class="overflow-x-auto rounded-2xl border border-line bg-white shadow-sm">
    <table class="min-w-full text-sm">
        <thead class="bg-paper text-left text-xs uppercase tracking-wide text-ink/50">
            <tr>
                <th class="px-5 py-3">Siswa</th>
                <th class="px-5 py-3">NIM</th>
                <th class="px-5 py-3">Status</th>
                <th class="px-5 py-3">Skor</th>
                <th class="px-5 py-3 text-right">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-line">
            @forelse($students as $student)
            @php($jawaban = $jawabanByStudent->get($student->id))
            <tr>
                <td class="px-5 py-4 font-medium">{{ $student->user->name ?? '-' }}</td>
                <td class="px-5 py-4 text-ink/60">{{ $student->nim }}</td>
                <td class="px-5 py-4"><span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $jawaban ? 'bg-green-50 text-green-700' : 'bg-gray-100 text-gray-600' }}">{{ $jawaban ? 'Sudah submit' : 'Belum submit' }}</span></td>
                <td class="px-5 py-4 font-semibold">{{ $jawaban?->skor !== null ? number_format((float)$jawaban->skor, 2) : '-' }}</td>
                <td class="px-5 py-4 text-right">
                    @if($jawaban)
                    <div class="flex items-center justify-end gap-4">
                        <a href="{{ route('lecturer.akademik.quiz.jawaban.show', [$quiz, $jawaban]) }}" class="font-semibold text-teal hover:underline">Lihat jawaban</a>

                        <form method="POST"
                            action="{{ route('lecturer.akademik.quiz.jawaban.reset', [$quiz, $jawaban]) }}"
                            onsubmit="return confirm(@js('Reset pengerjaan ' . ($student->user->name ?? 'siswa ini') . '? Skor dan jawabannya akan dihapus permanen.'))">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="rounded-lg border border-red-200 px-3 py-1.5 text-xs font-semibold text-red-600 transition hover:bg-red-50">
                                Reset
                            </button>
                        </form>
                    </div>
                    @else
                    <span class="text-ink/30">-</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-5 py-10 text-center text-ink/50">Belum ada siswa di kelas ini.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection