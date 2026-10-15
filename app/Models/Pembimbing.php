<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Pembimbing extends Model
{
    protected $fillable = [
        'user_id',
        'nama',
        'nip',
        'email',
        'hp_wa',
        'departemen_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function departemen()
    {
        return $this->belongsTo(Departemen::class);
    }

    public function kelompoks()
    {
        return $this->hasMany(Kelompok::class);
    }

    public function mahasiswas(): HasManyThrough
    {
        return $this->hasManyThrough(User::class, Kelompok::class, 'pembimbing_id', 'kelompok_id', 'id', 'id')
            ->where('users.role', 'MAHASISWA');
    }
}
