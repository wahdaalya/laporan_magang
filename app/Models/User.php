<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'nim_nip',
        'instansi',
        'unit',
        'foto',
        'dosen_id',
        'mentor_id',
        'kelompok_id',
        'hp_wa',
        'kampus',
        'fakultas',
        'prodi',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function getFotoUrlAttribute(): ?string
    {
        $disk = Storage::disk('public');

        return $this->foto && $disk->exists($this->foto)
            ? asset('storage/'.$this->foto)
            : null;
    }

    public function kelompok()
    {
        return $this->belongsTo(Kelompok::class, 'kelompok_id');
    }

    public function laporans()
    {
        return $this->hasMany(Laporan::class);
    }

    public function dosen()
    {
        return $this->belongsTo(User::class, 'dosen_id');
    }

    public function mentor()
    {
        return $this->belongsTo(User::class, 'mentor_id');
    }

    public function mahasiswaBimbinganDosen()
    {
        return $this->hasMany(User::class, 'dosen_id');
    }

    public function mahasiswaBimbinganMentor()
    {
        return $this->hasMany(User::class, 'mentor_id');
    }

    public function penilaians()
    {
        return $this->hasMany(Penilaian::class, 'mahasiswa_id');
    }

    public function laporanAkhir(): HasOne
    {
        return $this->hasOne(LaporanAkhir::class, 'mahasiswa_id');
    }

    public function nilaiAkademik(): HasOne
    {
        return $this->hasOne(NilaiAkademik::class, 'mahasiswa_id');
    }
}
