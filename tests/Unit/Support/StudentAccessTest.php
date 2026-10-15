<?php

namespace Tests\Unit\Support;

use App\Models\User;
use App\Support\StudentAccess;
use PHPUnit\Framework\TestCase;

class StudentAccessTest extends TestCase
{
    public function test_student_can_view_own_profile_media(): void
    {
        $student = $this->user(1, 'MAHASISWA');

        $this->assertTrue(StudentAccess::canView($student, $student));
    }

    public function test_admin_can_view_student_media(): void
    {
        $admin = $this->user(1, 'ADMIN');
        $student = $this->user(2, 'MAHASISWA');

        $this->assertTrue(StudentAccess::canView($admin, $student));
    }

    public function test_legacy_dosen_id_does_not_grant_access_without_a_group(): void
    {
        $dosen = $this->user(2, 'DOSEN');
        $student = $this->user(3, 'MAHASISWA', ['dosen_id' => 2]);

        $this->assertFalse(StudentAccess::canView($dosen, $student));
    }

    public function test_legacy_mentor_id_does_not_grant_access_without_a_group(): void
    {
        $mentor = $this->user(4, 'MENTOR');
        $student = $this->user(3, 'MAHASISWA', ['mentor_id' => 4]);

        $this->assertFalse(StudentAccess::canView($mentor, $student));
    }

    public function test_unassigned_account_cannot_view_student_media(): void
    {
        $unassignedStudent = $this->user(2, 'MAHASISWA');
        $student = $this->user(3, 'MAHASISWA');

        $this->assertFalse(StudentAccess::canView($unassignedStudent, $student));
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    private function user(int $id, string $role, array $attributes = []): User
    {
        $user = new User;
        $user->setRawAttributes(array_merge(['id' => $id, 'role' => $role], $attributes), true);

        return $user;
    }
}
