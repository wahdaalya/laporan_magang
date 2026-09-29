<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Laporan extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'tanggal',
        'kegiatan',
        'keterangan',
        'foto',
        'status',
        'catatan_pembimbing',
        'diperiksa_oleh',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
        ];
    }

    public function mahasiswa()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getFotoUrlAttribute(): ?string
    {
        return $this->foto ? Storage::disk('public')->url($this->foto) : null;
    }
}