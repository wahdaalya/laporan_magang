<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
}
