<?php

namespace Tests\Feature;

use App\Models\Kelompok;
use App\Models\Laporan;
use App\Models\User;
use Tests\TestCase;

class LaporanAkhirRekapTest extends TestCase
{
    public function test_empty_state_is_shown_without_a_group_or_approved_reports(): void
    {
        $mahasiswa = User::factory()->role('MAHASISWA')->create();

        $this->actingAs($mahasiswa)->get(route('mahasiswa.laporan-akhir.index'))
            ->assertOk()
            ->assertSee('Belum ada laporan harian yang disetujui. Laporan akhir akan muncul otomatis setelah laporan harianmu disetujui pembimbing.');
    }

    public function test_preview_only_includes_approved_reports_and_warns_about_others(): void
    {
        $mahasiswa = User::factory()->role('MAHASISWA')->create();
        $kelompok = Kelompok::query()->create([
            'nama_kelompok' => 'KEL-2026-001',
            'tanggal_mulai' => '2026-09-01',
            'tanggal_selesai' => '2026-10-01',
        ]);
        $mahasiswa->kelompok()->associate($kelompok)->save();

        $this->createReport($mahasiswa, '2026-09-01', 'Disetujui Minggu Satu', 'Disetujui');
        $this->createReport($mahasiswa, '2026-09-10', 'Disetujui Minggu Dua', 'Disetujui');
        $this->createReport($mahasiswa, '2026-09-02', 'Laporan Revisi', 'Revisi');
        $this->createReport($mahasiswa, '2026-09-03', 'Laporan Menunggu', 'Belum Diperiksa');

        $this->actingAs($mahasiswa)->get(route('mahasiswa.laporan-akhir.index'))
            ->assertOk()
            ->assertSee('Disetujui Minggu Satu')
            ->assertSee('Disetujui Minggu Dua')
            ->assertDontSee('Laporan Revisi')
            ->assertDontSee('Laporan Menunggu')
            ->assertSee('Minggu 2')
            ->assertSee('Ada 2 laporan yang belum disetujui dan belum masuk rekap.');
    }

    public function test_pdf_route_generates_the_current_students_report(): void
    {
        $mahasiswa = User::factory()->role('MAHASISWA')->create();
        $kelompok = Kelompok::query()->create([
            'nama_kelompok' => 'KEL-2026-002',
            'tanggal_mulai' => '2026-09-01',
            'tanggal_selesai' => '2026-10-01',
        ]);
        $mahasiswa->kelompok()->associate($kelompok)->save();
        $this->createReport($mahasiswa, '2026-09-01', 'Kegiatan Milik Mahasiswa', 'Disetujui');

        $response = $this->actingAs($mahasiswa)->get(route('mahasiswa.laporan-akhir.cetak'));

        $response->assertOk()->assertHeader('Content-Type', 'application/pdf');
        $this->assertStringStartsWith('%PDF', $response->getContent());
    }

    private function createReport(User $mahasiswa, string $tanggal, string $kegiatan, string $status): void
    {
        Laporan::query()->create([
            'user_id' => $mahasiswa->id,
            'tanggal' => $tanggal,
            'kegiatan' => $kegiatan,
            'keterangan' => 'Keterangan '.$kegiatan,
            'status' => $status,
        ]);
    }
}
