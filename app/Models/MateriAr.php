<?php

// app/Models/MateriAr.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MateriAr extends Model
{
    protected $table = 'materi_ar';

    protected $fillable = [
        'materi_id',
        'judul',
        'deskripsi',
        'file_model',
    ];

    public function materi()
    {
        return $this->belongsTo(Materi::class, 'materi_id');
    }
}
