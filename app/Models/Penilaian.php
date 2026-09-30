<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penilaian extends Model
{
    protected $fillable = [
        'mahasiswa_id',
        'file',
        'catatan',
        'uploaded_by',
        'nilai_bimbingan',
        'nilai_laporan_akhir',
        'nilai_presentasi',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(User::class, 'mahasiswa_id');
    }
}
