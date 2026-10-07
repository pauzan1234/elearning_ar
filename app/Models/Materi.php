<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Materi extends Model
{
    protected $table = 'materis';

    protected $fillable = [
        'pengajaran_id',
        'judul',
        'deskripsi',
        'urutan',
        'is_hidden'
    ];

    public function pengajaran()
    {
        return $this->belongsTo(PengajaranDosen::class, 'pengajaran_id');
    }

    public function files()
    {
        return $this->hasMany(MateriFile::class, 'materi_id')
            ->orderBy('urutan');
    }
    public function materiAr()
    {
        return $this->hasMany(MateriAr::class, 'materi_id');
    }



    protected $casts = [
        'is_hidden' => 'boolean',
    ];

    // Scope: hanya materi yang tampil ke siswa
    public function scopeVisible($query)
    {
        return $query->where('is_hidden', false);
    }
}
