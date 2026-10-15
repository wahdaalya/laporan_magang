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

        if (in_array($viewer->role, ['ADMIN', 'SUPERADMIN', 'AUDITOR'], true) || $viewer->is($student)) {
            return true;
        }

        if ($viewer->role === 'MAHASISWA' && $viewer->getKey() !== null) {
            if ($student->role === 'DOSEN') {
                return $viewer->kelompok()
                    ->whereHas('dosen', fn ($query) => $query->where('user_id', $student->getKey()))
                    ->exists();
            }

            if ($student->role === 'MENTOR') {
                return $viewer->kelompok()
                    ->whereHas('pembimbing', fn ($query) => $query->where('user_id', $student->getKey()))
                    ->exists();
            }
        }

        if ($student->role !== 'MAHASISWA' || $viewer->getKey() === null) {
            return false;
        }

        if ($student->kelompok_id === null) {
            return false;
        }

        if ($viewer->role === 'DOSEN') {
            return $student->kelompok()
                ->whereHas('dosen', fn ($query) => $query->where('user_id', $viewer->getKey()))
                ->exists();
        }

        if ($viewer->role === 'MENTOR') {
            return $student->kelompok()
                ->whereHas('pembimbing', fn ($query) => $query->where('user_id', $viewer->getKey()))
                ->exists();
        }

        return false;
    }
}
