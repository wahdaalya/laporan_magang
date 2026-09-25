<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Departemen extends Model
{
        use HasFactory;

    public const PILIHAN = [
        'Executive General Manager / Office of the GM',
        'Finance / Keuangan',
        'Human Capital',
        'Operation & Services',
        'Aviation Security (Avsec)',
        'Engineering / Teknik',
        'Procurement / Pengadaan',
    ];

    protected $fillable = [
        'nama_departemen',
        'nama_pimpinan',
    ];
    public function pembimbings()
    {
        return $this->hasMany(Pembimbing::class);
    }

    public function kelompoks()
    {
        return $this->hasMany(Kelompok::class);
    }
}
