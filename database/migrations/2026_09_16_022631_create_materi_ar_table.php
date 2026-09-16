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
        Schema::create('materi_ar', function (Blueprint $table) {
            $table->id();

            $table->foreignId('materi_id')
                ->constrained('materis')
                ->cascadeOnDelete();

            $table->string('judul');
            $table->text('deskripsi')->nullable();
            $table->string('file_model');
            $table->string('file_marker')->nullable();
            $table->enum('tipe_ar', ['tanpa_marker', 'marker'])->default('tanpa_marker');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materi_ar');
    }
};
