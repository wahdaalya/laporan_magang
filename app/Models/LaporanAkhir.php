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
        'status_mengetahui',
        'mengetahui_oleh',
        'mengetahui_at',
        'status_setuju',
        'disetujui_oleh',
        'disetujui_at',
    ];

    protected function casts(): array
    {
        return [
            'status_mengetahui' => 'boolean',
            'status_setuju' => 'boolean',
            'mengetahui_at' => 'datetime',
            'disetujui_at' => 'datetime',
        ];
    }

    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(User::class, 'mahasiswa_id');
    }

    public function dosen(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dosen_user_id');
    }
}
