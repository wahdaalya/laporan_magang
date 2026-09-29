<?php

namespace Tests\Feature;

use App\Models\Dosen;
use App\Models\Kelompok;
use App\Models\LaporanAkhir;
use App\Models\NilaiAkademik;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DosenDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_dosen_dashboard_only_shows_students_from_their_assigned_groups(): void
    {
        $dosen = User::factory()->role('DOSEN')->create();
        $otherDosen = User::factory()->role('DOSEN')->create();
        $dosenProfile = Dosen::create(['user_id' => $dosen->id, 'nama' => $dosen->name]);
        $otherDosenProfile = Dosen::create(['user_id' => $otherDosen->id, 'nama' => $otherDosen->name]);
        $kelompok = Kelompok::create([
            'nama_kelompok' => 'KEL-DOSEN-01',
            'tanggal_mulai' => today()->subWeek(),
            'tanggal_selesai' => today()->addWeeks(8),
            'dosen_id' => $dosenProfile->id,
        ]);
        $kelompokLain = Kelompok::create([
            'nama_kelompok' => 'KEL-DOSEN-02',
            'tanggal_mulai' => today()->subWeek(),
            'tanggal_selesai' => today()->addWeeks(8),
            'dosen_id' => $otherDosenProfile->id,
        ]);
        $peserta = User::factory()->role('MAHASISWA')->create([
            'name' => 'Peserta Saya',
            'kelompok_id' => $kelompok->id,
        ]);
        User::factory()->role('MAHASISWA')->create([
            'name' => 'Peserta Dosen Lain',
            'kelompok_id' => $kelompokLain->id,
        ]);

        $this->actingAs($dosen)->get(route('dosen.dashboard'))
            ->assertOk()
            ->assertSee('Peserta Saya')
            ->assertDontSee('Peserta Dosen Lain')
            ->assertSee('Mahasiswa Aktif Magang');
    }

    public function test_logbook_only_shows_mentor_approved_reports_for_assigned_students(): void
    {
        $dosen = User::factory()->role('DOSEN')->create();
        $dosenProfile = Dosen::create(['user_id' => $dosen->id, 'nama' => $dosen->name]);
        $kelompok = Kelompok::create([
            'nama_kelompok' => 'KEL-LOGBOOK-01',
            'tanggal_mulai' => '2026-09-01',
            'tanggal_selesai' => '2026-09-30',
            'dosen_id' => $dosenProfile->id,
        ]);
        $peserta = User::factory()->role('MAHASISWA')->create(['kelompok_id' => $kelompok->id]);
        $peserta->laporans()->create(['tanggal' => '2026-09-10', 'kegiatan' => 'Logbook disetujui mentor', 'status' => 'Disetujui']);
        $peserta->laporans()->create(['tanggal' => '2026-09-11', 'kegiatan' => 'Logbook masih pending', 'status' => 'Belum Diperiksa']);

        $this->actingAs($dosen)->get(route('dosen.logbook.index', ['bulan' => '2026-09']))
            ->assertOk()
            ->assertSee('Logbook disetujui mentor')
            ->assertDontSee('Logbook masih pending');
    }

    public function test_student_can_upload_a_pdf_final_report_draft(): void
    {
        Storage::fake('public');
        $peserta = User::factory()->role('MAHASISWA')->create();

        $this->actingAs($peserta)->post(route('mahasiswa.laporan-akhir.store'), [
            'judul_laporan' => 'Draf Laporan Magang',
            'file' => UploadedFile::fake()->createWithContent('draf-laporan.pdf', "%PDF-1.4\n%%EOF"),
        ])->assertRedirect();

        $laporan = LaporanAkhir::query()->where('mahasiswa_id', $peserta->id)->firstOrFail();
        $this->assertTrue(Storage::disk('public')->exists($laporan->file_path));
        $this->assertSame('Draf Laporan Magang', $laporan->judul_laporan);
        $this->assertSame('Menunggu Review', $laporan->status);
    }

    public function test_dosen_can_request_revision_for_an_assigned_final_report(): void
    {
        $dosen = User::factory()->role('DOSEN')->create();
        $dosenProfile = Dosen::create(['user_id' => $dosen->id, 'nama' => $dosen->name]);
        $kelompok = Kelompok::create([
            'nama_kelompok' => 'KEL-REV-01',
            'tanggal_mulai' => '2026-09-01',
            'tanggal_selesai' => '2026-12-01',
            'dosen_id' => $dosenProfile->id,
        ]);
        $peserta = User::factory()->role('MAHASISWA')->create(['kelompok_id' => $kelompok->id]);
        $laporanAkhir = LaporanAkhir::create([
            'mahasiswa_id' => $peserta->id,
            'judul_laporan' => 'Draf Laporan PKL',
            'file_path' => 'laporan-akhir/draf.pdf',
        ]);

        $this->actingAs($dosen)->patch(route('dosen.laporan-akhir.update', $laporanAkhir), [
            'keputusan' => 'revisi',
            'catatan_revisi' => 'Lengkapi bagian metodologi.',
        ])->assertRedirect();

        $this->assertSame('Revisi', $laporanAkhir->refresh()->status);
        $this->assertSame('Lengkapi bagian metodologi.', $laporanAkhir->catatan_revisi);
    }

    public function test_dosen_can_approve_an_assigned_final_report(): void
    {
        $dosen = User::factory()->role('DOSEN')->create();
        $dosenProfile = Dosen::create(['user_id' => $dosen->id, 'nama' => $dosen->name]);
        $kelompok = Kelompok::create([
            'nama_kelompok' => 'KEL-ACC-01',
            'tanggal_mulai' => '2026-09-01',
            'tanggal_selesai' => '2026-12-01',
            'dosen_id' => $dosenProfile->id,
        ]);
        $peserta = User::factory()->role('MAHASISWA')->create(['kelompok_id' => $kelompok->id]);
        $laporanAkhir = LaporanAkhir::create([
            'mahasiswa_id' => $peserta->id,
            'judul_laporan' => 'Laporan Final PKL',
            'file_path' => 'laporan-akhir/final.pdf',
        ]);

        $this->actingAs($dosen)->patch(route('dosen.laporan-akhir.update', $laporanAkhir), [
            'keputusan' => 'setujui',
        ])->assertRedirect();

        $this->assertSame('Disetujui', $laporanAkhir->refresh()->status);
        $this->assertSame($dosen->id, $laporanAkhir->dosen_user_id);
    }

    public function test_dosen_can_save_final_academic_scores_for_an_assigned_student(): void
    {
        $dosen = User::factory()->role('DOSEN')->create();
        $dosenProfile = Dosen::create(['user_id' => $dosen->id, 'nama' => $dosen->name]);
        $kelompok = Kelompok::create([
            'nama_kelompok' => 'KEL-NILAI-01',
            'tanggal_mulai' => '2026-09-01',
            'tanggal_selesai' => '2026-12-01',
            'dosen_id' => $dosenProfile->id,
        ]);
        $peserta = User::factory()->role('MAHASISWA')->create(['kelompok_id' => $kelompok->id]);

        $this->actingAs($dosen)->put(route('dosen.penilaian-akhir.update', $peserta), [
            'nilai_bimbingan' => 88,
            'nilai_laporan_akhir' => 90,
            'nilai_presentasi' => 92,
        ])->assertRedirect();

        $this->assertSame(1, NilaiAkademik::query()->where('mahasiswa_id', $peserta->id)->count());
        $this->assertSame('90.00', $peserta->nilaiAkademik()->firstOrFail()->nilai_laporan_akhir);
    }
}
