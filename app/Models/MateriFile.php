<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class MateriFile extends Model
{
    public const TIPE_PDF     = 'pdf';
    public const TIPE_AUDIO   = 'audio';
    public const TIPE_YOUTUBE = 'video_youtube';
    public const TIPE_HTML    = 'html';

    protected $table = 'materi_files';

    protected $fillable = [
        'materi_id',
        'tipe',
        'file_path',
        'nama_asli',
        'youtube_url',
        'youtube_id',
        'urutan',
    ];

    public function materi()
    {
        return $this->belongsTo(Materi::class, 'materi_id');
    }

    // Accessor: url file untuk dipakai di view
    // - pdf/audio : url publik di storage
    // - html      : lewat route terproteksi (diberi header CSP sandbox)
    public function getUrlAttribute()
    {
        if ($this->tipe === self::TIPE_HTML) {
            return route('materi-html.file', $this->id);
        }

        if (in_array($this->tipe, [self::TIPE_PDF, self::TIPE_AUDIO]) && $this->file_path) {
            return asset('storage/' . $this->file_path);
        }

        return null;
    }

    // Accessor: url embed youtube, siap dipakai di <iframe src="">
    public function getYoutubeEmbedUrlAttribute()
    {
        if ($this->tipe === self::TIPE_YOUTUBE && $this->youtube_id) {
            return 'https://www.youtube.com/embed/' . $this->youtube_id;
        }

        return null;
    }

    protected static function booted(): void
    {
        // Hapus file fisik saat record dihapus langsung lewat Eloquent
        static::deleting(function (MateriFile $file) {
            if (! $file->file_path) {
                return;
            }

            // html disimpan di disk private (local), pdf/audio di public
            $disk = $file->tipe === self::TIPE_HTML ? 'local' : 'public';
            Storage::disk($disk)->delete($file->file_path);
        });
    }
}
