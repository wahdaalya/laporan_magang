<?php

namespace Tests\Feature;

use App\Models\Departemen;
use App\Models\Dosen;
use App\Models\Kelompok;
use App\Models\Pembimbing;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_page_is_displayed(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get('/profile');

        $response->assertOk();
    }

    public function test_profile_page_uses_a_private_photo_route_when_photo_exists(): void
    {
        Storage::fake('local');
        Storage::disk('local')->put('foto-profil/profile.png', 'image contents');
        $user = User::factory()->role('MAHASISWA')->create([
            'foto' => 'foto-profil/profile.png',
        ]);

        $response = $this
            ->actingAs($user)
            ->get('/profile');

        $response
            ->assertOk()
            ->assertSee('src="'.route('media.profile-photo', $user).'"', false)
            ->assertSee('alt="Foto profil '.$user->name.'"', false);
    }

    public function test_profile_page_displays_fallback_when_photo_file_is_missing(): void
    {
        Storage::fake('local');
        $user = User::factory()->role('MAHASISWA')->create([
            'foto' => 'foto-profil/missing.png',
        ]);

        $response = $this
            ->actingAs($user)
            ->get('/profile');

        $response
            ->assertOk()
            ->assertSee(strtoupper(substr($user->name, 0, 1)))
            ->assertDontSee('missing.png');
    }

    public function test_student_profile_uses_the_group_department(): void
    {
        $departemen = Departemen::query()->create([
            'nama_departemen' => 'Airport Operations',
        ]);
        $kelompok = Kelompok::query()->create([
            'nama_kelompok' => 'KNO-01',
            'tanggal_mulai' => '2026-01-01',
            'tanggal_selesai' => '2026-02-01',
            'departemen_id' => $departemen->id,
        ]);
        $mahasiswa = User::factory()->role('MAHASISWA')->create([
            'kelompok_id' => $kelompok->id,
        ]);

        $this->actingAs($mahasiswa)->get('/profile')
            ->assertSee('Departemen Penempatan')
            ->assertSee('Airport Operations');
    }

    public function test_dosen_profile_displays_profile_data_and_assigned_group(): void
    {
        $user = User::factory()->role('DOSEN')->create();
        $dosen = Dosen::query()->create([
            'user_id' => $user->id,
            'nama' => 'Dosen Uji',
            'nidn' => '0123456789',
            'kampus' => 'Universitas Kualanamu',
            'email' => 'dosen@example.test',
            'hp_wa' => '081234567890',
            'fakultas' => 'Teknik',
            'prodi' => 'Informatika',
        ]);
        $kelompok = Kelompok::query()->create([
            'nama_kelompok' => 'Kelompok Dosen Uji',
            'tanggal_mulai' => '2026-01-01',
            'tanggal_selesai' => '2026-02-01',
            'dosen_id' => $dosen->id,
        ]);
        User::factory()->role('MAHASISWA')->create(['kelompok_id' => $kelompok->id]);

        $this->actingAs($user)->get('/profile')
            ->assertSee('0123456789')
            ->assertSee('dosen@example.test')
            ->assertSee('Universitas Kualanamu')
            ->assertSee('Kelompok Dosen Uji')
            ->assertSee('1 kelompok')
            ->assertSee('1 mahasiswa');
    }

    public function test_mentor_profile_displays_department_manager_and_assigned_group(): void
    {
        $user = User::factory()->role('MENTOR')->create();
        $departemen = Departemen::query()->create([
            'nama_departemen' => 'Engineering',
            'nama_pimpinan' => 'Pimpinan Uji',
        ]);
        $pembimbing = Pembimbing::query()->create([
            'user_id' => $user->id,
            'departemen_id' => $departemen->id,
            'nama' => 'Mentor Uji',
            'nip' => '1987654321',
            'email' => 'mentor@example.test',
            'hp_wa' => '081298765432',
        ]);
        $kelompok = Kelompok::query()->create([
            'nama_kelompok' => 'Kelompok Mentor Uji',
            'tanggal_mulai' => '2026-01-01',
            'tanggal_selesai' => '2026-02-01',
            'pembimbing_id' => $pembimbing->id,
        ]);
        User::factory()->role('MAHASISWA')->create(['kelompok_id' => $kelompok->id]);

        $this->actingAs($user)->get('/profile')
            ->assertSee('1987654321')
            ->assertSee('mentor@example.test')
            ->assertSee('Pimpinan Uji')
            ->assertSee('Kelompok Mentor Uji')
            ->assertSee('1 kelompok')
            ->assertSee('1 peserta');
    }

    #[DataProvider('profileRoles')]
    public function test_profile_show_is_read_only_for_all_account_roles(string $role, string $roleLabel): void
    {
        $user = User::factory()->role($role)->create();

        $this->actingAs($user)->get('/profile')
            ->assertOk()
            ->assertSee($roleLabel)
            ->assertSee('Email')
            ->assertSee('Detail profil pengguna', false)
            ->assertSee('Lihat Profil Lengkap')
            ->assertSee('Edit Profil')
            ->assertSee('Keluar')
            ->assertDontSee('Ubah Foto')
            ->assertDontSee('Simpan Perubahan');
    }

    public function test_edit_profile_page_only_contains_editable_account_fields(): void
    {
        $user = User::factory()->role('MAHASISWA')->create();

        $this->actingAs($user)->get('/profile/edit')
            ->assertOk()
            ->assertSee('Ubah Foto')
            ->assertSee('Nama Lengkap')
            ->assertSee('Email')
            ->assertSee('Nomor WhatsApp / Telepon')
            ->assertSee('Simpan Perubahan')
            ->assertSee('Ubah Kata Sandi')
            ->assertSee('Pengelolaan akun')
            ->assertDontSee('NIM / NISN')
            ->assertDontSee('Departemen Penempatan');
    }

    public static function profileRoles(): array
    {
        return [
            'admin' => ['ADMIN', 'Administrator'],
            'field mentor' => ['MENTOR', 'Pembimbing Lapangan'],
            'lecturer' => ['DOSEN', 'Dosen / Guru Pembimbing'],
            'participant' => ['MAHASISWA', 'Peserta Magang / PKL'],
        ];
    }

    public function test_profile_information_can_be_updated(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->patch('/profile', [
                'name' => 'Test User',
                'email' => 'test@example.com',
                'hp_wa' => '+6281234567890',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('profile.show'));

        $user->refresh();

        $this->assertSame('Test User', $user->name);
        $this->assertSame('test@example.com', $user->email);
        $this->assertSame('+6281234567890', $user->hp_wa);
        $this->assertNull($user->email_verified_at);
    }

    public function test_email_verification_status_is_unchanged_when_the_email_address_is_unchanged(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->patch('/profile', [
                'name' => 'Test User',
                'email' => $user->email,
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/profile');

        $this->assertNotNull($user->refresh()->email_verified_at);
    }

    public function test_user_can_delete_their_account(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->delete('/profile', [
                'password' => 'password',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/');

        $this->assertGuest();
        $this->assertNull($user->fresh());
    }

    public function test_correct_password_must_be_provided_to_delete_account(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->from('/profile')
            ->delete('/profile', [
                'password' => 'wrong-password',
            ]);

        $response
            ->assertSessionHasErrorsIn('userDeletion', 'password')
            ->assertRedirect('/profile');

        $this->assertNotNull($user->fresh());
    }
}
