<?php

namespace Tests\Feature;

use App\Models\Dosen;
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

    public function test_mentor_dashboard_lists_only_their_group_participants_and_review_status(): void
    {
        $mentor = User::factory()->role('MENTOR')->create();
        $otherMentor = User::factory()->role('MENTOR')->create();
        $mentorProfile = Pembimbing::create(['user_id' => $mentor->id, 'nama' => $mentor->name]);
        $otherMentorProfile = Pembimbing::create(['user_id' => $otherMentor->id, 'nama' => $otherMentor->name]);
        $dosenUser = User::factory()->role('DOSEN')->create();
        $dosen = Dosen::create([
            'user_id' => $dosenUser->id,
            'nama' => 'Dosen Kelompok Mentor',
            'hp_wa' => '081234567890',
        ]);
        $kelompok = Kelompok::create([
            'nama_kelompok' => 'KEL-MENTOR-SAYA',
            'tanggal_mulai' => '2026-09-01',
            'tanggal_selesai' => '2026-09-30',
            'pembimbing_id' => $mentorProfile->id,
            'dosen_id' => $dosen->id,
        ]);
        $kelompokLain = Kelompok::create([
            'nama_kelompok' => 'KEL-MENTOR-LAIN',
            'tanggal_mulai' => '2026-09-01',
            'tanggal_selesai' => '2026-09-30',
            'pembimbing_id' => $otherMentorProfile->id,
        ]);
        $peserta = User::factory()->role('MAHASISWA')->create([
            'name' => 'Peserta Mentor Saya',
            'kelompok_id' => $kelompok->id,
            'hp_wa' => '089988776655',
        ]);
        $peserta->laporans()->create(['tanggal' => today(), 'kegiatan' => 'Perlu diperiksa', 'status' => 'Belum Diperiksa']);
        $peserta->penilaians()->create(['file' => 'penilaian/nilai.pdf', 'uploaded_by' => $mentor->name]);
        User::factory()->role('MAHASISWA')->create([
            'name' => 'Peserta Mentor Lain',
            'kelompok_id' => $kelompokLain->id,
        ]);

        $this->actingAs($mentor)->get(route('pembimbingan.index'))
            ->assertOk()
            ->assertSee('Peserta Bimbingan Saya')
            ->assertSee('KEL-MENTOR-SAYA')
            ->assertDontSee('KEL-MENTOR-LAIN')
            ->assertSee('Dosen Kelompok Mentor')
            ->assertSee('href="https://wa.me/6281234567890"', false)
            ->assertSee('Peserta Mentor Saya')
            ->assertSee('Belum diperiksa: 1')
            ->assertSee('Penilaian: Sudah diunggah')
            ->assertSee('href="'.route('pembimbingan.laporan.index', ['peserta' => $peserta->id]).'"', false);
    }

    public function test_supervisor_can_filter_and_approve_an_owned_report(): void
    {
        $supervisor = User::factory()->role('MENTOR')->create();
        $supervisorProfile = Pembimbing::create(['user_id' => $supervisor->id, 'nama' => $supervisor->name]);
        $group = Kelompok::create([
            'nama_kelompok' => 'KEL-APPROVE-01',
            'tanggal_mulai' => '2026-09-01',
            'tanggal_selesai' => '2026-09-30',
            'pembimbing_id' => $supervisorProfile->id,
        ]);
        $participant = User::factory()->role('MAHASISWA')->create(['kelompok_id' => $group->id]);
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
        $otherSupervisorProfile = Pembimbing::create(['user_id' => $otherSupervisor->id, 'nama' => $otherSupervisor->name]);
        $otherGroup = Kelompok::create([
            'nama_kelompok' => 'KEL-OTHER-01',
            'tanggal_mulai' => '2026-09-01',
            'tanggal_selesai' => '2026-09-30',
            'pembimbing_id' => $otherSupervisorProfile->id,
        ]);
        $participant = User::factory()->role('MAHASISWA')->create(['kelompok_id' => $otherGroup->id]);
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
        $supervisorProfile = Pembimbing::create(['user_id' => $supervisor->id, 'nama' => $supervisor->name]);
        $otherSupervisorProfile = Pembimbing::create(['user_id' => $otherSupervisor->id, 'nama' => $otherSupervisor->name]);
        $group = Kelompok::create([
            'nama_kelompok' => 'KEL-BULK-01',
            'tanggal_mulai' => '2026-09-01',
            'tanggal_selesai' => '2026-09-30',
            'pembimbing_id' => $supervisorProfile->id,
        ]);
        $otherGroup = Kelompok::create([
            'nama_kelompok' => 'KEL-BULK-02',
            'tanggal_mulai' => '2026-09-01',
            'tanggal_selesai' => '2026-09-30',
            'pembimbing_id' => $otherSupervisorProfile->id,
        ]);
        $participant = User::factory()->role('MAHASISWA')->create(['kelompok_id' => $group->id]);
        $otherParticipant = User::factory()->role('MAHASISWA')->create(['kelompok_id' => $otherGroup->id]);
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
        Storage::fake('local');
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
        $this->assertTrue(Storage::disk('local')->exists($penilaian->file));
        $this->assertSame($supervisor->name, $penilaian->uploaded_by);
    }

    public function test_supervisor_cannot_upload_assessment_for_another_supervisors_participant(): void
    {
        Storage::fake('local');
        $supervisor = User::factory()->role('MENTOR')->create();
        $otherSupervisor = User::factory()->role('MENTOR')->create();
        $otherSupervisorProfile = Pembimbing::create(['user_id' => $otherSupervisor->id, 'nama' => $otherSupervisor->name]);
        $otherGroup = Kelompok::create([
            'nama_kelompok' => 'KEL-UPLOAD-OTHER',
            'tanggal_mulai' => '2026-09-01',
            'tanggal_selesai' => '2026-09-30',
            'pembimbing_id' => $otherSupervisorProfile->id,
        ]);
        $participant = User::factory()->role('MAHASISWA')->create(['kelompok_id' => $otherGroup->id]);

        $this->actingAs($supervisor)
            ->post(route('pembimbingan.penilaian.store', $participant), [
                'file' => UploadedFile::fake()->createWithContent('nilai-akhir.pdf', "%PDF-1.4\n%%EOF"),
            ])
            ->assertNotFound();

        $this->assertDatabaseCount('penilaians', 0);
    }

    public function test_supervisor_upload_rejects_non_pdf_files(): void
    {
        Storage::fake('local');
        $supervisor = User::factory()->role('MENTOR')->create();
        $supervisorProfile = Pembimbing::create(['user_id' => $supervisor->id, 'nama' => $supervisor->name]);
        $group = Kelompok::create([
            'nama_kelompok' => 'KEL-UPLOAD-01',
            'tanggal_mulai' => '2026-09-01',
            'tanggal_selesai' => '2026-09-30',
            'pembimbing_id' => $supervisorProfile->id,
        ]);
        $participant = User::factory()->role('MAHASISWA')->create(['kelompok_id' => $group->id]);

        $this->actingAs($supervisor)
            ->from(route('pembimbing.peserta'))
            ->post(route('pembimbingan.penilaian.store', $participant), [
                'file' => UploadedFile::fake()->createWithContent('nilai-akhir.txt', 'bukan PDF'),
            ])
            ->assertSessionHasErrors('file');

        $this->assertDatabaseCount('penilaians', 0);
    }
}
