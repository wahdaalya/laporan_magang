<?php

namespace Tests\Feature;

use App\Models\Kelompok;
use App\Models\Pembimbing;
use App\Models\Penilaian;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PembimbinganWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_participant_page_lists_group_assigned_participants_for_their_supervisor(): void
    {
        $supervisor = User::factory()->role('MENTOR')->create();
        $otherSupervisor = User::factory()->role('MENTOR')->create();
        $supervisorProfile = Pembimbing::create(['user_id' => $supervisor->id, 'nama' => $supervisor->name]);
        $otherSupervisorProfile = Pembimbing::create(['user_id' => $otherSupervisor->id, 'nama' => $otherSupervisor->name]);
        $group = Kelompok::create([
            'nama_kelompok' => 'KEL-2026-021',
            'tanggal_mulai' => '2026-09-01',
            'tanggal_selesai' => '2026-09-30',
            'pembimbing_id' => $supervisorProfile->id,
        ]);
        $otherGroup = Kelompok::create([
            'nama_kelompok' => 'KEL-2026-022',
            'tanggal_mulai' => '2026-09-01',
            'tanggal_selesai' => '2026-09-30',
            'pembimbing_id' => $otherSupervisorProfile->id,
        ]);
        $participant = User::factory()->role('MAHASISWA')->create([
            'name' => 'Peserta Bimbingan',
            'kelompok_id' => $group->id,
        ]);
        User::factory()->role('MAHASISWA')->create([
            'name' => 'Peserta Lain',
            'kelompok_id' => $otherGroup->id,
        ]);

        $this->actingAs($supervisor)->get('/pembimbing/peserta')
            ->assertOk()
            ->assertSee($participant->name)
            ->assertDontSee('Peserta Lain');
    }

    public function test_supervisor_can_filter_and_approve_an_owned_report(): void
    {
        $supervisor = User::factory()->role('MENTOR')->create();
        $participant = User::factory()->role('MAHASISWA')->create(['mentor_id' => $supervisor->id]);
        $laporan = $participant->laporans()->create([
            'tanggal' => today()->toDateString(),
            'kegiatan' => 'Membuat laporan mingguan',
            'status' => 'Belum Diperiksa',
        ]);

        $this->actingAs($supervisor)
            ->from(route('pembimbingan.laporan.index'))
            ->patch(route('pembimbingan.laporan.setujui', $laporan), [
                'catatan_pembimbing' => 'Sudah baik.',
            ])
            ->assertRedirect(route('pembimbingan.laporan.index'));

        $this->assertSame('Disetujui', $laporan->refresh()->status);
        $this->assertSame('Sudah baik.', $laporan->catatan_pembimbing);
        $this->assertSame($supervisor->name, $laporan->diperiksa_oleh);
    }

    public function test_supervisor_cannot_review_a_report_from_another_supervisors_participant(): void
    {
        $supervisor = User::factory()->role('MENTOR')->create();
        $otherSupervisor = User::factory()->role('MENTOR')->create();
        $participant = User::factory()->role('MAHASISWA')->create(['mentor_id' => $otherSupervisor->id]);
        $laporan = $participant->laporans()->create([
            'tanggal' => today()->toDateString(),
            'kegiatan' => 'Laporan di luar bimbingan',
            'status' => 'Belum Diperiksa',
        ]);

        $this->actingAs($supervisor)
            ->patch(route('pembimbingan.laporan.setujui', $laporan), [])
            ->assertNotFound();

        $this->assertSame('Belum Diperiksa', $laporan->refresh()->status);
    }

    public function test_bulk_approval_only_updates_owned_pending_reports_from_today(): void
    {
        $supervisor = User::factory()->role('MENTOR')->create();
        $otherSupervisor = User::factory()->role('MENTOR')->create();
        $participant = User::factory()->role('MAHASISWA')->create(['mentor_id' => $supervisor->id]);
        $otherParticipant = User::factory()->role('MAHASISWA')->create(['mentor_id' => $otherSupervisor->id]);
        $ownedToday = $participant->laporans()->create(['tanggal' => today(), 'kegiatan' => 'Laporan hari ini']);
        $ownedEarlier = $participant->laporans()->create(['tanggal' => today()->subDay(), 'kegiatan' => 'Laporan kemarin']);
        $otherToday = $otherParticipant->laporans()->create(['tanggal' => today(), 'kegiatan' => 'Laporan lain']);

        $this->actingAs($supervisor)->post(route('pembimbingan.laporan.setujui-hari-ini'))
            ->assertRedirect();

        $this->assertSame('Disetujui', $ownedToday->refresh()->status);
        $this->assertSame('Belum Diperiksa', $ownedEarlier->refresh()->status);
        $this->assertSame('Belum Diperiksa', $otherToday->refresh()->status);
    }

    public function test_supervisor_can_upload_a_pdf_assessment_for_an_owned_participant(): void
    {
        Storage::fake('public');
        $supervisor = User::factory()->role('MENTOR')->create();
        $supervisorProfile = Pembimbing::create(['user_id' => $supervisor->id, 'nama' => $supervisor->name]);
        $group = Kelompok::create([
            'nama_kelompok' => 'KEL-2026-023',
            'tanggal_mulai' => '2026-09-01',
            'tanggal_selesai' => '2026-09-30',
            'pembimbing_id' => $supervisorProfile->id,
        ]);
        $participant = User::factory()->role('MAHASISWA')->create(['kelompok_id' => $group->id]);

        $this->actingAs($supervisor)
            ->post(route('pembimbingan.penilaian.store', $participant), [
                'file' => UploadedFile::fake()->createWithContent('nilai-akhir.pdf', "%PDF-1.4\n%%EOF"),
            ])
            ->assertRedirect(route('pembimbing.peserta'));

        $penilaian = Penilaian::query()->where('mahasiswa_id', $participant->id)->firstOrFail();
        $this->assertTrue(Storage::disk('public')->exists($penilaian->file));
        $this->assertSame($supervisor->name, $penilaian->uploaded_by);
    }

    public function test_supervisor_cannot_upload_assessment_for_another_supervisors_participant(): void
    {
        Storage::fake('public');
        $supervisor = User::factory()->role('MENTOR')->create();
        $otherSupervisor = User::factory()->role('MENTOR')->create();
        $participant = User::factory()->role('MAHASISWA')->create(['mentor_id' => $otherSupervisor->id]);

        $this->actingAs($supervisor)
            ->post(route('pembimbingan.penilaian.store', $participant), [
                'file' => UploadedFile::fake()->createWithContent('nilai-akhir.pdf', "%PDF-1.4\n%%EOF"),
            ])
            ->assertNotFound();

        $this->assertDatabaseCount('penilaians', 0);
    }

    public function test_supervisor_upload_rejects_non_pdf_files(): void
    {
        Storage::fake('public');
        $supervisor = User::factory()->role('MENTOR')->create();
        $participant = User::factory()->role('MAHASISWA')->create(['mentor_id' => $supervisor->id]);

        $this->actingAs($supervisor)
            ->from(route('pembimbing.peserta'))
            ->post(route('pembimbingan.penilaian.store', $participant), [
                'file' => UploadedFile::fake()->createWithContent('nilai-akhir.txt', 'bukan PDF'),
            ])
            ->assertSessionHasErrors('file');

        $this->assertDatabaseCount('penilaians', 0);
    }
}
