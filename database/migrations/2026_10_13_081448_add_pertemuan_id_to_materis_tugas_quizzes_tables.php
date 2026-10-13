<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        foreach (['materis', 'tugas', 'quizzes'] as $tabel) {
            Schema::table($tabel, function (Blueprint $table) {
                $table->foreignId('pertemuan_id')->nullable()
                    ->constrained('pertemuan')->nullOnDelete();
            });
        }

        Schema::table('pertemuan', function (Blueprint $table) {
            $table->unique(['kelas_id', 'pertemuan_ke']);
        });

        // Data lama dimasukkan ke Pertemuan 1 supaya tidak hilang dari tampilan
        foreach (DB::table('kelas')->pluck('id') as $kelasId) {
            $pid = DB::table('pertemuan')
                ->where('kelas_id', $kelasId)->where('pertemuan_ke', 1)->value('id')
                ?? DB::table('pertemuan')->insertGetId([
                    'kelas_id' => $kelasId,
                    'pertemuan_ke' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

            $pdIds = fn() => DB::table('pengajaran_dosen')->where('kelas_id', $kelasId)->select('id');

            DB::table('materis')->whereIn('pengajaran_id', $pdIds())->update(['pertemuan_id' => $pid]);
            DB::table('tugas')->whereIn('pengajaran_dosen_id', $pdIds())->update(['pertemuan_id' => $pid]);
            DB::table('quizzes')->whereIn('pengajaran_dosen_id', $pdIds())->update(['pertemuan_id' => $pid]);
        }
    }

    public function down(): void
    {
        Schema::table('pertemuan', fn(Blueprint $t) => $t->dropUnique(['kelas_id', 'pertemuan_ke']));
        foreach (['materis', 'tugas', 'quizzes'] as $tabel) {
            Schema::table($tabel, function (Blueprint $table) {
                $table->dropConstrainedForeignId('pertemuan_id');
            });
        }
    }
};
