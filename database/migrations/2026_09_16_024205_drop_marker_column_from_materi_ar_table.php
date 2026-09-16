<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('materi_ar', function (Blueprint $table) {
            $table->dropColumn(['file_marker', 'tipe_ar']);
        });
    }

    public function down(): void
    {
        Schema::table('materi_ar', function (Blueprint $table) {
            $table->string('file_marker')->nullable();
            $table->enum('tipe_ar', ['tanpa_marker', 'marker'])->default('tanpa_marker');
        });
    }
};
