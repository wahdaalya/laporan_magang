<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PenilaianMahasiswaControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_missing_pdf_shows_the_participant_facing_empty_state(): void
    {
        $user = User::factory()->role('MAHASISWA')->create();

        $this->actingAs($user)->get(route('mahasiswa.penilaian.index'))
            ->assertOk()
            ->assertSee('Lembar Penilaian Belum Tersedia')
            ->assertSee('Pembimbing Lapangan / Dosen / Guru Pembimbing belum mengunggah dokumen penilaian.');
    }

    public function test_latest_available_pdf_is_read_only_and_has_view_and_download_actions(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('penilaian/lembar-akhir.pdf', "%PDF-1.4\nPenilaian peserta\n%%EOF");
        $user = User::factory()->role('MAHASISWA')->create();
        $penilaian = $user->penilaians()->create([
            'file' => 'penilaian/lembar-akhir.pdf',
            'uploaded_by' => 'Dosen Pembimbing',
        ]);

        $this->actingAs($user)->get(route('mahasiswa.penilaian.index'))
            ->assertOk()
            ->assertSee('lembar-akhir.pdf')
            ->assertSee('Tanggal Upload:')
            ->assertSee('Lihat PDF')
            ->assertSee('Unduh PDF')
            ->assertSee(route('mahasiswa.penilaian.download', $penilaian));
    }

    public function test_participant_can_download_their_own_pdf(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('penilaian/lembar.pdf', "%PDF-1.4\nPenilaian peserta\n%%EOF");
        $user = User::factory()->role('MAHASISWA')->create();
        $penilaian = $user->penilaians()->create(['file' => 'penilaian/lembar.pdf']);

        $this->actingAs($user)
            ->get(route('mahasiswa.penilaian.download', $penilaian))
            ->assertDownload('lembar.pdf');
    }

    public function test_participant_cannot_download_another_participants_pdf(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('penilaian/lembar.pdf', "%PDF-1.4\nPenilaian peserta\n%%EOF");
        $owner = User::factory()->role('MAHASISWA')->create();
        $otherParticipant = User::factory()->role('MAHASISWA')->create();
        $penilaian = $owner->penilaians()->create(['file' => 'penilaian/lembar.pdf']);

        $this->actingAs($otherParticipant)
            ->get(route('mahasiswa.penilaian.download', $penilaian))
            ->assertNotFound();
    }

    public function test_non_pdf_files_are_not_displayed_as_assessment_documents(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('penilaian/catatan.txt', 'Not a PDF');
        $user = User::factory()->role('MAHASISWA')->create();
        $user->penilaians()->create(['file' => 'penilaian/catatan.txt']);

        $this->actingAs($user)->get(route('mahasiswa.penilaian.index'))
            ->assertOk()
            ->assertSee('Lembar Penilaian Belum Tersedia')
            ->assertDontSee('catatan.txt');
    }
}
