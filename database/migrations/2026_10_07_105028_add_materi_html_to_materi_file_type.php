<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Tambah nilai 'html' ke kolom enum (MySQL / MariaDB)
        DB::statement("ALTER TABLE materi_files
            MODIFY tipe ENUM('pdf','audio','video_youtube','html') NOT NULL");
    }

    public function down(): void
    {
        DB::table('materi_files')->where('tipe', 'html')->delete();

        DB::statement("ALTER TABLE materi_files
            MODIFY tipe ENUM('pdf','audio','video_youtube') NOT NULL");
    }
};
