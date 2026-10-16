<?php

namespace Tests\Feature;

use App\Models\CalonAnggotaKelompok;
use App\Models\Departemen;
use App\Models\Dosen;
use App\Models\Kelompok;
use App\Models\Pembimbing;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class AdminDirectoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_mahasiswa_search_paginates_and_keeps_the_query_string(): void
    {
        $admin = User::factory()->role('ADMIN')->create();

        for ($index = 1; $index <= 11; $index++) {
            User::factory()->role('MAHASISWA')->create([
                'name' => 'Peserta Target '.$index,
            ]);
        }

        $response = $this->actingAs($admin)
            ->get(route('mahasiswa.index', ['q' => 'Peserta Target']));

        $response
            ->assertOk()
            ->assertViewHas('mahasiswas', fn ($mahasiswas): bool => $mahasiswas->total() === 11 && $mahasiswas->count() === 10)
            ->assertSee('value="Peserta Target"', false)
            ->assertSee(route('mahasiswa.index', ['q' => 'Peserta Target', 'page' => 2]));
    }

    public function test_kelompok_search_matches_the_assigned_dosen(): void
    {
        $admin = User::factory()->role('ADMIN')->create();
        $dosen = Dosen::query()->create(['nama' => 'Dosen Target']);
        Kelompok::query()->create([
            'nama_kelompok' => 'Kelompok A',
            'tanggal_mulai' => '2026-01-01',
            'tanggal_selesai' => '2026-02-01',
            'dosen_id' => $dosen->id,
        ]);

        $this->actingAs($admin)
            ->get(route('kelompok.index', ['q' => 'Dosen Target']))
            ->assertOk()
            ->assertViewHas('kelompoks', fn ($kelompoks): bool => $kelompoks->total() === 1)
            ->assertSee('Kelompok A');
    }

    public function test_dosen_search_matches_nidn(): void
    {
        $admin = User::factory()->role('ADMIN')->create();
        Dosen::query()->create([
            'nama' => 'Dosen Contoh',
            'nidn' => '1234567890',
        ]);

        $this->actingAs($admin)
            ->get(route('dosen.index', ['q' => '1234567890']))
            ->assertOk()
            ->assertViewHas('dosens', fn ($dosens): bool => $dosens->total() === 1)
            ->assertSee('Dosen Contoh');
    }

    public function test_admin_can_create_a_school_mentor_with_an_alphanumeric_educator_id(): void
    {
        $admin = User::factory()->role('ADMIN')->create();

        $this->actingAs($admin)
            ->from(route('dosen.index'))
            ->post(route('dosen.store'), [
                'nama' => 'Guru Pembimbing Uji',
                'nidn' => 'NUPTK-1234567890123456',
                'kampus' => 'SMK Negeri 1 Medan',
                'email' => 'guru.pembimbing@example.test',
                'password' => 'Password123!',
            ])
            ->assertRedirect(route('dosen.index'))
            ->assertSessionHas('status', 'Data pembimbing sekolah/kampus berhasil ditambahkan.');

        $this->assertDatabaseHas('dosens', [
            'nama' => 'Guru Pembimbing Uji',
            'nidn' => 'NUPTK-1234567890123456',
            'kampus' => 'SMK Negeri 1 Medan',
        ]);
    }

    public function test_pembimbing_search_matches_their_department(): void
    {
        $admin = User::factory()->role('ADMIN')->create();
        $departemen = Departemen::query()->create(['nama_departemen' => 'Departemen Target']);
        Pembimbing::query()->create([
            'nama' => 'Pembimbing Contoh',
            'departemen_id' => $departemen->id,
        ]);

        $this->actingAs($admin)
            ->get(route('pembimbing.index', ['q' => 'Departemen Target']))
            ->assertOk()
            ->assertViewHas('pembimbings', fn ($pembimbings): bool => $pembimbings->total() === 1)
            ->assertSee('Pembimbing Contoh');
    }

    public function test_superadmin_can_hard_delete_a_student_and_their_dependent_data(): void
    {
        Storage::fake('local');
        $superadmin = User::factory()->role('SUPERADMIN')->create();
        $kelompok = Kelompok::query()->create([
            'nama_kelompok' => 'KEL-HAPUS-01',
            'tanggal_mulai' => '2026-09-01',
            'tanggal_selesai' => '2026-10-01',
        ]);
        $mahasiswa = User::factory()->role('MAHASISWA')->create([
            'foto' => 'foto-profil/mahasiswa-hapus.png',
            'kelompok_id' => $kelompok->id,
        ]);
        $laporan = $mahasiswa->laporans()->create([
            'tanggal' => '2026-09-10',
            'kegiatan' => 'Kegiatan yang akan dihapus',
            'foto' => 'laporan/mahasiswa-hapus.jpg',
        ]);
        $penilaian = $mahasiswa->penilaians()->create([
            'file' => 'penilaian/mahasiswa-hapus.pdf',
        ]);
        $laporanAkhir = $mahasiswa->laporanAkhir()->create([
            'judul_laporan' => 'Laporan akhir yang akan dihapus',
            'file_path' => 'laporan-akhir/mahasiswa-hapus.pdf',
        ]);
        $nilaiAkademik = $mahasiswa->nilaiAkademik()->create([
            'nilai_bimbingan' => 90,
            'nilai_laporan_akhir' => 90,
            'nilai_presentasi' => 90,
        ]);
        $calonAnggota = CalonAnggotaKelompok::query()->create([
            'kelompok_id' => $kelompok->id,
            'nama' => $mahasiswa->name,
            'user_id' => $mahasiswa->id,
        ]);

        Storage::disk('local')->put('foto-profil/mahasiswa-hapus.png', 'profile photo');
        Storage::disk('local')->put('laporan/mahasiswa-hapus.jpg', 'report photo');
        Storage::disk('local')->put('penilaian/mahasiswa-hapus.pdf', '%PDF-1.4');
        Storage::disk('local')->put('laporan-akhir/mahasiswa-hapus.pdf', '%PDF-1.4');

        $this->actingAs($superadmin)
            ->from(route('mahasiswa.index'))
            ->delete(route('mahasiswa.destroy', $mahasiswa))
            ->assertRedirect(route('mahasiswa.index'));

        $this->assertDatabaseMissing('users', ['id' => $mahasiswa->id]);
        $this->assertDatabaseMissing('laporans', ['id' => $laporan->id]);
        $this->assertDatabaseMissing('penilaians', ['id' => $penilaian->id]);
        $this->assertDatabaseMissing('laporan_akhirs', ['id' => $laporanAkhir->id]);
        $this->assertDatabaseMissing('nilai_akademiks', ['id' => $nilaiAkademik->id]);
        $this->assertDatabaseHas('calon_anggota_kelompoks', [
            'id' => $calonAnggota->id,
            'user_id' => null,
        ]);
        Storage::disk('local')->assertMissing([
            'foto-profil/mahasiswa-hapus.png',
            'laporan/mahasiswa-hapus.jpg',
            'penilaian/mahasiswa-hapus.pdf',
            'laporan-akhir/mahasiswa-hapus.pdf',
        ]);
    }

    public function test_admin_cannot_delete_a_student(): void
    {
        $admin = User::factory()->role('ADMIN')->create();
        $mahasiswa = User::factory()->role('MAHASISWA')->create();

        $this->actingAs($admin)
            ->delete(route('mahasiswa.destroy', $mahasiswa))
            ->assertForbidden();

        $this->assertModelExists($mahasiswa);
    }

    #[DataProvider('nonMahasiswaRoles')]
    public function test_superadmin_cannot_delete_an_account_that_is_not_a_student_via_mahasiswa_route(string $role): void
    {
        $superadmin = User::factory()->role('SUPERADMIN')->create();
        $user = User::factory()->role($role)->create();

        $this->actingAs($superadmin)
            ->delete(route('mahasiswa.destroy', $user))
            ->assertNotFound();

        $this->assertModelExists($user);
    }

    public static function nonMahasiswaRoles(): array
    {
        return [
            'admin account' => ['ADMIN'],
            'dosen account' => ['DOSEN'],
            'mentor account' => ['MENTOR'],
        ];
    }

    public function test_student_rows_offer_delete_action_only_to_superadmin(): void
    {
        $admin = User::factory()->role('ADMIN')->create();
        $superadmin = User::factory()->role('SUPERADMIN')->create();
        $mahasiswa = User::factory()->role('MAHASISWA')->create(['name' => 'Peserta Siap Hapus']);

        // Admin cannot see delete action
        $this->actingAs($admin)
            ->get(route('mahasiswa.index'))
            ->assertOk()
            ->assertDontSee('Hapus peserta '.$mahasiswa->name)
            ->assertDontSee('Yakin ingin menghapus peserta ini?');

        // Super Admin can see delete action and confirmation modal
        $this->actingAs($superadmin)
            ->get(route('mahasiswa.index'))
            ->assertOk()
            ->assertSee('Hapus peserta '.$mahasiswa->name)
            ->assertSee('Yakin ingin menghapus peserta ini?')
            ->assertSee('dan seluruh laporan serta dokumen terkait akan dihapus permanen.')
            ->assertSee('Ya, Hapus');
    }
}
