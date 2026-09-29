<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kampus extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_universitas',
        'alamat',
        'fakultas',
        'prodi',
    ];

    public function kelompoks()
    {
        return $this->hasMany(Kelompok::class);
    }
}