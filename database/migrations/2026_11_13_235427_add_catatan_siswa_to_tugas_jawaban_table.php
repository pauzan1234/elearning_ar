<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tugas_jawaban', function (Blueprint $table) {
            $table->text('catatan_siswa')->nullable()->after('waktu_submit');
        });
    }

    public function down(): void
    {
        Schema::table('tugas_jawaban', function (Blueprint $table) {
            $table->dropColumn('catatan_siswa');
        });
    }
};
