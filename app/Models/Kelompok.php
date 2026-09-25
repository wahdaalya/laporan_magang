<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelompok extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_kelompok',
        'tanggal_mulai',
        'tanggal_selesai',
        'kampus_id',
        'dosen_id',
        'pembimbing_id',
        'departemen_id',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_mulai' => 'date',
            'tanggal_selesai' => 'date',
        ];
    }

    public function kampus()
    {
        return $this->belongsTo(Kampus::class);
    }

    public function dosen()
    {
        return $this->belongsTo(Dosen::class);
    }

    public function pembimbing()
    {
        return $this->belongsTo(Pembimbing::class);
    }

    public function departemen()
    {
        return $this->belongsTo(Departemen::class);
    }

    public function mahasiswas()
    {
        return $this->hasMany(User::class, 'kelompok_id');
    }
}
