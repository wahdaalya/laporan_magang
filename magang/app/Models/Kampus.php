<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kampus extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_kampus',
        'nama_kampus',
        'alamat_kampus',
    ];

    public function kelompoks()
    {
        return $this->hasMany(Kelompok::class);
    }
}