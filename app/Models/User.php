<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
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
        $disk = Storage::disk('local');

        return $this->foto && $disk->exists($this->foto)
            ? route('media.profile-photo', $this)
            : null;
    }

        /** Nama admin yang selalu ditampilkan di seluruh tampilan. */
    public const ADMIN_DISPLAY_NAME = 'Admin Kualanamu';

    public function getDisplayNameAttribute(): string
    {
        return $this->role === 'ADMIN'
            ? self::ADMIN_DISPLAY_NAME
            : (string) $this->name;
    }
    public function kelompok()
    {
        return $this->belongsTo(Kelompok::class, 'kelompok_id');
    }

    public function mahasiswaBimbinganDosen(): Builder
    {
        return self::query()
            ->where('role', 'MAHASISWA')
            ->whereHas('kelompok.dosen', fn (Builder $query) => $query->where('user_id', $this->id));
    }

    public function mahasiswaBimbinganMentor(): Builder
    {
        return self::query()
            ->where('role', 'MAHASISWA')
            ->whereHas('kelompok.pembimbing', fn (Builder $query) => $query->where('user_id', $this->id));
    }

    public function laporans()
    {
        return $this->hasMany(Laporan::class);
    }

    /** Jalur lama, tidak dipakai lagi; sumber data = kelompok. */
    public function getDosenAttribute(): ?Dosen
    {
        $this->loadMissing('kelompok.dosen');

        return $this->kelompok?->dosen;
    }

    /** Jalur lama, tidak dipakai lagi; sumber data = kelompok. */
    public function getMentorAttribute(): ?Pembimbing
    {
        $this->loadMissing('kelompok.pembimbing');

        return $this->kelompok?->pembimbing;
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
