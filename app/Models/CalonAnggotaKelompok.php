<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CalonAnggotaKelompok extends Model
{
    protected $table = 'calon_anggota_kelompoks';

    protected $fillable = [
        'kelompok_id',
        'nama',
        'user_id',
    ];

    public function kelompok()
    {
        return $this->belongsTo(Kelompok::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}