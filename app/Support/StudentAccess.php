<?php

namespace App\Support;

use App\Models\User;

class StudentAccess
{
    public static function canView(User $viewer, ?User $student): bool
    {
        if (! $student) {
            return false;
        }

        if ($viewer->role === 'ADMIN' || $viewer->is($student)) {
            return true;
        }

        if ($student->role !== 'MAHASISWA' || $viewer->getKey() === null) {
            return false;
        }

        if ($viewer->role === 'DOSEN') {
            return ($student->dosen_id !== null && (int) $student->dosen_id === (int) $viewer->getKey())
                || $student->kelompok()
                    ->whereHas('dosen', fn ($query) => $query->where('user_id', $viewer->getKey()))
                    ->exists();
        }

        if ($viewer->role === 'MENTOR') {
            return ($student->mentor_id !== null && (int) $student->mentor_id === (int) $viewer->getKey())
                || $student->kelompok()
                    ->whereHas('pembimbing', fn ($query) => $query->where('user_id', $viewer->getKey()))
                    ->exists();
        }

        return false;
    }
}
