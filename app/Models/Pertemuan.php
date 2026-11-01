<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pertemuan extends Model
{
    // Nama tabel singular, jadi harus ditulis eksplisit
    protected $table = 'pertemuan';

    protected $fillable = [
        'kelas_id',
        'pertemuan_ke',
        'is_hidden',      // baru
    ];

    // baru
    protected $casts = [
        'is_hidden' => 'boolean',
    ];

    // baru: hanya pertemuan yang tampil untuk mahasiswa
    public function scopeVisible(Builder $query): Builder
    {
        return $query->where('is_hidden', false);
    }

    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class);
    }

    public function materi(): HasMany
    {
        return $this->hasMany(Materi::class);
    }

    public function tugas(): HasMany
    {
        return $this->hasMany(Tugas::class);
    }

    public function quizzes(): HasMany
    {
        return $this->hasMany(Quiz::class);
    }
}
