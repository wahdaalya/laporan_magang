<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AbsensiControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_participant_can_download_monthly_portrait_attendance_pdf(): void
    {
        $user = User::factory()->role('MAHASISWA')->create();

        $response = $this->actingAs($user)
            ->get(route('mahasiswa.absensi.cetak', ['bulan' => 9, 'tahun' => 2026]))
            ->assertDownload('lembar-presensi-2026-09.pdf');

        $this->assertSame('application/pdf', $response->headers->get('Content-Type'));
        $this->assertStringStartsWith('attachment;', (string) $response->headers->get('Content-Disposition'));
        $this->assertSame('nosniff', $response->headers->get('X-Content-Type-Options'));
    }
}
