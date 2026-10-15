<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Dosen extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nama',
        'nidn',
        'kampus',
        'email',
        'hp_wa',
        'fakultas',
        'prodi',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function kelompoks()
    {
        return $this->hasMany(Kelompok::class);
    }

    public function mahasiswas(): HasManyThrough
    {
        return $this->hasManyThrough(User::class, Kelompok::class, 'dosen_id', 'kelompok_id', 'id', 'id')
            ->where('users.role', 'MAHASISWA');
    }
}
