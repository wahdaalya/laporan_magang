<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NilaiAkademik extends Model
{
    protected $fillable = [
        'mahasiswa_id',
        'dosen_user_id',
        'nilai_bimbingan',
        'nilai_laporan_akhir',
        'nilai_presentasi',
    ];

    protected function casts(): array
    {
        return [
            'nilai_bimbingan' => 'decimal:2',
            'nilai_laporan_akhir' => 'decimal:2',
            'nilai_presentasi' => 'decimal:2',
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
