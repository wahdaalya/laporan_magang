<?php

namespace Tests\Feature;

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

    public function test_profile_page_displays_photo_when_file_exists_on_public_disk(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('foto-profil/profile.png', 'image contents');
        $user = User::factory()->role('MAHASISWA')->create([
            'foto' => 'foto-profil/profile.png',
        ]);

        $response = $this
            ->actingAs($user)
            ->get('/profile');

        $response
            ->assertOk()
            ->assertSee('src="'.asset('storage/foto-profil/profile.png').'"', false)
            ->assertSee('alt="Foto profil '.$user->name.'"', false);
    }

    public function test_profile_page_displays_fallback_when_photo_file_is_missing(): void
    {
        Storage::fake('public');
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

    #[DataProvider('profileRoles')]
    public function test_profile_uses_the_shared_banner_layout_for_all_account_roles(string $role, string $roleLabel): void
    {
        $user = User::factory()->role($role)->create();

        $this->actingAs($user)->get('/profile')
            ->assertOk()
            ->assertSee('Ubah Foto')
            ->assertSee($roleLabel)
            ->assertSee('Nama Lengkap')
            ->assertSee('Email')
            ->assertSee('Nomor WhatsApp / Telepon')
            ->assertSee('Detail profil pengguna', false)
            ->assertSee('Edit Profil')
            ->assertSee('Keluar')
            ->assertSee('name="_token"', false)
            ->assertSee('name="_method" value="PATCH"', false);
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
            ->assertRedirect('/profile');

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
