<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LaporanAkhir extends Model
{
    protected $fillable = [
        'mahasiswa_id',
        'judul_laporan',
        'file_path',
        'status',
        'catatan_revisi',
        'dosen_user_id',
    ];

    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(User::class, 'mahasiswa_id');
    }

    public function dosen(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dosen_user_id');
    }
}
